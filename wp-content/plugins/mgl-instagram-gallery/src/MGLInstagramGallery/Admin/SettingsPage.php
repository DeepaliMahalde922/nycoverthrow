<?php
class MGLInstagramGallery_Admin_SettingsPage extends MGLInstagramGallery_Admin_WPSubPage {

  public function render_settings_page() {
    $current_tab = ( isset($_GET['tab']) ) ?  $_GET['tab'] : 'instagram-app';
    $option_name = $this->settings_page_properties['option_name'];
    $option_group = $this->settings_page_properties['option_group'];
    $settings_data = $this->get_settings_data();

     if( isset($_GET['access_token'])) $this->save_access_token();
     if( isset($_GET['delete_instagram_account']) ) $this->remove_access_token();
     mgl_instagram_render_flash_message_bag();
    ?>
    <div class="wrap">
      <h2>Instagram Gallery</h2>

      <?php $this->render_tabs( $current_tab ); ?>
      <?php $this->render_tab_content( $current_tab ); ?>

    </div>
    <?php
  }

  public function get_default_settings_data()
  {

    $defaults = array(
      'configuration' => array(
          'jquery' => 1,
          'access_token' => '',
          'observer' => 0,
          'purchase_code' => '',
          'log' => 0
          ),
      'settings' => array(
          'cols'          => 4,
          'template'      => 'default',
          'custom_templates'  => '',
          'count'         => 12,
          'cache'         => 3600,
          )
    );

    return $defaults;
  }

  public function get_settings_data(){
    $option = get_option( $this->settings_page_properties['option_name'], $this->get_default_settings_data() );

    if( !is_array( $option ) ) {
      $option =  $this->get_default_settings_data();
    }

    return $option;
  }

  public function render_tabs( $current = 'instagram-app' )
  {
        $tabs = array(
            'instagram-app' => __('Instagram application',MGL_INSTAGRAM_GALLERY_DOMAIN),
            'configuration' => __('Configuration',MGL_INSTAGRAM_GALLERY_DOMAIN),
            'documentation' => __('Documentation',MGL_INSTAGRAM_GALLERY_DOMAIN),
            'status'        => __('Plugin status',MGL_INSTAGRAM_GALLERY_DOMAIN)
        );

        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page={$this->settings_page_properties["menu_slug"]}&tab=$tab'>$name</a>";
        }
        echo '</h2>';
  }

  public function render_tab_content( $tab )
  {
    global $mgl_ig;
    $tab_filepath = $mgl_ig['path'].'src/MGLInstagramGallery/Admin/Views/'.$tab.'.php';

    $option_name = $this->settings_page_properties['option_name'];
    $option_group = $this->settings_page_properties['option_group'];
    $settings_data = $this->get_settings_data();

    if( isset($settings_data['configuration']['purchase_code'] ) && '' !== esc_attr( $settings_data['configuration']['purchase_code'] ) )
    {
      $access_token = '';

      if(isset($settings_data['configuration']['access_token'])) {
        $access_token = $settings_data['configuration']['access_token'];
      }

      $oauth = new MGLInstagramGallery_Admin_Oauth();
      $authorize_url =  $oauth->get_authorize_url( $settings_data['configuration']['purchase_code'] );
    }

    if( file_exists( $tab_filepath )) {
      include $tab_filepath;
    }
  }

  public function load_styles()
  {

    if ( ('settings_page_' . $this->settings_page_properties["menu_slug"]) == get_current_screen()->id) {
        wp_enqueue_style("mgl_instagram_admin", MGL_INSTAGRAM_GALLERY_URL_BASE . "assets/css/mgl_instagram_admin.css", false, "1.0", "all" );
    }

    if( 'widgets' === get_current_screen()->id){
        wp_enqueue_style("mgl_instagram_admin", MGL_INSTAGRAM_GALLERY_URL_BASE . "assets/css/mgl_instagram_admin.css", false, "1.0", "all" );
        wp_enqueue_script("mgl_instagram_admin_widgets", MGL_INSTAGRAM_GALLERY_URL_BASE . "/widgets.js", false, "1.0", "all" );
    }
  }

  public function save_access_token(){
    $access_token = $_GET['access_token'];

    $message =  __('<strong>App authorized!</strong> You are ready to go', MGL_INSTAGRAM_GALLERY_DOMAIN);

    mgl_instagram_add_flash_message( $message , 'success' );

    $settings_data = $this->get_settings_data();
    $settings_data['configuration']['access_token'] = $access_token;

    update_option( 'MGLInstagramGallery_option_name', $settings_data );
  }

  public function remove_access_token(){
      $settings_data = $this->get_settings_data();
      $settings_data['configuration']['access_token'] = '';

      update_option( 'MGLInstagramGallery_option_name', $settings_data );

      $messageSettingsSaved = __('Application settings removed correctly', MGL_INSTAGRAM_GALLERY_DOMAIN );
      //mgl_instagram_add_flash_message( $messageSettingsSaved, 'success' );
      wp_redirect( menu_page_url( $this->settings_page_properties["menu_slug"], false ) );

  }
}
