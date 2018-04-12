<?php
  class VC_CFB_Element_Field_Textarea extends VC_CFB_Element_Field
  {
    protected $placeholder;
    protected $pattern;
    protected $tab;
    protected $maxlength;
    protected $cols;
    protected $rows;
    protected $wrap;
    
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'textarea';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'tab'                     => '',
        'required'                => false,
        'autofocus'               => false,
        'disabled'                => false,
        'readonly'                => false,
        'placeholder'             => '',
        'default'                 => '',
        'wrap'                    => 'soft',
        'id'                      => '',
        'classes'                 => '',
        'cols'                    => '',
        'rows'                    => '',
        'maxlength'               => '',
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

      $this->wrap = $attr['wrap'];
      $this->cols = $attr['cols'];
      $this->rows = $attr['rows'];
      $this->maxlength = $attr['maxlength'];
}

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Textarea', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/textarea' );
      $this->settings['description'] = __( 'Multi-line text area', 'vc_cfb' );
      
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
        if( !empty($this->maxlength) && strlen( $this->value ) > $this->maxlength ):
          $this->valid = FALSE; 
          $this->validation_messages[] = 'invalid';
          return;
        endif;
      
      $this->valid = TRUE;
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_textarea_field_classes', array( 'VC_CFB_Element_Field_Textarea', '__add_error_validation_class' ), 10, 2 );
    }

    public static function __add_error_validation_class( $classes, $field )
    {
      if( $field->valid === FALSE )
        $classes[] = 'vc_cfb_error';

      return $classes;
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
      ?>
      <textarea
        name="<?= $this->name;?>" 
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
        wrap="<?= $this->wrap;?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= !empty($this->cols) ? 'cols="'.$this->cols.'"' : '';?>
        <?= !empty($this->rows) ? 'rows="'.$this->rows.'"' : '';?>
        <?= !empty($this->maxlength) && !in_array( VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->validation['type'], array( 'without', 'server' ) ) ? 'maxlength="'.$this->maxlength.'"' : '' ;?>
        <?= (bool)$this->readonly ? 'readonly' : '' ;?>
        <?= (bool)$this->disabled ? 'disabled' : '' ;?>
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      ><?= empty($this->value) ? str_replace( '<br />', "", $this->default ) : $this->value; ?></textarea>
      <?php
      $this->__render_label( 'end' );
    }
  }