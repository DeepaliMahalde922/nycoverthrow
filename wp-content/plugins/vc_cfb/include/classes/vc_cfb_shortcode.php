<?php
  class VC_CFB_Shortcode
  {
    static function __sanitize_name( $name )
    {
      $name = preg_replace('/[^\w]+/u', '__', $name);
      return preg_replace('/__+/u', '_', $name);
    }

    static function __type_by_title( $name )
    {
      foreach( VC_CFB_Manager::$elements as $type => $element )
        if( $element->shortcode_name == $name )
          return $type;
    }

    static function __init_elements_schedule()
    {
      self::__init_elements( 'field' );
      self::__init_elements();
    }

    private static function __init_elements( $folder = '' )
    {
      $elements = array_diff( scandir( VC_CFB_Manager::$path.'elements/'.$folder ), array( '.', '..', 'field' ) );
      if( count($elements) <= 0 )
        return;

      foreach( $elements as $element ):
        $type = substr( $element, 0, -4 );
        self::__init_element( 'cfb_'.$type, $type, 'VC_CFB_Element_'.( !empty($folder) ? ucfirst($folder).'_' : '' ).ucfirst( $type ), VC_CFB_Manager::$path.'elements/'.( !empty($folder) ? $folder.'/' : '' ).$element );
        unset($type);
      endforeach;
    }

    static function __init_element( $name, $type, $class, $path )
    {
      if( array_key_exists( $type, VC_CFB_Manager::$elements ) )
        return;
      
      require_once( $path );
      if( class_exists($class) ):
        VC_CFB_Manager::$elements[ $type ] = new $class( $name );
        add_shortcode( $name, array( $class, '__processing') );
        
        add_action( 'vc_before_init', array( VC_CFB_Manager::$elements[ $type ], '__init_vc' ) );
        VC_CFB_Manager::$elements[ $type ]->__init_hooks_description();
          
        add_action( 'admin_enqueue_scripts', array( $class, 'frontend_admin_enqueue_script' ) );
        add_action( 'admin_enqueue_scripts', array( $class, 'frontend_admin_enqueue_styles' ) );
      endif;
    }

    static function __params( $path ) 
    {
      include_once( VC_CFB_Manager::$path.'params/'.$path.'.php' );
      return $params;
    }

    private static function __pattern( $text ) 
    {
      $pattern = get_shortcode_regex();
      preg_match_all( "/$pattern/s", $text, $c );
      return $c;
    }

    private static function parse_atts( $content ) 
    {
      $content = preg_match_all( '/([^ ]*)=(\'([^\']*)\'|\"([^\"]*)\"|([^ ]*))/', trim( $content ), $c );
      list( $dummy, $keys, $values ) = array_values( $c );
      $c = array();
      foreach ( $keys as $key => $value ) {
          $value = trim( $values[ $key ], "\"'" );
          $type = is_numeric( $value ) ? 'int' : 'string';
          $type = in_array( strtolower( $value ), array( 'true', 'false' ) ) ? 'bool' : $type;
          switch ( $type ) {
              case 'int': $value = (int) $value; break;
              case 'bool': $value = strtolower( $value ) == 'true'; break;
          }
          $c[ $keys[ $key ] ] = $value;
      }
      return $c;
    }

    static function __shortcodes( $text, $output = array(), $child = false ) 
    {
      $patts = self::__pattern( $text );
      $t = array_filter( self::__pattern( $text ) );
      if ( ! empty( $t ) ) 
      {
          list( $d, $d, $parents, $atts, $d, $contents ) = $patts;
          $out2 = array();
          $n = 0;
          foreach( $parents as $k=>$parent ) 
          {
              ++$n;
              $name = $child ? 'child' . $n : $n;
              $t = array_filter( self::__pattern( $contents[ $k ] ) );
              $t_s = self::__shortcodes( $contents[ $k ], $out2, true );
              $output[ $name ] = array( 'name' => $parents[ $k ] );
              $output[ $name ]['atts'] = self::parse_atts( $atts[ $k ] );
              $output[ $name ]['original_content'] = $contents[ $k ];
              $output[ $name ]['content'] = ! empty( $t ) && ! empty( $t_s ) ? $t_s : $contents[ $k ];
          }
      }

      return ( empty($output) ? NULL : array_values( $output ) );
    }
  }