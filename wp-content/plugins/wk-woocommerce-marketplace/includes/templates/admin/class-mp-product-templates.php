<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function seller_metabox(){
    require_once('single-product/metabox.php');
}

function add_seller_metabox() {
 	  add_meta_box("seller-meta-box","Seller","seller_metabox","product","side","low",NULL);
}
