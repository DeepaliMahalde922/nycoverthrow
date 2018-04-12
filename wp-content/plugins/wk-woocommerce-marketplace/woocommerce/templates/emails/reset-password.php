<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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

$loginurl=$data['user_login'];
$alert=$data['alrt'];
$siteurl=$data['siteurl'];
$username=$data['username'];
$notification=$data['notification'];
$resetmessage=$data['resetmessage'];
$resetlink=$data['resetlink'];

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
									<h3> <strong>'.$alert.'</strong><h3></br>
							   </td>
							</tr>
							<tr>
							  <td class="content-block">
									<h4>'.$siteurl.'</h4>
							  </td>
							</tr>
							<tr style="padding:0px;">
								<td class="content-block" style="padding:0px;">
									<h3 style="color:'.$backgroundcolor.';">'.$username.'</h3>
								</td>
							</tr>
							<tr style="margin-top:0px; padding:0px;">
								<td class="content-block" style="padding:0px;">
									<h3 style="color:'.$backgroundcolor.';">'.$notification.'</h3>
								</td>
							</tr>
							<tr>
							  <td class="content-block">
								  <h4 style="display:inline-block; margin:0px;">'.$resetmessage.'</h4>
								  <h4 style="color:'.$backgroundcolor.';margin:0px; display:inline-block">'.$resetlink.'</h4>
							  </td>
							</tr>
							<tr>
							  <td class="content-block">
								  <br><strong>Thanks for choosing Marketplace.</strong>
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
