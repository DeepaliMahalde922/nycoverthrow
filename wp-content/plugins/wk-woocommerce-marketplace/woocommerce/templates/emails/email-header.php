<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_email;

            global $wpdb;
			$sql = "SELECT option_value FROM wp_options WHERE option_name = '$tableName'";
	        $results = $wpdb->get_results($sql);
			$heading=maybe_unserialize($results[0]->option_value)['heading'];
			$v=maybe_unserialize($results[0]->option_value)['email_template'];
			$footer=maybe_unserialize($results[0]->option_value)['footer'];
		    $heading=strtoupper($heading);
            $footer=strtoupper($footer);

			$query="SELECT * FROM {$wpdb->prefix}emailTemplate WHERE title='$v'";
			$result = $wpdb->get_results($query);
			$result=$result[0];
			$backgroundcolor=$result->backgroundcolor;
			$base=$result->basecolor;
			$textcolor=$result->textcolor;
			$body=$result->bodycolor;
			$width_page=$result->pagewidth;
			$logo=$result->logoPath;
            if($backgroundcolor==$textcolor){
				$txt='white';
			}
$result='<html>
		<head></head>
		<body>
		   <table cellspacing="0" class="body-wrap" style=" width:'. $width_page.';  box-shadow: 3px 3px 5px #888888; box-sizing:border-box; background-color:white; border:1px solid black; ">
		    <tr style="margin:0px;">
	           <td class="alert alert-warning" style="background-color: '.$backgroundcolor.' !important; width:100%; margin:0px; padding:20px; text-align:center;"><h1 style="color:'. $textcolor.'; font-size:40px;">'. $heading.'</td>
		    </tr>
			<tr>
		      <td class="container" width="600" style="display: block !important; max-width: 600px !important; margin: 0 auto !important; clear: both !important;">
			     <div class="content" style="max-width: 600px;  margin: 0 auto;  display: block;  padding: 20px;">
				    <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color:white;">';
					?>
