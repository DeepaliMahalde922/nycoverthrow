<?php
  class VC_CFB_Element
  {
    /* shortcode name */
    public $shortcode_name;

    /* field name */
    protected $name;

    /* field type */
    protected $type;

    /* field id */
    protected $id;
    
    /* field classes */
    protected $classes = array();

    /* field settings for admin */
    protected $settings;

    /* Default list settings field for VC admin area */
    private static $settings_fields;

    /* Null, False, True */
    protected $valid = NULL;

    function __construct( $name )
    {
      self::$settings_fields = array(
                                        'name'    =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield",
                                                            "heading"       =>  __( 'Field Name', 'vc_cfb' ),
                                                            "param_name"    =>  "name",
                                                            "value"         =>  '',
                                                            "description"   =>  sprintf( __( 'This value will be used for attribute <code>%s</code> your field and must be unique within one form. Do not use space. Example: form_1, demo_form', 'vc_cfb' ), 'name' ),
                                                            "group"         =>  __( 'General', 'vc_cfb' )
                                                          ),
                                        'label'    =>  array(
                                                            "type"          =>  "encoded_text",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Field Label', 'vc_cfb' ),
                                                            "param_name"    =>  "label",
                                                            "value"         =>  '',
                                                            "description"   =>  __( 'Label of field for showing only in email. Uses in default email template.', 'vc_cfb' ),
                                                            "group"         =>  __( 'General', 'vc_cfb' )
                                                          ),
                                        'label_position'    =>  array(
                                                            'type'          => 'dropdown',
                                                            'class'         => 'vc_cfb_hide_field',
                                                            'heading'       => __( 'Label position', 'vc_cfb' ),
                                                            'description'   => __( 'Select label position.', 'vc_cfb' ),
                                                            'param_name'    => 'label_position',
                                                            'value'         => array(
                                                                                __( 'Left', 'vc_cfb' )                                  =>  'left',
                                                                                __( 'Right', 'vc_cfb' )                                 =>  'right',
                                                                                __( 'Top', 'vc_cfb' )                                   =>  'top',
                                                                                __( 'Bottom', 'vc_cfb' )                                =>  'bottom',
                                                                                __( 'Show only in default email template', 'vc_cfb' )   =>  'none',
                                                                              ),
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'label_width'    =>  array(
                                                            'type'          => 'dropdown',
                                                            'class'         => 'vc_cfb_hide_field',
                                                            'heading'       => __( 'Label width', 'vc_cfb' ),
                                                            'description'   => __( 'Label width in column. Full line width is 12 column.', 'vc_cfb' ),
                                                            'param_name'    => 'label_width',
                                                            'dependency'   => array(
                                                                                      'element' => 'label_position',
                                                                                      'value'   => array( 'left', 'right' ),
                                                                                    ),
                                                            "value"         =>  array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ),
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'classes'       =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Classes', 'vc_cfb' ),
                                                            "param_name"    =>  "classes",
                                                            "value"         =>  '',
                                                            "description"   =>  __( 'List of classes space separated. They will combined with default CFB classes.', 'vc_cfb' ),
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'id'            =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Id', 'vc_cfb' ),
                                                            "param_name"    =>  "id",
                                                            "value"         =>  '',
                                                            "description"   =>  __( 'This value must be unique per page.', 'vc_cfb' ),
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'css'           =>  array(
                                                            'type'          =>  'css_editor',
                                                            'class'         =>  'vc_cfb_hide_field',
                                                            'heading'       =>  __( 'CSS box', 'vc_cfb' ),
                                                            'param_name'    =>  'css',
                                                            'group'         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'required'      =>  array(
                                                            'type'          =>  'checkbox',
                                                            'param_name'    =>  'required',
                                                            "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                                                            'heading'       =>  __( 'Is this field required?', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' ),
                                                            "is_html5"      =>  true,
                                                            'value'         =>  'false',
                                                            'description'   =>  __( 'It\'s important for validation.', 'vc_cfb' )
                                                          ),
                                        'disabled'      =>  array(
                                                            'type'          =>  'checkbox',
                                                            'param_name'    =>  'disabled',
                                                            'value'         =>  'false',
                                                            "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                                                            'heading'       =>  __( 'Is this field disabled?', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' ),
                                                            'description'   =>  __( 'A disabled input element is unusable and un-clickable. Disabled element in a form will not be submitted.', 'vc_cfb' )
                                                          ),
                                        'readonly'      =>  array(
                                                            'type'          =>  'checkbox',
                                                            'param_name'    =>  'readonly',
                                                            'value'         =>  'false',
                                                            "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                                                            'heading'       =>  __( 'Is this field read-only?', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' ),
                                                            'description'   =>  __( 'A read-only input field cannot be modified (however, a user can tab to it, highlight it, and copy the text from it).', 'vc_cfb' )
                                                          ),
                                        'autocomplete'  =>  array(
                                                            'type'          =>  'checkbox',
                                                            'param_name'    =>  'autocomplete',
                                                            'value'         =>  'false',
                                                            "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                                                            'heading'       =>  __( 'Disable autocomplete?', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' ),
                                                            "is_html5"      =>  true,
                                                            'description'   =>  __( 'Autocomplete allows the browser to predict the value. When a user starts to type in a field, the browser should display options to fill in the field, based on earlier typed values.', 'vc_cfb' )
                                                          ),
                                        'autofocus'     =>  array(
                                                            'type'          =>  'checkbox',
                                                            'param_name'    =>  'autofocus',
                                                            'value'         =>  'false',
                                                            "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                                                            'heading'       =>  __( 'Is this field autofocus?', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' ),
                                                            "is_html5"      =>  true,
                                                            'description'   =>  __( 'When present, it specifies that an element should automatically get focus when the page loads.', 'vc_cfb' )
                                                          ),
                                        'placeholder'   =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Short hint', 'vc_cfb' ),
                                                            "param_name"    =>  "placeholder",
                                                            "value"         =>  '',
                                                            "is_html5"      =>  true,
                                                            "description"   =>  __( 'The short hint is displayed in the input field before the user enters a value.', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' )
                                                          ),
                                        'default'       =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Default value', 'vc_cfb' ),
                                                            "param_name"    =>  "default",
                                                            "value"         =>  '',
                                                            "description"   =>  '',
                                                            "group"         =>  __( 'General', 'vc_cfb' )
                                                          ),
                                        'tab'           =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Tab index', 'vc_cfb' ),
                                                            "param_name"    =>  "tab",
                                                            "value"         =>  '',
                                                            "description"   =>  __( 'The tabindex attribute specifies the tab order of an element (when the "tab" button is used for navigating).', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' )
                                                          ),
                                        'maxlength'     =>  array(
                                                            "type"          =>  "textfield",
                                                            "holder"        =>  "div",
                                                            "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                            "heading"       =>  __( 'Max length', 'vc_cfb' ),
                                                            "param_name"    =>  "maxlength",
                                                            "value"         =>  '',
                                                            "description"   =>  __( 'The maximum number of characters allowed in the element.', 'vc_cfb' ),
                                                            "group"         =>  __( 'Advanced', 'vc_cfb' )
                                                          ),
                                        'add_icon'          =>  array(
                                                            'type'          => 'checkbox',
                                                            'class'         => 'vc_cfb_hide_field',
                                                            'heading'       => __( 'Add icon?', 'vc_cfb' ),
                                                            'param_name'    => 'add_icon',
                                                            'value'         =>  'false',
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_align'        =>  array(
                                                            'type'          => 'dropdown',
                                                            'class'         => 'vc_cfb_hide_field',
                                                            'heading'       => __( 'Icon Alignment', 'vc_cfb' ),
                                                            'description'   => __( 'Select icon alignment.', 'vc_cfb' ),
                                                            'param_name'    => 'icon_align',
                                                            'value'         => array(
                                                                                __( 'Left', 'vc_cfb' ) => 'left',
                                                                                __( 'Right', 'vc_cfb' ) => 'right',
                                                                              ),
                                                            'dependency' => array(
                                                              'element' => 'add_icon',
                                                              'value' => 'true',
                                                            ),
                                                            "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_type'       => array(
                                                              'type'        => 'dropdown',
                                                              'class'       => 'vc_cfb_hide_field',
                                                              'heading'     => __( 'Icon library', 'vc_cfb' ),
                                                              'value'       => array(
                                                                                  __( 'Font Awesome', 'vc_cfb' )   => 'fontawesome',
                                                                                  __( 'Open Iconic', 'vc_cfb' )    => 'openiconic',
                                                                                  __( 'Typicons', 'vc_cfb' )       => 'typicons',
                                                                                  __( 'Entypo', 'vc_cfb' )         => 'entypo',
                                                                                  __( 'Linecons', 'vc_cfb' )       => 'linecons',
                                                              ),
                                                              'param_name' => 'icon_type',
                                                              'description' => __( 'Select icon library.', 'vc_cfb' ),
                                                              'dependency'   => array(
                                                                                'element' => 'add_icon',
                                                                                'value'   => 'true',
                                                                              ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                              ),
                                        'icon_fa'         =>  array(
                                                             'type'         =>  'iconpicker',
                                                             'class'        =>  'vc_cfb_hide_field',
                                                             'heading'      =>  __( 'Icon', 'vc_cfb' ),
                                                             'param_name'   =>  'icon_fontawesome',
                                                             'value'        =>  'fa fa-adjust', 
                                                             'settings'     =>  array(
                                                                                  'emptyIcon'     => false,
                                                                                  'iconsPerPage'  => 4000,
                                                                                ),
                                                             'description'  =>  __( 'Select icon from library.', 'vc_cfb' ),
                                                             "group"        =>  __( 'Design', 'vc_cfb' ),
                                                             'dependency'   => array(
                                                                                'element' => 'icon_type',
                                                                                'value'   => 'fontawesome',
                                                                              ),
                                                             "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_oi'         =>  array(
                                                              'type'        =>  'iconpicker',
                                                              'class'       =>  'vc_cfb_hide_field',
                                                              'heading'     =>  __( 'Icon', 'vc_cfb' ),
                                                              'param_name'  =>  'icon_openiconic',
                                                              'settings'    =>  array(
                                                                                  'emptyIcon'     => false, 
                                                                                  'type'          => 'openiconic',
                                                                                  'iconsPerPage'  => 200
                                                                                ),
                                                              'dependency'  =>  array(
                                                                                  'element' => 'icon_type',
                                                                                  'value'   => 'openiconic',
                                                                                ),
                                                              'description' => __( 'Select icon from library.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_ti'         =>  array(
                                                              'type'        =>  'iconpicker',
                                                              'class'       =>  'vc_cfb_hide_field',
                                                              'heading'     =>  __( 'Icon', 'vc_cfb' ),
                                                              'param_name'  =>  'icon_typicons',
                                                              'settings'    =>  array(
                                                                                  'emptyIcon'     => false,
                                                                                  'type'          => 'typicons',
                                                                                  'iconsPerPage'  => 200
                                                                                ),
                                                              'dependency'  =>  array(
                                                                                  'element' => 'icon_type',
                                                                                  'value' => 'typicons',
                                                                                ),
                                                              'description' => __( 'Select icon from library.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_ei'         =>  array(
                                                              'type'        =>  'iconpicker',
                                                              'class'       =>  'vc_cfb_hide_field',
                                                              'heading'     =>  __( 'Icon', 'vc_cfb' ),
                                                              'param_name'  =>  'icon_entypo',
                                                              'settings'    =>  array(
                                                                                  'emptyIcon'     => false,
                                                                                  'type'          => 'entypo',
                                                                                  'iconsPerPage'  => 300,
                                                                                ),
                                                              'dependency'  =>  array(
                                                                                  'element' => 'icon_type',
                                                                                  'value' => 'entypo',
                                                                                ),
                                                              'description' => __( 'Select icon from library.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_li'         =>  array(
                                                              'type'        =>  'iconpicker',
                                                              'class'       =>  'vc_cfb_hide_field',
                                                              'heading'     =>  __( 'Icon', 'vc_cfb' ),
                                                              'param_name'  =>  'icon_linecons',
                                                              'settings'    =>  array(
                                                                                  'emptyIcon'     => false,
                                                                                  'type'          => 'linecons',
                                                                                  'iconsPerPage'  => 200,
                                                                                ),
                                                              'dependency'  =>  array(
                                                                                  'element'   => 'icon_type',
                                                                                  'value'     => 'linecons',
                                                                                ),
                                                              'description' =>  __( 'Select icon from library.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'icon_color'      =>  array(
                                                              "type"          =>  "colorpicker",
                                                              "holder"        =>  "div",
                                                              "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                                                              "heading"       =>  __( 'Icon color', 'vc_cfb' ),
                                                              "param_name"    =>  "icon_color",
                                                              "value"         =>  '',
                                                              "description"   =>  '',
                                                              'dependency'   => array(
                                                                                'element' => 'add_icon',
                                                                                'value'   => 'true',
                                                                              ),
                                                              "group"         =>  __( 'Design', 'vc_cfb' )
                                                          ),
                                        'add_mask'        =>  array(
                                                              'type'          => 'checkbox',
                                                              'value'         =>  'false',
                                                              'class'         => 'vc_cfb_hide_field',
                                                              'heading'       => __( 'Show mask in field?', 'vc_cfb' ),
                                                              'param_name'    => 'add_mask',
                                                              "group"         =>  __( 'Content', 'vc_cfb' )
                                                          ),
                                        'mask_template'   =>  array(
                                                              "type"          =>  "encoded_text",
                                                              "holder"        =>  "div",
                                                              "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                              "heading"       =>  __( 'Mask template', 'vc_cfb' ),
                                                              "param_name"    =>  "mask_template",
                                                              "value"         =>  '',
                                                              "description"   =>  __( 'Examples: <a href="https://github.com/digitalBush/jquery.maskedinput" target="_blank">jquery.maskedinput</a>.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Content', 'vc_cfb' ),
                                                              'dependency'   => array(
                                                                                'element' => 'add_mask',
                                                                                'value'   => 'true',
                                                                              ),
                                                          ),
                                        'mask_placeholder'   =>  array(
                                                              "type"          =>  "textfield",
                                                              "holder"        =>  "div",
                                                              "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                                                              "heading"       =>  __( 'Short hint for mask', 'vc_cfb' ),
                                                              "param_name"    =>  "mask_placeholder",
                                                              "value"         =>  '_',
                                                              "description"   =>  __( 'The short hint is displayed in the field before the user enters a value.', 'vc_cfb' ),
                                                              "group"         =>  __( 'Content', 'vc_cfb' ),
                                                              'dependency'   => array(
                                                                                'element' => 'add_mask',
                                                                                'value'   => 'true',
                                                                              ),
                                                          ),
                                        'mask_autoclear'    =>  array(
                                                              'type'          => 'checkbox',
                                                              'value'         =>  'false',
                                                              'class'         => 'vc_cfb_hide_field',
                                                              'heading'       => __( 'Disable autoclear?', 'vc_cfb' ),
                                                              'param_name'    => 'mask_autoclear',
                                                              'dependency'   => array(
                                                                                'element' => 'add_mask',
                                                                                'value'   => 'true',
                                                                              ),
                                                              "group"         =>  __( 'Content', 'vc_cfb' )
                                                          ),
                                      );

      $this->settings = array(
                                'name'        =>  '',
                                'class'       =>  array( 'vc_cfb_shortcode_settings' ),
                                'category'    =>  __( 'Forms Builder', 'vc_cfb' ),
                                'icon'        =>  '',
                                'params'      =>  array(),
                                'description' =>  ''
                              );
      
      $this->shortcode_name = $name;
    }

    /* Set attributes from shortcode */
    protected function __set_attr( $attr, $content )
    {
      $this->name = VC_CFB_Shortcode::__sanitize_name( $attr['name'] );
      
      if( isset($attr['classes']) )
        $this->classes = explode( ' ', $attr['classes'] );
      
      if( isset( $attr['css'] ) )
        $this->classes[] = vc_shortcode_custom_css_class( $attr['css'] );

      if( isset($attr['id']) )
        $this->id = VC_CFB_Shortcode::__sanitize_name( $attr['id'] );
    }

    static function filter_params_as_html5( $params )
    {
      foreach( $params as $key => $param )
        if( isset($param['is_html5']) ):
          if( (bool)$param['is_html5'] )
            $params[$key]['heading'] = '<span class="vc_cfb_html5_attribute"></span>'.$params[$key]['heading'];
          unset($params[$key]['is_html5']);
        endif;

      return $params;
    }

    public function __init_vc()
    {
      $settings = array(
              "name"                        =>  $this->settings['name'],
              "base"                        =>  ( isset($this->settings['base']) && !empty($this->settings['base']) ? $this->settings['base'] : "cfb_".$this->type ),
              "class"                       =>  implode( ' ', $this->settings['class'] ),
              "category"                    =>  $this->settings['category'],
              "icon"                        =>  $this->settings['icon'],
              "params"                      =>  self::__create_array_params($this->settings['params']),
              );

      foreach( array( 'show_settings_on_create', 'js_view', 'is_container', 'as_parent', 'as_child', 'description' ) as $key )
        if( isset($this->settings[$key]) )
          $settings[$key] = $this->settings[$key];
        else
          continue;
      vc_map( $settings );
    }

    private static function __create_array_params( $list )
    {
      $params = array();
      foreach( $list as $value )
        if( is_array($value) )
          $params[] = $value;
        else
          if( isset(self::$settings_fields[$value]) )
            $params[] = self::$settings_fields[$value];

      add_filter( 'cfb_elements_params', array( 'VC_CFB_Element', 'filter_params_as_html5' ) );
      return apply_filters( 'cfb_elements_params', $params );
    }

    public static function __processing( $atts, $content )
    {
      if( empty($atts['name']) )
        return;
      
      $field = VC_CFB_Manager::$forms[ VC_CFB_Manager::$current_form ]->__field_by_name( VC_CFB_Shortcode::__sanitize_name( $atts['name'] ) );
      if( $field === NULL )
        return;

      $string = '';
        ob_start(); 
          echo apply_filters( 'cfb_before_field_block', '', $field );
            $field->__render_wrap();
          echo apply_filters( 'cfb_after_field_block', '', $field );
        $string = ob_get_contents();  
        ob_end_clean(); 

      return VC_CFB_Minify::minify_html( $string );
    }

    static protected function __val_to_string( $value )
    {
      if( $value === TRUE )
        return 'true';
      elseif( $value === FALSE )
        return 'false';
      else
        return (string)$value;
    }

    public function __get_property( $name )
    {
      return $this->$name;
    }

    protected function __after_validate()
    {
      $this->valid = apply_filters( 'cfb_after_validate', $this->valid, $this );
      $this->validation_messages = apply_filters( 'cfb_after_validate_messages', $this->validation_messages, $this );
    }

    protected function __render() {}
    protected function __default_attr( $attr ) {}
    protected function __validate() {}
    public static function frontend_admin_enqueue_script() {}
    public static function frontend_admin_enqueue_styles() {}
    protected function __enqueue_custom_element_styles() {}
    protected function __enqueue_custom_element_scripts() {}
    public function __init_hooks_description(){}
  }