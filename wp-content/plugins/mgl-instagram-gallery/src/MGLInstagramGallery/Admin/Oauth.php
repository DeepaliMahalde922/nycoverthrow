<?php
class MGLInstagramGallery_Admin_Oauth {
    const INSTAGRAM_SERVER_API_HOST = 'https://api.instagram.com/oauth/access_token';

    private $client_id = '33b6a56e2faa4515949b9c54a451f462';

    private function get_redirect_uri( $purchase_code )
    {
        $current_encoded_url = urlencode( $this->full_url($_SERVER) );
        return urlencode( "http://www.mageeklab.com/oauth-instagram-gallery-service/?user_uri={$current_encoded_url}&purchase_code=$purchase_code" );
    }

    public function get_authorize_url( $purchase_code )
    {
        $redirect_uri = $this->get_redirect_uri( $purchase_code );
        return "https://api.instagram.com/oauth/authorize/?client_id={$this->client_id}&redirect_uri={$redirect_uri}&response_type=code&scope=public_content";
    }


    //Functions to get current URL
    private function url_origin( $s, $use_forwarded_host = false )
    {
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    private function full_url( $s, $use_forwarded_host = false )
    {
        return $this->url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
    }

}
