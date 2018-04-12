<?php
  class VC_CFB_Element_Field_Recaptcha extends VC_CFB_Element_Field
  {
    /* tab index */
    protected $tab;
    protected $key;
    protected $secret_key;
    protected $widget_type;
    protected $hl;
    protected $theme;
    protected $url;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'recaptcha';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'           => '',
        'key'           => '',
        'secret_key'    => '',
        'type'          => 'image',
        'hl'            => '',
        'theme'         => 'light',
        'size'          => 'normal',
        'tab'           => '',
        'classes'       => '',
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->key = $attr['key'];
      $this->secret_key = $attr['secret_key'];
      $this->widget_type = $attr['type'];
      $this->hl = $attr['hl'];
      $this->theme = $attr['theme'];
      $this->size = $attr['size'];

      $this->classes[] = 'g-recaptcha';
      $this->id = 'g-rec-'.$this->name.'-'.mt_rand();

      $url = array();
      if( !empty($this->hl) )
        $url[] = 'hl='.$this->hl;

      $url[] = 'onload=reCaptchaOnloadCallback';
      $url[] = 'render=explicit';

      $this->url = 'https://www.google.com/recaptcha/api.js'.( sizeof($url) > 0 ? '?'.implode('&', $url) : '' );
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'reCAPTCHA 2.0', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/recaptcha' );
      $this->settings['description'] = __( 'Protect your site from spam', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __set_value()
    {
      if( isset($_REQUEST['g-recaptcha-response']) )
        $this->value = htmlspecialchars($_REQUEST['g-recaptcha-response']);
    }

    protected function __validate() 
    {
      if( $this->value == '' ):
        $this->valid = FALSE;
        $this->validation_messages[] = 'captcha_enter';
        return;
      endif;

      require_once VC_CFB_Manager::$path."modules/reCaptcha.php";
      $reCaptcha = new ReCaptcha( $this->secret_key );

      $response = $reCaptcha->verifyResponse(
          $_SERVER["REMOTE_ADDR"],
          $this->value
      );
      if( $response != null && $response->success )
        $this->valid = TRUE;
      else{
        $this->valid = FALSE;
        $this->validation_messages[] = 'captcha';
      }
    }

    public function __get_value()
    {
      return apply_filters( 'cfb_field_get_value', NULL, $this->name, $this->type, $this );
    }

    protected function __render()
    {
      $form = VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ];
      if( $form->ajax && $form->valid !== NULL ):
    ?>
        <script type="text/javascript">
              reCaptchaOnloadCallback();
        </script>
    <?php endif;?>
      <div 
        data-sitekey="<?= $this->key;?>" 
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        data-type="<?= $this->widget_type;?>"
        data-size="<?= $this->size;?>"
        data-theme="<?= $this->theme;?>"
        <?= !empty($this->tab) ? 'data-tabindex="'.$this->tab.'"' : ''; ?>
      ></div>
<?php
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_field_wrapper_classes', array( 'VC_CFB_Element_Field_Recaptcha', '__add_wrapper_classes' ), 10, 2 );
    }

    public static function __add_wrapper_classes( $classes, $field )
    {
      if( $field->type == 'recaptcha' )
        $classes[] = 'g-recaptcha-wrapper';

      return $classes;
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_register_script( 'vc_cfb_field_recaptcha', $this->__get_api_url( 'vc_cfb_field_recaptcha' ), array(), '2.0' );   
      wp_enqueue_script( 'vc_cfb_field_recaptcha' );

      wp_register_script( 'vc_cfb_field_recaptcha_handler', VC_CFB_Manager::$url.'/js/recaptcha_handler.js', array( 'vc_cfb_field_recaptcha' ), VC_CFB_Manager::$version );   
      wp_enqueue_script( 'vc_cfb_field_recaptcha_handler' );
    }
  }