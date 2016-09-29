# Theme Middleware
Support themes middleware that allows to switch between themes. It is generally designed 
for Zend Expressive.

## Installation

`composer require atukai/theme-middleware`

## Usage

Zend Expressive:

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

## Resolvers
It uses resolvers to detect theme name that should be currently used for rendering.
By default the `At\Theme\Resolver\ConfigurationResolver` is used to get theme specified in config:
 
```php
'themes' => [
    'theme_paths' => [
         __DIR__ . '/../themes/'        
    ],
    'default_theme' => 'default',
];
``` 

