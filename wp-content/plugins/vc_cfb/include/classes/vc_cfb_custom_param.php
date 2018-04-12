<?php
  class VC_CFB_Custom_Param
  {
    protected $name;
    protected $script;
    protected $css;

    function __construct()
    {
      if( !empty($this->script) )
        vc_add_shortcode_param( $this->name, array( $this, '__render' ), $this->script );
      else
        vc_add_shortcode_param( $this->name, array( $this, '__render' ) );

      if( !empty($this->css) )
        add_action( 'admin_enqueue_scripts', array( $this, '__init_css' ) );
    }

    public function __init_css()
    {
      wp_register_style( 'vc_cfb_params_'.$this->name, $this->css, array( 'vc_cfb_backend' ), VC_CFB_Manager::$version );    
      wp_enqueue_style( 'vc_cfb_params_'.$this->name ); 
    }

    public static function __init_params()
    {
      $params = array_diff( scandir( VC_CFB_Manager::$path.'custom_params' ), array( '.', '..' ) );
      if( count($params) <= 0 )
        return;

      foreach( $params as $param ):
        $class = 'VC_CFB_Custom_Param_';
        foreach( explode( '_', substr( $param, 0, -4 ) ) as $s )
          $class .= ucfirst($s);
        
        require_once( VC_CFB_Manager::$path.'custom_params'.'/'.$param );
        if( class_exists($class) )
          new $class();
        
        unset($class);
      endforeach;
    }

    public static function __render( $settings, $value ) {}
  }