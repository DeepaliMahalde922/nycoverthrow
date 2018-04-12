<?php
  class VC_CFB_Element_Field_Pickatime extends VC_CFB_Element_Field
  {
    protected $placeholder;

    protected $format;
    protected $min;
    protected $max;
    protected $locale;
    protected $interval;

    protected $closeOnSelect;
    protected $closeOnClear;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'pickatime';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'label'                   => '',
        'label_position'          => 'left',
        'label_width'             => 1,
        'placeholder'             => '',
        'default'                 => '',
        'closeonselect'           =>  true,
        'closeonclear'            =>  true,
        'interval'                => '',
        'min'                     => '',
        'required'                => false,
        'autofocus'               => false,
        'max'                     => '',
        'locale'                  => get_locale(),
        'format'                  => '',
        'id'                      => '',
        'classes'                 => '',
        'add_icon'                => false,
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'icon_color'              => '',
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->closeOnSelect = (bool)$attr['closeonselect'];
      $this->closeOnClear = (bool)$attr['closeonclear'];
      
      if( !empty($attr['max']) )
        $this->max = '['.date( 'H,i', strtotime( $attr['max'] ) ).']';
      if( !empty($attr['min']) )
        $this->min = '['.date( 'H,i', strtotime( $attr['min'] ) ).']';
      
      $this->format = $attr['format'];
      $this->interval = $attr['interval'];
      $this->locale = $attr['locale'];
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_pickdatetime_field_classes', array( 'VC_CFB_Element_Field_Pickatime', '__add_error_validation_class' ), 10, 2 );
    }

    public static function __add_error_validation_class( $classes, $field )
    {
      if( $field->valid === FALSE )
        $classes[] = 'vc_cfb_error';

      return $classes;
    }

    protected function __validate() 
    {
      if( $this->required && $this->value == '' ):
        $this->valid = FALSE; 
        $this->validation_messages[] = 'required';
        return;
      endif;

      $this->valid = TRUE;
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Pickatime', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/time.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/pickatime' );
      $this->settings['description'] = __( 'jQuery time input picker', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __render()
    {
      $fields = array();
      foreach( array( 'closeOnSelect', 'closeOnClear' ) as $field )
        $fields[] = $field.':'.self::__val_to_string( $this->$field );

      foreach( array( 'min', 'max', 'interval' ) as $field )
        if( !empty($this->$field) )
          $fields[] = $field.':'.$this->$field;

      if( !empty($this->format) ):
        $fields[] = 'format:"'.$this->format.'"';
        $fields[] = 'formatSubmit:"'.$this->format.'"';
      endif;

      $translations = $this->__get_translate();
      if( !empty($translations) )
        $fields[] = "clear:'".$translations."'";

      $fields = implode( ',', $fields );

      $this->__render_label( 'start' );
      ?>
      <script type="text/javascript">
        (function($){$(document).ready(function(){$("form input[name=<?= $this->name;?>]").pickatime({<?= $fields; ?>});});})(jQuery);
      </script>
      <?php $this->__show_icon(); ?>
      <input 
        type="text"
        name="<?= $this->name;?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        value="<?= empty($this->value) ? $this->default : $this->value; ?>"
        autocomplete="off"
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
      $this->__render_label( 'end' );
    }

    protected function __get_translate()
    {
      $file = VC_CFB_Manager::$path.'../assets/js/modules/pickadate/translations/'.$this->locale.'.js';
      if( !file_exists($file) )
        return '';
      
      ob_start();
      readfile( $file );
      $data = ob_get_clean();

      preg_match_all( '/clear:"([^"]+)"/s', $data, $c );
      if( !isset($c[1][0]) )
        return '';

      return $c[1][0];
    }

    protected function __enqueue_custom_element_styles()
    {
      wp_register_style( 'vc_cfb_field_pickadate_popup', VC_CFB_Manager::$url.'/css/modules/pickadate/default.css', false, '3.5.6' );   
      wp_enqueue_style( 'vc_cfb_field_pickadate_popup' );

      wp_register_style( 'vc_cfb_field_pickadate_popup_time', VC_CFB_Manager::$url.'/css/modules/pickadate/default.time.css', array( 'vc_cfb_field_pickadate_popup' ), '3.5.6' );   
      wp_enqueue_style( 'vc_cfb_field_pickadate_popup_time' );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_enqueue_script('jquery');    
      
      wp_register_script( 'vc_cfb_field_pickadate_picker', VC_CFB_Manager::$url.'/js/modules/pickadate/picker.js', array( 'jquery' ), '3.5.6' );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_picker' );

      wp_register_script( 'vc_cfb_field_pickadate_picker_time', VC_CFB_Manager::$url.'/js/modules/pickadate/picker.time.js', array( 'vc_cfb_field_pickadate_picker' ) );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_picker_time' );

      wp_register_script( 'vc_cfb_field_pickadate_legacy', VC_CFB_Manager::$url.'/js/modules/pickadate/legacy.js', array( 'vc_cfb_field_pickadate_picker' ) );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_legacy' );
    }
  }