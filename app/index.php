<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$config = require 'config.php';

// Load Core Classes (Namespaced)
require 'Core/Database.php';
require 'Core/Router.php';

use App\Core\Database;
use App\Core\Router;

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

function view($name, $data = [])
{
    extract($data);
    // Path: views/admin/dashboard.view.php
    return require "views/{$name}.view.php";
}

// Redirect helper
function redirect($path)
{
    // Ensure absolute path for redirect
    $base = trim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    $path = trim($path, '/');
    $location = $base ? "/{$base}/{$path}" : "/{$path}";
    header("Location: {$location}");
    exit();
}

// Router initialization
$router = Router::load('routes.php');

// Robust URI identification
$uri = $_SERVER['REQUEST_URI'];
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim(parse_url($uri, PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->direct($uri, $method);
} catch (Exception $e) {
    die($e->getMessage());
}
