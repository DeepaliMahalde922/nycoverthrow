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
 * @class      YITH_Role_Changer_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( ! class_exists( 'YITH_Role_Changer_Admin' ) ) {
    /**
     * Class YITH_Role_Changer_Admin
     *
     * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
     */
    class YITH_Role_Changer_Admin {
        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0.0
         */
        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'yith_wcarc_rules_tab', array( $this, 'rules_tab' ) );
            add_action( 'ywarc_print_rules', array( $this, 'load_rules' ) );
            add_action( 'wp_ajax_ywarc_add_rule', array( $this, 'add_rule' ) );
            add_action( 'wp_ajax_ywarc_save_rule', array( $this, 'save_rule' ) );
            add_action( 'wp_ajax_ywarc_delete_rule', array( $this, 'delete_rule' ) );
            add_action( 'wp_ajax_ywarc_delete_all_rules', array( $this, 'delete_all_rules' ) );
            add_action( 'add_meta_boxes_shop_order', array( $this, 'add_role_granted_info_meta_box' ) );
            add_action( 'manage_shop_order_posts_custom_column', array( $this, 'role_column_content' ), 10, 2 );
        }


        public function rules_tab() {
            if( isset( $_GET['page'] ) && $_GET['page'] == 'yith_wcarc_panel'
                && file_exists( YITH_WCARC_TEMPLATE_PATH . '/admin/rules-tab.php' ) ) {
                include_once( YITH_WCARC_TEMPLATE_PATH . '/admin/rules-tab.php' );
            }
        }


        public function load_rules() {
            include_once( YITH_WCARC_TEMPLATE_PATH . '/admin/load-rules.php' );
        }


        public function add_rule() {
            $rule_id = uniqid();
            $title = $_POST['title'];
            $unique_title = true;

            $rules = get_option( 'ywarc_rules' );
            if ( $rules ) {
	            foreach ( $rules as $rule ) {
		            if ( $rule['title'] == $title ) {
			            $unique_title = false;
			            break;
		            }
	            }
            }

            if ( $unique_title ) {
                $new_rule = true;
                include( YITH_WCARC_TEMPLATE_PATH . 'admin/add-rule.php' );
            } else {
                echo 'duplicated_name_error';
            }
            die();
        }


        public function save_rule() {
            $rules = get_option( 'ywarc_rules' );

            $new_rule_options = apply_filters( 'ywarc_save_rule_array', array(
                'title' => $_POST['title'],
                'role_selected' => $_POST['role_selected'],
                'radio_group' => 'product',
                'product_selected' => $_POST['product_selected']
            ) );

            $rules[$_POST['rule_id']] = $new_rule_options;
            update_option( 'ywarc_rules', $rules );
            die();
        }

        public function delete_rule() {
            $rules = get_option( 'ywarc_rules' );
            unset( $rules[$_POST['rule_id']] );
            update_option( 'ywarc_rules', $rules );
            die();
        }

        public function delete_all_rules() {
            update_option( 'ywarc_rules', array() );
            die();
        }


        function add_role_granted_info_meta_box( $post ) {
            if ( $post ) {
                $order = wc_get_order( $post->ID );
                $rules = yit_get_prop( $order, '_ywarc_rules_granted', true );

                if ( $rules ) {
                    add_meta_box( 'ywarc-order-roles-granted', __( 'Automatic role changer', 'yith-automatic-role-changer-for-woocommerce' ),
                        array( $this, 'ywarc_order_roles_granted_content' ), 'shop_order', 'side', 'core', $rules );
                }
            }
        }


        function ywarc_order_roles_granted_content( $post, $meta ) {
            if ( $post && $meta['args'] ) {
                $rules = $meta['args'];

                if ( $rules ) {
                    // Count the total number of roles granted.
                    $roles_count = 0;
                    foreach ( $rules as $rule_id => $rule ) {
                        $roles_count = $roles_count + count( $rule['role_selected'] );
                    }

                    echo '<p>';
                    printf( _n(
                        'Customer gains the following role: ',
                        'Customer gains the following roles: ', $roles_count, 'yith-automatic-role-changer-for-woocommerce' ) );
                    echo '</p>';

                    foreach ( $rules as $rule_id => $rule ) {
                        foreach ( $rule['role_selected'] as $role ) {
                            $role_name = wp_roles()->roles[$role]['name'];
                            echo '<div class="ywarc_metabox_gained_role"><span class="ywarc_metabox_role_name">' .
                                $role_name . '</span>';
                            do_action( 'ywarc_after_metabox_content', $rule );
                            echo '</div>';
                        }
                    }
                }
            }
        }


        public function role_column_content( $column_name, $post_id ) {
            $order = wc_get_order( $post_id );
            $rules = yit_get_prop( $order, '_ywarc_rules_granted', true );
            if ( $rules && 'order_status' == $column_name ) {

                // Count the total number of roles granted.
                $roles_count = 0;
                foreach ( $rules as $rule_id => $rule ) {
                    $roles_count = $roles_count + count( $rule['role_selected'] );
                }

                echo '<img class="ywarc_role_icon" title="' . sprintf(
                        _n( 'A new role gained with this order', '%d new roles gained with this order', $roles_count, 'yith-automatic-role-changer-for-woocommerce' ),
                        $roles_count ) .
                    '" src="' . YITH_WCARC_ASSETS_URL . '/images/badge-03.png' . '"></span>';
            }
        }

        public function enqueue_scripts( $hook_suffix ) {
            wp_enqueue_style( 'ywarc-admin-style',
                YITH_WCARC_ASSETS_URL . '/css/ywarc-admin.css',
                array(),
                YITH_WCARC_VERSION );
            $premium_suffix = defined( 'YITH_WCARC_PREMIUM' ) && YITH_WCARC_PREMIUM ? '-premium' : '';
            wp_register_script(
                'ywarc-admin',
                YITH_WCARC_ASSETS_JS_URL . yit_load_js_file( 'ywarc-admin' . $premium_suffix . '.js' ),
                array( 'jquery' ),
                YITH_WCARC_VERSION );
            wp_localize_script( 'ywarc-admin', 'localize_js_ywarc_admin', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'before_2_7' => version_compare( WC()->version, '2.7', '<' ) ? true : false,
                'search_categories_nonce' => wp_create_nonce( 'search-categories' ),
                'search_tags_nonce'       => wp_create_nonce( 'search-tags' ),
                'empty_name_msg' => __( 'Please, name this rule.', 'yith-automatic-role-changer-for-woocommerce' ),
                'duplicated_name_msg' => __( 'This name already exists and is used to identify another rule. Please, try name.', 'yith-automatic-role-changer-for-woocommerce' ),
                'delete_rule_msg' => __( 'Are you sure you want to delete this rule?', 'yith-automatic-role-changer-for-woocommerce' ),
                'delete_all_rules_msg' => __( 'Are you sure you want to delete all the rules? This cannot be undone.', 'yith-automatic-role-changer-for-woocommerce' )
            ) );
            wp_enqueue_script( 'ywarc-admin' );
        }
    }
}