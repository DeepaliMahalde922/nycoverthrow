<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/custom_mail.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$loginurl=get_option('admin_email');
$welcome=sprintf( __("Welcome to %s! "), get_option('blogname')) . "\r\n\n";
$msg= __('Someone asked query for following account:') . "\r\n\r\n";
$username=__('Username:-','marketplace').$data['email'];
$password=__('Question to ask- :','marketplace')."\r\n";
$admin=__('Query:-','marketplace').$data['ask'];
$reference=__('Subject:-','marketplace').$data['subject'];
$thnksmsg=__('Thanks for choosing Marketplace.','marketplace');




global $wpdb;
			$sql = "SELECT option_value FROM wp_options WHERE option_name = '$tableName'";
	        $results = $wpdb->get_results($sql);
			$v=maybe_unserialize($results[0]->option_value)['email_template'];
			$footer=maybe_unserialize($results[0]->option_value)['footer'];
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

  $result=' <tr>
					 <td class="alert alert-warning" >
						<h4>HI </h4>'.$loginurl.'
					 </td>
				  </tr>
		          <tr>
		 			<td class="content-wrap" style="padding: 20px;">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
							   <td class="content-block" style="color:'.$backgroundcolor.';">
									<h3> <strong>'.$welcome.'</strong><h3></br>
							   </td>
							</tr>
							<tr>
							  <td class="content-block">
									<h4>'.$msg.'</h4>
							  </td>
							</tr>
							<tr style="padding:0px;">
								<td class="content-block" style="padding:0px;">
									<h3 style="color:'.$backgroundcolor.';">'.$username.'</h3>
								</td>
							</tr>
							<tr style="margin-top:0px; padding:0px;">
								<td class="content-block" style="padding:0px;">
									<h3 style="color:'.$backgroundcolor.';">'.$password.'</h3>
								</td>
							</tr>
							<tr>
							  <td class="content-block">
								  <h4 style="display:inline-block; margin:0px;">'.$reference.'</h4>
								  <h4 style="color:'.$backgroundcolor.';margin:0px; display:inline-block">'.$admin.'</h4>
							  </td>
							</tr>
							<tr>
							  <td class="content-block">
								  <br><strong>'.$thnksmsg.'</strong>
							  </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		 </div>
	  </td>
	</tr>';

		 return $result;
	?>
