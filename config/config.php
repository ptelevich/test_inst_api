<?php
namespace config;

/**
 *
 */
define('CONST_ROOT_DIR', dirname(__FILE__).'/..');

$params = array(

    'defaultController' => 'main',
    'defaultAction' => 'index',
    'mainViewAction' => 'view',

    'apiKey' => '1d219a2c1b7045e2b3085694070c9951',
    'apiSecret' => '684c33494b774cd182f49211cca14199',
    'apiCallback' => 'http://'.$_SERVER['SERVER_NAME'] . '/' . Config::CONST_INFOLDER . 'instagramCallBack.php',
);

/**
 * Class Config
 * @package config
 */
class Config
{
    /**
     *
     */
    const CONST_INFOLDER = 'instagram/web/';
    /**
     *
     */
    const CONST_ROOT_DIR = CONST_ROOT_DIR;

    /**
     * @var
     */
    private static $_config;

    /**
     * @param $params
     */
    public function __construct($params)
    {
        self::$_config = $params;
    }

    /**
     * @param $index
     * @return null
     */
    public static function get($index)
    {
        return (isset(self::$_config[$index])) ? self::$_config[$index] : null;
    }
}
new Config($params);
