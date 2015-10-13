<?php
namespace components;

use config\Config;

/**
 * Class Helper
 * @package components
 */
class Helper {

    /**
     * @var int
     */
    private static $limitException = 0;

    /**
     * @var string
     */
    private static $layout = 'layouts/main.php';

    /**
     * @param $str
     * @param string $encoding
     * @return string
     */
    public static function ucFirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    /**
     * @param $controller
     * @param $action
     * @param array $params
     */
    public static function redirect($controller,$action, $params = [])
    {
        header('Location: '.self::generateUrl($controller, $action, $params));
        exit;
    }

    /**
     * @param $url
     */
    public static function redirectToUrl($url)
    {
        header('Location: '. $url);
        exit;
    }

    /**
     * @param $controller
     * @param $action
     * @param array $params
     * @return string
     */
    public static function generateUrl($controller, $action, $params = [])
    {
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= '&'.$key.'='.$value;
        }
        return '/'.Config::CONST_INFOLDER.'?c='.strtolower($controller).'&a='.strtolower($action).$paramString;
    }

    /**
     * @param $message
     * @param int $code
     */
    public static function HException($message, $code=404)
    {

        self::$limitException++;

        if(self::$limitException < 2) {
            try {
                throw new \Exception($message, $code);
            } catch(\Exception $e) {
                self::renderStatic('exception', array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ));
            }
        } else {
            echo $code.': ';
            echo $message;
        }
        exit;
    }

    /**
     * @param $filename
     * @param array $params
     * @return bool|string
     */
    public function render($filename, array $params=array())
    {
        return self::renderStatic($filename, $params);
    }

    /**
     * @param $name
     * @param $default
     * @return mixed
     */
    public static function requestGet($name, $default)
    {
        return !empty($_GET[$name]) ? $_GET[$name] : $default;
    }
    /**
     * Returns whether this is an AJAX (XMLHttpRequest) request.
     * @return boolean whether this is an AJAX (XMLHttpRequest) request.
     */
    public static function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
    }

    /**
     * @param $filename
     * @param array $params
     * @param bool $isLayout
     * @param bool $return
     * @return bool|string
     */
    public static function renderStatic($filename, array $params=array(), $isLayout = true, $return = false)
    {
        $view_dir = CONST_ROOT_DIR.'/views/';

        $layout = $view_dir.self::$layout;

        if(!is_file($layout)) {
            self::HException("Layout '".self::$layout."' does not exist.");
        }

        $file_path = $view_dir.$filename.'.php';

        if(!is_file($file_path)) {
            self::HException("The template file '$filename' does not exist.");
        }

        extract($params,EXTR_PREFIX_SAME,'params');

        ob_start();
        ob_implicit_flush(false);

        include($file_path);


        if($isLayout) {
            $params = array('content' => ob_get_clean());

            extract($params,EXTR_PREFIX_SAME,'params');

            ob_start();
            ob_implicit_flush(false);
            include($layout);
        }

        if($return) {
            return ob_get_clean();
        }

        echo ob_get_clean();

        return true;
    }
}
