/* 
 * For cart button functionality on the shop page
 */

var $jf = jQuery.noConflict();

$jf(document).ready(function(){
    $jf("a.add_to_cart_button.product_type_fast-credit").addClass("product_type_simple");
    $jf("a.add_to_cart_button.product_type_fast-credit").removeClass("product_type_fast-credit");
});
