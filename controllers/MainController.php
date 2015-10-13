<?php
namespace controllers;

use components\Helper;
use config\Config;
use model\UserInfo;

/**
 * Class MainController
 * Name of this class set in Config file
 * @package controllers
 */
class MainController extends Controller
{
    /**
     * Method login with scopes and init in Instagram
     * @throws \Exception
     */
    public function indexAction()
    {
        $scopes = ['basic', 'comments', 'relationships', 'likes'];
        Helper::redirectToUrl($this->instagram->getLoginUrl($scopes));
    }

    /**
     * Main method that view main grid with user's media
     * Name of this action set in Config file
     * @return bool
     */
    public function viewAction()
    {
        // get request
        $code = Helper::requestGet('code', null);
        $userId = Helper::requestGet('userId', null);
        $userName = Helper::requestGet('userName', null);

        // get token string
        $token = $this->getTokenByCode($code);

        // set user access token
        $this->instagram->setAccessToken($token);

        $userInfo = [];

        if (!$userId || $userName) {

            // get user id after send query to api with user name
            $userId = $this->getUserIdByName($userName);
        }

        // get user information
        $userData = $this->instagram->getUser($userId);

        // check for error
        $this->hasError($userData);

        // check for success response
        if ($userData->meta->code == self::SUCCESS_RESPONSE && !empty($userData->data)) {
            $userInfo = $userData->data;
        }

        // init User info model
        $userInfo = new UserInfo($userInfo);

        // get media data
        $media = $this->instagram->getUserMedia($userId, self::COUNT_MEDIA_DATA_LIMIT);

        // check for error
        $this->hasError($media);

        // check for success response
        if ($media->meta->code == self::SUCCESS_RESPONSE && !empty($media->data)) {

            // detector, if next url exists, by default
            $hasNextUrl = false;

            // check if next url exist in response
            if (isset($media->pagination->next_url)) {

                // set detector as exist
                $hasNextUrl = true;

                // set pagination next url
                $this->setMediaNextUrlByCode($code, $media->pagination->next_url);
            }

            // options for view rendering
            $options = [
                'c' => Config::get('defaultController'),
                'a' => Config::get('mainViewAction'),
                'a_next' => 'next',
                'code' => $code,
                'userId' => $userInfo->id,
                'userName' => $userInfo->username,
                'hasNextUrl' => $hasNextUrl,
            ];

            // rendering view with grid
            Helper::renderStatic('main/viewMedia', compact('media', 'options', 'userInfo'));
        }

        return true;
    }

    /**
     * Method that work with Ajax
     */
    public function nextAction()
    {
        // check if request is ajax
        if (Helper::isAjaxRequest()) {
            // get request
            $code = Helper::requestGet('code', null);
            $userName = Helper::requestGet('userName', null);

            // get next url string
            $url = $this->getMediaNextUrlByCode($code);

            if ($url) {
                // do request for content
                $media = file_get_contents($url);

                // decode json
                $media = json_decode($media);

                // check for error
                $this->hasError($media);

                // check if json valid and response success
                if (!json_last_error() && $media->meta->code == self::SUCCESS_RESPONSE && !empty($media->data)) {
                    $hasNextUrl = false;

                    if (isset($media->pagination->next_url)) {
                        $hasNextUrl = true;

                        // set next pagination url
                        $this->setMediaNextUrlByCode($code, $media->pagination->next_url);
                    }

                    // options for view rendering
                    $options = [
                        'c' => Config::get('defaultController'),
                        'a_next' => 'next',
                        'code' => $code,
                        'userName' => $userName,
                        'hasNextUrl' => $hasNextUrl,
                    ];

                    // rendering view without layout
                    Helper::renderStatic('main/_viewMediaMore', compact('media', 'options'), false);
                    exit;
                }
            }
        }
    }

    /**
     *
     */
    public function likesDetailsAction()
    {
        // get requests
        $code = Helper::requestGet('code', null);
        $mediaId = Helper::requestGet('mediaId', null);
        $userName = Helper::requestGet('userName', null);

        // get token value
        $token = $this->getTokenByCode($code);

        $this->instagram->setAccessToken($token);

        // get media data from api
        $data = $this->instagram->getMediaLikes($mediaId);

        // check for errors
        $this->hasError($data);

        // check for success response
        if ($data->meta->code == self::SUCCESS_RESPONSE && !empty($data->data)) {
            // get data
            $media = $data->data;

            // set option for rendering views
            $options = compact('code', 'userId', 'userName');

            // render view
            Helper::renderStatic('main/likesDetails', compact('media', 'options'));
            exit;
        }
    }

