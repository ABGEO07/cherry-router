# Cherry-Router
The Cherry-project Router

[![GitHub license](https://img.shields.io/github/license/abgeo07/cherry-router.svg)](https://github.com/ABGEO07/cherry-router/blob/master/LICENSE)

[![GitHub release](https://img.shields.io/github/release/abgeo07/cherry-router.svg)](https://github.com/ABGEO07/cherry-router/releases)

[![Packagist Version](https://img.shields.io/packagist/v/cherry-project/router.svg "Packagist Version")](https://packagist.org/packages/cherry-project/router "Packagist Version")

------------

## Including
**Install from composer** `composer require cherry-project/router`

**Include Autoloader in your main file** (Ex.: index.php)
```php
require_once __DIR__ . '/vendor/autoload.php';
```

Define application root directory
```php
define('__ROOT__', __DIR__);
```

In your application you must have **config.json** file for storing app configuration settings and you must define his location:
```php
define('CONFIG_FILE', __DIR__ . '/config/config.json');
```

**config.json** must contain path to **routes.json** and controllers directory

```json
{
    "ROUTES_FILE": "config/routes.json",
    "CONTROLLERS_PATH": "controllers"
}
```

Get app config parameters and define it:

```php
$config = file_get_contents(CONFIG_FILE)
    or die("Unable to open config file!");

$config = json_decode($config, 1);

foreach ($config as $k => $v)
    define($k, __DIR__ . '/' . $v);
```

**Notice**: This approach will be replaced in the new version :))

It's time to configure routes file 

The routes file is a json file, where object key is route unique name. 

Each route must have **path**, **method** and **action** keys. Homepage route example:
```json
{
  "homepage": {
      "path": "/",
      "method": "GET",
      "action": "Cherry\\Controller\\DefaultController::index"
  }
}
```

**Router file basic structure**
```json
{
    "[RouteName]": {
        "path": "[URL]",
        "method": "[HTTP_Method]",
        "action": "[Namespace]\\[Controller]::[Method]"
    }
}
```

Definitions for router keys:
- **[RouteName]** - Route unique name;
- **path** - Route url. (Ex.: For address http://www.example.com/homepage [URL] is *homepage*);
- **method** - Route HTTP Method. Allowed all [HTTP methods](https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods "HTTP methods");
- **action** - Route callback action. The firs part of action (before *::*) is your controller (stored in **CONTROLLERS_PATH**).
Controller is a simple [PHP Class](http://php.net/manual/en/language.oop5.php "PHP Class") where [Namespace] is his Namespace and 
[Controller] Class name (Class name and class filename must have same names (Ex.: **[Controller].php**)).
The second part of action key (after ::) is controllers (class) public method;

Your route path can use **Placeholders**. Placeholder is a template of your route.

Route example with placeholder:
```json
{
    "homepage": {
        "path": "/hello/{name}",
        "method": "GET",
        "action": "Cherry\\Controller\\DefaultController::sayHello"
    }
}
```

There we have placeholder called **{name}** and we can get this value in controller:
```php
public function sayHello($name)
{
    echo "Hello, {$name}";
}
```

**2019 &copy; Cherry-project**
