<?php
  class VC_CFB_Element_Field_Options extends VC_CFB_Element_Field
  {
    protected $tab;
    protected $placeholder;
    protected $field_type;
    protected $size;
    protected $column_width;
    protected $options = array();
    protected $multiple = false;
    
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'options';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'type'                    => 'select',
        'options'                 => '',
        'options_file'            => '',
        'placeholder'             => '',
        'tab'                     => '',
        'default'                 => '',
        'autofocus'               => '',
        'disabled'                => '',
        'required'                => false,
        'size'                    => '',
        'id'                      => '',
        'classes'                 => '',
        'column_width'            => '1',
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

      $this->field_type = $attr['type'];
      $this->column_width = $attr['column_width'];
      
      $this->__options_from_json( urldecode($attr['options']) );
      $this->__options_from_csv( $attr['options_file'] );
      
      // only for checkboks and multiselect
      if( in_array( $this->field_type, array( 'multiselect', 'checkbox' ) ) )
        $this->multiple = true;

      if( in_array( $this->field_type, array( 'multiselect' ) ) )
        $this->size = $attr['size'];
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Field With Options', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/options' );
      $this->settings['description'] = __( 'Select|Checkbox|Radio', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      if( $this->required && empty($this->value) ):
        $this->valid = FALSE;
        $this->validation_messages[] = 'required';
        return;
      endif;

      if( !empty($this->value) )
        foreach( $this->value as $key )
          if( !isset($this->options[$key]) ):
            $this->valid = FALSE;
            $this->validation_messages[] = 'invalid';
            return;
          endif;

      $this->valid = TRUE;
    }

    protected function __set_value()
    {
      $this->value = array();

      if( isset($_REQUEST[$this->name]) )
      {
        if( is_array($_REQUEST[$this->name]) )
          foreach( $_REQUEST[$this->name] as $element )
            $this->value[] = htmlspecialchars($element);
        else
          $this->value[] = htmlspecialchars($_REQUEST[$this->name]);
      }

      if( empty( $this->value ) && $this->default !== '' ):
        $this->default = explode( ',', $this->default );
        $this->value = (array)$this->default;
      endif;

      $this->value = apply_filters( 'cfb_field_set_value', $this->value, $this->name, $this->type, $this );
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
        if( in_array( $this->field_type, array( 'select', 'multiselect' ) ) )
          $this->__render_select_block();

        if( in_array( $this->field_type, array( 'checkbox', 'radio' ) ) )
          $this->__render_checkbox_radio_block();
      $this->__render_label( 'end' );
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_field_wrapper_classes', array( 'VC_CFB_Element_Field_Options', '__add_wrapper_classes' ), 10, 2 );
      add_filter( 'cfb_before_field', array( 'VC_CFB_Element_Field_Options', '__add_before_wrapper_for_field' ), 11, 2 );
    }

    public static function __add_before_wrapper_for_field( $code, $field )
    {
      if( $field->type != 'options' )
        return $code; 
      
      return '<div class="'.implode( ' ', apply_filters( 'cfb_field_wrapper_classes', array( 'cfb_field_wrapper' ), $field ) ).'" '.$field->__field_id().'>';
    }

    public static function __add_wrapper_classes( $classes, $field )
    {
      if( $field->type == 'options' ):
        $classes[] = 'cfb_options_wrapper';
        preg_match_all("/class=\"(.*)\"/i", $field->__field_classes(), $matches);
        if( isset($matches[1][0]) )
          $classes = array_merge( $classes, explode(' ', $matches[1][0] ) );
      endif;
      
      return $classes;
    }

    private function __render_checkbox_radio( $key, $value )
    {
      ?>
        <input 
          type="<?= $this->field_type;?>" 
          name="<?= $this->name.( (bool)$this->multiple ? '[]' : '' );?>" 
          value="<?= $key;?>" 
          <?= (bool)$this->disabled ? 'disabled' : '' ;?>
          <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
          <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
          <?= in_array( $key, $this->value ) ? 'checked' : '';?>
          <?= (bool)$this->required ? 'required' : '' ;?>
          <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
        /> <?= $value;?>
      <?php
    }

    private function __render_checkbox_radio_block()
    {
      $count = 1;
      foreach( $this->options as $key => $value ):
        if( $count == 1 ):
      ?>
        <div class="vc_row_inner wpb_row vc_inner vc_row-fluid">
      <?php
        endif;
      ?>
        <div class="vc_col-sm-<?=$this->column_width;?> wpb_column vc_column_container ">
          <?= $this->__render_checkbox_radio( $key, $value );?>
        </div>
      <?php
        if( 12 / $this->column_width == $count )
          $count = 0;
        $count ++;
        if( $count == 1 )
          echo '</div>';
      endforeach;
      if( $count != 1 )
        echo '</div>';
    }

    private function __render_select_block()
    {
      ?>
        <select  
          name="<?= $this->name.( (bool)$this->multiple ? '[]' : '' );?>"
          <?= (bool)$this->disabled ? 'disabled' : '' ;?>
          <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
          <?= (bool)$this->required ? 'required' : '' ;?>
          <?= (bool)$this->multiple ? 'multiple' : '' ;?>
          <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
          <?= $this->__field_id();?>
          <?= $this->__field_classes();?>
          <?= !empty($this->size) ? 'size="'.$this->size.'"' : '' ;?>
          <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
        >
          <?php 
            if( !empty($this->placeholder) )
              echo '<option value="" disabled '.( empty($this->value) ? 'selected' : '' ).' '.( (bool)$this->multiple ? '' : 'style="display:none;"' ).'>'.$this->placeholder.'</option>';
            foreach( $this->options as $key => $value )
              echo '<option value="'.$key.'" '.( in_array( $key, $this->value ) ? 'selected' : '' ).'>'.$value.'</option>';
          ?>
        </select>
      <?php
    }

    public function __get_value()
    {
      if( empty($this->value) )
        return '-';
      
      $labels = array();
      
      foreach( $this->value as $key )
        if( isset($this->options[$key]) )
          $labels[] = $this->options[$key];

      return implode( apply_filters( 'cfb_field_get_value', $labels, $this->name, $this->type, $this ), ', ' );
    }

    private function __options_from_json( $json )
    {
      $json = json_decode( $json, true );
      if( !is_array($json) )
        return;

      foreach( $json as $element ):
        $element['key'] = VC_CFB_Shortcode::__sanitize_name( trim($element['key']) );
        $element['label'] = trim($element['label']);
        if( $element['key'] != '' && $element['label'] != '' )
          $this->options[ $element['key'] ] = $element['label'];
      endforeach;
    }

    private function __options_from_csv( $file )
    {
      if( empty($file) )
        return;

      $file = get_post( $file );

      if( $file == null )
        return;

      if( $file->post_mime_type != 'text/csv' )
        return;

      $file = file( $file->guid );
      if( $file === FALSE )
        return;

      foreach( $file as $string ):
        $element = VC_CFB_FGeneral::str_getcsv( $string );
        $element[0] = VC_CFB_Shortcode::__sanitize_name( trim($element[0]) );
        $element[1] = trim($element[1]);
        if( $element[0] != '' && $element[1] != '' )
          $this->options[ $element[0] ] = $element[1];
      endforeach;
    }
  }