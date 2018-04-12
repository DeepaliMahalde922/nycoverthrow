<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$custom_attributes = defined( 'YITH_WCBM_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );

// Create Array for badge select
$badge_array = array(
    'none' => __( 'None', 'yith-woocommerce-badges-management' ),
);

global $sitepress;
$current_language = '';
if ( isset( $sitepress ) ) {
    $current_language = $sitepress->get_current_language();
    $default_language = $sitepress->get_default_language();
    $sitepress->switch_lang( $default_language );
}

$args   = array(
    'posts_per_page'   => -1,
    'post_type'        => 'yith-wcbm-badge',
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_status'      => 'publish',
    'suppress_filters' => false,
);
$badges = get_posts( $args );
foreach ( $badges as $badge ) {
    $badge_array[ $badge->ID ] = get_the_title( $badge->ID );
}

if ( isset( $sitepress ) ) {
    $sitepress->switch_lang( $current_language );
}

$a_settings = array(

    'settings' => array(

        'general-options' => array(
            'title' => __( 'General Options', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-general-options',
        ),

        'hide-on-sale-default-badge' => array(
            'id'      => 'yith-wcbm-hide-on-sale-default',
            'name'    => __( 'Hide "On sale" badge', 'yith-woocommerce-badges-management' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Select to hide the default Woocommerce "On sale" badge.', 'yith-woocommerce-badges-management' ),
            'default' => 'no',
        ),

        'hide-in-sidebar' => array(
            'id'      => 'yith-wcbm-hide-in-sidebar',
            'name'    => __( 'Hide in sidebars', 'yith-woocommerce-badges-management' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Select to hide the badges in sidebars and widgets.', 'yith-woocommerce-badges-management' ),
            'default' => 'yes',
        ),

        'product-badge-overrides-default-on-sale' => array(
            'id'      => 'yith-wcbm-product-badge-overrides-default-on-sale',
            'name'    => __( 'Product Badge overrides default on sale badge', 'yith-woocommerce-badges-management' ),
            'type'    => 'checkbox',
            'desc'    => __( 'Select if you want to hide WooCommerce default "On Sale" badge when the product has another badge', 'yith-woocommerce-badges-management' ),
            'default' => 'yes',
        ),

        'general-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcqv-general-options',
        ),

        'recent-badge-options' => array(
            'title' => __( 'Recent Products', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-recent-badge-options',
        ),

        'recent-products-badge' => array(
            'name'              => __( 'Badge for Recent products', 'yith-woocommerce-badges-management' ),
            'type'              => 'select',
            'desc'              => __( 'Select the badge you want to apply to all recent products.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-recent-products-badge',
            'options'           => $badge_array,
            'custom_attributes' => $custom_attributes,
            'default'           => 'none',
        ),

        'badge-newer-than' => array(
            'name'              => __( 'Newer than', 'yith-woocommerce-badges-management' ),
            'type'              => 'number',
            'desc'              => __( 'Show the badge for products that are newer than X days.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-badge-newer-than',
            'custom_attributes' => $custom_attributes,
            'default'           => '0',
        ),

        'recent-badge-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcbm-recent-badge-options',
        ),

        'on-sale-badge-options' => array(
            'title' => __( 'On Sale [Automatic]', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-on-sale-badge-options',
        ),

        'on-sale-badge' => array(
            'name'              => __( 'On sale Badge', 'yith-woocommerce-badges-management' ),
            'type'              => 'select',
            'desc'              => __( 'Select the Badge for products on sale.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-on-sale-badge',
            'options'           => $badge_array,
            'custom_attributes' => $custom_attributes,
            'default'           => 'none',
        ),

        'on-sale-badge-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcbm-on-sale-badge-options',
        ),

        'featured-badge-options' => array(
            'title' => __( 'Featured [Automatic]', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-featured-badge-options',
        ),

        'featured-badge' => array(
            'name'              => __( 'Featured badge', 'yith-woocommerce-badges-management' ),
            'type'              => 'select',
            'desc'              => __( 'Select the badge for featured products.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-featured-badge',
            'options'           => $badge_array,
            'custom_attributes' => $custom_attributes,
            'default'           => 'none',
        ),

        'featured-badge-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcbm-featured-badge-options',
        ),

        'out-of-stock-badge-options' => array(
            'title' => __( 'Out of stock [Automatic]', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-out-of-stock-badge-options',
        ),

        'out-of-stock-badge' => array(
            'name'              => __( 'Out of stock Badge', 'yith-woocommerce-badges-management' ),
            'type'              => 'select',
            'desc'              => __( 'Select the Badge for products out of stock.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-out-of-stock-badge',
            'options'           => $badge_array,
            'custom_attributes' => $custom_attributes,
            'default'           => 'none',
        ),

        'out-of-stock-badge-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcbm-out-of-stock-badge-options',
        ),

        'single-product-badge-options' => array(
            'title' => __( 'Single Product', 'yith-woocommerce-badges-management' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'yith-wcbm-single-product-badge-options',
        ),

        'hide-on-single-product' => array(
            'name'              => __( 'Hide on Single Product', 'yith-woocommerce-badges-management' ),
            'type'              => 'checkbox',
            'desc'              => __( 'Select to hide badges on Single Product Page.', 'yith-woocommerce-badges-management' ),
            'id'                => 'yith-wcbm-hide-on-single-product',
            'custom_attributes' => $custom_attributes,
            'default'           => 'no',
        ),

        'show-advanced-badge-in-variable-products' => array(
            'name'              => __( 'Show advanced badges in variable products', 'yith-woocommerce-badges-management' ),
            'type'              => 'select',
            'id'                => 'yith-wcbm-show-advanced-badge-in-variable-products',
            'custom_attributes' => $custom_attributes,
            'default'           => 'same',
            'options'           => array(
                'same' => __( 'only if the discount percentage/amount is the same for all variations', 'yith-woocommerce-badges-management' ),
                'min'  => __( 'Show minimum discount percentage/amount', 'yith-woocommerce-badges-management' ),
                'max'  => __( 'Show maximum discount percentage/amount', 'yith-woocommerce-badges-management' ),
            ),
        ),

        'single-product-badge-options-end' => array(
            'type' => 'sectionend',
            'id'   => 'yith-wcbm-single-product-badge-options',
        ),
    ),
);

//return $settings;