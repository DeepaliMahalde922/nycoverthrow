<?php

if( ! defined ( 'ABSPATH' ) )

    exit;

/*----------*/ /*---------->>> Order Invoice Template <<<----------*/ /*----------*/

function mp_virtual_menu_invoice_page(){
    require_once('order/invoice.php');
}

/*----------*/ /*---------->>> Order Invoice Button <<<----------*/ /*----------*/

function order_invoice_button( $order ) {
    require('order/invoice-button.php');
}


function wk_admin_end_invoice($order_id) {
    require_once('order/admin-invoice.php');
}
