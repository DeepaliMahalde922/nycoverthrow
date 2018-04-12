var $j=jQuery.noConflict();$j(document).ready(function(){"use strict"
	
	jQuery(document.body).on('DOMNodeInserted', 'form.woocommerce-checkout', function(e) {
	  
		if (jQuery(e.target).attr('class') == 'woocommerce-error') 
		{	  	
		    if(jQuery(e.target).find('li').html()=="Please choose How did you here about us?")
		    {
		    	jQuery("#hear_about_us_opt_field").addClass('hear_about_us_error');		    			    	
		    }
		    else
		    {
		    	jQuery("#hear_about_us_opt_field").removeClass('hear_about_us_error');	
		    }
	  	}

	});
	
	var duplicate_product_header = jQuery(".single.single-product.postid-47100").find(".page_header.transparent.has_woocommerce_dropdown.dark.regular");
	duplicate_product_header.removeClass('dark').addClass('light');
});