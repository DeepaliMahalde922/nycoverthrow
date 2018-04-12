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
 * @class      YITH_Role_Changer_Admin_Premium
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( !class_exists( 'YITH_Role_Changer_Admin_Premium' ) ) {
    /**
     * Class YITH_Role_Changer_Admin_Premium
     *
     * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
     */
    class YITH_Role_Changer_Admin_Premium extends YITH_Role_Changer_Admin {
        /**
         * Construct
         *
         * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
         * @since 1.0.0
         */
        public function __construct() {

            parent::__construct();

            // Premium content for add-rule.php
            add_action( 'ywarc_before_specific_product_block',
                array( $this, 'add_rule_content_before_specific_product' ), 10, 3 );
            add_action( 'ywarc_after_specific_product_block',
                array( $this, 'add_rule_content_after_specific_product' ), 10, 3 );

            // Select2 taxonomy searchers
            add_action( 'wp_ajax_ywarc_category_search', array( $this, 'category_search' ) );
            add_action( 'wp_ajax_ywarc_tag_search', array( $this, 'tag_search' ) );

            add_action( 'ywarc_after_metabox_content', array( $this, 'after_metabox_content' ) );

            // Alter the rule options for Premium
            add_filter( 'ywarc_save_rule_array', array( $this, 'save_rule_array' ) );

            // Cron
            add_action( 'ywarc_schedule_add_role', array( $this, 'schedule_add_role' ), 10, 2 );
            add_action( 'ywarc_schedule_remove_role', array( $this, 'schedule_remove_role' ), 10, 2 );
        }

        public function add_rule_content_before_specific_product( $new_rule, $rule, $rule_id ) {
            ?>
            <div class="rule_radio_group block radio_group">
                <p>
                    <b><?php echo _x( 'When:',
                            'As used in the following sentence: "The user will adquire the role WHEN": ',
                            'yith-automatic-role-changer-for-woocommerce' ); ?></b>
                    <span class="ywarc_required_field"></span>
                </p>
                <p>
                    <label for="ywarc_rule_radio_product[<?php echo $rule_id; ?>]">
                        <input id="ywarc_rule_radio_product[<?php echo $rule_id; ?>]"
                               class="ywarc_rule_radio_button"
                               name="ywarc_rule_radio[<?php echo $rule_id; ?>]" type="radio"
                               value="product"<?php
                        $radio_group = $rule['radio_group'];
                        if ( ! $new_rule ) {
                            echo checked( $radio_group, esc_attr( 'product' ), false );
                        }
                        ?>><?php echo __( 'User purchases a specific product',
                            'yith-automatic-role-changer-for-woocommerce' ); ?>
                    </label>
                </p>
                <p>
                    <label for="ywarc_rule_radio_range[<?php echo $rule_id; ?>]">
                        <input id="ywarc_rule_radio_range[<?php echo $rule_id; ?>]"
                               class="ywarc_rule_radio_button"
                               name="ywarc_rule_radio[<?php echo $rule_id; ?>]" type="radio"
                               value="range"<?php
                        if ( ! $new_rule ) {
                            echo checked( $radio_group, esc_attr( 'range' ), false );
                        }
                        ?>><?php echo __( 'Total spend falls within the following price range',
                            'yith-automatic-role-changer-for-woocommerce' ); ?>
                    </label>
                </p>
                <p>
                    <label for="ywarc_rule_radio_taxonomy[<?php echo $rule_id; ?>]">
                        <input id="ywarc_rule_radio_taxonomy[<?php echo $rule_id; ?>]"
                               class="ywarc_rule_radio_button"
                               name="ywarc_rule_radio[<?php echo $rule_id; ?>]" type="radio"
                               value="taxonomy"<?php
                        if ( ! $new_rule ) {
                            echo checked( $radio_group, esc_attr( 'taxonomy' ), false );
                        }
                        ?>><?php echo __( 'User purchases products from specific categories or tags',
                            'yith-automatic-role-changer-for-woocommerce' ); ?>
                    </label>
                </p>
            </div>
            <?php
        }

        public function add_rule_content_after_specific_product( $new_rule, $rule, $rule_id ) {
            ?>

            <div class="specific_range_block block">
                <p>
                    <b><?php echo __( 'Select a price range: ', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
                    <span class="ywarc_required_field ywarc_optional">(<?php _e( 'Fill at least one', 'yith-automatic-role-changer-for-woocommerce' ); ?>)</span>
                </p>
                <p>
					<span>
						<b><?php echo _x( 'From: ', 'start date', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
					</span>
                    <input class="wc_input_price range" type="text"
                           placeholder="<?php echo __( 'Amount&hellip;', 'yith-automatic-role-changer-for-woocommerce' )?>"
                           name="price_range_from" maxlength="7"
                           value="<?php
                           if ( ! $new_rule && ! empty( $rule['price_range_from'] ) ) {
                               esc_attr_e( $rule['price_range_from'] );
                           } ?>" />
					<span>
						<b><?php echo _x( 'To: ', 'end date', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
					</span>
                    <input class="wc_input_price range" type="text"
                           placeholder="<?php echo __( 'Amount&hellip;', 'yith-automatic-role-changer-for-woocommerce' )?>"
                           name="price_range_to" maxlength="7"
                           value="<?php
                           if ( ! $new_rule && ! empty( $rule['price_range_to'] ) ) {
                               esc_attr_e( $rule['price_range_to'] );
                           } ?>" />
                    <span class="range ywarc_warning"><?php _e( '"To" field must be greather than "From" field', 'yith-automatic-role-changer-for-woocommerce' ); ?></span>
                </p>
            </div>

            <div class="specific_taxonomy_block block radio_group">
                <p>
                    <b><?php echo __( 'Select a taxonomy: ', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
                    <span class="ywarc_required_field"></span>
                </p>
                <p>
                    <label for="ywarc_tax_radio_cat[<?php echo $rule_id ?>]">
                        <input id="ywarc_tax_radio_cat[<?php echo $rule_id ?>]"
                               class="ywarc_tax_radio_button"
                               name="ywarc_tax_radio[<?php echo $rule_id ?>]" type="radio"
                               value="category"<?php
                        $tax_radio_group = ! empty( $rule['tax_radio_group'] ) ? $rule['tax_radio_group'] : 'category';

                        if ( ! $new_rule ) {
                            echo checked( $tax_radio_group, esc_attr( 'category' ), false );
                        }
                        ?>/ ><?php echo __( 'Category', 'yith-automatic-role-changer-for-woocommerce' );
                        ?></label>
                </p>
                <p>
                    <label for="ywarc_tax_radio_tag[<?php echo $rule_id ?>]">
                        <input id="ywarc_tax_radio_tag[<?php echo $rule_id ?>]"
                               class="ywarc_tax_radio_button"
                               name="ywarc_tax_radio[<?php echo $rule_id ?>]" type="radio"
                               value="tag" <?php
                        if ( ! $new_rule ) {
                            echo checked( $tax_radio_group, esc_attr( 'tag' ), false );
                        }
                        ?>/ ><?php echo __( 'Tag', 'yith-automatic-role-changer-for-woocommerce' );
                        ?></label>
                </p>
                <div class="category_search_block block"><?php
                    $data_selected = array();
	                if ( ! $new_rule && ! empty( $rule['categories_selected'] ) ) {
		                $categories = is_array( $rule['categories_selected'] ) ? $rule['categories_selected'] : explode( ',', $rule['categories_selected'] );
		                if ( $categories ) {
			                foreach ( $categories as $category_id ) {
				                $term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
				                $data_selected[$category_id] = $term['name'];
			                }
		                }
	                }

	                $search_cat_array = array(
		                'type'              => '',
		                'class'             => 'ywarc-category-search',
		                'id'                => 'ywarc_category_selector[' . $rule_id . ']',
		                'name'              => '',
		                'data-placeholder'  => esc_attr__( 'Search for a category&hellip;', 'yith-automatic-role-changer-for-woocommerce' ),
		                'data-allow_clear'  => false,
		                'data-selected'     => $data_selected,
		                'data-multiple'     => true,
		                'data-action'       => '',
		                'value'             => empty( $rule['categories_selected'] ) ? '' : $rule['categories_selected'],
		                'style'             => ''
	                );
	                yit_add_select2_fields( $search_cat_array );
                    ?>
                </div>
                <div class="tag_search_block block"><?php
	                $data_selected = array();
	                if ( ! $new_rule && ! empty( $rule['tags_selected'] ) ) {
		                $tags = is_array( $rule['tags_selected'] ) ? $rule['tags_selected'] : explode( ',', $rule['tags_selected'] );
		                if ( $tags ) {
			                foreach ( $tags as $tag_id ) {
				                $term = get_term_by( 'id', $tag_id, 'product_tag', 'ARRAY_A' );
				                $data_selected[$tag_id] = $term['name'];
			                }
		                }
	                }

	                $search_tag_array = array(
		                'type'              => 'hidden',
		                'class'             => 'ywarc-tag-search',
		                'id'                => 'ywarc_tag_selector[' . $rule_id . ']',
		                'name'              => '',
		                'data-placeholder'  => esc_attr__( 'Search for a tag&hellip;', 'yith-automatic-role-changer-for-woocommerce' ),
		                'data-allow_clear'  => false,
		                'data-selected'     => $data_selected,
		                'data-multiple'     => true,
		                'data-action'       => '',
		                'value'             => empty( $rule['tags_selected'] ) ? '' : $rule['tags_selected'],
		                'style'             => ''
	                );
	                yit_add_select2_fields( $search_tag_array );
                    ?>
                </div>
            </div>

            <div class="date_range_block block">
                <p>
                    <b><?php echo __( 'Set a date range: ', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
				<span class="ywarc_optional"><?php
                    _e( '(Optional)', 'yith-automatic-role-changer-for-woocommerce' );
                    ?></span>
                    <?php echo wc_help_tip( __(
                        "Note: If you do not either enter any end date or a duration in days, this role will be valid forever",
                        'yith-automatic-role-changer-for-woocommerce' ) ); ?>
                </p>
                <div class="date_ranges_group">
                    <p class="form-field sale_price_dates_fields">
					<span>
						<b><?php echo _x( 'From: ', 'start date', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
					</span>
                        <input type="text" class="sale_price_dates_from range" maxlength="10"
                               placeholder="<?php echo _x( 'From&hellip;', 'placeholder', 'woocommerce') ?> YYYY-MM-DD"
                               value="<?php
                               if ( ! $new_rule && ! empty( $rule['date_from'] ) ) {
                                   echo $rule['date_from'];
                               }
                               ?>" />
					<span>
						<b><?php echo _x( 'To: ', 'end date', 'yith-automatic-role-changer-for-woocommerce' ); ?></b>
					</span>
                        <input type="text" class="sale_price_dates_to range" maxlength="10"
                               placeholder="<?php echo _x( 'To&hellip;', 'placeholder', 'woocommerce') ?> YYYY-MM-DD"
                               value="<?php
                               if ( ! $new_rule && ! empty( $rule['date_to'] ) ) {
                                   echo $rule['date_to'];
                               }
                               ?>" />
                    </p>
                </div>
            </div>

            <div class="duration_block block">
                <p>
                    <b><?php echo __( 'Set a duration for the roles (days): ',
                            'yith-automatic-role-changer-for-woocommerce' ); ?></b>
				<span class="ywarc_optional"><?php
                    _e( '(Optional)', 'yith-automatic-role-changer-for-woocommerce' );
                    ?></span>
                    <?php echo wc_help_tip( __(
                        "Note: This role will last as long as specified in these settings, even though an end date for this rule has been already specified. If the rule has no end date, and you leave this field empty, the role will never be removed.",
                        'yith-automatic-role-changer-for-woocommerce' ) ); ?>
                </p>
                <input class="ywarc_duration" type="number" min="0" value="<?php
                if ( ! $new_rule && ! empty( $rule['duration'] ) ) {
                    echo $rule['duration'];
                }
                ?>">

            </div>

            <div class="role_filter_selector_block block">
                <p>
                    <b><?php echo __( 'Do not apply this rule to users with the following role(s):',
                            'yith-automatic-role-changer-for-woocommerce' ); ?></b>
				<span class="ywarc_optional"><?php
                    _e( '(Optional)', 'yith-automatic-role-changer-for-woocommerce' ) ?>
				</span>
                </p>

                <select multiple class="role_filter_selector">
                    <?php
                    if ( ! $new_rule && ! empty( $rule['role_filter'] ) ) {
                        if ( is_array( $rule['role_filter'] ) ) {
                            foreach ( array_reverse( get_editable_roles() ) as $role => $rolename ): ?>
                                <option
                                    value="<?php echo $role ?>"
                                    <?php selected( in_array( $role, $rule['role_filter'] ) ) ?>>
                                    <?php echo $rolename['name'] ?>
                                </option>
                            <?php endforeach;
                        }
                    } else {
                        wp_dropdown_roles();
                    }
                    ?>
                </select>
            </div>

            <?php
        }

        public function save_rule_array() {
            $new_rule_options = array(
                'title' => $_POST['title'],
                'role_selected' => $_POST['role_selected'],
                'radio_group' => $_POST['radio_group'],
                'product_selected' => $_POST['product_selected'],
                'price_range_from' => $_POST['price_range_from'],
                'price_range_to' => $_POST['price_range_to'],
                'tax_radio_group' => $_POST['tax_radio_group'],
                'categories_selected' => $_POST['categories_selected'],
                'tags_selected' => $_POST['tags_selected'],
                'date_from' => $_POST['date_from'],
                'date_to' => $_POST['date_to'],
                'duration' => $_POST['duration'],
                'role_filter' => ! empty( $_POST['role_filter'] ) ? $_POST['role_filter'] : array()
            );
            return $new_rule_options;
        }


        public function category_search() {
            check_ajax_referer( 'search-categories', 'security' );

            ob_start();

	        if ( version_compare( WC()->version, '2.7', '<' ) ) {
		        $term = (string) wc_clean( stripslashes( $_GET['term'] ) );
	        } else {
		        $term = (string) wc_clean( stripslashes( $_GET['term']['term'] ) );
	        }

            if ( empty( $term ) ) {
                die();
            }
            global $wpdb;
            $terms = $wpdb->get_results( 'SELECT name, slug, wpt.term_id FROM ' . $wpdb->prefix . 'terms wpt, ' . $wpdb->prefix . 'term_taxonomy wptt WHERE wpt.term_id = wptt.term_id AND wptt.taxonomy = "product_cat" and wpt.name LIKE "%'.$term.'%" ORDER BY name ASC;' );

            $found_categories = array();

            if ( $terms ) {
                foreach ( $terms as $cat ) {
                    $found_categories[$cat->term_id] = ( $cat->name ) ? $cat->name : 'ID: ' . $cat->slug;
                }
            }

            $found_categories = apply_filters( 'ywarc_json_search_categories', $found_categories );
            wp_send_json( $found_categories );
        }

        public function tag_search() {
            check_ajax_referer( 'search-tags', 'security' );

            ob_start();

	        if ( version_compare( WC()->version, '2.7', '<' ) ) {
		        $term = (string) wc_clean( stripslashes( $_GET['term'] ) );
	        } else {
		        $term = (string) wc_clean( stripslashes( $_GET['term']['term'] ) );
	        }

            if ( empty( $term ) ) {
                die();
            }
            global $wpdb;
            $terms = $wpdb->get_results( 'SELECT name, slug, wpt.term_id FROM ' . $wpdb->prefix . 'terms wpt, ' . $wpdb->prefix . 'term_taxonomy wptt WHERE wpt.term_id = wptt.term_id AND wptt.taxonomy = "product_tag" and wpt.name LIKE "%'.$term.'%" ORDER BY name ASC;' );

            $found_tags = array();

            if ( $terms ) {
                foreach ( $terms as $tag ) {
                    $found_tags[$tag->term_id] = ( $tag->name ) ? $tag->name : 'ID: ' . $tag->slug;
                }
            }

            $found_tags = apply_filters( 'ywarc_json_search_tags', $found_tags );
            wp_send_json( $found_tags );
        }

        function after_metabox_content( $rule ) {
            $wp_date_format = get_option( 'date_format' );
            if ( $rule ) {
                echo '<div class="ywarc_metabox_dates">';
                if ( isset( $rule['activation_date'] ) && $rule['activation_date'] ) {
                    $activation_date = date( $wp_date_format, $rule['activation_date'] );
                    echo '<div class="ywarc_metabox_date_from">' .
                        __( 'Activation date: ', 'yith-automatic-role-changer-for-woocommerce' ) . $activation_date .
                        '</div>';
                } else if ( isset( $rule['date_from'] ) && $rule['date_from'] ) {
                    echo '<div class="ywarc_metabox_date_from">' .
                        __( 'Activation date: ', 'yith-automatic-role-changer-for-woocommerce' ) . $rule['date_from'] . '</div>';
                } else {
                    echo '<div class="ywarc_metabox_date_from">' .
                        __( 'Activated at the time of purchase', 'yith-automatic-role-changer-for-woocommerce' ) .
                        '</div>';
                }
                if ( isset( $rule['expiration_date'] ) && $rule['expiration_date'] ) {
                    $expiration_date = date( $wp_date_format, $rule['expiration_date'] );
                    echo '<div class="ywarc_metabox_date_to">' .
                        __( 'Valid until: ', 'yith-automatic-role-changer-for-woocommerce' ) . $expiration_date .
                        '</div>';
                } else if ( isset( $rule['date_to'] ) && $rule['date_to'] ) {
                    echo '<div class="ywarc_metabox_date_to">' .
                        __( 'Valid until: ', 'yith-automatic-role-changer-for-woocommerce' ) . $rule['date_to'] . '</div>';
                } else {
                    echo '<div class="ywarc_metabox_date_to">' .
                        __( 'Permanent role', 'yith-automatic-role-changer-for-woocommerce' ) . '</div>';
                }
                echo '</div>';
            }
        }


    }
}