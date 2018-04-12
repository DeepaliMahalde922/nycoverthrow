<?php

class WC_Bookings_Test extends WP_UnitTestCase {
	public function test_constants_defined() {
		$this->assertTrue( defined( 'WC_BOOKINGS_VERSION' ) );
		$this->assertTrue( defined( 'WC_BOOKINGS_TEMPLATE_PATH' ) );
	}
}
