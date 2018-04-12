<?php
  $params = array( 
              'name',
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Button type', 'vc_cfb' ),
                "param_name"    =>  "type",
                "value"         =>  array( 'submit' => 'submit', 'reset' => 'reset', 'button' => 'button' ),
                "description"   =>  '',
                "group"         =>  __( 'General', 'vc_cfb' )
              ),
              'default',
              'tab',
              'autofocus',
              'disabled',
              array(
                "type"          =>  "textfield",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textfield vc_cfb_hide_field",
                "heading"       =>  __( 'Event onclick', 'vc_cfb' ),
                "param_name"    =>  "onclick",
                "value"         =>  '',
                "description"   =>  __( 'Enter function for event onclick.', 'vc_cfb' ),
                "group"         =>  __( 'Content', 'vc_cfb' )
              ),
              array(
                "type"          =>  "textarea_raw_html",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_textarea_raw_html vc_cfb_hide_field",
                "heading"       =>  __( 'JavaScript', 'vc_cfb' ),
                "param_name"    =>  "content",
                "value"         =>  '',
                "description"   =>  '',
                "group"         =>  __( 'Content', 'vc_cfb' )
              ),
              array(
                "type"          =>  "dropdown",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_select vc_cfb_hide_field",
                "heading"       =>  __( 'Button align', 'vc_cfb' ),
                "param_name"    =>  "align",
                "value"         =>  array( 'left' => 'left', 'center' => 'center', 'right' => 'right' ),
                "description"   =>  '',
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
              'add_icon',
              'icon_align',
              'icon_type',
              'icon_fa',
              'icon_oi',
              'icon_ti',
              'icon_ei',
              'icon_li',
              'icon_color',
              array(
                "type"          =>  "element_padding",
                "holder"        =>  "div",
                "class"         =>  "vc_cfb_field_padding vc_cfb_hide_field",
                "heading"       =>  __( 'Padding', 'vc_cfb' ),
                "param_name"    =>  "padding",
                "value"         =>  '',
                "description"   =>  '',
                "group"         =>  __( 'Design', 'vc_cfb' )
              ),
              'id',
              'classes',
              );