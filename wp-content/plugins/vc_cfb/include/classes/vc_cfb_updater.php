<?php
  class VC_CFB_Updater
  {
    protected static $_instance;

    protected static $changelog = '';

    protected static $url = 'http://cfb.morfim.net/release';

    private function __construct()
    {
      add_filter( 'pre_set_site_transient_update_plugins', array( 'VC_CFB_Updater', '__check_update' ) );
      add_filter( 'plugins_api', array( 'VC_CFB_Updater', '__check_info' ), 20, 3 );
    }

    static function __check_update( $transient )
    {
      if( (int)self::__info( 'release' ) > (int)VC_CFB_Manager::$release )
      {
        $obj = new stdClass();
        $obj->slug = VC_CFB_Manager::$action;
        $obj->new_version = self::__new_version();
        $obj->url = VC_CFB_Manager::__plugin_info( 'PluginURI' );
        $obj->name = VC_CFB_Manager::__plugin_info( 'Name' );
        $obj->tested = self::__last_wp_version();
        $obj->plugin_slug = VC_CFB_Manager::__plugin_info( 'PluginURI' );

        $transient->response[ VC_CFB_Manager::$slug ] = $obj;
      }
      return $transient;
    }

    static function __check_info( $false, $action, $arg )
    {
      if ( isset( $arg->slug ) && $arg->slug === VC_CFB_Manager::$action ) 
      {
        $information->name = VC_CFB_Manager::__plugin_info( 'Name' );
        $information->banners = array( 'high' => self::__info( 'preview' ) );
        $information->sections[__( 'Description', 'vc_cfb' )] = VC_CFB_Manager::__plugin_info( 'Description' );
        $information->tested = self::__last_wp_version();
        $information->homepage = 'http://codecanyon.net/item/custom-forms-builder-for-visual-composer/13712855?ref=morfi';
        $information->version = self::__new_version();
        $information->slug = VC_CFB_Manager::$action;
        $information->last_updated = self::__info( 'last_updated' );

        return $information;
      }else
        return $false;
    }

    private function __clone() {}

    public static function _instance()
    {
      if ( NULL === self::$_instance)
        self::$_instance = new self();

      return self::$_instance;
    }

    private static function __info( $name )
    {
      $request = wp_remote_post( self::$url );
      if( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 )
      {
        $request = json_decode( $request['body'], TRUE );
        return $request[$name];
      }
      else
        return null;
    }

    private static function __last_wp_version()
    {
      $request = wp_remote_post( 'https://api.wordpress.org/core/version-check/1.7/' );
      if( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 )
      {
        $request = json_decode( $request['body'], TRUE );
        return $request['offers'][0]['current'];
      }else{
        global $wp_version;
        return $wp_version;
      }
    }

    private static function __new_version()
    {
      return str_replace( '.'.VC_CFB_Manager::$release, '.'.self::__info( 'release' ), VC_CFB_Manager::$version );
    }
  }