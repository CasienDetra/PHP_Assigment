<?php

spl_autoload_register(function ($class) {

    $prefix = 'App\\';
    $base_dir = __DIR__.'/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir.str_replace('\\', '/', $relative_class).'.php';

    if (file_exists($file)) {
        require $file;
    } else {
        if (file_exists(__DIR__.'/core/'.$class.'.php')) {
            require __DIR__.'/core/'.$class.'.php';

            return;
        }
    }
});

$config = require 'config.php';

require 'core/Database.php';
require 'core/Router.php';

class App
{
    protected static $registry = [];

    public static function bind($key, $value)
    {
        static::$registry[$key] = $value;
    }

    public static function get($key)
    {
        if (! array_key_exists($key, static::$registry)) {
            throw new Exception("No {$key} is bound in the container.");
        }

        return static::$registry[$key];
    }
}

App::bind('config', $config);
App::bind('database', new Database(
    $config['database'],
    $config['database']['username'],
    $config['database']['password']
));

// Helper to view
function view($name, $data = [])
{
    extract($data);

    return require "views/{$name}.view.php";
}

// Redirect helper
function redirect($path)
{
    header("Location: /{$path}");
    exit();
}

$router = Router::load('routes.php');

$uri = trim($_SERVER['REQUEST_URI'], '/');
$uri = parse_url($uri, PHP_URL_PATH);
$uri = trim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->direct($uri, $method);
} catch (Exception $e) {
    exit($e->getMessage());
}