    /**
     *
     */
    public function commentsDetailsAction()
    {
        // get requests
        $code = Helper::requestGet('code', null);
        $mediaId = Helper::requestGet('mediaId', null);
        $userName = Helper::requestGet('userName', null);

        // get token value
        $token = $this->getTokenByCode($code);

        $this->instagram->setAccessToken($token);

        // get media comments
        $data = $this->instagram->getMediaComments($mediaId);

        // check for errors
        $this->hasError($data);

        // check for success response
        if ($data->meta->code == self::SUCCESS_RESPONSE && !empty($data->data)) {
            // get data
            $media = $data->data;

            // set option for rendering views
            $options = compact('code', 'userId', 'userName');

            // render view
            Helper::renderStatic('main/commentsDetails', compact('media', 'options'));
            exit;
        }
    }

    /**
     * Method return followers
     */
    public function followersAction()
    {
        // get requests
        $code = Helper::requestGet('code', null);
        $userName = Helper::requestGet('userName', null);

        if ($userName) {
            // get token value
            $token = $this->getTokenByCode($code);

            $this->instagram->setAccessToken($token);

            // get user id by user name
            $userId = $this->getUserIdByName($userName);

            $data = $this->instagram->getUserFollower($userId, self::COUNT_FOLLOWERS_DATA_LIMIT);

            $this->hasError($data);

            // check for success response
            if ($data->meta->code == self::SUCCESS_RESPONSE && !empty($data->data)) {
                $hasNextUrl = false;

                if (isset($data->pagination->next_url)) {
                    $hasNextUrl = true;

                    // set followers pagination next link
                    $this->setFollowersNextUrlByCode($code, $data->pagination->next_url);
                }

                $media = $data->data;

                // set params for rendering
                $options = [
                    'c' => Config::get('defaultController'),
                    'a' => 'followers',
                    'a_next' => 'nextFollowers',
                    'code' => $code,
                    'userName' => $userName,
                    'hasNextUrl' => $hasNextUrl,
                ];

                Helper::renderStatic('main/followers', compact('media', 'options'));

                exit;
            }
        }
    }

    /**
     * Method return paginations fo followers
     */
    public function nextFollowersAction()
    {
        // check if request is Ajax
        if (Helper::isAjaxRequest()) {
            // get request
            $code = Helper::requestGet('code', null);
            $userName = Helper::requestGet('userName', null);

            // get followers next url
            $url = $this->getFollowersNextUrlByCode($code);

            if ($url) {
                // get next followers content
                $media = file_get_contents($url);

                $media = json_decode($media);

                // check for error
                $this->hasError($media);

                if (!json_last_error() && $media->meta->code == self::SUCCESS_RESPONSE && !empty($media->data)) {
                    $hasNextUrl = false;

                    if (isset($media->pagination->next_url)) {
                        $hasNextUrl = true;

                        $this->setFollowersNextUrlByCode($code, $media->pagination->next_url);
                    }

                    $media = $media->data;

                    $options = [
                        'c' => Config::get('defaultController'),
                        'a_next' => 'nextFollowers',
                        'code' => $code,
                        'userName' => $userName,
                        'hasNextUrl' => $hasNextUrl,
                    ];

                    Helper::renderStatic('main/_followersList', compact('media','hasNextUrl','options'), false);

                    exit;
                }
            }
        }
    }


    /**
     *
     */
    public function followsAction()
    {
        $code = Helper::requestGet('code', null);
        $userName = Helper::requestGet('userName', null);

        if ($userName) {

            $token = $this->getTokenByCode($code);

            $this->instagram->setAccessToken($token);

            $search = $this->instagram->searchUser($userName, self::COUNT_SEARCH_USER);

            $this->hasError($search);

            if ($search->meta->code == self::SUCCESS_RESPONSE && !empty($search->data)) {
                $userId = current($search->data)->id;

                $data = $this->instagram->getUserFollows($userId, self::COUNT_FOLLOWS_DATA);

                $this->hasError($data);

                if ($data->meta->code == self::SUCCESS_RESPONSE && !empty($data->data)) {
                    $media = $data->data;

                    $options = [
                        'c' => Config::get('defaultController'),
                        'a' => 'follows',
                        'code' => $code,
                        'userName' => $userName,
                    ];

                    Helper::renderStatic('main/follows', compact('media', 'options'));

                    exit;
                }
            }
        }
    }
}
