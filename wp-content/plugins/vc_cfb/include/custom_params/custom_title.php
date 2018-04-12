<?php
  class VC_CFB_Custom_Param_CustomTitle extends VC_CFB_Custom_Param
  {
    function __construct()
    {
      $this->name = 'custom_title';
      parent::__construct();
    }

    public static function __render( $settings, $value )
    {
      return '';
    }
  }