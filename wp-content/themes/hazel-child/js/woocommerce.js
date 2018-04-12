
$j(document).ready(function() {
	"use strict";

    $j('.price_slider_wrapper').parents('.widget').addClass('widget_price_filter');
    initSelect2();
    initAddToCartPlusMinus();
});

function initSelect2(){
    $j('.woocommerce-ordering .orderby, #calc_shipping_country, #dropdown_product_cat').select2({
        minimumResultsForSearch: -1
    });
    $j('.woocommerce-account .country_select').select2();
}

function initAddToCartPlusMinus(){

	$j(document).on( 'click', '.quantity .plus, .quantity .minus', function() {

		// Get values
		var $qty		= $j(this).closest('.quantity').find('.qty'),
			currentVal	= parseFloat( $qty.val() ),
			max			= parseFloat( $qty.attr( 'max' ) ),
			min			= parseFloat( $qty.attr( 'min' ) ),
			step		= $qty.attr( 'step' );

		// Format values
		if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
		if ( max === '' || max === 'NaN' ) max = '';
		if ( min === '' || min === 'NaN' ) min = 0;
		if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

		// Change the value
		if ( $j( this ).is( '.plus' ) ) {

			if ( max && ( max == currentVal || currentVal > max ) ) {
				$qty.val( max );
			} else {
				$qty.val( currentVal + parseFloat( step ) );
			}

		} else {

			if ( min && ( min == currentVal || currentVal < min ) ) {
				$qty.val( min );
			} else if ( currentVal > 0 ) {
				$qty.val( currentVal - parseFloat( step ) );
			}
		}

		// Trigger change event
		$qty.trigger( 'change' );
	});
}

/**
 * Here about us field
 */
var hear_about_us = '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Friend" /><label for="hear_about_us_radio">Friend</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Flier" /><label for="hear_about_us_radio">Flier</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="News/Press" /><label for="hear_about_us_radio">News/Press</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Instagram" /><label for="hear_about_us_radio">Instagram</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Google" /><label for="hear_about_us_radio">Google</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Yelp" /><label for="hear_about_us_radio">Yelp</label></span>';
hear_about_us += '<span class="optt"><input type="radio" name="hear_about_us_radio" value="Other" /><label for="hear_about_us_radio">Other</label></span>';

jQuery('body.woocommerce-checkout #hear_about_us_opt_field label').after(hear_about_us);

jQuery('input[type=radio][name=hear_about_us_radio]').change(function() {
    if (this.value !== 'Other') {
    	jQuery('#hear_about_us_opt').hide(0);
        jQuery('#hear_about_us_opt').val( jQuery(this).val() );
    }else{
    	jQuery('#hear_about_us_opt').attr('value','');
    	jQuery('#hear_about_us_opt').show(0);
    }
});

jQuery('input[name=newsletter_signup]').attr('checked', 'checked');