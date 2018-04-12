<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see        http://docs.woothemes.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/**
 * @var WC_Email $current_email
 */
global $current_email;

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

$result='<tr style="margin:0px;">
		            <td style=" width:100%; margin:0px; padding:10px; text-align:center;color:'.$backgroundcolor.'!important;">
						<h2>'. $footer.'</h2>
			       </td>
		        </tr>
        </table></body></html>';

		?>
