<?php
namespace components;

use config\config;
use controllers\Controller;

/**
 * Class Init
 * @package components
 */
class Init
{
    const COUNT_SEARCH_USER = 1; // necessary always 1

    const COUNT_MEDIA_DATA_LIMIT = 10;
    const COUNT_FOLLOWERS_DATA_LIMIT = 50;
    const SUCCESS_RESPONSE = 200;
    const INVALID_RESPONSE = 400;
    const HTTP_NOT_FOUND = 404;
    const COUNT_FOLLOWS_DATA = 1000;

    /**
     * @var Instagram
     */
    protected $instagram;

    /**
     * @var string
     */
    protected static $prefixSessionToken = 'instagramToken_';

    /**
     *
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // init Instagram library
        $this->instagram = new Instagram(array(
            'apiKey'      => Config::get('apiKey'),
            'apiSecret'   => Config::get('apiSecret'),
            'apiCallback' => Config::get('apiCallback')
        ));
    }

    /**
     * @return Instagram
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param $code
     * @return bool
     */
    public function setToken($code)
    {
        $auth = $this->instagram->getOAuthToken($code);
        $this->hasError($auth);
        if(isset($auth->access_token)) {
            $token = $auth->access_token;

            $this->setSession(self::$prefixSessionToken.$code, $token);
            return true;
        }
        return false;
    }

    /**
     * Method return user id after send query to api with user name
     * @param string $userName
     * @return string
     */
    protected function getUserIdByName($userName)
    {
        $userId = 'self';

        if ($userName) {
            $search = $this->instagram->searchUser($userName, self::COUNT_SEARCH_USER);
            $this->hasError($search);
            if($search->meta->code == self::SUCCESS_RESPONSE && !empty($search->data)) {
                $userId = current($search->data)->id;
            }
        }

        return $userId;
    }

    /**
     * Method return token string
     * @param string $code
     * @return string or null
     */
    protected function getTokenByCode($code)
    {
        return $this->getSession('instagramToken_'.$code);
    }

    /**
     * @param string $code
     * @return string or null
     */
    protected function getMediaNextUrlByCode($code)
    {
        return $this->getSession('instagramToken_'.$code.'_nextUrl');
    }

    /**
     * @param string $code
     * @param $value
     */
    protected function setMediaNextUrlByCode($code, $value)
    {
        $this->setSession('instagramToken_'.$code.'_nextUrl', $value);
    }

    /**
     * @param string $code
     * @return string or null
     */
    protected function getFollowersNextUrlByCode($code)
    {
        return $this->getSession('instagramToken_'.$code.'_nextUrlFollowers');
    }

    /**
     * @param string $code
     * @param string $value
     */
    protected function setFollowersNextUrlByCode($code, $value)
    {
        $this->setSession('instagramToken_'.$code.'_nextUrlFollowers', $value);
    }

    /**
     * @param $result
     * @return bool
     */
    protected function hasError($result)
    {
        if(empty($result)) {
            Helper::HException('No data found', self::HTTP_NOT_FOUND);
        }
        if(
            (isset($result->code, $result->error_message) && $result->code === self::INVALID_RESPONSE) ||
            (isset($result->meta, $result->meta->code) && $result->meta->code === self::INVALID_RESPONSE)
        ) {
            $error_message = isset($result->error_message) ? $result->error_message : $result->meta->error_message;
            $error_code = isset($result->code) ? $result->code : $result->meta->code;
            Helper::HException($error_message, $error_code);
        }

        return false;
    }

    /**
     * @param $name
     * @return null
     */
    protected function getSession($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }
}
