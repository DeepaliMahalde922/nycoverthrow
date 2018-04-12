<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $qode_options;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
    $woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
    return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
    $classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
    $classes[] = 'last';

if(isset($qode_options['woo_product_border']) && $qode_options['woo_product_border'] !== '') {
	$classes[] = 'product_with_borders';
}

if(isset($qode_options['woo_product_text_align']) && $qode_options['woo_product_text_align'] !== '') {
	$classes[] = 'text_align_'.$qode_options['woo_product_text_align'];
}
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="top-product-section">

            <a class="product_list_thumb_link" href="<?php the_permalink(); ?>">
                <span class="image-wrapper">
                <?php
                    /**
                     * woocommerce_before_shop_loop_item_title hook
                     *
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
                </span>
            </a>

			<?php do_action('qode_woocommerce_after_product_image'); ?>

        </div>

        <a href="<?php the_permalink(); ?>" class="product-category">
            <h6><?php the_title(); ?></h6>

			<?php if(isset($qode_options['woo_separator_after_title']) && $qode_options['woo_separator_after_title'] == 'yes') { ?>
				<div class="product_list_separator separator small"></div>
			<?php } ?>

            <?php
                /**
                 * woocommerce_after_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
        </a>

        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>