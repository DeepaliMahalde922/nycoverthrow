<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$user_id = get_current_user_id();

	$shop_address=get_user_meta($user_id,'shop_address',true);

	$sellerurl=urldecode(get_query_var('main_page'));

	if( $sellerurl == $shop_address ) :

	$zones=WC_Shipping_Zones::get_zones();

	if(empty($zones)) :  ?>

		<h2>

			<?php _e( 'Shipping Zones', 'marketplace' ); ?>

			<?php echo wc_help_tip( __( 'A shipping zone is a geographic region where a certain set of shipping methods and rates apply. WooCommerce will automatically choose the correct shipping zone based on your customer&lsquo;s shipping address and present the shipping methods within that zone to them.', 'marketplace' ) ); ?>
		</h2>

	<?php endif;


	$final_obj=new SaveShipingOptions();

	?>
	<div class="new-ship-zone">

		<a href="<?php echo home_url("seller/".$shop_address."/shipping/add/");?>" ><?php echo __('Add New Shipping Zone', 'marketplace'); ?></a>

	</div>

	<table class="wc-shipping-zones-list widefat">

		<thead>

			<tr>

				<th class="wc-shipping-zone-name"><?php esc_html_e( 'Zone Name', 'marketplace' ); ?></th>

				<th class="wc-shipping-zone-region"><?php esc_html_e( 'Region(s)', 'marketplace' ); ?></th>

				<th class="wc-shipping-zone-methods"><?php esc_html_e( 'Shipping Method(s)', 'marketplace' ); ?></th>

				<th class="wc-shipping-zone-actions"><?php esc_html_e( 'Actions', 'marketplace' ); ?></th>

			</tr>

		</thead>

		<tbody class="wc-shipping-zone-rows ui-sortable">

			<?php

				global $wpdb;

				$table_name = $wpdb->prefix . "mpseller_meta";

				$seller_zones=$wpdb->get_results( "SELECT zone_id FROM $table_name where seller_id=".$user_id);

				foreach ($seller_zones as $k => $v) {
					$u_zones[]=$v->zone_id;
				}

				if (!empty($u_zones)) :


					if(!empty($zones)) :


						foreach ($zones as $key => $value) :

								if(in_array($value['zone_id'], $u_zones)){

									$ship_locations=$final_obj->get_formatted_location($value['zone_locations']);

									$ship_locations=explode(",", $ship_locations);

							?>

								<tr class="final-editing">

									<td class="wc-shipping-zone-name">

										<div class="view">

											<p><?php echo $value['zone_name'];?></p>

										</div>

									</td>

									<td class="wc-shipping-zone-region">
										<div class="mp_select_country">
												<?php
													foreach ($ship_locations as $key_location => $value_location) {
														echo '<div class="mp_ship_tags">'.$value_location.'</div>';
													}
												?>
										</div>

									</td>
									<td class="wc-shipping-zone-methods list-shipping">
										<div>
											<ul>
											<?php

												$zones=new WC_Shipping_Zone($value['zone_id']);

												$methods   = $zones->get_shipping_methods();

												if ( ! empty( $methods ) ) {
													foreach ( $methods as $method ) {
														$class_name = 'yes' === $method->enabled ? 'method_enabled' : 'method_disabled';
														echo '<li class="wc-shipping-zone-method"><a href="javascript:void(0)" class="' . esc_attr( $class_name ) . '">' . esc_html( $method->get_title() ) . '</a></li>';
													}
												} else {
													echo '<p>' . __( 'No shipping methods offered to this zone.', 'marketplace' ) . '</p>';
														}
													?>

											</ul>
										</div>
									</td>

									<td>


										<a id="editprod" href="<?php echo home_url("seller/".$shop_address."/shipping/edit/". $value['zone_id']);?>"><?php echo __('edit', 'marketplace'); ?></a>
					            		<a id="delprod" class="wc-shipping-zone-delete" href="javascript:void(0)" data-zone-id="<?php echo $value['zone_id'];?>" class="ask"><?php echo __('delete', 'marketplace'); ?></a>

					            	</td>

								</tr>

							<?php

								}

						endforeach;

					endif;

				endif; ?>

		</tbody>

		<tbody>

			<tr data-id="0">

				<td class="wc-shipping-zone-name">

					<div class="view">

						<p><?php esc_html_e( 'Rest of the World', 'marketplace' ); ?></p>

					</div>

				</td>

				<td class="wc-shipping-zone-region"><?php esc_html_e( 'This zone is used for shipping addresses that aren&lsquo;t included in any other shipping zone. Adding shipping methods to this zone is optional.', 'marketplace' ); ?></td>

				<td class="wc-shipping-zone-methods">

					<ul>

						<?php
							$worldwide = new WC_Shipping_Zone( 0 );

							$methods   = $worldwide->get_shipping_methods();

							if ( ! empty( $methods ) ) {
								foreach ( $methods as $method ) {
									$class_name = 'yes' === $method->enabled ? 'method_enabled' : 'method_disabled';
									echo '<li class="wc-shipping-zone-method"><a href="admin.php?page=wc-settings&amp;tab=shipping&amp;instance_id=' . absint( $method->instance_id ) . '" class="' . esc_attr( $class_name ) . '">' . esc_html( $method->get_title() ) . '</a></li>';
								}
							} else {
								echo '<li class="wc-shipping-zone-method">' . __( 'No shipping methods offered to this zone.', 'marketplace' ) . '</li>';
							}
						?>


					</ul>

				</td>

			</tr>

		</tbody>

	</table>

<?php else : ?>

 <h1>Cheating huh ???</h1>
 <p>Sorry, You can't access other seller's shipping zones.</p>

<?php endif; ?>
