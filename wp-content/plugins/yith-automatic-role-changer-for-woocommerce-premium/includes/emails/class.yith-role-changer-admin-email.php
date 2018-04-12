<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCARC_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Role_Changer_Admin_Email
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( ! class_exists( 'YITH_Role_Changer_Admin_Email' ) ) {
	/**
	 * Class YITH_Role_Changer_Admin_Email
	 *
	 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
	 */
	class YITH_Role_Changer_Admin_Email extends WC_Email {

		public $user_id = null;
		public $order_id = null;


		public function __construct() {

			$this->id = 'yith_ywarc_admin_email';

			$this->title = __( 'Automatic Role Changer email for Admin', 'yith-automatic-role-changer-for-woocommerce' );
			$this->description = __( 'The administrator will receive an email when a order with roles to be 
			granted passes to "Completed" or "Processing" status.', 'yith-automatic-role-changer-for-woocommerce' );

			$this->heading = __( 'New roles will be added to user', 'yith-automatic-role-changer-for-woocommerce' );
			$this->subject = __( 'New roles will be added to user', 'yith-automatic-role-changer-for-woocommerce' );

			$this->template_html = 'emails/role-changer-admin.php';

			add_action( 'send_email_to_admin', array( $this, 'trigger' ), 10, 3 );
			add_filter( 'woocommerce_email_styles', array( $this, 'style' ) );

			parent::__construct();
			$this->recipient = get_option( 'admin_email' );
		}

		public function trigger( $valid_rules, $user_id, $order_id ) {
			if ( !$this->is_enabled() ) {
				return;
			}
			$this->object = $valid_rules;
			$this->user_id = $user_id;
			$this->order_id = $order_id;

			$this->send( $this->get_recipient(),
				$this->get_subject(),
				$this->get_content(),
				$this->get_headers(),
				$this->get_attachments() );
		}


		public function style( $style ) {
			$style = $style .
				".ywarc_metabox_gained_role {
				border: #dcdada solid 1px;
				padding: 15px;
				text-align: center;
				margin: 10px auto;
				width: 270px;
				}
				
				.ywarc_metabox_role_name {
				font-size: 24px;
				color: grey;
				}
				.ywarc_metabox_dates {
				font-size: 12px;
				margin-top: 10px;
				}";
			return $style;
		}


		public function get_content_html() {
			return wc_get_template_html( $this->template_html, array(
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => true,
				'plain_text'    => false,
				'email'         => $this
			),
				'',
				YITH_WCARC_TEMPLATE_PATH );
		}


		public function get_content_plain() {
			return wc_get_template_html( $this->template_plain, array(
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => true,
				'plain_text'    => true,
				'email'         => $this
			),
				'',
				YITH_WCARC_TEMPLATE_PATH );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => __( 'Enable/Disable', 'woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'woocommerce' ),
					'default' => 'yes'
				),
				'subject'    => array(
					'title'       => __( 'Subject', 'woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce' ), $this->subject ),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => true
				),
				'heading'    => array(
					'title'       => __( 'Email Heading', 'woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( __( 'This controls the main heading included in the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => true
				),
				'email_type' => array(
					'title'       => __( 'Email type', 'woocommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => true
				)
			);
		}

	}

}
return new YITH_Role_Changer_Admin_Email();
