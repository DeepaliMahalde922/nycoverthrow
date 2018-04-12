<?php
  class VC_CFB_Element_Field_Hidden extends VC_CFB_Element_Field
  {
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'hidden';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'default'                 => '',
        'id'                      => '',
        'classes'                 => '',
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Hidden Field', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/hidden' );
      $this->settings['description'] = __( 'Hidden field without validation', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      $this->valid = TRUE;
    }

    protected function __render_wrap()
    {
      $this->__render();
    }

    protected function __render()
    {
      ?>
      <input 
        type="hidden"
        name="<?= $this->name;?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        value="<?= empty($this->value) ? $this->default : $this->value; ?>"
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
    }
  }