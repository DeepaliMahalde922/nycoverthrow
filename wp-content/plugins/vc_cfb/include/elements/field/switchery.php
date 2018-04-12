<?php
  class VC_CFB_Element_Field_Switchery extends VC_CFB_Element_Field
  {
    protected $yes_no;
    protected $speed;
    protected $color;
    protected $secondaryColor;
    protected $jackColor;
    protected $jackSecondaryColor;
    protected $size;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'switchery';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'default_value'           => 'false',
        'id'                      => '',
        'size'                    => 'medium',
        'yesnoemail'              => 0,
        'speed'                   => '0,1',
        'color'                   => '#64bd63',
        'secondarycolor'          => '#dfdfdf',
        'jackcolor'               => '#fff',
        'jacksecondarycolor'      => '',
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

      $this->default = ( $attr['default_value'] === 'false' ? false : true );
      $this->yes_no = (bool)$attr['yesnoemail'];
      $this->speed = str_replace( ',', '.', $attr['speed'] ).'s';
      $this->color = $attr['color'];
      $this->size = $attr['size'];
      $this->secondaryColor = $attr['secondarycolor'];
      $this->jackColor = $attr['jackcolor'];
      $this->jackSecondaryColor = $attr['jacksecondarycolor'];
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Switchery', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/switchery' );
      $this->settings['description'] = __( 'iOS 7 style checkbox', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      $this->valid = TRUE;
    }

    public function __get_value()
    {
      if( $this->yes_no && (bool)$this->value === TRUE )
        $value = __( 'Yes', 'vc_cfb' );
      elseif( $this->yes_no && (bool)$this->value === FALSE )
        $value = __( 'No', 'vc_cfb' );
      else
        $value = ucfirst( self::__val_to_string( (bool)$this->value ) );
      
      return apply_filters( 'cfb_field_get_value', $value, $this->name, $this->type, $this );
    }

    protected function __render()
    {
      $params = array();
      foreach( array( 'color', 'secondaryColor', 'jackColor', 'jackSecondaryColor', 'speed', 'size' ) as $key )
        if( !empty($this->$key) )
          $params[] = $key.": '".$this->$key."'";

      $this->__render_label( 'start' );
      ?>
      <script type="text/javascript">
        (function($) {
          $(document).ready(function() {
            var el = document.querySelector("input[name=<?= $this->name;?>]");
            new Switchery( el, <?= !empty($params) ? '{ '.implode( ',', $params ).' }' : '';?> );
          });
        })(jQuery);
      </script>
      <input 
        type="checkbox"
        name="<?= $this->name;?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= $this->__check_checked();?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
      $this->__render_label( 'end' );
    }

    protected function __check_checked()
    {
      $value = empty($this->value) ? (bool)$this->default : (bool)$this->value;
      echo $value ? 'checked' : '';
    }

    protected function __enqueue_custom_element_styles()
    {
      wp_register_style( 'vc_cfb_field_switchery', VC_CFB_Manager::$url.'/css/modules/switchery/switchery.min.css', false, '0.8.1' );   
      wp_enqueue_style( 'vc_cfb_field_switchery' );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_enqueue_script('jquery');    
      
      wp_register_script( 'vc_cfb_field_switchery', VC_CFB_Manager::$url.'/js/modules/switchery/switchery.min.js', array( 'jquery' ), '0.8.1' );   
      wp_enqueue_script( 'vc_cfb_field_switchery' );
    }
  }