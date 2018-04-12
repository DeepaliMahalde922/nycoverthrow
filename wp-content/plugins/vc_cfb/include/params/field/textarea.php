<?php
  $params = array( 
              'name',
              'label',
              'placeholder',
              'required',
              'maxlength',
              'tab',
              'autofocus',
              'disabled',
              'readonly',
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Wrap', 'vc_cfb' ),
                "param_name"    =>  "wrap",
                "value"         =>  array( 'soft' => 'soft', 'hard' => 'hard' ),
                "description"   =>  __( 'The wrap attribute specifies how the text in a text area is to be wrapped when submitted in a form.', 'vc_cfb' ),
                "group"         =>  __( 'Advanced', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textarea",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Default value', 'vc_cfb' ),
                "param_name"    =>  "default",
                "value"         =>  '',
                "description"   =>  '',
                "group"         =>  __( 'Content', 'vc_cfb' )
              ),
              'label_position',
              'label_width',
              'id',
              'classes',
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Columns', 'vc_cfb' ),
                "param_name"    =>  "cols",
                "value"         =>  '',
                "description"   =>  __( 'Specifies the visible width of a text area. It can be rewrited on css. If value is empty, then option will be disabled.', 'vc_cfb' ),
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Rows', 'vc_cfb' ),
                "param_name"    =>  "rows",
                "value"         =>  '',
                "description"   =>  __( 'Specifies the visible number of lines in a text area. It can be rewrited on css. If value is empty, then option will be disabled.', 'vc_cfb' ),
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
            );