<?php

/**
 * Class MGL_InstagramGalleryModel
 * Information retrieving system. Deals with every Instagram request either if it is cached or from Instagram servers
 */
class MGLInstagramGallery_Model_Model
{

    const INSTAGRAM_API_BASE_URL = 'https://api.instagram.com/v1/';

    // Base of Instagram URL request
    const CONNECTION_ATTEMPTS = 3;
    public $instagramAccount,
        $access_token;

    public function __construct()
    {
        // User's access token
        $this->access_token = $this->get_access_token();
    }

    /**
     * get_access_token Returns the access token
     * @return mixed|void
     * @throws Exception
     */
    public function get_access_token()
    {
        global $mgl_ig;

        // Get access token stored on Wordpress DB
        $access_token = get_option('mgl_instagram_access_token');
        $instagram_gallery_options = get_option('MGLInstagramGallery_option_name');
        $access_token = '';
        if (isset($instagram_gallery_options['configuration']['access_token'])) {
            $access_token = $instagram_gallery_options['configuration']['access_token'];
        }

        if ($access_token == '') {
            // Log error. Access token not found
            $mgl_ig['logger']->error('Access Token not found');

            // Throw exception if access token does not exists
            throw new Exception(sprintf(__("Error: You don't have an access token, have you configurated correctly the plugin from %s?", 'mgl_instagram_gallery'), '<a href="' . site_url() . '/wp-admin/options-general.php?page=instagram-gallery-settings" target="_blank">' . __('here', 'mgl_instagram_gallery') . '</a>'));
        }

        return $access_token;
    }

    /**
     * get_response Return cached Instagram response if it exits or from Instagram servers
     * @param $url parcial url to make a Instagram request
     * @param $params Array of params to build Instagram request URL
     * @param $cacheTime The time the new Instagram response will be cached (Also affect to retrieve a chached one)
     * @return array|bool|mixed|object Instagram Response
     * @throws Exception
     */
    public function get_response($url, $params, $cacheTime)
    {
        // Build the base request URL
        $url = self::INSTAGRAM_API_BASE_URL . $url;
        // Build request params
        $atts_query = (is_array($params)) ? http_build_query($params) : '';
        // Encode request URL and cache time to get a Key. This will be the cache key
        $cachekey = base64_encode($url . $atts_query . $cacheTime);
        // Get cached response
        $cached_response = $this->get_cached_response($cachekey);

        if (false !== $cached_response) {
            // Return cached response if it exists
            return $cached_response;
        }
        // Get response from Instagram servers
        $response = $this->get_remote_response($url, $params, $cacheTime);

        if ($response->meta->code == '200' && $cacheTime > 0) {
            // Cache response if it returns a status 200 value
            set_transient($cachekey, base64_encode(serialize($response)), $cacheTime);
        }

        return $response;
    }

    /**
     * get_cached_response Return cached response if exists to avoid retrieving information from Instagram servers. It
     * improves dramatically the performance
     * @param $key
     * @return bool|mixed
     */
    public function get_cached_response($key)
    {
        // Retrieve cached response by key if it exists
        $cached_response = get_transient($key);

        // If cached response was not found, return false
        if ($cached_response === false) return false;

        // Decode cached response and unserialize it to return it
        return unserialize(base64_decode($cached_response));
    }

    /**
     * get_remote_response Try to retrieve a response from Instagram Servers, if after all the attemps the connection fails,
     * it throws an exception
     * @param $url
     * @param $params
     * @return array|bool|mixed|object
     * @throws Exception
     */
    public function get_remote_response($url, $params)
    {
        global $mgl_ig;

        // Add access token to request params
        $params['access_token'] = $this->access_token;

        // Built request query with params
        $url_with_args = add_query_arg($params, $url);

        // Create a WP_Http;
        $request = new WP_Http;

        // Make attemps to retrieve a response
        for ($i = 0; $i < self::CONNECTION_ATTEMPTS; $i++) {
            // Make a GET request to the Instagram Servers
            $result = $request->request(
                $url_with_args,
                array(
                    'method' => 'GET',
                    'headers' => array()
                )
            );

            // If the response is OK, break the loop
            if (!is_wp_error($result)) break;

            // Log warning message. Impossible to connect with Instagram servers
            $mgl_ig['logger']->warning('Impossible to connect with Instagram servers. Attempt:' . ($i + 1));
        }

        if (is_wp_error($result)) {
            // Log error message.
            $mgl_ig['logger']->error('Connection with Instagram servers was impossible. Error: ' . $result->get_error_message());

            // If after all the attempts the connection fails, throw an exception
            throw new Exception($result->get_error_message());
        }

        if ($result['response']['code'] != 200) {
            // Log error message.
            $mgl_ig['logger']->error('Error response. Error: ' . $result['body'] . ' Request was: ' . $url_with_args);

            // If response code is not 200, show an Instagram response error
            $this->showInstagramResponseError(json_decode($result['body']));

            return false;
        }

        // Return a json encoded response
        return json_decode($result['body']);
    }

    /**
     * showInstagramResponseError throws an exception with information about why the response failed
     * @param $result
     * @throws Exception
     */
    public function showInstagramResponseError($result)
    {
        // Get meta information about why the response failed
        $result = $result->meta;
        // Throw an exception with information about failed response
        throw new Exception(__('Error: Cannot retrive photos from Instagram.', 'mgl_instagram_gallery') . 'Response code: ' . $result->code . '. Message error: ' . $result->error_message . '. Error type: ' . $result->error_type);
    }

    /**
     * get_user_id Returns the user id either is cached or from Instagram servers
     * @param $username Username to found user id
     * @return bool|mixed
     * @throws Exception
     */
    public function get_user_id($username)
    {
        // TODO: Use get_response way instead this if it is possible
        // Get lower-cased version of username
        $username = strtolower($username);
        // Get a key for the cached user id
        $key = "mgl_ins_user_" . $username . '_id';
        // Get cached user id
        $cached_user_id = get_transient($key);
        // Return cached user id if it exists
        if (false !== $cached_user_id) return $cached_user_id;
        // Get user info from Instagram Servers
        $response = $this->get_remote_response(self::INSTAGRAM_API_BASE_URL . "users/search", array('q' => $username), 0);
        // Get users from Instagram response
        $users = $response->data;

        if (isset($users)) {
            // Loop through them ( Instagram doesn't return users in best match order )
            foreach ($users as $user) {
                // Search for an user with the exact name we're searching
                if ($user->username == $username) {
                    // Cache the username and return it
                    set_transient($key, $user->id, 86400);
                    return $user->id;
                }
            }
        }
        // Return false if nothing was found
        return false;
    }
}
