<?php
  class VC_CFB_Element_Field_Text extends VC_CFB_Element_Field
  {
    /* field placeholder */
    protected $placeholder;

    /* field pattern */
    protected $pattern;
    protected $masks = array( 
                          'zip_US'                =>  "^\d{5}([\-]?\d{4})?$",
                          'zip_UK'                =>  "^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
                          'zip_DE'                =>  "\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
                          'zip_CA'                =>  "^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
                          'zip_FR'                =>  "^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
                          'zip_IT'                =>  "^(V-|I-)?[0-9]{5}$",
                          'zip_AU'                =>  "^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
                          'zip_NL'                =>  "^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
                          'zip_ES'                =>  "^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
                          'zip_DK'                =>  "^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
                          'zip_SE'                =>  "^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
                          'zip_BE'                =>  "^[1-9]{1}[0-9]{3}$",
                          'zip_IN'                =>  "^\d{6}$",
                          'ip'                    =>  "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$",
                          'url'                   =>  "^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$",
                          'email'                 =>  "^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$",
                          'hex'                   =>  "^#?([a-f0-9]{6}|[a-f0-9]{3})$",
                          'ssn'                   =>  "^(?!000|666)(?:[0-6][0-9]{2}|7(?:[0-6][0-9]|7[0-2]))-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$",
                          'time_12_without_ss'    =>  "^(1[0-2]|0?[1-9]):([0-5]?[0-9])$",
                          'time_12_with_ss'       =>  "^(1[0-2]|0?[1-9]):([0-5]?[0-9]):([0-5]?[0-9])$",
                          'time_24_with_ss'       =>  "^(2[0-3]|[01]?[0-9]):([0-5]?[0-9]):([0-5]?[0-9])$",
                          'time_24_without_ss'    =>  "^(2[0-3]|[01]?[0-9]):([0-5]?[0-9])$",
                          'lat'                   =>  "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$",
                          'lng'                   =>  "^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$",
                            );
    
    /* tab index */
    protected $tab;

    /* autocomplete enable */
    protected $autocomplete;

    protected $size;
    protected $maxlength;
    protected $mask = false;
    protected $field_type;
    protected $max;
    protected $min;
    protected $step;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'text';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'tab'                     => '',
        'required'                => false,
        'autocomplete'            => false,
        'autofocus'               => false,
        'disabled'                => false,
        'readonly'                => false,
        'placeholder'             => '',
        'default'                 => '',
        'id'                      => '',
        'classes'                 => '',
        'size'                    => '',
        'maxlength'               => '',
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'add_icon'                => false,
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
        'pattern'                 => 'custom',
        'max'                     => '',
        'min'                     => '',
        'step'                    => '',
        'input_type'              => 'text',
        'icon_color'              => '',
        'add_mask'                => false,
        'mask_template'           => '',
        'mask_placeholder'        => '_',
        'mask_autoclear'          => false,
        'custom_pattern'          => '',
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

      $this->size = $attr['size'];
      $this->maxlength = $attr['maxlength'];
      if( $attr['pattern'] == 'custom' )
        $this->pattern = urldecode( $attr['custom_pattern'] );
      else
        foreach( $this->masks as $key => $pattern )
          if( $key == $attr['pattern'] )
            $this->pattern = $pattern;

      $this->field_type = $attr['input_type'];
      if( in_array( $this->field_type, array( 'number', 'date', 'range', 'datetime', 'datetime-local', 'month', 'time', 'week' ) ) ):
        $this->max = $attr['max'];
        $this->min = $attr['min'];
        $this->step = $attr['step'];
      endif;  
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'General field', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/text' );
      $this->settings['description'] = __( 'Basic field for text', 'vc_cfb' );
      
      parent::__init_vc();
    }

    public function __init_hooks_description()
    {
      VC_CFB_Manager::__register_hook_description( 'cfb_field_set_value', VC_CFB_Manager::$path.'/hooks/text/cfb_field_set_value.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_field_get_value', VC_CFB_Manager::$path.'/hooks/text/cfb_field_get_value.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_field_label', VC_CFB_Manager::$path.'/hooks/text/cfb_field_label.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_before_field', VC_CFB_Manager::$path.'/hooks/text/cfb_before_field.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_{%type%}_field_classes', VC_CFB_Manager::$path.'/hooks/text/cfb_type_field_classes.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_elements_params', VC_CFB_Manager::$path.'/hooks/text/cfb_elements_params.php', __( 'Field', 'vc_cfb' ) );
      VC_CFB_Manager::__register_hook_description( 'cfb_{%type%}_field_custom_attribute', VC_CFB_Manager::$path.'/hooks/text/cfb_type_field_custom_attribute.php', __( 'Field', 'vc_cfb' ) );
    }

    protected function __validate() 
    {
      if( $this->required && $this->value == '' ):
        $this->valid = FALSE; 
        $this->validation_messages[] = 'required';
        return;
      endif;

      if( $this->value != '' ):
        if( ( !empty($this->pattern) && !preg_match( '/'.$this->pattern.'/i', $this->value ) ) || ( !empty($this->maxlength) && strlen( $this->value ) > $this->maxlength ) ):
          $this->valid = FALSE; 
          $this->validation_messages[] = 'invalid';
          return;
        endif;

        if( ( $this->max != '' && $this->value >= $this->max ) || ( $this->min != '' && $this->value < $this->min ) ):
          $this->valid = FALSE; 
          $this->validation_messages[] = 'invalid';
          return;
        endif;
      endif;
      
      $this->valid = TRUE;
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
      if( $this->mask !== FALSE ):
      ?>
      <script type="text/javascript">
        (function($) {
          $(document).ready(function() {
            $('form input[name=<?= $this->name;?>]').mask( "<?= $this->mask['template'];?>", { autoclear: <?= self::__val_to_string( $this->mask['autoclear'] );?>, placeholder: "<?= $this->mask['placeholder'];?>" } );
          });
        })(jQuery);
      </script>
      <?php
        endif;
        $this->__show_icon();
      ?>
      <input 
        type="<?= $this->field_type;?>"
        <?= $this->max != '' ? 'max="'.$this->max.'"' : ''; ?>
        <?= $this->min != '' ? 'min="'.$this->min.'"' : ''; ?>
        <?= $this->step != '' ? 'step="'.$this->step.'"' : ''; ?>
        name="<?= $this->name;?>" 
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
        value="<?= empty($this->value) ? $this->default : $this->value; ?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= !empty($this->size) ? 'size="'.$this->size.'"' : '' ;?>
        <?php
          if( !in_array( VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->validation['type'], array( 'without', 'server' ) ) ):
            echo !empty($this->maxlength) ? 'maxlength="'.$this->maxlength.'"' : '' ;
            echo !empty($this->pattern) ? 'pattern="'.$this->pattern.'"' : '' ;
          endif;
        ?>
        <?= (bool)$this->readonly ? 'readonly' : '' ;?>
        <?= (bool)$this->disabled ? 'disabled' : '' ;?>
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= (bool)$this->autocomplete ? 'autocomplete="off"' : '' ;?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
<?php
      $this->__render_label( 'end' );
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_text_field_classes', array( 'VC_CFB_Element_Field_Text', '__add_error_validation_class' ), 10, 2 );
    }

    public static function __add_error_validation_class( $classes, $field )
    {
      if( $field->valid === FALSE )
        $classes[] = 'vc_cfb_error';

      return $classes;
    }

    protected function __enqueue_custom_element_styles()
    { 
      if( $this->mask !== FALSE ):
        wp_enqueue_script( 'jquery' );

        wp_register_script( 'vc_cfb_field_maskedinput', VC_CFB_Manager::$url.'/js/modules/jquery.maskedinput.min.js', array(), '1.4.1' );   
        wp_enqueue_script( 'vc_cfb_field_maskedinput' );
      endif;
    }
  }