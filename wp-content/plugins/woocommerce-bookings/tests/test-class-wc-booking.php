<?php

class Class_WC_Booking_Test extends WP_UnitTestCase {

	public function test_create_booking() {
		$product = WC_Booking_Product_Test_Helper::create_bookable_product();
		$this->assertTrue( is_a( $product, 'WC_Product_Booking' ) );

		$booking = new WC_Booking( array(
			'product_id' => $product->id,
			'start_date' => '2016-03-06',
			'end_date'   => '2016-03-07',
			'all_day'    => 1,
			'parent_id'  => 0,
		) );
		$booking->create();
		$booking->populate_data( $booking->id );

		$this->assertEquals( $booking->post->post_type, 'wc_booking' );
	}

}
