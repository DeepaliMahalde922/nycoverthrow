<?php

/*----------*/ /*---------->>> Exit if Accessed Directly <<<----------*/ /*----------*/
if(!defined('ABSPATH')){
	exit;
}
	?>

	<?php
	if(isset($_POST['submit_favourite'])){
		if (! isset( $_POST['fv_sel_nonce_field'] ) || ! wp_verify_nonce( $_POST['fv_sel_nonce_field'], 'fv_sel_action' )) {

			die('secrity check...!');
		}
		else{
			$sellers=get_user_meta(get_current_user_id(),'favourite_seller',false);

			$seller=intval($_POST['seller']);

			if($seller==get_current_user_id()){

				wc_print_notice( __( 'You can not add yourself on favourite list.', 'marketplace' ), 'error' );
			}
			elseif (in_array($seller, $sellers)) {

				wc_print_notice( __( 'Seller is already added in favourite list.', 'marketplace' ), 'error' );

			}
			else{

				add_user_meta(get_current_user_id(),'favourite_seller',$seller);

			}

		}
	}?>

	<div class="favourite-seller">

 			<?php

 				$seller_list=get_user_meta(get_current_user_id(),'favourite_seller',false);

 			?>

				<table class="fav-sel">

					<thead>

						<tr>
							<th class=""><?php esc_html_e( 'Seller Profile', 'marketplace' ); ?></th>
							<th class=""><?php esc_html_e( 'Seller Name', 'marketplace' ); ?></th>
							<th class=""><?php esc_html_e( 'Seller Collection', 'marketplace' ); ?></th>
							<th class=""><?php esc_html_e( 'Action', 'marketplace' ); ?></th>
						</tr>

					</thead>

					<tbody>
						<?php

							if(!empty($seller_list)){

								$wpmp_obj12=new MP_Form_Handler();


								foreach ($seller_list as $seller_key => $seller_value) {

									$avatar=$wpmp_obj12->get_user_avatar($seller_value,'avatar');

									if(empty($avatar)){

										$avatar = WK_MARKETPLACE.'/assets/images/genric-male.png';

									}
									else{

										$up=wp_upload_dir();
										$avatar=$avatar[0]->meta_value;
										$avatar=$up['baseurl'].'/'.$avatar;
									}

									$seller_store=get_user_meta($seller_value,'shop_address',true);

									$seller_name=get_user_meta($seller_value,'first_name',true);


									?>

									<tr class="order">

										<td><img src="<?php echo $avatar; ?>" alt="" height="40" width="40"></td>

										<td><?php echo $seller_name; ?></td>

										<?php

											if (empty($seller_store)) {

												echo '<td>';
												echo __('Not Available', 'marketplace');
												echo '</td>';
											}
											else{

												echo '<td><a href='.home_url("seller/store/".$seller_store).'>'.$seller_store.'</a></td>';

											}
										?>

										<td><span class="remove-icon" data-seller-id="<?php echo $seller_value; ?>" data-customer-id="<?php echo get_current_user_id(); ?>"></span></td>

									</tr>

									<?php
								}
							}
						?>
					</tbody>

				</table>

		</div>
<?php
