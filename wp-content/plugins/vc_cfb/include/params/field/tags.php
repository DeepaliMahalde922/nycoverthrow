<?php
  $params = array( 
              'name',
              'label',
              'default',
              'placeholder',
              'required',
              'readonly',
              'disabled',
              array(
                'type'          =>  'checkbox',
                'param_name'    =>  'removewithbackspace',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Remove with backspace?', 'vc_cfb' ),
                'value'         =>  'true',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'delimiter',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Delimiter?', 'vc_cfb' ),
                'value'         =>  ',',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'minchars',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Min chars for tag?', 'vc_cfb' ),
                'value'         =>  '0',
                'description'   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                'type'          =>  'textfield',
                'param_name'    =>  'maxchars',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                'heading'       =>  __( 'Max chars for element?', 'vc_cfb' ),
                'value'         =>  '',
                'description'   =>  __( 'If empty there is no limit', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "colorpicker",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_colorpicker vc_cfb_hide_field",
                "heading"       =>  __( 'Placeholder color', 'vc_cfb' ),
                "param_name"    =>  "placeholdercolor",
                "value"         =>  '#666666',
                "description"   =>  '',
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              'label_position',
              'label_width',
              'id',
              'classes',
            );