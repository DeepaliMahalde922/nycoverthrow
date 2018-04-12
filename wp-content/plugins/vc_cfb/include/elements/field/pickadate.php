<?php
  class VC_CFB_Element_Field_Pickadate extends VC_CFB_Element_Field
  {
    protected $placeholder;
    
    protected $showMonthsShort;
    protected $showWeekdaysFull;
    protected $selectYears;
    protected $selectMonths;
    protected $closeOnSelect;
    protected $closeOnClear;

    protected $firstDay;
    protected $disable_dates = array();

    protected $total_years;
    protected $format;
    protected $min;
    protected $max;
    protected $locale;
    
    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'pickadate';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'label'                   => '',
        'label_position'          => 'left',
        'label_width'             => 1,
        'placeholder'             => '',
        'default'                 => '',
        'showmonthsshort'         =>  false,
        'showweekdaysfull'        =>  false,
        'selectyears'             =>  true,
        'total_years'             =>  4,
        'selectmonths'            =>  false,
        'closeonselect'           =>  true,
        'closeonclear'            =>  true,
        'firstday'                => 1,
        'disable_dates'           => '',
        'min'                     => '',
        'required'                => false,
        'autofocus'               => false,
        'max'                     => '',
        'locale'                  => get_locale(),
        'format'                  => '',
        'id'                      => '',
        'classes'                 => '',
        'add_icon'                => false,
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'icon_color'              => '',
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();

      $this->firstDay = ( $attr['firstday'] == 1 ? true : false );
      $this->__disable_dates( urldecode( $attr['disable_dates'] ) );
      
      $this->showMonthsShort = (bool)$attr['showmonthsshort'];
      $this->showWeekdaysFull = (bool)$attr['showweekdaysfull'];
      $this->selectYears = (bool)$attr['selectyears'];
      $this->selectMonths = (bool)$attr['selectmonths'];
      $this->closeOnSelect = (bool)$attr['closeonselect'];
      $this->closeOnClear = (bool)$attr['closeonclear'];

      $this->total_years = $attr['total_years'];
      
      if( !empty($attr['max']) )
        $this->max = '['.date( 'Y,m,d', strtotime( $attr['max'] ) ).']';
      if( !empty($attr['min']) )
        $this->min = '['.date( 'Y,m,d', strtotime( $attr['min'] ) ).']';

      $this->format = $attr['format'];
      $this->locale = $attr['locale'];
    }

    private function __disable_dates( $json )
    {
      $json = json_decode( $json, true );
      if( !is_array($json) )
        return;

      foreach( $json as $element ):
        if( empty($element['from']) )
          continue;

        $rule = '';
        if( mb_strlen( $element['from'] ) == 1 ):
          $rule = array();
          for( $i = $element['from']; $i <= (int)$element['to']; $i ++ )
            $rule[] = $i;
          $rule = implode( ',', $rule );
        elseif( !empty($element['from']) && !empty($element['to']) && $element['from'] != $element['to'] ):
          $rule = '{ from: ['.date( 'Y, m, d', strtotime( $element['from'] ) ).']';
          $rule .= ', to: ['.date( 'Y, m, d', strtotime( $element['to'] ) ).']';
          $rule .= ' }';
        elseif( !empty($element['from']) ):
          $rule = '['.date( 'Y, m, d', strtotime( $element['from'] ) ).']';
        endif;

        if( !empty($rule) )
          $this->disable_dates[] = $rule;

        unset($rule);
      endforeach;
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Pickadate', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/pickadate' );
      $this->settings['description'] = __( 'jQuery date input picker', 'vc_cfb' );
      
      parent::__init_vc();
    }

    public function __hooks()
    {
      parent::__hooks();

      add_filter( 'cfb_pickadate_field_classes', array( 'VC_CFB_Element_Field_Pickadate', '__add_error_validation_class' ), 10, 2 );
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
      $fields = array();
      foreach( array( 'showMonthsShort', 'showWeekdaysFull', 'closeOnSelect', 'closeOnClear', 'selectMonths', 'firstDay' ) as $field )
        $fields[] = $field.':'.self::__val_to_string( $this->$field );

      foreach( array( 'min', 'max' ) as $field )
        if( !empty($this->$field) )
          $fields[] = $field.':'.$this->$field;

      if( !empty($this->disable_dates) )
        $fields[] = 'disable:['.implode( ',', $this->disable_dates ).']';

      $fields[] = 'selectYears:'.( $this->selectYears == true && !empty($this->total_years) ? $this->total_years : self::__val_to_string( $this->selectYears ) );

      $fields = implode( ',', $fields );

      $translations = $this->__get_translate();
      if( !empty($translations) )
        $fields .= ','.$translations;

      if( !empty($this->format) ):
        $fields = preg_replace( "/,format:\"([^\"]+)\"/s", '', $fields );
        $fields = preg_replace( "/,formatSubmit:\"([^\"]+)\"/s", '', $fields );

        $fields .= ',format:"'.$this->format.'"';
        $fields .= ',formatSubmit:"'.$this->format.'"';
      endif;

      $this->__render_label( 'start' );
      ?>
      <script type="text/javascript">
        (function($){$(document).ready(function(){$("form input[name=<?= $this->name;?>]").pickadate({<?= $fields; ?>});});})(jQuery);
      </script>
      <?php $this->__show_icon(); ?>
      <input 
        type="text"
        name="<?= $this->name;?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        value="<?= empty($this->value) ? $this->default : $this->value; ?>"
        autocomplete="off"
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
      $this->__render_label( 'end' );
    }

    protected function __get_translate()
    {
      $file = VC_CFB_Manager::$path.'../assets/js/modules/pickadate/translations/'.$this->locale.'.js';
      if( !file_exists($file) )
        return '';
      
      ob_start();
      readfile( $file );
      $data = ob_get_clean();

      preg_match_all( "/jQuery\.extend\(jQuery\.fn\.pickadate\.defaults,\{([^\}]+)/s", $data, $c );
      if( !isset($c[1][0]) )
        return '';

      $data = $c[1][0];
      
      preg_match_all( "/format:\"([^\"]+)\"/s", $data, $c );
      if( isset($c[1][0]) )
        $data = preg_replace( "/formatSubmit:\"([^\"]+)\"/s", 'formatSubmit:"'.$c[1][0].'"', $data );
      
      return $data;
    }

    protected function __enqueue_custom_element_styles()
    {
      wp_register_style( 'vc_cfb_field_pickadate_popup', VC_CFB_Manager::$url.'/css/modules/pickadate/default.css', false, '3.5.6' );   
      wp_enqueue_style( 'vc_cfb_field_pickadate_popup' );

      wp_register_style( 'vc_cfb_field_pickadate_popup_date', VC_CFB_Manager::$url.'/css/modules/pickadate/default.date.css', array( 'vc_cfb_field_pickadate_popup' ), '3.5.6' );   
      wp_enqueue_style( 'vc_cfb_field_pickadate_popup_date' );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_enqueue_script('jquery');    
      
      wp_register_script( 'vc_cfb_field_pickadate_picker', VC_CFB_Manager::$url.'/js/modules/pickadate/picker.js', array( 'jquery' ), '3.5.6' );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_picker' );

      wp_register_script( 'vc_cfb_field_pickadate_picker_date', VC_CFB_Manager::$url.'/js/modules/pickadate/picker.date.js', array( 'vc_cfb_field_pickadate_picker' ) );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_picker_date' );

      wp_register_script( 'vc_cfb_field_pickadate_legacy', VC_CFB_Manager::$url.'/js/modules/pickadate/legacy.js', array( 'vc_cfb_field_pickadate_picker' ) );   
      wp_enqueue_script( 'vc_cfb_field_pickadate_legacy' );
    }
  }