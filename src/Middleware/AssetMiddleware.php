<?php

namespace At\Theme\Middleware;

use Dflydev\Canal\Analyzer\Analyzer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AssetMiddleware
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var string
     */
    protected $docRoot;

    /**
     * AssetMiddleware constructor.
     * @param array $paths
     * @param $docRoot
     */
    public function __construct(array $paths = [], $docRoot)
    {
        $this->paths = $paths;
        $this->docRoot = realpath($docRoot);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return static
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uriPath = $request->getUri()->getPath();

        $file = $this->findFile($uriPath);
        if ($file) {
            $contents = file_get_contents($file);
            $this->writeToWebDir($uriPath, $contents);
            try {
                $response->getBody()->rewind();
                $response->getBody()->write($contents);
                $response = $response->withStatus(200);
                return $response->withHeader('Content-Type', $this->detectMimeType($file));
            } catch (\Exception $e) {
                trigger_error(sprintf('Unable to serve %s. %s', $file, $e->getMessage()));
            }
        }

        return $next($request, $response);
    }

    /**
     * Finds the file from the request's uri path in the provided paths.
     *
     * @param string $uriPath the request uri path
     * @return boolean|string false if no file is found; the full file path if file is found
     */
    private function findFile($uriPath)
    {
        return array_reduce($this->paths, function ($file, $path) use ($uriPath) {
            if (false !== $file) {
                return $file;
            }

            $parts = explode('/', ltrim($uriPath, '/'));
            array_shift($parts);
            $uriPath = '/'.implode('/', $parts);

            //var_dump($uriPath);exit;

            $file = realpath($path) . $uriPath;
            if (is_file($file) && is_readable($file)) {
                return $file;
            }

            return false;
        }, false);
    }

    /**
     * @param $file
     * @return string
     */
    private function detectMimeType($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file);
        finfo_close($finfo);

        return $mime;
    }

    /**
     * Writes the file in the web_dir so next time web server serve it
     *
     * @param string $file
     * @param string $contents
     * @return null
     */
    private function writeToWebDir($file, $contents)
    {
        if (!$this->docRoot) {
            return;
        }

        if (!is_writable($this->docRoot)) {
            trigger_error(sprintf('Directory %s is not writeable', $this->webDir));
            return;
        }

        $destFile = $this->docRoot . $file;
        $destDir  = dirname($destFile);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }
        file_put_contents($destFile, $contents);
    }
}