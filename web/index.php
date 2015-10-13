<?php
use config\config;
use components\Helper;

error_reporting(E_ALL);
ini_set('display_errors','on');

// main loaders
include ('../loader.php');

// set conntroller
$controller = !empty($_GET['c']) ? $_GET['c'] : Config::get('defaultController');
// set action
$action = !empty($_GET['a']) ? $_GET['a'] : Config::get('defaultAction');

// generate full controller name
$controller = 'controllers\\'.Helper::ucfirst(mb_strtolower($controller)).'Controller';

if (class_exists($controller)) {

    // init controller
    $controller = new $controller;

    // generate action name
    $action_name = mb_strtolower($action).'Action';

    // check if action(method) exist
    if(!method_exists($controller, $action_name)) {
        Helper::HException('Action "'.$action.'" not exist', 403);
    }

    try {
        // call action
        $controller->$action_name();
    } catch(Exception $e) {
        Helper::HException($e->getMessage(), 409);
    }

} else {
    Helper::HException('Controller "'.$controller.'" not exist', 403);
}
