<?php
  /**
  * Plugin Name: Custom Forms Builder for Visual Composer
  * Plugin URI: http://cfb.morfim.net/
  * Description: Custom Forms Builder allows you create, manage and edit your forms very easily. Now you can create new forms with drag and drop form element without touching a single line of code. And yes, you can easily edit them whenever you wish! Maximum customization of code for showing - no programming knowledge required. You can create any forms in a few steps: Booking forms, Contact form or login form, it's very easy! Plugin is friendly for developers. It has many custom hooks (actions and filters) for programmatically customisations forms and fields.
  * Version: 1.0.2.24
  * Author: Morfi
  * Author URI: https://codecanyon.net/user/morfi/?ref=morfi
  **/

  add_action( 'plugins_loaded', 'vc_cfb_init' );
  register_activation_hook( __FILE__, array( 'VC_CFB_Manager', 'set_default_global_settings' ) );

  function vc_cfb_init()
  {
    if( class_exists( 'WPBMap' ) )
      VC_CFB_Manager::_instance();
  }

  class VC_CFB_Manager
  {
    protected static $_instance;

    static $path;
    static $url;
    static $version;
    static $release;

    static $action = 'vc_cfb';
    static $slug = 'vc_cfb/vc_cfb.php';
    static $upload_path = '';
    static $upload_url = '';
    
    /* list all fields for CFB */
    static $elements = array();

    /* list of forms for current page */
    /* form_name = Instance_of_Form */
    static $forms = array();
    /* name of current form */
    static $current_form = '';

    /* Current Page, Post, Custom post type ID */
    static $object_id;

    /* List of registered description for hooks */
    static $hooks;

    private function __construct()
    {
      self::$path = dirname(__FILE__).'/include/';
      self::$url = plugins_url( '', __FILE__ ).'/assets';

      self::$upload_path = wp_upload_dir();
      self::$upload_url = self::$upload_path['baseurl'].'/'.self::$action;
      self::$upload_path = self::$upload_path['basedir'].'/'.self::$action;
      if ( !file_exists( self::$upload_path ) )
        wp_mkdir_p( self::$upload_path );

      self::$version = self::__plugin_info( 'Version' );
      self::$release = self::__plugin_info( 'Release' );

      include_once( 'include/modules/minify.php' );
      include_once( 'include/modules/general.php' );

      register_deactivation_hook( __FILE__, array( $this, 'remove_default_global_settings' ) );

      include_once( 'include/classes/vc_cfb_updater.php' );  

      add_action( 'admin_init', array( $this, 'vc_cfb_dashboard_actions' ) );
      
      load_plugin_textdomain( 'vc_cfb', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
          
      include_once( 'include/classes/vc_cfb_page.php' );    
      include_once( 'include/classes/vc_cfb_custom_param.php' );    
      include_once( 'include/classes/vc_cfb_shortcode.php' );    
      include_once( 'include/classes/vc_cfb_element.php' );    
      include_once( 'include/classes/vc_cfb_element_field.php' );      

      VC_CFB_Page::_instance();
      
      /* Init custom params */
      VC_CFB_Custom_Param::__init_params();

      /* Init Form and fields for Form */
      VC_CFB_Shortcode::__init_elements_schedule();

      if( class_exists( 'VC_CFB_Element_Form' ) ):
        add_action( 'wp_ajax_'.wp_create_nonce( self::$action.'_ajax_form_submit' ), array( 'VC_CFB_Element_Form', 'vc_cfb_submit_request' ) );
        add_action( 'wp_ajax_nopriv_'.wp_create_nonce( self::$action.'_ajax_form_submit' ), array( 'VC_CFB_Element_Form', 'vc_cfb_submit_request' ) );
        add_action( 'wp_ajax_'.wp_create_nonce( self::$action.'_requests_history_list' ), array( 'VC_CFB_Element_Form', '__ajax_requests_history_list_answer' ) );
        add_action( 'init', array( 'VC_CFB_Element_Form', 'vc_cfb_submit_request' ) );
      endif;
      
      self::__register_hook_description( 'cfb_elements_titles', VC_CFB_Manager::$path.'/hooks/cfb_elements_titles.php' );
    }

    private function __clone() {}

    public static function _instance()
    {
      if ( NULL === self::$_instance)
        self::$_instance = new self();

      return self::$_instance;
    }

    static function set_default_global_settings()
    {
      if ( ! is_network_admin() )
        set_transient( '_vc_cfb_page_about_redirect', 1, 30 );
    }

    static function remove_default_global_settings()
    {
      delete_transient( '_vc_cfb_page_about_redirect' );
      //delete_option( 'vc_cfb_custom_css' );
    }

    static function vc_cfb_dashboard_actions()
    {
      VC_CFB_Updater::_instance();


      /* About redirect */
      $redirect = get_transient( '_vc_cfb_page_about_redirect' );
      delete_transient( '_vc_cfb_page_about_redirect' );
      $redirect && wp_redirect( admin_url( 'admin.php?page=vc-cfb' ) );
    }

    static function __elements_titles()
    {
      $elements = array_diff( scandir( self::$path.'elements' ), array( '.', '..', 'fields' ) );
      $elements = str_replace( '.php', '', $elements );
      
      $childs = array();
      foreach( self::$elements as $key => $value )
        if( !in_array( $key, $elements ) )
          $childs[] = $value->shortcode_name;

      
      return apply_filters( 'cfb_elements_titles', $childs );
    }

    static function __register_hook_description( $hook, $path, $category = 'General' )
    {
      if( $category == 'General' )
        $category = __( 'General', 'vc_cfb' );

      if( isset(self::$hooks[$hook]) )
        return;

      self::$hooks[$hook] = array( 'path' => $path, 'category' => $category );
    }

    static function __sort_hooks_by_cateogries()
    {
      $array = '';
      foreach( VC_CFB_Manager::$hooks as $name => $hook ):
        if( !isset($array[$hook['category']]) )
          $array[$hook['category']] = array();

        $array[$hook['category']][$name] = $hook['path'];
      endforeach;

      return $array;
    }

    static function __get_hook_description( $hook )
    {
      if( !isset(self::$hooks[$hook]) )
        return;

      include_once( self::$hooks[$hook]['path'] );
    }

    static function __plugin_info( $name )
    {
      $data = get_plugin_data( VC_CFB_Manager::$path.'/../vc_cfb.php' );
      
      if( $name == 'Release' )
      {
        $d = explode( '.', $data['Version'] );
        return $d[ sizeof($d) - 1 ];
      }
      return $data[$name];
    }
  }