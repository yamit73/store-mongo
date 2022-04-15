<?php
use Phalcon\Di\FactoryDefault;
//Required class for loader
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
/**
 * Required classes for DB
 */
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('URLROOT', 'http://localhost:8080');

//Composer autoload file
require_once(BASE_PATH.'/vendor/autoload.php');

// Register an autoloader
$loader = new Loader();
/**
 * Registering controllers and models dir
 */
$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->register();

$container = new FactoryDefault();

/**
 * Di container for view
 */
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);
/**
 * Url container
 */
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

/**
 * Container for config 
 * Contains neccessory variables
 */
$container->set(
    'config',
    function () {
        $file='../app/config/config.php';
        $factory=new ConfigFactory();
        return $factory->newInstance('php', $file);
    }
);

/**
 * Mongo DB container
 */

$container->set(
    'mongo',
    function () {
        $config=$this->get('config')->db;
        $mongo = new \MongoDB\Client("mongodb://mongo", array("username"=>$config->username, "password"=>$config->password));

        return $mongo->store;
    },
    true
);
//Creating object of application class
$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}