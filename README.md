# Theme Middleware
Support themes middleware. It is designed for Zend Expressive.

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
         './themes'        
    ],
];
```


