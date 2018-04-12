<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function attribute_variation_data($var_id,$wk_pro_id){
    require('single-product/variations.php');
}

function add_product(){
    require_once('single-product/add-product.php');
}

function edit_product(){
    require_once('single-product/edit-product.php');
}
