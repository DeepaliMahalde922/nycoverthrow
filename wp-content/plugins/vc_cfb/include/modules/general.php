<?php
  class VC_CFB_FGeneral 
  {
    public static function str_getcsv( $input, $delimiter = ',', $enclosure = '"' ) 
    {
      if( ! preg_match("/[$enclosure]/", $input) )
        return (array)preg_replace(array("/^\\s*/", "/\\s*$/"), '', explode($delimiter, $input));

      $token = "##"; $token2 = "::";
      $t1 = preg_replace(array("/\\\[$enclosure]/", "/$enclosure{2}/",
           "/[$enclosure]\\s*[$delimiter]\\s*[$enclosure]\\s*/", "/\\s*[$enclosure]\\s*/"),
           array($token2, $token2, $token, $token), trim(trim(trim($input), $enclosure)));

      $a = explode($token, $t1);
      foreach($a as $k=>$v)
        if ( preg_match("/^{$delimiter}/", $v) || preg_match("/{$delimiter}$/", $v) )
          $a[$k] = trim($v, $delimiter); $a[$k] = preg_replace("/$delimiter/", "$token", $a[$k]);
      $a = explode($token, implode($token, $a));

      return (array)preg_replace(array("/^\\s/", "/\\s$/", "/$token2/"), array('', '', $enclosure), $a);
    }

    public static function __remove_files( $dirPath )
    {
      if( substr( $dirPath, strlen($dirPath) - 1, 1) != '/' )
          $dirPath .= '/';
      
      $files = glob( $dirPath . '*', GLOB_MARK );
      foreach( $files as $file )
          if (is_dir($file))
            self::__remove_files($file);
          else
              unlink($file);

      rmdir($dirPath);
    }

    public static function get_client_ip() 
    {
      $ipaddress = '';
      if ( getenv( 'HTTP_CLIENT_IP' ) )
          $ipaddress = getenv( 'HTTP_CLIENT_IP' );
      else if( getenv( 'HTTP_X_FORWARDED_FOR' ) )
          $ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
      else if( getenv( 'HTTP_X_FORWARDED' ) )
          $ipaddress = getenv( 'HTTP_X_FORWARDED' );
      else if( getenv( 'HTTP_FORWARDED_FOR' ) )
          $ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
      else if( getenv( 'HTTP_FORWARDED' ) )
         $ipaddress = getenv( 'HTTP_FORWARDED' );
      else if( getenv( 'REMOTE_ADDR' ) )
          $ipaddress = getenv( 'REMOTE_ADDR' );
      else
          $ipaddress = __( 'UNKNOWN', 'vc_cfb' );
      return $ipaddress;
    }

    public static function get_os() 
    { 
        $os_platform    =   __( 'Unknown OS Platform', 'vc_cfb' );
        $os_array       =   array(
                                '/windows nt 10/i'     =>  'Windows 10',
                                '/windows nt 6.3/i'     =>  'Windows 8.1',
                                '/windows nt 6.2/i'     =>  'Windows 8',
                                '/windows nt 6.1/i'     =>  'Windows 7',
                                '/windows nt 6.0/i'     =>  'Windows Vista',
                                '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                '/windows nt 5.1/i'     =>  'Windows XP',
                                '/windows xp/i'         =>  'Windows XP',
                                '/windows nt 5.0/i'     =>  'Windows 2000',
                                '/windows me/i'         =>  'Windows ME',
                                '/win98/i'              =>  'Windows 98',
                                '/win95/i'              =>  'Windows 95',
                                '/win16/i'              =>  'Windows 3.11',
                                '/macintosh|mac os x/i' =>  'Mac OS X',
                                '/mac_powerpc/i'        =>  'Mac OS 9',
                                '/linux/i'              =>  'Linux',
                                '/ubuntu/i'             =>  'Ubuntu',
                                '/iphone/i'             =>  'iPhone',
                                '/ipod/i'               =>  'iPod',
                                '/ipad/i'               =>  'iPad',
                                '/android/i'            =>  'Android',
                                '/blackberry/i'         =>  'BlackBerry',
                                '/webos/i'              =>  'Mobile'
                            );
        foreach ($os_array as $regex => $value)
          if (preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
            $os_platform    =   $value;
        return $os_platform;
    }

    public static function get_browser() 
    {
        $browser        =   __( 'Unknown Browser', 'vc_cfb' );
        $browser_array  =   array(
                                '/msie/i'       =>  'Internet Explorer',
                                '/firefox/i'    =>  'Firefox',
                                '/safari/i'     =>  'Safari',
                                '/chrome/i'     =>  'Chrome',
                                '/edge/i'       =>  'Edge',
                                '/opera/i'      =>  'Opera',
                                '/netscape/i'   =>  'Netscape',
                                '/maxthon/i'    =>  'Maxthon',
                                '/konqueror/i'  =>  'Konqueror',
                                '/mobile/i'     =>  'Handheld Browser'
                            );
        foreach ($browser_array as $regex => $value)
          if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) 
            $browser    =   $value;
        return $browser;
    }
  }