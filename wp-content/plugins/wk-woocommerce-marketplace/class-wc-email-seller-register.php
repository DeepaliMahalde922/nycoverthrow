<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MP_EMAIL' ) ) :

/**
 * New Order Email.
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class       MP_EMAIL
 * @version     2.0.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class MP_EMAIL extends WC_Email {
	function get_options(){
	global $wpdb;
	$templates='Templates not found';
	$sql = "SELECT title FROM {$wpdb->prefix}emailTemplate  WHERE status='publish'";
	$results = $wpdb->get_results($sql);
	if($results){
	foreach($results as $key=>$value){

	$types[$value->title]      = __( $value->title, 'woocommerce' );

	}
	return $types;
	}
	else{
		return $templates;
	}
}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id               = 'new_seller';
		$this->title            = __( 'seller register', 'woocommerce' );
		$this->description      = __( 'New seller emails are sent to chosen recipient(s) ', 'woocommerce' );
		$this->heading          = __( 'new seller register', 'woocommerce' );
		$this->subject          = __( '[{site_title}] New seller register ({register_number}) - {register_date}', 'woocommerce' );
		$this->template_html    = 'seller-register.php';


		// Triggers for this email
		// add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
		// add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this, 'trigger' ) );
		// add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );
		// add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'trigger' ) );
		// add_action( 'woocommerce_order_status_failed_to_completed_notification', array( $this, 'trigger' ) );
		// add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'trigger' ) );

		// Call parent constructor
		parent::__construct();

		// Other settings
		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	}

	/**
	 * Trigger.
	 *
	 * @param int $order_id
	 */
	public function trigger( $order_id ) {
		if ( $order_id ) {
			$this->object                  = wc_get_order( $order_id );
			$this->find['register-date']      = '{register_date}';
			$this->find['register-number']    = '{register_number}';
			$this->replace['register-date']   = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );
			$this->replace['register-number'] = $this->object->get_order_number();
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'			=> $this
		) );
	}

	/**
	 * Get content plain.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
			'email'			=> $this
		) );
	}

	/**
	 * Initialise settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'         => __( 'Enable/Disable', 'woocommerce' ),
				'type'          => 'checkbox',
				'label'         => __( 'Enable this email notification', 'woocommerce' ),
				'default'       => 'yes'
			),
			'recipient' => array(
				'title'         => __( 'Recipient(s)', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'woocommerce' ), esc_attr( get_option('admin_email') ) ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			),
			'subject' => array(
				'title'         => __( 'Subject', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce' ), $this->subject ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			),
			'heading' => array(
				'title'         => __( 'Email Heading', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			),
			'email_type' => array(
				'title'         => __( 'Email type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'normal',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true
			),
			'email_template'=>array(
				'title'         => __( 'Email template', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'default',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_options(),
				'desc_tip'      => true

			),
			'footer' => array(
				'title'         => __( 'Email Footer', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'This controls the main Footer contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			)
		);
	}
}

endif;

return new MP_EMAIL();
