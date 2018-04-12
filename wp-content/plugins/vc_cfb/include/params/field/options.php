<?php
  $params = array( 
              'name',
              'label',
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Element type', 'vc_cfb' ),
                "param_name"    =>  "type",
                "value"         =>  array( 'select' => 'select', 'multi select' => 'multiselect', 'checkbox' => 'checkbox', 'radio' => 'radio' ),
                "description"   =>  '',
                "group"         =>  __( 'General', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Default value', 'vc_cfb' ),
                "param_name"    =>  "default",
                "value"         =>  '',
                "description"   =>  __( 'Keys for values, separated comma for checkbox or multi select', 'vc_cfb' ),
                "group"         =>  __( 'General', 'vc_cfb' )
              ),
              array(
                "type"          =>  "custom_options",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  "",
                "param_name"    =>  "options",
                "value"         =>  '',
                "description"   =>  '',
                "format"        =>  '<div><input type="text" name="key" placeholder="'.__( 'Unique key', 'vc_cfb' ).'"/><input type="text" name="label" placeholder="'.__( 'Label', 'vc_cfb' ).'"/></div>',
                "group"         =>  __( 'Values', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Short hint', 'vc_cfb' ),
                "param_name"    =>  "placeholder",
                'dependency'   => array(
                                          'element' => 'type',
                                          'value'   => array( 'multiselect', 'select' ),
                                        ),
                "value"         =>  '',
                "description"   =>  __( 'The short hint is displayed in the field before the user select a value. This option is confirmed working in the following browsers:<ul><li>Google Chrome - v.43.0.2357.132</li><li>Mozilla Firefox - v.39.0</li><li>Safari - v.8.0.7</li><li>Microsoft Internet Explorer - v.11</li><li>Project Spartan - v.15.10130</li></ul>', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'checkbox',
                'value'         =>  'false',
                'param_name'    =>  'required',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Is this field required?', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' ),
                "is_html5"      =>  true,
                'description'   =>  __( 'It\'s important for validation.', 'vc_cfb' )
              ),
              array(
                "type"          =>  "attach_image",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_upload vc_cfb_hide_field",
                "heading"       =>  __( 'Upload Field Values', 'vc_cfb' ),
                "param_name"    =>  "options_file",
                "value"         =>  '',
                "description"   =>  __( 'May be used to add bulk values. Only <strong>CSV</strong> file. Two columns: key, value.', 'vc_cfb' ),
                "group"         =>  __( 'Values', 'vc_cfb' )
              ),
              'tab',
              'autofocus',
              'disabled',
              'label_position',
              'label_width',
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Size', 'vc_cfb' ),
                "param_name"    =>  "size",
                'dependency'   => array(
                                          'element' => 'type',
                                          'value'   => 'multiselect',
                                        ),
                "value"         =>  '',
                "description"   =>  __( 'Defines the number of visible options in a drop-down list.', 'vc_cfb' ),
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Column width', 'vc_cfb' ),
                "param_name"    =>  "column_width",
                'dependency'   => array(
                                          'element' => 'type',
                                          'value'   => array( 'checkbox', 'radio' ),
                                        ),
                "value"         =>  array( '1/12' => '1', '1/6' => '2', '1/4' => '3', '1/3' => '4', '1/2' => '6', __( 'Full width', 'vc_cfb' ) => '12' ),
                "description"   =>  '',
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
              'id',
              'classes',
            );