<?php
/**
 * Gift Card product add to cart
 *
 * @author  Yithemes
 * @package YITH WooCommerce Gift Cards
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $amounts ) : ?>
	<select id="gift_amounts" name="gift_amounts">
		<?php do_action( 'yith_gift_card_amount_selection_first_option', $product ); ?>
		<?php foreach ( $amounts as $value => $item ) : ?>
			<option
				value="<?php echo $value; ?>"
				<?php echo selected( $item['price'], $value, false ); ?>
				data-price="<?php echo $item['price']; ?>"
				data-wc-price="<?php echo $item['wc-price']; ?>">
				<?php echo $item['title']; ?>
			</option>
		<?php endforeach; ?>
		<?php do_action( 'yith_gift_card_amount_selection_last_option', $product ); ?>
	</select>
	<?php
endif;

do_action( 'yith_gift_cards_template_after_amounts', $product );