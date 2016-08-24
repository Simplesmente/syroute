# PHP SyRoute class

Simple Router for methods GET and POST.
<br>  
This class handle all requests coming from Verbs GET and POST

# Author

- [André Teles](https://github.com/Simplesmente)


# Easy to install with **composer**

```sh
$ not yet published
```

## Usage

```php
<?php
require __DIR__.'/src/Route/SyRoute.php';
 

$route = new Simply\Route\SyRoute;

## Use without params

$route->addRoute('/','App\Controller\ExampleController@index');

## Use params type Strings

$route->addRoute('/post/(\w+)','App\Controller\HomeController@home');

## Use params type Integer

$route->addRoute('/post/(\d+)','App\Controller\HomeController@home');

$route->dispath(); // Run all requests from the URI 





```

## License

MIT Licensed, http://www.opensource.org/licenses/MIT
