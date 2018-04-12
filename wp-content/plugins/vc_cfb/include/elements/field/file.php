<?php
  class VC_CFB_Element_Field_File extends VC_CFB_Element_Field
  {
    protected $size;
    protected $tab;
    protected $accept;
    protected $multiple;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'file';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'tab'                     => '',
        'required'                => false,
        'autofocus'               => false,
        'file_size'               => '',
        'accept'                  => '',
        'multiple'                => false,
        'id'                      => '',
        'classes'                 => '',
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

      $this->size = (int)trim( $attr['file_size'] );
      $this->accept = urldecode( trim( $attr['accept'] ) );
      if( isset($attr['multiple']) )
        $this->multiple = (bool)$attr['multiple'];
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'File Field', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/file' );
      $this->settings['description'] = __( 'Field for uploading files', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      if( $this->required && empty($this->value) ):
        $this->valid = FALSE;
        $this->validation_messages[] = 'required';
        return;
      endif;

      foreach( $this->value as $file ):
        if( $file['error'] != 0 ):
          $this->valid = FALSE;
          $this->validation_messages[] = 'warning';
          return;
        endif;
        
        if( !empty($this->size) && $file["size"] > $this->size ):
          $this->valid = FALSE;
          $this->validation_messages[] = 'file_size';
          return;
        endif; 

        if( !is_uploaded_file($file["tmp_name"]) ):
          $this->valid = FALSE;
          $this->validation_messages[] = 'warning';
          return;
        endif;
        
        if( !empty($this->accept) ):
          $accept = $this->__accept_array();
          
          if( !empty($accept['extensions']) ):
            $ext = substr( $file['name'], strrpos( $file['name'], "." ) );
            if( !in_array( $ext, $accept['extensions'] ) ):
              $this->valid = FALSE;
              $this->validation_messages[] = 'file_accept';
              return;
            endif;
          endif;

          if( !empty($accept['mimetype']) )
            if( !in_array( $file['type'], $accept['mimetype'] ) ):
              $this->valid = FALSE;
              $this->validation_messages[] = 'file_accept';
              return;
            endif;
          
          if( !empty($accept['mask']) ):
            $mask = explode( '/', $file['type'] );
            $mask = ( is_array($mask) ? $mask[0] : $file['type'] );
            if( !in_array( $mask, $accept['mask']) ):
              $this->valid = FALSE;
              $this->validation_messages[] = 'file_accept';
              return;
            endif;
          endif;
        endif;
      endforeach;


      $this->valid = TRUE;
    }

    private function __accept_array()
    {
      $accept = array( 'extensions' => array(), 'mimetype' => array(), 'mask' => array() );
      $list = explode( ',', $this->accept );
      foreach( $list as $element ):
        $element = trim( $element );

        if( in_array( $element, array( 'audio/*', 'video/*', 'image/*' ) ) ):
          $element = explode( '/', $element );
          $accept['mask'][] = $element[0];
          continue;
        endif;

        if( $element[0] == '.' ):
          $accept['extensions'][] = $element;
          continue;
        endif;

        $accept['mimetype'][] = $element;
      endforeach;

      return $accept;
    }

    protected function __set_value()
    {
      $this->value = array();
      if( !isset($_FILES[$this->name]) || empty($_FILES[$this->name]) )
        return;

      foreach( $_FILES[$this->name] as $key => $data )
        foreach( $data as $data_key => $value )
          $this->value[$data_key][$key] = $value;


      foreach( $this->value as $key => $element )
        if( empty($element['name']) || $element['error'] == 4 )
          unset($this->value[$key]);

      $this->value = apply_filters( 'cfb_field_set_value', $this->value, $this->name, $this->type, $this );
    }

    public function __get_value()
    {
      return apply_filters( 'cfb_field_get_value', NULL, $this->name, $this->type, $this );
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
      ?>
      <input 
        type="<?= $this->type;?>"
        name="<?= $this->name;?>[]" 
        <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
        <?= !empty($this->accept) ? 'accept="'.$this->accept.'"' : ''; ?>
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= (bool)$this->multiple ? 'multiple' : '' ;?>
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
      $this->__render_label( 'end' );
    }

    function __get_files()
    {
      return $this->value;
    }
  }