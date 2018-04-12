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
 * @class      YITH_Role_Changer_Set_Roles
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( !class_exists( 'YITH_Role_Changer_Set_Roles' ) ) {
    /**
     * Class YITH_Role_Changer_Set_Roles
     *
     * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
     */
    class YITH_Role_Changer_Set_Roles {
        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0.0
         */
        public function __construct() {
            add_action( 'woocommerce_order_status_changed', array( $this, 'search_for_rules' ) );

            // Email hooks
            add_filter( 'woocommerce_email_classes', array( $this, 'register_email_classes' ) );
            add_filter( 'woocommerce_locate_core_template', array( $this, 'locate_core_template' ), 10, 3 );
        }

        public function search_for_rules( $order_id ) {
            $order = wc_get_order( $order_id );
            $user_id = $order->get_user_id();
            $new_status = $order->get_status();
            // Check if the order has already granted roles.
	        $granted_rules = yit_get_prop( $order, '_ywarc_rules_granted', true );
            $valid_order_statuses = apply_filters( 'ywarc_valid_order_statuses', array( 'completed', 'processing' ) );
            $invalid_order_statuses = apply_filters( 'ywarc_invalid_order_statuses', array( 'cancelled', 'refunded' ) );
            // If the order has not granted roles, get in.
            if ( ! $granted_rules ) {
                if ( in_array( $new_status, $valid_order_statuses ) ) {
                    $rules = $this->get_rules();
                    if ( $rules ) {
                        $valid_rules_by_product_id = $this->search_valid_rules_by_product_id( $rules, $order_id );

                        $valid_rules = $valid_rules_by_product_id;

                        if ( $valid_rules ) {
                            $this->schedule_roles( $valid_rules, $user_id );
                            $this->send_emails( $valid_rules, $user_id, $order_id );
	                        yit_save_prop( $order, '_ywarc_rules_granted', $valid_rules );
                        }
                    }
                }
            } else {
                if ( in_array( $new_status, $invalid_order_statuses ) ) {
                    $user = new WP_User( $user_id );
                    foreach ( $granted_rules as $rule ) {
                        foreach ( $rule['role_selected'] as $role ) {
                            $user->remove_role( $role );
                        }
                    }
                }
            }
        }

        public function get_rules() {
            $rules = get_option( 'ywarc_rules' ) ? get_option( 'ywarc_rules' ) : '';
            return $rules;
        }

        public function search_valid_rules_by_product_id( $rules, $order_id ) {
            $order = wc_get_order( $order_id );
            $valid_rules = array();

            foreach ( $order->get_items() as $item ) {
                foreach ( $rules as $rule_id => $rule ) {
                    if ( ! empty( $rule['product_selected'] ) ) {
                        if ( ( $item['product_id'] == $rule['product_selected'] ) || ( $item['variation_id'] == $rule['product_selected'] ) ) {
                            $valid_rules[$rule_id] = $rule;
                        }
                    }
                }
            }
            return $valid_rules;
        }

        public function schedule_roles( $valid_rules, $user_id ) {
            foreach ( $valid_rules as $rule ) {
                $this->schedule_add_role( $rule['role_selected'], $user_id );
            }
        }

        public function schedule_add_role( $roles, $user_id ) {
            $user = new WP_User( $user_id );
            foreach ( $roles as $role ) {
                $user->add_role( $role );
            }
        }

        public function send_emails( $valid_rules, $user_id, $order_id ) {
            WC()->mailer();
            do_action( 'send_email_to_admin', $valid_rules, $user_id, $order_id );
            do_action( 'send_email_to_user', $valid_rules, $user_id, $order_id );
        }

        function register_email_classes( $email_classes ) {
            $email_classes['YITH_Role_Changer_Admin_Email'] = include(
                YITH_WCARC_PATH . 'includes/emails/class.yith-role-changer-admin-email.php' );
            $email_classes['YITH_Role_Changer_User_Email'] = include(
                YITH_WCARC_PATH . 'includes/emails/class.yith-role-changer-user-email.php' );
            return $email_classes;

        }

        public function locate_core_template( $core_file, $template, $template_base ) {
            $custom_template = array(
                'emails/role-changer-admin.php',

                'emails/role-changer-user.php'
            );

            if ( in_array( $template, $custom_template ) ) {
                $core_file = YITH_WCARC_TEMPLATE_PATH . $template;
            }
            return $core_file;
        }

    }
}