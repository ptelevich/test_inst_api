<?php
use config\Config;
use components\Init;
use components\Helper;

error_reporting(E_ALL);
ini_set('display_errors','on');

// main loaders
include ('../loader.php');

$code = isset($_GET['code']) ? $_GET['code'] : null;

// save token value for using in future
$saveToken = (new Init())->setToken($code);

// if token success saved - welcome to main view page!
if ($saveToken) {
    Helper::redirect(Config::get('defaultController'), Config::get('mainViewAction'), compact('code'));
}
exit;


