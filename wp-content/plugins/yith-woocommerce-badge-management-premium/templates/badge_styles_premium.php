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

switch ( $type ) {
    case 'text':
    case 'custom':
        ?>
        .yith-wcbm-badge-<?php echo $id_badge ?>
        {
        <?php $line_height = ( $line_height > -1 ) ? $line_height : $height; ?>
        color: <?php echo $txt_color ?>;
        background-color: <?php echo $bg_color ?>;
        width: <?php echo $width ?>px;
        height: <?php echo $height ?>px;
        line-height: <?php echo $line_height ?>px;
        border-top-left-radius: <?php echo $border_top_left_radius ?>px;
        border-bottom-left-radius: <?php echo $border_bottom_left_radius ?>px;
        border-top-right-radius: <?php echo $border_top_right_radius ?>px;
        border-bottom-right-radius: <?php echo $border_bottom_right_radius ?>px;
        padding-top: <?php echo $padding_top ?>px;
        padding-bottom: <?php echo $padding_bottom ?>px;
        padding-left: <?php echo $padding_left ?>px;
        padding-right: <?php echo $padding_right ?>px;
        font-size: <?php echo $font_size ?>px;
        <?php echo $position_css ?>
        opacity: <?php echo $opacity / 100 ?>;
        }
        <?php
        break;

    case 'image':
        ?>
        .yith-wcbm-badge-<?php echo $id_badge ?>
        {
        <?php echo $position_css ?>
        opacity: <?php echo $opacity / 100 ?>;
        }
        <?php
        break;

    case 'css':
        $id_css_badge = $id_badge;
        $args         = array(
            'type'           => 'css',
            'id_css_badge'   => $id_css_badge,
            'id_badge_style' => $css_badge,
            'css_bg_color'   => $css_bg_color,
            'css_text_color' => $css_text_color,
            'position_css'   => $position_css,
            'opacity'        => $opacity
        );
        yith_wcbm_get_badge_style( $args );
        break;
    case 'advanced':
        $id_advanced_badge = $id_badge;

        $args = array(
            'type'                => 'advanced',
            'id_advanced_badge'   => $id_advanced_badge,
            'id_badge_style'      => $advanced_badge,
            'advanced_bg_color'   => $advanced_bg_color,
            'advanced_text_color' => $advanced_text_color,
            'position_css'        => $position_css,
            'opacity'             => $opacity
        );
        yith_wcbm_get_badge_style( $args );
        break;
}


?>


