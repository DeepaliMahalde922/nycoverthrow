<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

function wk_mp_invoice($order_id) {
    require_once('order/invoice.php');
}

function order_history(){
    require_once('order/order-history.php');
}

function order_view(){
    require_once('order/order-view.php');
}
