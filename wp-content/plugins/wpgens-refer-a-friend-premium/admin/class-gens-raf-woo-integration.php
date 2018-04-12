<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/admin
 * @author     Your Name <email@example.com>
 */
class WPGens_Settings_RAF extends WC_Settings_Page {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $gens_raf    The ID of this plugin.
	 */
	private $gens_raf;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $gens_raf       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct() {

		$this->id    = 'gens_raf';
		$this->label = __( 'Refer A Friend', 'gens-raf');

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );

	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {

		$sections = array(
			''         => __( 'General', 'gens-raf' ),
			'tabs'         => __( 'RAF Woo Tab Settings', 'gens-raf' ),
			'share' => __( 'Share ShortCode', 'gens-raf' ),
			'emails' => __( 'Email', 'gens-raf' ),
			'howto' => __( 'How to use plugin', 'gens-raf' ),
			'plugins' => __( 'More Free Plugins', 'gens-raf' )
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array
	 *
	 * @since 1.0.0
	 * @param string $current_section Optional. Defaults to empty string.
	 * @return array Array of settings
	 */
	public function get_settings( $current_section = '' ) {
		$prefix = 'gens_raf_';
		switch ($current_section) {
			case 'emails':
				$settings = array(
					array(
						'name' => __( 'Email Settings', 'gens-raf' ),
						'type' => 'title',
						'desc' => 'Setup the look of email that will be sent to the referal together with coupon.',
						'id'   => 'email_options',
					),
					array(
						'id'			=> $prefix.'email_subject',
						'name' 			=> __( 'Email Subject', 'gens-raf' ),
						'type' 			=> 'text',
						'desc_tip'		=> __( 'Enter the subject of email that will be sent when notifiying the user of their coupon code.', 'gens-raf'),
						'default' 		=> 'Hey there!'
					),
					array(
						'id'			=> $prefix.'email_message',
						'name' 			=> __( 'Email Message', 'gens-raf' ),
						'type' 			=> 'textarea',
						'class'         => 'input-text wide-input',
						'desc'			=> __( 'Text that will appear in email that is sent to user once they get the code. Use {{code}} to add coupon code.HTML allowed.', 'gens-raf'),
						'default' 		=> 'You referred someone! Here is your coupone code reward: {{code}} .'
					),
					array(
						'id'			=> $prefix.'buyer_email_subject',
						'name' 			=> __( 'Buyer Email Subject', 'gens-raf' ),
						'type' 			=> 'text',
						'desc_tip'		=> __( 'Enter the subject of email that will be sent when notifiying the buyer of their coupon code.', 'gens-raf'),
						'default' 		=> 'Hey there!'
					),
					array(
						'id'			=> $prefix.'buyer_email_message',
						'name' 			=> __( 'Buyer Email Message', 'gens-raf' ),
						'type' 			=> 'textarea',
						'class'         => 'input-text wide-input',
						'desc'			=> __( 'Text that will appear in email that is sent to buyer once they get the code. Use {{code}} to add coupon code.HTML allowed.', 'gens-raf'),
						'default' 		=> 'You purchased through referral link and earned coupon! Here is your coupone code reward: {{code}} .'
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'email_options',
					),
				);
				break;
			case 'tabs':
				$settings = array(
					array(
						'name' => __( 'RAF Woo Tab Settings', 'gens-raf' ),
						'type' => 'title',
						'desc' => 'Each product can have RAF Tab enabled that includes share buttons with some text. Update text here. If you want to add some content before or after raf tab content, use "gens_tab_filter_before" and "gens_tab_filter_after" filters.',
						'id'   => 'email_options',
					),
					array(
						'id'			=> $prefix.'tabs_disable',
						'name' 			=> __( 'Disable', 'gens-raf' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Disable tabs', 'gens-raf' ), // checkbox only
						'desc'			=> __( 'Check to disable. RAF Tab wont show.', 'gens-raf'),
						'default' 		=> 'no'
					),
					array(
						'id'			=> $prefix.'share_text',
						'name' 			=> __( 'Share text', 'gens-raf' ),
						'type' 			=> 'textarea',
						'class'         => 'input-text wide-input',
						'desc_tip'		=> __( 'Text that is showing above share icons.', 'gens-raf'),
						'default' 		=> 'Share your referral URL with friends:'
					),
					array(
						'id'			=> $prefix.'guest_text',
						'name' 			=> __( 'Guest text', 'gens-raf' ),
						'type' 			=> 'textarea',
						'class'         => 'input-text wide-input',
						'desc_tip'		=> __( 'Text to show when user is not logged in.', 'gens-raf'),
						'default' 		=> 'Please register to get your referral link.'
					),
					array(
						'id'			=> $prefix.'friends_text',
						'name' 			=> __( 'Friends text', 'gens-raf' ),
						'type' 			=> 'textarea',
						'class'         => 'input-text wide-input',
						'desc_tip'		=> __( 'Text before number that shows how many users joined through referal link.', 'gens-raf'),
						'default' 		=> 'friends have joined.'
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'email_options',
					),
				);
				break;
			case 'share':
				$settings = array(
					array(
						'name' => __( 'Configure share shortcode', 'gens-raf' ),
						'type' => 'title',
						'desc' => __( 'Use shortcode on any page as: [WOO_GENS_RAF_ADVANCE guest_text="Text to show when user is not logged in" share_text="Text showing above share icons" friends_text="friends have joined."]', 'gens-raf' ),
						'id'   => 'plugin_options',
					),
					array(
						'id'			=> $prefix.'twitter_via',
						'name' 			=> __( 'Twitter via (without @)', 'gens-raf' ),
						'type' 			=> 'text',
						'default' 		=> ''
					),
					array(
						'id'			=> $prefix.'twitter_title',
						'name' 			=> __( 'Twitter/WhatsUp/Email Title', 'gens-raf' ),
						'type' 			=> 'text',
						'desc'			=> __( 'Default Text that will appear as subject in Email and as title in Twitter and WhatsUp. User can change it themself.', 'gens-raf'),
						'default' 		=> ''
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'Plugins', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'plugin_options',
					),
				);
				break;
			case 'howto':
				$settings = array(
					array(
						'name' => __( 'Small tutorial to help you get started', 'gens-raf' ),
						'type' => 'title',
						'desc' => sprintf( __( 'Thanks for supporting Refer a Friend plugin by purchasing premium version. <br/>
							There are additional options that comes with premium version as well as fully styled shortcodes and user statistics. <br/><br/>
							You can find new options under this "Refer a Friend" tab, while shortcodes to be used are as follow:<br/><br/>
							1. Simple Shortcode that shows just link and can be used anywhere: <strong>[WOO_GENS_RAF]</strong><br/>
							2. Advance Shortcode that comes with share links, like example on <a href="%s" target="_blank">this page</a>. To get that exact look, just center text via editor. Shortcode is:<br/> <strong>[WOO_GENS_RAF_ADVANCE guest_text="Text to show when user is not logged in" share_text="Text showing above share icons" friends_text="friends have joined."]</strong><br/>
							3. Shortcode for Contact Form 7 plugin is <strong>[gens_raf]</strong>. User who uses contact form will have his referral URL shown in that shortcode. Place it in two places, first one before submit button inside form. Second one place in mail tab that will show in email sent to user.<br/>
							<h3>SETUP GUIDE</h3>
							<ul>
								<li>1. After installing plugin, go to Refer a friend settings (this page), and click on General tab inside Refer a friend tab. Then setup coupon options.</li>
								<li>2. Click on RAF Woo Tab Settings option and populate tab text, or turn it off. You can use "gens_tab_filter_before" and "gens_tab_filter_after" filters to easily add text before and after RAF tab text/share icons.</li>
								<li>3. Click on email tab and populate text that will be sent to user after he gets coupon.</li>
								<li>Thats it! Now every user will have referral link in their account page, or in page where you placed shortcode. After someone makes a purchase through their referral link, and after order is marked as complete. They will recieve coupon in their inbox.</li>
							</ul>
							', 'gens-raf' ), 'http://itsgoran.com/wp/testing-gens-raf/', 'https://profiles.wordpress.org/goran87/#content-plugins'),
						'id'   => 'plugin_options',
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'Plugins', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'plugin_options',
					),
				);
				break;
			case 'plugins':
				$settings = array(
					array(
						'name' => __( 'Check out all of our super cool plugins', 'gens-raf' ),
						'type' => 'title',
						'desc' => sprintf( __( 'Thanks for using Refer a Friend plugin. If you have any cool idea that we could add to plugin, be sure to contact us at <a target="_blank" href="%s">goranefbl@gmail.com</a>. 
						<br/>Our plugins are coded with best practices in mind, they will not slow down your site or spam database. Guaranteed to work and always up to date.
						Check out all of our plugins at: <a target="_blank" href="%s">this link</a> and subscribe to our newsletter to get notified once new free plugin is out.', 'gens-raf' ), 'mailto:goranefbl@gmail.com', 'https://profiles.wordpress.org/goran87/#content-plugins'),
						'id'   => 'plugin_options',
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'Plugins', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'plugin_options',
					),
				);
				break;

			default:
				$settings = array(
					array(
						'name' => __( 'General', 'gens-raf' ),
						'type' => 'title',
						'desc' => 'General Options, setup plugin here first.',
						'id'   => 'general_options',
					),
					array(
						'id'			=> $prefix.'disable',
						'name' 			=> __( 'Disable', 'gens-raf' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Disable Coupons', 'gens-raf' ), // checkbox only
						'desc'			=> __( 'Check to disable. Referal links wont work anymore.', 'gens-raf'),
						'default' 		=> 'no'
					),
					array(
						'id'		=> $prefix.'cookie_time',
						'name' 		=> __( 'Cookie Time', 'gens-raf' ),
						'type' 		=> 'number',
						'desc_tip'	=> __( 'As long as cookie is saved, user will recieve coupon after referal purchase product.', 'gens-raf'),
						'desc' 		=> __( 'How long to keep cookies before it expires.(In days)','gens-raf' )
					),
					array(
						'id'		=> $prefix.'min_ref_order',
						'name' 		=> __( 'Minimum referral order', 'gens-raf' ),
						'type' 		=> 'number',
						'desc' 		=> __( 'Set how much someone needs to purchase in order to generate coupon for referral','gens-raf' )
					),
					array(
						'id'		=> $prefix.'cookie_remove',
						'name' 		=> __( 'Single Purchase', 'gens-raf' ),
						'label' 	=> __( 'Single Purchase', 'gens-raf' ), // checkbox only
						'type' 		=> 'checkbox',
						'desc_tip'	=> __( 'This means that coupon is sent only the first time referral makes a purchase, as referral cookie is deleted after it.', 'gens-raf'),
						'desc' 		=> __( 'If checked, cookie will be deleted after customer makes a purchase.' ),
					),
					array(
						'id'			=> $prefix.'my_account_url',
						'name' 			=> __( 'My Account Page Share Link', 'gens-raf' ),
						'type' 			=> 'text',
						'class'         => 'regular-input',
						'desc'          => __( 'Page URL that is used for refer a friend link in my account page. Leave empty for home page.', 'gens-raf'),
						'desc_tip'		=> __( 'Default share url that is shown in my account page is home url. Change it here to some other. Use full link like: http://mysite.com/some-page/', 'gens-raf'),
						'default' 		=> ''
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'general_options',
					),
					array(
						'name' => __( 'Referrer Coupon Settings', 'gens-raf' ),
						'type' => 'title',
						'desc' => __( 'Coupon for a user that has referred a person.' ),
						'id'   => 'coupon_options',
					),
					array(
						'id'			=> $prefix.'coupon_type',
						'name' 			=> __( 'Coupon Type', 'gens-raf' ), // Type: fixed_cart, percent, fixed_product, percent_product
						'type' 			=> 'select',
						'class'    => 'wc-enhanced-select',
						'options'		=> array(
							'fixed_cart'	=> 'Cart Discount',
							'percent'	=> 'Cart % Discount',
							'fixed_product'	=> 'Product Discount',
							'percent_product'	=> 'Product % Discount'
						)
					),
					array(
						'id'		=> $prefix.'coupon_amount',
						'name' 		=> __( 'Coupon Amount', 'gens-raf' ), 
						'type' 		=> 'number',
						'desc_tip'	=> __( ' Entered without the currency unit or a percent sign as these will be added automatically, e.g., ’10’ for 10£ or 10%.', 'gens-raf'),
						'desc' 		=> __( 'Fixed value or percentage off depending on the discount type you choose.', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'product_ids',
						'name' 		=> __( 'Products', 'gens-raf' ), 
						'type' 		=> 'text',
						'desc_tip'	=> __( ' Products which need to be in the cart to use this coupon or, for "Product Discounts", which products are discounted. ', 'gens-raf'),
						'desc' 		=> __( 'Write product ID-s, separated by comma, e.g. 152,321,25', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'product_categories',
						'name' 		=> __( 'Product Categories', 'gens-raf' ),
						'type' 		=> 'text',
						'desc_tip'	=> __( 'A product must be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will be discounted.', 'gens-raf'),
						'desc' 		=> __( 'Write category ID-s, separated by comma, e.g. 3,5,7', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'exclude_product_categories',
						'name' 		=> __( 'Exclude Categories', 'gens-raf' ),
						'type' 		=> 'text',
						'desc_tip'	=> __( 'Product must not be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted. ', 'gens-raf'),
						'desc' 		=> __( 'Write category ID-s, separated by comma, e.g. 3,5,7', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'coupon_duration',
						'name' 		=> __( 'Coupon Duration', 'gens-raf' ),
						'type' 		=> 'text',
						'desc' 		=> 'How many days coupon should last, just type number, like: 30'
					),
					array(
						'id'		=> $prefix.'min_order',
						'name' 		=> __( 'Minimum Order', 'gens-raf' ), // Type: fixed_cart, percent, fixed_product, percent_product
						'type' 		=> 'number',
						'desc' 		=> __( 'Define minimum order subtotal in order for coupon to work.', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'individual_use',
						'name' 		=> __( 'Individual Use', 'gens-raf' ),
						'type' 		=> 'checkbox',
						'desc' 	=> __( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'gens-raf' ), // checkbox only
						'default' 	=> 'no'
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'coupon_options',
					),
					array(
						'name' => __( 'Friend/Buyer Coupon Settings', 'gens-raf' ),
						'type' => 'title',
						'desc' => __( 'Give coupon to a user being referred as well. AFTER their first purchase. (Scroll down for first purchase.)' ),
						'id'   => 'coupon_options',
					),
					array(
						'id'			=> $prefix.'friend_enable',
						'name' 			=> __( 'Enable', 'gens-raf' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Enable Coupons for friends', 'gens-raf' ), // checkbox only
						'desc'			=> __( 'Check to enable. Coupon code will be sent to their email.', 'gens-raf'),
						'default' 		=> 'no'
					),
					array(
						'id'			=> $prefix.'friend_coupon_type',
						'name' 			=> __( 'Coupon Type', 'gens-raf' ), // Type: fixed_cart, percent, fixed_product, percent_product
						'type' 			=> 'select',
						'class'    => 'wc-enhanced-select',
						'options'		=> array(
							'fixed_cart'	=> 'Cart Discount',
							'percent'	=> 'Cart % Discount',
							'fixed_product'	=> 'Product Discount',
							'percent_product'	=> 'Product % Discount'
						)
					),
					array(
						'id'		=> $prefix.'friend_coupon_amount',
						'name' 		=> __( 'Coupon Amount', 'gens-raf' ), 
						'type' 		=> 'number',
						'desc_tip'	=> __( ' Entered without the currency unit or a percent sign as these will be added automatically, e.g., ’10’ for 10£ or 10%.', 'gens-raf'),
						'desc' 		=> __( 'Fixed value or percentage off depending on the discount type you choose.', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'friend_product_ids',
						'name' 		=> __( 'Products', 'gens-raf' ), 
						'type' 		=> 'text',
						'desc_tip'	=> __( ' Products which need to be in the cart to use this coupon or, for "Product Discounts", which products are discounted. ', 'gens-raf'),
						'desc' 		=> __( 'Write product ID-s, separated by comma, e.g. 152,321,25', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'friend_product_categories',
						'name' 		=> __( 'Product Categories', 'gens-raf' ),
						'type' 		=> 'text',
						'desc_tip'	=> __( 'A product must be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will be discounted.', 'gens-raf'),
						'desc' 		=> __( 'Write category ID-s, separated by comma, e.g. 3,5,7', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'friend_exclude_product_categories',
						'name' 		=> __( 'Exclude Categories', 'gens-raf' ),
						'type' 		=> 'text',
						'desc_tip'	=> __( 'Product must not be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted. ', 'gens-raf'),
						'desc' 		=> __( 'Write category ID-s, separated by comma, e.g. 3,5,7', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'friend_coupon_duration',
						'name' 		=> __( 'Coupon Duration', 'gens-raf' ),
						'type' 		=> 'text',
						'desc' 		=> 'How many days coupon should last, just type number, like: 30'
					),
					array(
						'id'		=> $prefix.'friend_min_order',
						'name' 		=> __( 'Minimum Order', 'gens-raf' ), // Type: fixed_cart, percent, fixed_product, percent_product
						'type' 		=> 'number',
						'desc' 		=> __( 'Define minimum order subtotal in order for coupon to work.', 'gens-raf' )
					),
					array(
						'id'		=> $prefix.'friend_individual_use',
						'name' 		=> __( 'Individual Use', 'gens-raf' ),
						'type' 		=> 'checkbox',
						'desc' 	=> __( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'gens-raf' ), // checkbox only
						'default' 	=> 'no'
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'coupon_options',
					),
					array(
						'name' => __( 'Referral Coupons applied on first purchase', 'gens-raf' ),
						'type' => 'title',
						'desc' => __( 'This option will allow you to automatically apply coupon to a person being referred to your site via someones referral link, which means on their first purchase.<br/>
			First go to WooCommerce coupons page and manually create a coupon, choose options you want and make sure it does not have limit or email restriction. Then put coupon code in the textbox below.<br/>
			Once you activate this option, deactivate the above one, or you will be sending coupons to referrals on both first and second purchase.' ),
						'id'   => 'coupon_options',
					),
					array(
						'id'			=> $prefix.'guest_enable',
						'name' 			=> __( 'Enable', 'gens-raf' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Enable Coupons for referrals as well.', 'gens-raf' ), // checkbox only
						'desc'			=> __( 'Check to enable. Coupon code will be automatically applied in cart.', 'gens-raf'),
						'default' 		=> 'no'
					),
					array(
						'id'		=> $prefix.'guest_coupon_code',
						'name' 		=> __( 'Coupon CODE', 'gens-raf' ),
						'type' 		=> 'text',
						'desc' 		=> 'Read the description above. Then paste coupon code that will be applied to referrals on first purchase.'
					),
					array(
						'id'		=> $prefix.'guest_coupon_msg',
						'name' 		=> __( 'Cart Message', 'gens-raf' ),
						'type' 		=> 'textarea',
						'class'     => 'input-text wide-input',
						'desc' 		=> 'This is the message that will be shown at the cart page when coupon is automatically applied.',
						'default' 	=> 'Your purchase through referral link earned you a coupon!'
					),
					array(
						'id'		=> '',
						'name' 		=> __( 'General', 'gens-raf' ),
						'type' 		=> 'sectionend',
						'desc' 		=> '',
						'id'   		=> 'coupon_options',
					),
				);
				break;
		}

		/**
		 * Filter Memberships Settings
		 *
		 * @since 1.0.0
		 * @param array $settings Array of the plugin settings
		 */
		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );

	}

	/**
	 * Output the settings
	 *
	 * @since 1.0
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}


	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}

}

return new WPGens_Settings_RAF();
