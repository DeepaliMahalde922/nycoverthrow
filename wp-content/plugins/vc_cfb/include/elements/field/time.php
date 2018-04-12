<?php
  class VC_CFB_Element_Field_Time extends VC_CFB_Element_Field
  {
    protected $placement;
    protected $align;
    protected $autoclose;
    protected $vibrate;
    protected $twelvehour;
    protected $donetext;
    protected $placeholder;
    protected $tab;
    protected $pattern;
    protected $autocomplete;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'time';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'default'                 => '',
        'tab'                     => '',
        'placeholder'             => '',
        'placement'               => 'bottom',
        'required'                => false,
        'autocomplete'            => false,
        'autofocus'               => false,
        'align'                   => 'left',
        'autoclose'               => false,
        'twelvehour'              => false,
        'donetext'                => '',
        'vibrate'                 => true,
        'id'                      => '',
        'classes'                 => '',
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'add_icon'                => false,
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
        'icon_color'              => '',
        'label'                   => '',
        'label_position'          => 'left',
        'label_width'             => 1,
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->placement = $attr['placement'];
      $this->align = $attr['align'];
      $this->donetext = $attr['donetext'];
      $this->autoclose = !(bool)$attr['autoclose'];
      $this->twelvehour = (bool)$attr['twelvehour'];
      $this->vibrate = (bool)$attr['vibrate'];

      if( $this->twelvehour )
        $this->pattern = '^(1[0-2]|0?[1-9]):([0-5]?[0-9])(AM|PM)$';
      else
        $this->pattern = '^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$';
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Time Field', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/time' );
      $this->settings['description'] = __( 'Time field for easy set value', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
       if( $this->required && $this->value == '' ):
        $this->valid = FALSE; 
        $this->validation_messages[] = 'required';
        return;
      endif;

      if( $this->value != '' )
        if( !empty($this->pattern) && !preg_match( '/'.$this->pattern.'/i', $this->value ) ):
          $this->valid = FALSE; 
          $this->validation_messages[] = 'invalid';
          return;
        endif;
      
      $this->valid = TRUE;
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_field_wrapper_classes', array( 'VC_CFB_Element_Field_Time', '__add_wrapper_classes' ), 10, 2 );
    }

    public static function __add_wrapper_classes( $classes, $field )
    {
      if( $field->type == 'time' )
        $classes[] = 'clockpicker-element';

      return $classes;
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
      $this->__show_icon();
      ?>
        <input 
          type="text" 
          name="<?= $this->name;?>" 
          value="<?= empty($this->value) ? $this->default : $this->value; ?>"
          <?php
            if( !in_array( VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->validation['type'], array( 'without', 'server' ) ) )
              echo 'pattern="'.$this->pattern.'"';
          ?>
          <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
          <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
          <?= $this->__field_id();?>
          <?= $this->__field_classes();?>
          <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
          <?= (bool)$this->autocomplete ? 'autocomplete="off"' : '' ;?>
          <?= (bool)$this->required ? 'required' : '' ;?>
          <?php 
            foreach( array( 'placement', 'align', 'donetext', 'autoclose', 'twelvehour', 'vibrate' ) as $key )
              echo 'data-'.$key.'="'.self::__val_to_string( $this->$key ).'"'."\r\n";
          ?>
          <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
        />
      <?php
      $this->__render_label( 'end' );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_enqueue_script( 'jquery' );

      wp_register_script( 'vc_cfb_field_clockpicker', VC_CFB_Manager::$url.'/js/modules/clockpicker/clockpicker.min.js', array(), '0.0.7' );   
      wp_enqueue_script( 'vc_cfb_field_clockpicker' );

      wp_register_script( 'vc_cfb_field_clockpicker_handler', VC_CFB_Manager::$url.'/js/modules/clockpicker/clockpicker_handler.js', array( 'vc_cfb_field_clockpicker' ), '0.0.7' );   
      wp_enqueue_script( 'vc_cfb_field_clockpicker_handler' );

      wp_register_style( 'vc_cfb_field_clockpicker', VC_CFB_Manager::$url.'/css/modules/clockpicker/clockpicker.min.css', array(), '0.0.7' );
      wp_enqueue_style( 'vc_cfb_field_clockpicker' );

      wp_register_style( 'vc_cfb_field_clockpicker_standalone', VC_CFB_Manager::$url.'/css/modules/clockpicker/standalone.css', array( 'vc_cfb_field_clockpicker' ), '0.0.7' );
      wp_enqueue_style( 'vc_cfb_field_clockpicker_standalone' );
    }
  }