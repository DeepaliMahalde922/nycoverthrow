<?php
  class VC_CFB_Element_Field_Tags extends VC_CFB_Element_Field
  {
    /* field placeholder */
    protected $placeholder;
    
    protected $delimiter;
    protected $removeWithBackspace;
    protected $minChars;
    protected $maxChars;
    protected $placeholderColor;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'tags';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'label'                   => '',
        'default'                 => '',
        'label_position'          => 'left',
        'label_width'             => 1,
        'placeholder'             => '',
        'required'                => false,
        'disabled'                => false,
        'readonly'                => false,
        'removewithbackspace'     => true,
        'delimiter'               => ',',
        'minchars'                => '0',
        'maxchars'                => '',
        'placeholdercolor'        => '#666666',
        'id'                      => '',
        'classes'                 => '',
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->delimiter = $attr['delimiter'];
      $this->minChars = $attr['minchars'];
      $this->maxChars = $attr['maxchars'];
      $this->placeholderColor = $attr['placeholdercolor'];
      $this->removeWithBackspace = (bool)$attr['removewithbackspace'];
      
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Tags Input', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/tags' );
      $this->settings['description'] = __( 'Tag list into input', 'vc_cfb' );
      
      parent::__init_vc();
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
          (function($){$(document).ready(function(){$("form input[name=<?= $this->name;?>]").tagsInput({
             'width': 'auto',
             'defaultText': '<?= $this->placeholder;?>',
             'delimiter': '<?= $this->delimiter;?>',
             'removeWithBackspace' : <?= self::__val_to_string( $this->removeWithBackspace );?>,
             'minChars' : <?= $this->minChars;?>,
             <?= !empty($this->maxChars) ? "'maxChars' : ".$this->maxChars."," : '';?>
             'placeholderColor' : '<?= $this->placeholderColor;?>'
          });});})(jQuery);
        </script>
        <?php
        $this->__show_icon();
        ?>
        <input 
          type="text"
          name="<?= $this->name;?>" 
          <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
          value="<?= empty($this->value) ? $this->default : $this->value; ?>"
          <?= $this->__field_id();?>
          <?= $this->__field_classes();?>
          <?= (bool)$this->readonly ? 'readonly' : '' ;?>
          <?= (bool)$this->disabled ? 'disabled' : '' ;?>
          <?= (bool)$this->required ? 'required' : '' ;?>
          <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
        />
        <?php
      $this->__render_label( 'end' );
    }

    protected function __enqueue_custom_element_styles()
    {
      wp_register_style( 'vc_cfb_field_tags_input', VC_CFB_Manager::$url.'/css/modules/jquery.tagsinput.min.css', false, '1.3.3' );   
      wp_enqueue_style( 'vc_cfb_field_tags_input' );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_enqueue_script('jquery');    
      
      wp_register_script( 'vc_cfb_field_tags_input', VC_CFB_Manager::$url.'/js/modules/jquery.tagsinput.min.js', array( 'jquery' ), '1.3.3' );   
      wp_enqueue_script( 'vc_cfb_field_tags_input' );
    }
  }