<?php
  class VC_CFB_Page
  {
    protected static $_instance;

    private function __construct()
    {
      add_action( 'vc_menu_page_build', array( $this, 'vc_cfb_about_page' ), 12 );
      add_action( 'admin_init', array( $this, 'vc_cfb_about_page_css_save' ), 12 );
      add_action( 'admin_init', array( $this, 'vc_cfb_about_page_history_remove' ), 12 );
      add_action( 'wp_head', array( 'VC_CFB_Page', 'vc_cfb_custom_css' ) );
    }

    private function __clone() {}

    public static function _instance()
    {
      if ( NULL === self::$_instance)
        self::$_instance = new self();

      return self::$_instance;
    }

    function vc_cfb_about_page()
    {
      $page = add_submenu_page( VC_PAGE_MAIN_SLUG, __( 'CFB', 'vc_cfb' ), __( 'CFB', 'vc_cfb' ), 'manage_options', 'vc-cfb', array( $this, 'vc_cfb_about_page_render' ) );
      add_action( 'admin_print_styles-' . $page, 'vc_page_css_enqueue' );

      wp_register_style( 'vc_cfb_field_pickadate_popup', VC_CFB_Manager::$url.'/css/pages/about/css.css', false, VC_CFB_Manager::$version );   
      wp_enqueue_style( 'vc_cfb_field_pickadate_popup' );

      wp_register_script( 'ace-editor', vc_asset_url( 'lib/bower/ace-builds/src-min-noconflict/ace.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
      wp_enqueue_script( 'ace-editor' );
    }

    function vc_cfb_about_page_render()
    {
      include_once( VC_CFB_Manager::$path.'pages/about.php' );
    }

    static function vc_cfb_page_tab_content( $path, $tab )
    {
      echo '<div class="vc_'.$tab.'-tab changelog">';
      include_once( VC_CFB_Manager::$path.'pages/partials/'.$path.'/_'.$tab.'.php' );
      echo '</div>';
    }

    function vc_cfb_about_page_history_remove()
    {
      if( !is_admin() || !is_user_logged_in() || !isset($_REQUEST['vc_cfb_about_page_history']) )
        return;
      if( !current_user_can( 'edit_themes' ) ):
        add_action( 'admin_notices', array( 'VC_CFB_Page', 'vc_cfb_about_page_save_error_message' ) );
        return;
      endif; 

      $name = htmlspecialchars($_REQUEST['vc_cfb_about_page_history']);
      delete_option( VC_CFB_Element_Form::__history_db_name( $name ) );
      VC_CFB_FGeneral::__remove_files( VC_CFB_Manager::$upload_path.'/'.$name );
      add_action( 'admin_notices', array( 'VC_CFB_Page', 'vc_cfb_about_page_success_remove_message' ) );
    }

    function vc_cfb_about_page_css_save()
    {
      if( !is_admin() || !is_user_logged_in() || !isset($_REQUEST['vc_cfb_custom_css']) )
        return;
      if( !current_user_can( 'edit_themes' ) ):
        add_action( 'admin_notices', array( 'VC_CFB_Page', 'vc_cfb_about_page_save_error_message' ) );
        return;
      endif;

      $value = base64_encode(stripslashes($_REQUEST['vc_cfb_custom_css']));
      update_option( 'vc_cfb_custom_css', $value );
      add_action( 'admin_notices', array( 'VC_CFB_Page', 'vc_cfb_about_page_save_success_message' ) );
    }

    static function vc_cfb_custom_css()
    {
      $custom_css = base64_decode(get_option( 'vc_cfb_custom_css' ));
      if( !empty($custom_css) && $custom_css != '' )
        echo '<style type="text/css">'.VC_CFB_Minify::minify_css($custom_css).'</style>';
    }

    static function vc_cfb_about_page_save_success_message()
    {
      echo "<div class=\"vc_cfb_success_notice vc_cfb_notice\">".__( "Your data was successfully saved.", "vc_cfb" )."</div>";
    }

    static function vc_cfb_about_page_save_error_message()
    {
      echo "<div class=\"vc_cfb_error_notice vc_cfb_notice\">".__( "You don't have permission for this.", "vc_cfb" )."</div>";
    }

    static function vc_cfb_about_page_success_remove_message()
    {
      echo "<div class=\"vc_cfb_success_notice vc_cfb_notice\">".__( "Your data was successfully removed.", "vc_cfb" )."</div>";
    }
  }