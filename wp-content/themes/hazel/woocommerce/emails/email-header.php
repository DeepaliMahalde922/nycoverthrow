<?php
/**
 * Email Header
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    	<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            	<tr>
                	<td align="center" valign="top">
						<div style="" id="template_header_image">
	                		<?php
	                			if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
	                				echo '<p style="margin-top:0;margin-bottom:0;line-height:0">'; ?>
                                    <a target="_blank" href="<?php echo home_url(); ?>">
                                    <?php
                                        echo '<img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" />';
                                    ?>
                                    </a>
                                    <?php
                                        echo '</p>';
                                    ?>
                                    <div style="background: #000000 none repeat scroll 0 0;max-width: 975px;padding: 15px 0;" class="social_icons">
                                        <a target="_blank" style="font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="http://instagram.com/overthrownewyork"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/instagram_email_template.jpg"></a>
                                        <a target="_blank" style="margin: 0 10px;font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="https://www.facebook.com/EnjoyGoodDays?ref=hl"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/facebook_email_template.jpg"></a>
                                        <a target="_blank" style="font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="https://twitter.com/overthrownyc"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/twitter_email_template.jpg"></a>
                                    </div>
                                    <?php
	                			}
	                		?>
						</div>
                    	<table border="0" cellpadding="0" cellspacing="0" width="975" id="template_container">                        	
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">
