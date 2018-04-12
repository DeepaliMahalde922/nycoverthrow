<?php

if( ! defined( 'ABSPATH' ) )

    exit;

// Adding the id var so that WP recognizes it
function wp_insertcustom_vars($vars){
    $vars[] = 'main_page';
    $vars[] = 'pagename';
    $vars[] = 'pid';
    $vars[] = 'sid';
    $vars[] = 'action';
    $vars[] = 'info';
    $vars[] = 'shop_name';
    $vars[] = 'order_id';
    $vars[] = 'ship';
    $vars[] = 'zone_id';
    $vars[] = 'ship_page';
    return $vars;
}

function wp_insertcustom_rules($rules) {

    $newrules = array();
    $newrules=array(
      '(.+)/(.+)/shipping/edit/([0-9]+)/?'      => 'index.php?pagename=$matches[1]&main_page=$matches[2]&ship=shipping&action=edit&zone_id=$matches[3]',
      '(.+)/(.+)/shipping/add/?'       		  => 'index.php?pagename=$matches[1]&main_page=$matches[2]&ship=shipping&action=add',
      '(.+)/(.+)/edit/([0-9]+)/?'      		  => 'index.php?pagename=$matches[1]&main_page=$matches[2]&action=edit&pid=$matches[3]',
      '(.+)/(.+)/shipping/?'       		  => 'index.php?pagename=$matches[1]&main_page=$matches[2]&ship_page=shipping',
      '(.+)/invoice/(.+)/?'  		  => 'index.php?pagename=$matches[1]&main_page=invoice&order_id=$matches[2]',
      'seller/([a-z]+)/(.+)/?'       		  => 'index.php?pagename=seller&main_page=$matches[1]&info=$matches[2]',
      '(.+)/(.+)/delete/([0-9]+)/?'    		  => 'index.php?pagename=$matches[1]&main_page=$matches[2]&action=delete&pid=$matches[3]',
      '(.+)/seller-product/(.+)/?' 		  => 'index.php?pagename=$matches[1]&main_page=seller-product&info=$matches[2]',
      '(.+)/order-history/([0-9]+)/?'  		  => 'index.php?pagename=$matches[1]&main_page=order-history&order_id=$matches[2]',
      'my-account/(.+)/(.+)?'                              => 'index.php?pagename=my-account&$matches[1]=$matches[1]&$matches[1]=$matches[2]',
      'my-account/(.+)/?'                    		  => 'index.php?pagename=my-account&$matches[1]=$matches[1]',
      '(.+)/(.+)/?'                    		  => 'index.php?pagename=$matches[1]&main_page=$matches[2]'
    );

    return $newrules + $rules;
}
