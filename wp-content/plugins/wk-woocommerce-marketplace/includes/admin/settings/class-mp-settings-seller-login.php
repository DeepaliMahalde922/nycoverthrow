<?php
/**
 * marketplace Account Settings
 *
 * @author 		webkul
 * @category 	Admin
 * @package 	marketplace/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MP_Settings_Seller' ) ) :

/**
 * WC_Settings_Accounts
 */
class MP_Settings_Seller extends MP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'seller';
		$this->label = __( 'Seller', 'marketplace' );

		add_filter( 'marketplace_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'marketplace_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'marketplace_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'marketplace_' . $this->id . '_settings', array(

			array( 'title' => __( 'Seller Page', 'marketplace' ), 'type' => 'title', 'desc' => __( 'These pages need to be set to seller in marketplace.', 'marketplace' ), 'id' => 'seller_page_options' ),

			array(
				'title' => __( 'Seller Page', 'marketplace' ),
				'desc' 		=> __( 'Page contents:', 'marketplace' ) . ' [' . apply_filters( 'marketplace_seller_shortcode_tag', 'seller' ) . ']',
				'id' 		=> 'marketplace_seller_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

		)); // End pages settings
	}
}

endif;

return new MP_Settings_Seller();
