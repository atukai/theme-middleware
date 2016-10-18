# Theme Middleware
Support themes middleware that allows to switch between themes. It is generally designed 
for Zend Expressive.

## Installation

`composer require atukai/theme-middleware`

## Usage

**Zend Expressive**:

Include config from ConfigProvider.php. Recommend to use [Expressive Configuration Manager]
(https://github.com/mtymek/expressive-config-manager)

```php
$configManager = new ConfigManager([
    ...,
    \At\Theme\ConfigProvider::class,
]);
``` 
 
Create themes folder.

```php
./themes
``` 
 
Configure your settings. 

```php
'themes' => [
    'theme_paths' => [
         __DIR__ . '/../themes/'        
    ],
];
```

**Other frameworks**:

You should implement `Zend\Expressive\Template\TemplateRendererInterface` and 
put it under `TemplateRendererInterface::class` key into your container

## Resolvers
It uses resolvers to detect theme name that should be currently used for rendering.
By default the `At\Theme\Resolver\ConfigurationResolver` is used to get theme specified in config.
You can add one or more resolvers with priority.
 
```php
'themes' => [
    'paths' => [
         __DIR__ . '/../themes/'        
    ],
    'default_theme' => 'default',
    'resolvers' => [
        \At\Theme\Resolver\HttpRequestResolver::class => 20,
    ],
];
``` 

## Assets
 
```php
'themes' => [
    'paths' => [
         __DIR__ . '/../themes/'        
    ],
    'default_theme' => 'default',
    'resolvers' => [
        \At\Theme\Resolver\HttpRequestResolver::class => 20,
    ],
    'assets' => [
        'paths' => [__DIR__ . '/../themes/default/assets'],
        'cache_dir' => __DIR__ . '/../public'
    ]
];
```