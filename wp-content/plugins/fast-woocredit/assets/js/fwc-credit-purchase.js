/* 
 * To show/hide credit related options
 */

var $j = jQuery.noConflict();

$j(document).ready(function(){
    $j("#general_product_data .options_group.pricing").addClass("show_if_fast-credit");
    $j("#general_product_data #_tax_status").parents("#general_product_data div.options_group").addClass("show_if_fast-credit");
    $j(".show_if_only_fast-credit").css('display', 'none');
    var chkd0=document.getElementById("_fwc_credit_purchase");
    if( chkd0.checked === true ) {
        $j(".options_group.show_if_credit_purchase").show();
    }
    var chkd1=document.getElementById("product-type");
    if( chkd1.value === 'fast-credit' ) {
        $j("#general_product_data .show_if_only_fast-credit").css('display', 'block');
        $j(".type_box .show_if_only_fast-credit").css('display', 'inline');
        $j("#general_product_data .show_if_fast-credit").css('display', 'block');
        $j(".type_box .show_if_fast-credit").css('display', 'inline');
    }
    $j("input#_fwc_credit_purchase").click(function() {
        var chkd=document.getElementById("_fwc_credit_purchase");
        if( chkd.checked === true ) {
            $j(".options_group.show_if_credit_purchase").show();
        }
        if( chkd.checked === false ) {
            $j(".options_group.show_if_credit_purchase").hide();
        }
    });
    $j('select#product-type').on('change', function() {
        if( this.value === 'fast-credit' ) {
            $j("#general_product_data .show_if_only_fast-credit").css('display', 'block');
            $j(".type_box .show_if_only_fast-credit").css('display', 'inline');
        } else {
            $j(".show_if_only_fast-credit").css('display', 'none');
        }
    });
});
