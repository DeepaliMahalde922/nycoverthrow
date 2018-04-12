<h4><?php _e( 'Description', 'vc_cfb' );?></h4>
<p><?php _e( '<strong>cfb_before_form</strong> is a filter applied to the string html code of wrapper for form.', 'vc_cfb' );?></p>
<h4><?php _e( 'Parameters', 'vc_cfb' );?></h4>
<div class="hook_parametr">
  <strong>$code</strong>
  <p><?php _e( 'HTML code string', 'vc_cfb' );?></p>
  <p><?php _e( 'Default: String', 'vc_cfb' );?></p>
</div>
<div class="hook_parametr">
  <strong>$form</strong>
  <p><?php _e( 'Object of class VC_CFB_Element_Form', 'vc_cfb' );?></p>
  <p><?php _e( 'Default: Object', 'vc_cfb' );?></p>
</div>
<h4><?php _e( 'Examples', 'vc_cfb' );?></h4>
<div class="hook_example">
<pre>function __before_form( $string, $form )
  {
    return '&lt;div class="'.implode( ' ', apply_filters( 'cfb_befor_form_classes', array( 'vc_cfb_form_wrapper' ), $form ) ).'"&gt;';
  }

add_filter( 'cfb_before_form', array( VC_CFB_Manager::$forms[$name], '__before_form' ), 10, 2 );</pre>
</div>
<h4><?php _e( 'Related', 'vc_cfb' );?></h4>
<p>cfb_after_form</p>