<?php

$position_css = "";

$pos_top    = ( is_numeric( $pos_top ) ) ? ( $pos_top . "px" ) : $pos_top;
$pos_bottom = ( is_numeric( $pos_bottom ) ) ? ( $pos_bottom . "px" ) : $pos_bottom;
$pos_left   = ( is_numeric( $pos_left ) ) ? ( $pos_left . "px" ) : $pos_left;
$pos_right  = ( is_numeric( $pos_right ) ) ? ( $pos_right . "px" ) : $pos_right;

$position_css .= "top: " . $pos_top . ";";
$position_css .= "bottom: " . $pos_bottom . ";";
$position_css .= "left: " . $pos_left . ";";
$position_css .= "right: " . $pos_right . ";";

//--wpml-------------
$text     = yith_wcbm_wpml_string_translate( 'yith-woocommerce-badges-management', sanitize_title( $text ), $text );
$css_text = yith_wcbm_wpml_string_translate( 'yith-woocommerce-badges-management', sanitize_title( $css_text ), $css_text );
//-------------------


switch ( $type ) {
    case 'text':
    case 'custom':
        ?>
        <div class='yith-wcbm-badge yith-wcbm-badge-custom yith-wcbm-badge-<?php echo $id_badge ?>'>
            <?php echo $text ?>
        </div><!--yith-wcbm-badge-->
        <?php
        break;

    case 'image':
        //if the badge was created by free version
        if ( strlen( $image_url ) < 6 ) {
            $image_url = YITH_WCBM_ASSETS_URL . '/images/image-badge/' . $image_url;
        }
        $text = '<img src="' . $image_url . '" alt="" />';
        ?>
        <div class='yith-wcbm-badge yith-wcbm-badge-image yith-wcbm-badge-<?php echo $id_badge ?>'>
            <?php echo $text ?>
        </div><!--yith-wcbm-badge-->
        <?php
        break;

    case 'css':
        $css_badge = isset( $css_badge ) ? $css_badge : 'css';
        ?>
        <div
            class="yith-wcbm-badge yith-wcbm-badge-css yith-wcbm-badge-<?php echo $id_badge ?> yith-wcbm-badge-css-<?php echo $css_badge; ?> yith-wcbm-css-badge-<?php echo $id_badge ?>">
            <div class="yith-wcbm-css-s1"></div>
            <div class="yith-wcbm-css-s2"></div>
            <div class="yith-wcbm-css-text"><?php echo $css_text ?></div>
        </div>
        <?php
        break;
    case 'advanced':
        $product            = wc_get_product( $product_id );
        $product_is_on_sale = yith_wcbm_product_is_on_sale( $product );

        if ( ( $product && $product_is_on_sale ) || 'preview' === $product_id ) {
            $id_advanced_badge = $id_badge;
            include( YITH_WCBM_TEMPLATE_PATH . '/advanced_sale_badges.php' );
        }
        break;
}


?>


