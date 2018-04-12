<?php
  class VC_CFB_Element_Field_DPassword extends VC_CFB_Element_Field
  {
    protected $placeholder;
    protected $duration;
    
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'dpassword';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'classes'                 => '',
        'placeholder'             => '',
        'label'                   => '',
        'label_position'          => 'left',
        'label_width'             => 1,
        'duration'                => 500,
        'required'                => false,
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'add_icon'                => false,
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
        'icon_color'              => ''
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->id = 'dpassword-'.$this->name.'-'.mt_rand();
      $this->duration = $attr['duration'];
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'dPassword', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/dpassword' );
      $this->settings['description'] = __( 'Password with iOS logic', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_register_script( 'vc_cfb_field_dpassword', VC_CFB_Manager::$url.'/js/modules/jquery.dpassword.js', array(), '0.1.1' );   
      wp_enqueue_script( 'vc_cfb_field_dpassword' );
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_dpassword_field_classes', array( 'VC_CFB_Element_Field_DPassword', '__add_error_validation_class' ), 10, 2 );
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

    protected function __render()
    {
      $this->__render_label( 'start' );
      ?>
      <script type="text/javascript">
        (function($) {
          $(document).ready(function() {
            $('#<?= $this->id;?>').dPassword({ duration: <?= $this->duration;?> });
          });
        })(jQuery);
      </script>
      <?php $this->__show_icon();?>
      <input 
        type="password"
        name="<?= $this->name;?>" 
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        value="<?= empty($this->value) ? '' : $this->value; ?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= (bool)$this->required ? 'required="required"' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
      $this->__render_label( 'end' );
    }
  }