<?php
/**
 * My gift cards
 *
 * @package yith-woocommerce-gift-cards-premium\templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gift_card_columns = apply_filters( 'yith_ywgc_my_gift_cards_columns',
	array(
		'code'    => __( 'Code', 'yith-woocommerce-gift-cards' ),
		'value'   => __( 'Value', 'yith-woocommerce-gift-cards' ),
		'balance' => __( 'Balance', 'yith-woocommerce-gift-cards' ),
		'usage'   => __( "Usage", 'yith-woocommerce-gift-cards' ),
		'status'  => __( 'Status', 'yith-woocommerce-gift-cards' ),
	) );

$gift_cards_args = apply_filters( 'yith_ywgc_woocommerce_my_account_my_orders_query', array(
	'numberposts' => - 1,
	'fields'      => 'ids',
	'meta_key'    => YWGC_META_GIFT_CARD_CUSTOMER_USER,
	'meta_value'  => get_current_user_id(),
	'post_type'   => YWGC_CUSTOM_POST_TYPE_NAME,
	'post_status' => 'any',
) );

//  Retrieve the gift cards matching the criteria
$ids = get_posts( $gift_cards_args );

if ( $ids ) : ?>

	<h2><?php echo apply_filters( 'yith_ywgc_my_account_my_giftcards', __( 'My gift cards', 'yith-woocommerce-gift-cards' ) ); ?></h2>

	<table class="shop_table shop_table_responsive my_account_giftcards">
		<thead>
		<tr>
			<?php foreach ( $gift_card_columns as $column_id => $column_name ) : ?>
				<th class="<?php echo esc_attr( $column_id ); ?>"><span
						class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody>
		<?php foreach ( $ids as $gift_card_id ) :

			$gift_card = new YWGC_Gift_Card_Premium( array( 'ID' => $gift_card_id ) );

			if ( ! $gift_card->exists() ) {
				continue;
			}
			?>
			<tr class="ywgc-gift-card status-<?php echo esc_attr( $gift_card->status ); ?>">
				<?php foreach ( $gift_card_columns as $column_id => $column_name ) : ?>
					<td class="<?php echo esc_attr( $column_id ); ?> "
					    data-title="<?php echo esc_attr( $column_name ); ?>">

						<?php
						$value = '';
						switch ( $column_id ) {
							case 'code' :
								$value = $gift_card->get_code();
								break;

							case 'value' :
								$value = wc_price( apply_filters( 'yith_ywgc_get_gift_card_price', $gift_card->total_amount ) );
								break;

							case 'balance' :
								$value = wc_price( apply_filters( 'yith_ywgc_get_gift_card_price', $gift_card->get_balance() ) );
								break;

							case 'status' :
								$value = ywgc_get_status_label( $gift_card );
								if ( $gift_card->expiration ) {
									$value .= '<br>' . sprintf( _x( 'Expire on: %s (Y-m-d)', 'gift card expiration date', 'yith-woocommerce-gift-cards' ), date( 'Y-m-d', $gift_card->expiration ) );
								}
								break;

							case 'usage' :
								$orders = $gift_card->get_registered_orders();

								if ( $orders ) {
									foreach ( $orders as $order_id ) {
										?>
										<a href="<?php echo wc_get_endpoint_url( 'view-order', $order_id ); ?>"
										   class="ywgc-view-order button">
											<?php printf( __( "Order %s", 'yith-woocommerce-gift-cards' ), $order_id ); ?>
										</a><br>
										<?php
									}
								} else {
									_e( "The code has not been used yet", 'yith-woocommerce-gift-cards' );
								}
								break;
							default:
								$value = apply_filters( 'yith_ywgc_my_account_column', '', $column_id, $gift_card );
						}

						if ( $value ) {
							echo '<span>' . $value . '</span>';
						}
						?>

					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
