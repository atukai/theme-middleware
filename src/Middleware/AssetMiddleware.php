<?php

namespace At\Theme\Middleware;

use At\Theme\MimeDetector;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class AssetMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param array $paths
     * @param $cacheDir
     */
    public function __construct(array $paths, $cacheDir)
    {
        $this->paths = $paths;
        $this->cacheDir = realpath($cacheDir);
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $uriPath = $request->getUri()->getPath();

        // Prevent LFI
        if (preg_match('#\.\.[\\\/]#', $uriPath)) {
            $response = $delegate->process($request);
            return $response->withStatus(404);
        }

        $file = $this->resolveFile($uriPath);
        if ($file) {
            $this->cacheFile($file, $uriPath);
            $response = new Response();
            $response->getBody()->write(file_get_contents($file));
            $response = $response->withStatus(200);
            return $response->withHeader('Content-Type', $this->detectMimeType($file));
        }

        return $delegate->process($request);
    }

    /**
     * Resolves the file from the request's uri path in the provided paths.
     *
     * @param string $uriPath the request uri path
     * @return boolean|string false if no file is found; the full file path if file is found
     */
    private function resolveFile($uriPath)
    {
        return array_reduce($this->paths, function ($file, $path) use ($uriPath) {
            if (false !== $file) {
                return $file;
            }

            $file = realpath($path) . $uriPath;

            if (is_file($file) && is_readable($file)) {
                return $file;
            }

            return false;
        }, false);
    }

    /**
     * @param $file
     * @param $targetFile
     */
    private function cacheFile($file, $targetFile)
    {
        if (! $this->cacheDir) {
            return;
        }

        if (! is_writable($this->cacheDir)) {
            trigger_error(sprintf('Directory %s is not writeable', $this->cacheDir));
            return;
        }

        $targetFile = $this->cacheDir . $targetFile;
        $targetDir  = dirname($targetFile);
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        file_put_contents($targetFile, file_get_contents($file));
    }

    /**
     * @param $file
     * @return string
     */
    private function detectMimeType($file)
    {
        $detector = new MimeDetector();
        $mime = $detector->getMimeType($file);

        return $mime;
    }
}
