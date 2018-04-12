<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$custom_attributes = defined( 'YITH_WCBM_PREMIUM' ) ? '' : array( 'disabled' => 'disabled' );

// Create Array for badge select
$badge_array = array(
		'none' => __( 'None', 'yith-woocommerce-badges-management' )
);

global $sitepress;
$current_language = '';
if ( isset( $sitepress ) ) {
	$current_language = $sitepress->get_current_language();
	$default_language = $sitepress->get_default_language();
	$sitepress->switch_lang( $default_language );
}

$args   = array(
		'posts_per_page' => -1,
		'post_type'      => 'yith-wcbm-badge',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
		'suppress_filters' => false
);
$badges = get_posts( $args );
foreach ( $badges as $badge ) {
	$badge_array[ $badge->ID ] = get_the_title( $badge->ID );
}

if ( isset( $sitepress ) ) {
	$sitepress->switch_lang( $current_language );
}

//get categories of products and create an array of catagories
$cat_args = array(
			'type'					=> 'post',
			'orderby'				=> 'name',
			'order'					=> 'ASC',
			'hide_empty'			=> 0,
			'hierarchical'			=> 1,
			'taxonomy'              => 'product_cat'
			);

$list_category_opt = array(
	'category-badge-options' => array(
		'title' => __( 'Category Badges', 'yith-woocommerce-badges-management' ),
		'type' => 'title',
		'desc' => '',
		'id' => 'yith-wcbm-category-badge-options'
	)
);

$categories = get_categories( $cat_args );
foreach ($categories as $cat) {
	$id 	= $cat->term_id;
	$name 	= $cat->name;

	$list_category_opt['category-badge-' . $id ] = array(
		'name'              => $name,
		'type'              => 'select',
		'desc'              => sprintf( __( 'Select the Badge for all products of category %s', 'yith-woocommerce-badges-management' ), $name) ,
		'id'                => 'yith-wcbm-category-badge-' . $id,
		'options'           => $badge_array,
		'custom_attributes' => $custom_attributes,
		'default'           => 'none'
	);
}

$list_category_opt['category-badge-options-end'] = array(
	'type'      => 'sectionend',
	'id'        => 'yith-wcbm-category-badge-options'
);

$settings = array(
	'category'  => $list_category_opt
);

return $settings;