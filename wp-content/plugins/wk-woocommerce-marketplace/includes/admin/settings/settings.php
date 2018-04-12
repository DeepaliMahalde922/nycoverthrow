<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap">

		<h1><?php _e('Configuration') ?></h1>

		<p><hr></p>

		<form method="post" action="options.php">

				<?php settings_fields('marketplace-settings-group');?>

				<table class="form-table">

						<tr valign="top">

								<th scope="row"><label for="wkfb_mp_key_app_ID"><?php _e('Facebook App ID','marketplace');?></label></th>

								<td>
										<input name="wkfb_mp_key_app_ID" type="text" class="regular-text" id="wkfb_mp_key_app_ID" value="<?php echo get_option('wkfb_mp_key_app_ID'); ?>" />
										<p class="description">ex. 452074638268304</p>
								</td>

						</tr>

						<tr>

								<th scope="row"><label for="wkfb_mp_app_secret_key"><?php _e('Facebook App Secret','marketplace');?></label></th>

								<td>
										<input name="wkfb_mp_app_secret_key" class="regular-text" type="text" id="wkfb_mp_app_secret_key" value="<?php echo get_option('wkfb_mp_app_secret_key'); ?>" />
										<p class="description">ex. 7782182be29be0469caf79ab7877b1b2)</p>
								</td>

						</tr>

						<tr>

								<th scope="row"><label for="wkmpcom_minimum_com_onseller"><?php _e('Minimum Commission','marketplace');?></label></th>

								<td>
										<input name="wkmpcom_minimum_com_onseller" class="regular-text" type="text" id="wkmpcom_minimum_com_onseller" value="<?php echo get_option('wkmpcom_minimum_com_onseller'); ?>" />
										<p class="description">ex. 10 in percent</p>
								</td>

						</tr>

						<tr>

								<th scope="row"><label for="wkmpseller_ammount_to_pay"><?php _e('Amount Pay to Seller','marketplace');?></label></th>

								<td>
										<input name="wkmpseller_ammount_to_pay" class="regular-text" type="text" id="wkmpseller_ammount_to_pay" value="<?php echo get_option('wkmpseller_ammount_to_pay'); ?>" />
										<p class="description">ex. 500 in your currency</p>
								</td>

						</tr>

						<tr>

								<th scope="row"><label for="wkmp_seller_menu_tile"><?php _e('Seller Menu Title','marketplace');?></label></th>

								<td>
										<input name="wkmp_seller_menu_tile" type="text" class="regular-text" id="wkmp_seller_menu_tile" value="<?php echo get_option('wkmp_seller_menu_tile'); ?>" />
								</td>

						</tr>

						<input name="wkmp_seller_page_title" type="hidden" id="wkmp_seller_page_title" value="Seller" /></td>

						<tr>

									<th scope="row"><?php _e('Allow Seller to Publish','marketplace');?></th>

									<td>
											<label for="wkmp_seller_allow_publish">
													<input name="wkmp_seller_allow_publish" type="checkbox" id="wkmp_seller_allow_publish" value="1" <?php if(get_option('wkmp_seller_allow_publish')) echo 'checked'; ?>/>
													<?php echo _e("Can user publish his/her item online", "marketplace"); ?>
											</label>
									</td>

						</tr>

						<tr>

								<th scope="row"><?php _e('Auto Approve Seller','marketplace');?></th>

								<td>
										<label for="wkmp_auto_approve_seller">
												<input name="wkmp_auto_approve_seller" type="checkbox" id="wkmp_auto_approve_seller" value="1" <?php if(get_option('wkmp_auto_approve_seller')) echo 'checked'; ?>/>
												<?php echo _e("Seller will be automatically approved", "marketplace"); ?>
										</label>
								</td>

						</tr>

						<tr>

								<th scope="row"><?php _e('Separate Login Form','marketplace');?></th>

								<td>
										<label for="wkmp_show_seller_seperate_form">
												<input name="wkmp_show_seller_seperate_form" type="checkbox" id="wkmp_show_seller_seperate_form" value="1" <?php if(get_option('wkmp_show_seller_seperate_form')) echo 'checked'; ?>/>
												<?php echo _e("If checked, a separate login form will be created for sellers", "marketplace"); ?>
										</label>
								</td>

						</tr>

						<?php	do_action('wkmp_add_settings_field'); ?>

				</table>

				<?php	submit_button(); ?>

		</form>

</div>
