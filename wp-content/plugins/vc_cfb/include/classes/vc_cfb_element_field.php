<?php
  class VC_CFB_Element_Field extends VC_CFB_Element
  {
    protected $value;
    protected $label;
    protected $default;
    protected $required;
    protected $disabled;
    protected $readonly;
    protected $autofocus;
    protected $validation_messages = array();
    protected $icon = false;

    public function __init_vc()
    {
      $this->settings['as_child'] = array( 'only' => 'cfb_form,vc_column_inner' );

      parent::__init_vc();
    }

    protected function __set_attr( $attr, $content )
    {
      parent::__set_attr( $attr, $content );
      
      if( isset($attr['add_icon']) && (bool)$attr['add_icon'] )
        $this->icon = array( 'icon' => $attr['icon_'.$attr['icon_type']], 'align' => $attr['icon_align'], 'type' => $attr['icon_type'], 'color' => $attr['icon_color'] );

      if( isset($attr['add_mask']) && (bool)$attr['add_mask'] )
        $this->mask = array( 'template' => urldecode($attr['mask_template']), 'placeholder' => $attr['mask_placeholder'], 'autoclear' => !(bool)$attr['mask_autoclear'] );
    
      if( isset($attr['tab']) )
        $this->tab = $attr['tab'];
      if( isset($attr['label']) )
        $this->label = array( 'label' => urldecode( $attr['label'] ), 'position' => $attr['label_position'], 'width' => $attr['label_width'] );
      if( isset($attr['autofocus']) )
        $this->autofocus = (bool)$attr['autofocus'];
      if( isset($attr['default']) )
        $this->default = $attr['default'];
      if( isset($attr['required']) )
        $this->required = (bool)$attr['required'];
      if( isset($attr['autocomplete']) )
        $this->autocomplete = (bool)$attr['autocomplete'];
      if( isset($attr['disabled']) )
        $this->disabled = (bool)$attr['disabled'];
      if( isset($attr['placeholder']) )
        $this->placeholder = $attr['placeholder'];
      if( isset($attr['readonly']) )
        $this->readonly = (bool)$attr['readonly'];
    }

    protected function __set_value()
    {
      if( isset($_REQUEST[$this->name]) )
        $this->value = htmlspecialchars($_REQUEST[$this->name]);

      $this->value = apply_filters( 'cfb_field_set_value', $this->value, $this->name, $this->type, $this );
    }

    public function __get_value()
    {
      return apply_filters( 'cfb_field_get_value', $this->value, $this->name, $this->type, $this );
    }

    public function __get_label()
    {
      return apply_filters( 'cfb_field_label', $this->label['label'], $this->name, $this->type, $this );
    }

    protected function __render_label( $type = 'start' )
    {
      if( $this->label['position'] == 'none' || empty($this->label['label']) )
        return;

      $label = '<label for="'.$this->name.'">'.$this->label['label'].( (bool)$this->required ? ' <span class="vc_cfb_field_required">*</span>' : '' ).'</label>';

      if( $type == 'end' )
        switch ($this->label['position']) 
        {
          case 'left':
            echo '</div></div></div>';
            return; 
          case 'right':
            echo '</div></div><div class="vc_col-sm-'.$this->label['width'].' wpb_column vc_column_container">'.$label.'</div></div>';
            return; 
          case 'bottom':
            echo '</div>'.$label;
            return;         
          default:
            # top
            echo '</div>';
            return;
        }

      if( $this->label['position'] == 'top' ):
        echo $label.'<div class="cfb_field_block">';
        return;
      endif;

      if( $this->label['position'] == 'bottom' ):
        echo '<div class="cfb_field_block">';
        return;
      endif;

      $code = '<div class="vc_row_inner wpb_row vc_inner vc_row-fluid">';
      if( $this->label['position'] == 'left' )
        $code .= '<div class="vc_col-sm-'.$this->label['width'].' wpb_column vc_column_container">'.$label.'</div>';
      $code .= '<div class="vc_col-sm-'.( 12 - $this->label['width'] ).' wpb_column vc_column_container"><div class="cfb_field_block">';
      echo $code;
    }

    protected function __render_wrap()
    {
      if( $this->valid === FALSE )
        $this->__show_error_message( array( 'field', 'all' ) ); 

      echo apply_filters( 'cfb_before_field', '', $this );
        $this->__render();
      echo apply_filters( 'cfb_after_field', '', $this );
    }

    public function __show_error_message( $positions )
    {
      $form = VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ];

      if( !in_array( $form->validation['position'], $positions ) )
        return;

      foreach ($this->validation_messages as $key):
        echo apply_filters( 'cfb_before_message', '', $this, 'error' );
          echo sprintf( $form->validation['messages'][$key], !empty($this->label['label']) ? $this->label['label'] : $this->name );
        echo apply_filters( 'cfb_after_message', '', $this, 'error' );
      endforeach;
    }

    public function __init_hooks_description()
    {
      VC_CFB_Manager::__register_hook_description( 'cfb_before_field_block', VC_CFB_Manager::$path.'/hooks/text/cfb_before_field_block.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_field_wrapper_classes', VC_CFB_Manager::$path.'/hooks/text/cfb_field_wrapper_classes.php', __( 'Field', 'vc_cfb' ) );
    }

    public function __hooks()
    {
      add_filter( 'cfb_before_field', array( 'VC_CFB_Element_Field', '__add_before_wrapper_for_field' ), 10, 2 );
      add_filter( 'cfb_after_field', array( 'VC_CFB_Element_Field', '__add_after_wrapper_for_field' ), 10, 2 );

      add_filter( 'cfb_before_message', array( 'VC_CFB_Element_Field', '__before_message' ), 10, 2 );
      add_filter( 'cfb_after_message', array( 'VC_CFB_Element_Field', '__after_message' ), 10, 2 );

      add_filter( 'cfb_before_field_block', array( 'VC_CFB_Element_Field', '__add_before_field' ), 10, 2 );
      add_filter( 'cfb_after_field_block', array( 'VC_CFB_Element_Field', '__add_after_field' ), 10, 2 ); 
    }

    public static function __add_before_field( $code, $field )
    {
      return '<div class="vc_row_inner wpb_row vc_inner vc_row-fluid"><div class="vc_col-sm-12 wpb_column vc_column_container">';
    }

    public static function __add_after_field( $code, $field )
    {
      return '</div></div>';
    }

    public static function __add_before_wrapper_for_field( $code, $field )
    {
      $type = $field->type.( isset($field->field_type) ? '_'.$field->field_type : '' );
      return '<div class="'.implode( ' ', apply_filters( 'cfb_field_wrapper_classes', array( 'cfb_field_wrapper', 'cfb_element_'.mb_strtolower($type) ), $field ) ).'">';
    }

    public static function __add_after_wrapper_for_field( $code, $field )
    {
      return '</div>';
    }

    public static function __before_message( $code, $element )
    {
      if( $element->type == 'form' )
        return $code;
      
      $form = VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ];
      return ( in_array( $form->validation['position'], array( 'form', 'all' ) ) ? '<li>' : '<div class="vc_cfb_message vc_cfb_type_error"><div class="vc_cfb_icon"><i class="fa fa-exclamation-triangle"></i>
  </div><p>' );
    }

    static function __after_message( $code, $element )
    {
      if( $element->type == 'form' )
        return $code;

      $form = VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ];
      return ( in_array( $form->validation['position'], array( 'form', 'all' ) ) ? '</li>' : '</p></div>' );
    }

    protected function __field_classes()
    {
      $classes = apply_filters( 'cfb_'.$this->type.'_field_classes', $this->classes, $this );
      foreach( $classes as $key => $class )
        if( $class == '' )
          unset($classes[$key]);
      return ( sizeof($classes) > 0 ? 'class="'.implode( ' ', $classes ).'"' : '' );
    }

    protected function __field_id()
    { 
      return !empty($this->id) ? 'id="'.$this->id.'"' : '';
    }

    protected function __show_icon( $position = '' )
    {
      if( $this->icon !== FALSE )
        if( empty($position) || $this->icon['align'] == $position )
          echo '<i class="cfb_input_icon cfb_input_icon_align_'.$this->icon['align'].' '.$this->icon['icon'].'" '.( !empty($this->icon['color']) ? 'style="color:'.$this->icon['color'].';"' : '' ).'></i>';
    }

    protected function __get_api_url( $name )
    {
      $url = '';
      if( wp_script_is( $name ) )
      {
        global $wp_scripts;
        $url = $wp_scripts->registered[$name]->src;
      }
      else
        $url = $this->url;

      return $url;
    }

    public function __get_icon()
    {
      return $this->icon;
    }
  }