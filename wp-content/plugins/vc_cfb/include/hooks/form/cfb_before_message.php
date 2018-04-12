<h4><?php _e( 'Description', 'vc_cfb' );?></h4>
<p><?php _e( '<strong>cfb_before_message</strong> is a filter applied to the string html code of wrapper for messages.', 'vc_cfb' );?></p>
<h4><?php _e( 'Parameters', 'vc_cfb' );?></h4>
<div class="hook_parametr">
  <strong>$code</strong>
  <p><?php _e( 'HTML code string', 'vc_cfb' );?></p>
  <p><?php _e( 'Default: String', 'vc_cfb' );?></p>
</div>
<div class="hook_parametr">
  <strong>$elemenet</strong>
  <p><?php _e( 'Object of class VC_CFB_Element_Form|VC_CFB_Element_Field', 'vc_cfb' );?></p>
  <p><?php _e( 'Default: Object', 'vc_cfb' );?></p>
</div>
<div class="hook_parametr">
  <strong>$type</strong>
  <p><?php _e( 'Type message: error|success|warning', 'vc_cfb' );?></p>
  <p><?php _e( 'Default: String', 'vc_cfb' );?></p>
</div>
<h4><?php _e( 'Examples', 'vc_cfb' );?></h4>
<div class="hook_example">
<pre>function __before_message( $code, $element, $type )
    {
      if( $element->type != 'form' )
        return $code;

      return '&lt;div class="vc_cfb_message vc_cfb_type_error"&gt;&lt;div class="vc_cfb_icon"&gt;&lt;i class="fa fa-exclamation-triangle"&gt;&lt;/i&gt;
  &lt;/div&gt;&lt;ul&gt;';
    }

add_filter( 'cfb_before_message', array( VC_CFB_Manager::$forms[$name], '__before_message' ), 10, 3 );</pre>
</div>
<h4><?php _e( 'Related', 'vc_cfb' );?></h4>
<p>cfb_after_message</p>