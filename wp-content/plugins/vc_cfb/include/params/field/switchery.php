<?php
  $params = array( 
              'name',
              'label',
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Default value', 'vc_cfb' ),
                "param_name"    =>  "default_value",
                "std"           =>  'false',
                "value"         =>  array( 
                                        __( 'Yes/True', 'vc_cfb' )       =>  'true',
                                        __( 'No/False', 'vc_cfb' )       =>  'false',
                                          ),
                "description"   =>  '',
                "group"         =>  __( 'General', 'vc_cfb' )
              ),
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Size', 'vc_cfb' ),
                "param_name"    =>  "size",
                "std"           =>  'medium',
                "value"         =>  array( 
                                        __( 'Small', 'vc_cfb' )       =>  'small',
                                        __( 'Medium', 'vc_cfb' )      =>  'medium',
                                        __( 'Large', 'vc_cfb' )       =>  'large',
                                          ),
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'dropdown',
                'param_name'    =>  'yesnoemail',
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                'heading'       =>  __( 'Show results in email as:', 'vc_cfb' ),
                "std"           =>  0,
                "value"         =>  array( 
                                        __( 'Yes/No', 'vc_cfb' )       =>  1,
                                        __( 'True/False', 'vc_cfb' )   =>  0,
                                          ),
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'speed',
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                'heading'       =>  __( 'Length of time that the transition will take', 'vc_cfb' ),
                'value'         =>  '0,1',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "colorpicker",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                "heading"       =>  __( 'Color of the switch element', 'vc_cfb' ),
                "param_name"    =>  "color",
                "value"         =>  '#64bd63',
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "colorpicker",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                "heading"       =>  __( 'Secondary color for the background color and border, when the switch is off', 'vc_cfb' ),
                "param_name"    =>  "secondarycolor",
                "value"         =>  '#dfdfdf',
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "colorpicker",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                "heading"       =>  __( 'Default color of the jack/handle element', 'vc_cfb' ),
                "param_name"    =>  "jackcolor",
                "value"         =>  '#fff',
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "colorpicker",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                "heading"       =>  __( 'Color of unchecked jack/handle element', 'vc_cfb' ),
                "param_name"    =>  "jacksecondarycolor",
                "value"         =>  '',
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              'label_position',
              'label_width',
              'id',
              'classes',
            );