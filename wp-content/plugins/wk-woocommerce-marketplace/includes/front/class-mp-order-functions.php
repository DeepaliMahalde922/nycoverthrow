<?php

if ( ! defined ( 'ABSPATH' ) )

    exit;


function get_orders( $per_seller_items,$order,$tax_display = '' ) {
  if ( ! $tax_display ) {
    $tax_display = $order->get_data()['cart_tax'];
  }

  $total_rows = array();
  $subtotal=0;
  if ( $subtotal = get_seller_subtotal_to_display($order,$per_seller_items,false, $tax_display ) ) {
    $total_rows['cart_subtotal'] = array(
      'label' => __( 'Cart Subtotal:', 'woocommerce' ),
      'value'	=> $subtotal
    );
  }

  return apply_filters( 'woocommerce_get_order_item_totals', $total_rows, $order );
}

function get_seller_subtotal_to_display( $order,$per_seller_items,$compound = false, $tax_display = '' ) {

  $subtotal = 0;

  if ( ! $compound ) {
    foreach ( $per_seller_items as $item ) {

      if ( ! isset( $item['line_subtotal'] ) || ! isset( $item['line_subtotal_tax'] ) ) {
        return '';
      }

      $subtotal += $item['line_subtotal'];

      if ( 'incl' == $tax_display ) {
        $subtotal += $item['line_subtotal_tax'];
      }
    }

    $subtotal = wc_price( $subtotal, array('currency' => $order->get_currency()) );

    /*if ( $tax_display == 'excl' && $this->prices_require_tax ) {
      $subtotal .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
    }*/
    $prices_require_tax=false;
    if ( $tax_display == 'excl' && $prices_require_tax ) {
      $subtotal .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
    }

  } else {

    if ( 'incl' == $tax_display ) {
      return '';
    }

    foreach ( $this->get_items() as $item ) {

      $subtotal += $item['line_subtotal'];

    }

    // Add Shipping Costs
    $subtotal += $this->get_total_shipping();

    // Remove non-compound taxes
    foreach ( $this->get_taxes() as $tax ) {

      if ( ! empty( $tax['compound'] ) ) {
        continue;
      }

      $subtotal = $subtotal + $tax['tax_amount'] + $tax['shipping_tax_amount'];

    }

    // Remove discounts
    $subtotal = $subtotal - $order->get_cart_discount();

    $subtotal = wc_price( $subtotal, array('currency' => $order->get_order_currency()) );
  }

  return apply_filters( 'woocommerce_order_subtotal_to_display', $subtotal, $compound, $order );
}



