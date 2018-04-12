<?php

if ( ! defined ( 'ABSPATH' ) )

    exit;

function set_reviews()
{
  global $wpdb;

  if(isset($_POST['feed_value'])&& isset($_POST['feed_nickname'])&& isset($_POST['feed_summary'])&& isset($_POST['feed_review']))
  {
  $seller_id="'".$_POST['mp_wk_seller']."'";
  $user_id="'".$_POST['mp_wk_user']."'";
  $feedprice="'".$_POST['feed_price']."'";
  $feed_value="'".$_POST['feed_value']."'";
  $feed_quali="'".$_POST['feed_quality']."'";
  $nickname="'".$_POST['feed_nickname']."'";
  $summar="'".$_POST['feed_summary']."'";
  $review="'".$_POST['feed_review']."'";
  $cre_date="'".$_POST['create_date']."'";
  $sql = $wpdb->get_results("insert into {$wpdb->prefix}mpfeedback (seller_id,user_id,price_r,value_r,quality_r,nickname,review_summary,review_desc,review_time) VALUES ($seller_id,$user_id,$feedprice,$feed_value,$feed_quali,$nickname,$summar,$review,$cre_date)");
  do_action( 'mp_save_seller_review_notification', $_POST, $wpdb->insert_id );
  }
}

function get_review($id)
{
  global $wpdb;
  return $wpdb->get_results("select * from {$wpdb->prefix}mpfeedback where seller_id=$id");
}

function get_seller_details($user_id)
{
return get_user_meta($user_id);
}

function admin_mailer()
{
  if(isset($_POST['subject'])&&isset($_POST['message']))
  {
    if(!empty($_POST['subject']) && !empty($_POST['message']))
    {
      $current_user = wp_get_current_user();
      echo __("<div class='wkmp_askto_error'>".MP_Form_Handler::admin_ask($current_user->user_email,$_POST['subject'],$_POST['message'])."</div>", "marketplace");
      unset($_POST['mailfrom']);
      ?>
      <div class="wkmp-modal-footer">
        <input type="button" value="Close" class="button wk-ask-close">
        <span style="clear:both;"></span>
      </div>
      <?php
      exit;
    }
  }
}

//retirving password
function pass_reset()
{
	global $wpdb, $current_site;
	$errors = new WP_Error();
	if ( empty( $_POST['user_login'] ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', 'marketplace'));
	} else if ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.', 'marketplace'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
	}
	do_action('lostpassword_post');
	if ( $errors->get_error_code() )
		return $errors;
	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', 'marketplace'));
		return $errors;
	}
	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	do_action('retreive_password', $user_login);  // Misspelled and deprecated
	do_action('retrieve_password', $user_login);
	$allow = apply_filters('allow_password_reset', true, $user_data->ID);
	if ( ! $allow )
		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'marketplace'));
	else if ( is_wp_error($allow) )
		return $allow;
	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
	if ( empty($key) ) {
		// Generate something random for a key...
		$key = wp_generate_password(20, false);
		do_action('retrieve_password_key', $user_login, $key);
		// Now insert the new md5 key into the db
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}
	$message = __('Someone requested that the password be reset for the following account:', 'marketplace') . "\r\n\r\n";
	$message .= network_site_url() . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', 'marketplace'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'marketplace') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:', 'marketplace') . "\r\n\r\n";
	$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$title = sprintf( __('[%s] Password Reset', 'marketplace'), $blogname );
	$title = apply_filters('retrieve_password_title', $title);
	$message = apply_filters('retrieve_password_message', $message, $key);
	if ( $message && !wp_mail($user_email, $title, $message) ){
			$errors->add('invalidcombo', __('<strong>ERROR</strong>: The e-mail could not be sent. <br /> Possible reason: your host may have disabled the mail() function...', 'marketplace'));
		return $errors;
		}
	return true;
}
