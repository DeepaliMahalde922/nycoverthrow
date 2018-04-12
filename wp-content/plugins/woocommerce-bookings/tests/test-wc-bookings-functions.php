<?php

class WC_Bookings_Functions_Test extends WP_UnitTestCase {
	public function test_wc_booking_sanitize_time() {
		$this->assertEquals( '14:00', wc_booking_sanitize_time( '2 PM' ) );
		$this->assertEquals( '00:00', wc_booking_sanitize_time( '24:00' ) );

		// Malformed input should returns '00:00'.
		$this->assertEquals( '00:00', wc_booking_sanitize_time( 'xx' ) );
	}

	public function test_is_wc_booking_product() {
		// Mock booking product.
		$product = new stdClass;
		$product->product_type = 'booking';
		$this->assertTrue( is_wc_booking_product( $product ) );

		// Mock simple product.
		$product = new stdClass;
		$product->product_type = 'simple';
		$this->assertFalse( is_wc_booking_product( $product ) );

		$product = null;
		$this->assertFalse( is_wc_booking_product( $product ) );
	}
}
