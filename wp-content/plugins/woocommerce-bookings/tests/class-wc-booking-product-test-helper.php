<?php

class WC_Booking_Product_Test_Helper extends WC_Helper_Product {

	public static function create_bookable_product() {
		// Create the product
		$product_id = wp_insert_post( array(
			'post_title'  => 'Bookable Product',
			'post_type'   => 'product',
			'post_status' => 'publish',
		) );

		wp_set_object_terms( $product_id, 'booking', 'product_type' );

		return new WC_Product_Booking( $product_id );
	}
}
