<?php
  $params = array( 
              'name',
              'label',
              array(
                "type"          =>  "encoded_text",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_encoded_text vc_cfb_hide_field",
                "heading"       =>  __( 'Acceptable File Types', 'vc_cfb' ),
                "param_name"    =>  "accept",
                "value"         =>  '',
                "description"   =>  __( 'This attribute specifies the types of files that are allowed to be uploaded. Separated by commas.<br/>Example: .jpg, .png, .pdf, video/*, image/*, audio/* or valid media type', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "checkbox",
                "holder"        =>  "div",
                'value'         =>  'false',
                "class"         =>  "vc_cfb_field_checkbox vc_cfb_hide_field",
                "heading"       =>  __( 'Multiple', 'vc_cfb' ),
                "param_name"    =>  "multiple",
                "is_html5"      =>  true,
                "description"   =>  __( 'When present, it specifies that the user is allowed to enter more than one value in the field.', 'vc_cfb' ),
                "group"         =>  __( 'General', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Max File Size', 'vc_cfb' ),
                "param_name"    =>  "file_size",
                "value"         =>  '',
                "description"   =>  __( 'The size in bytes.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              'tab',
              'required',
              'autofocus',
              'label_position',
              'label_width',
              'id',
              'classes',
              );