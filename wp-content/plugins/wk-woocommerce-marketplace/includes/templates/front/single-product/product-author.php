<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function woocommerce_product_by() {

      $seller_id = get_the_author_meta('ID');

      $shop_url = get_user_meta($seller_id, 'shop_address', true);

      if (get_the_author_meta('ID')!=1) {

          echo __('<p>Product By : <a href="'.site_url().'/seller/store/'.$shop_url.'">'.ucfirst(get_the_author()).'</a></p>', 'marketplace');

      }

      else {

          echo __('<p>Product By : '.ucfirst(get_the_author()).'</p>', 'marketplace');

      }

}
