<?php
  class VC_CFB_Element_Field_Button extends VC_CFB_Element_Field
  {
    protected $tab;
    protected $field_type;
    protected $onclick;
    protected $align;
    protected $padding;
    
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'button';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'type'                    => 'submit',
        'onclick'                 => '',
        'tab'                     => '',
        'align'                   => 'left',
        'autofocus'               => '',
        'disabled'                => '',
        'default'                 => '',
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
        'padding'                 => ''
      ), $attr );
    }

    public function __get_value()
    {
      return apply_filters( 'cfb_field_get_value', NULL, $this->name, $this->type, $this );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->field_type = $attr['type'];
      $this->onclick = $attr['onclick'];
      $this->align = $attr['align'];
      $this->padding = $attr['padding'];
      $this->js = urldecode( base64_decode( trim( $content ) ) );
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Button field', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/button' );
      $this->settings['description'] = __( 'Reset|Submit|Button', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      $this->valid = TRUE;
    }

    protected function __render()
    {
      echo !empty( $this->js ) ? '<script type="text/javascript">'."\r\n".$this->js."\r\n".'</script>' : '';
    ?>
      <button 
        type="<?= $this->field_type;?>"
        name="<?= $this->name;?>"
        <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
        <?= !empty($this->onclick) ? 'onclick="'.$this->onclick.'"' : ''; ?>
        <?= (bool)$this->disabled ? 'disabled' : '' ;?>
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      >
        <?= $this->__show_icon( 'left' ); ?>
        <span><?= $this->default; ?></span>
        <?= $this->__show_icon( 'right' ); ?>
      </button>
      <?php
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_field_wrapper_classes', array( 'VC_CFB_Element_Field_Button', '__add_wrapper_classes' ), 10, 2 );
      add_filter( 'cfb_button_field_custom_attribute', array( 'VC_CFB_Element_Field_Button', '__add_button_padding' ), 5, 2 );
    }

    public static function __add_button_padding( $string, $field )
    {
      if( empty($field->padding) )
        return $string;

      $code = json_decode( urldecode($field->padding) );
      $paddings = array();
      if( is_array($code) )
        foreach( $code as $element )
          if( $element->value != '')
            $paddings[] = $element->name.':'.$element->value.'px;';

      $paddings = ' style="'.implode( '', $paddings ).'"';

      return $string.$paddings;
    }

    public static function __add_wrapper_classes( $classes, $field )
    {
      if( $field->type == 'button')
        $classes[] = 'vc_cfb_button_align-'.$field->align;

      return $classes;
    }
  }