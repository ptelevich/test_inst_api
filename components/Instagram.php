<?php
namespace components;

class Instagram extends \MetzWeb\Instagram\Instagram
{
    /**
     * Whether a signed header should be used
     *
     * @var boolean
     */
    private $_signedheader = false;
    private $_accesstoken;

    /**
     * The call operator
     *
     * @param string $function              API resource path
     * @param array [optional] $params      Additional request parameters
     * @param boolean [optional] $auth      Whether the function requires an access token
     * @param string [optional] $method     Request type GET|POST
     * @return mixed
     */
    protected function _makeCall($function, $auth = false, $params = null, $method = 'GET') {
        $this->_accesstoken = $this->getAccessToken();
        if (false === $auth) {
            // if the call doesn't requires authentication
            $authMethod = '?client_id=' . $this->getApiKey();
            if($this->_accesstoken) {
                $authMethod .= '&access_token=' . $this->_accesstoken;
            }
        } else {
            // if the call needs an authenticated user
            if (true === isset($this->_accesstoken)) {
                $authMethod = '?access_token=' . $this->getAccessToken();
            } else {
                throw new \Exception("Error: _makeCall() | $function - This method requires an authenticated users access token.");
            }
        }

        if (isset($params) && is_array($params)) {
            $paramString = '&' . http_build_query($params);
        } else {
            $paramString = null;
        }

        $apiCall = self::API_URL . $function . $authMethod . (('GET' === $method) ? $paramString : null);

        // signed header of POST/DELETE requests
        $headerData = array('Accept: application/json');
        if (true === $this->_signedheader && 'GET' !== $method) {
            $headerData[] = 'X-Insta-Forwarded-For: ' . $this->_signHeader();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ('POST' === $method) {
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
        } else if ('DELETE' === $method) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $jsonData = curl_exec($ch);
        if (false === $jsonData) {
            throw new \Exception("Error: _makeCall() - cURL error: " . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($jsonData);
    }

    /**
     * Sign header by using the app's IP and its API secret
     *
     * @return string                       The signed header
     */
    private function _signHeader() {
        $ipAddress = $_SERVER['SERVER_ADDR'];
        $signature = hash_hmac('sha256', $ipAddress, $this->getApiSecret(), false);
        return join('|', array($ipAddress, $signature));
    }
}
