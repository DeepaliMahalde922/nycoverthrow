jQuery(document).ready(function(){

	        // tabs on manage shipping page
        jQuery('#edit_ship_tab li a:not(:first)').addClass('inactive');
        jQuery('.shipping-container').hide();
        jQuery('.shipping-container:first').show();
        // Manage shipping Tabs
        jQuery('#edit_ship_tab li a').click(function(){
            shipElm = jQuery(this).data('link-id');
	            if(jQuery(this).hasClass('inactive')){ //this is the start of our condition 
		            jQuery('#edit_ship_tab li a').addClass('inactive');           
		            jQuery(this).removeClass('inactive');
		            
		            jQuery('.shipping-container').hide(); 
		            jQuery('#'+ shipElm).fadeIn('slow');
	
             }
        });


	jQuery(".wc-shipping-class-add").on("click",function(evt){
		jQuery(".wc-shipping-class-save").removeAttr("disabled");
		rand_no=Math.floor((Math.random() * 10000000) + 1);
		html='<tr data-id="new-3-'+rand_no+'" class="editing"><td class="wc-shipping-class-name"><div class="view" style="display:none"><div class="row-actions"><a class="wc-shipping-class-edit" href="#">Edit</a> | <a href="#" class="wc-shipping-class-delete">Remove</a></div></div><div class="edit" style="display:block;"><input type="text" name="name[new-3-'+ rand_no +']" data-attribute="name" value="" placeholder="Shipping Class Name"><div class="row-actions"><a class="wc-shipping-class-cancel-edit" href="#">Cancel changes</a></div></div></td><td class="wc-shipping-class-slug"><div class="edit" style="display: block;"><input type="text" name="slug[new-3-'+ rand_no +']" data-attribute="slug" value="" placeholder="Slug"></div></td><td class="wc-shipping-class-description"><div class="view" style="display: none;"></div><div class="edit" style="display: block;"><input type="text" name="description[new-3-'+ rand_no +']" data-attribute="description" value="" placeholder="Description for your reference"></div></td><td class="wc-shipping-class-count"><a href="http://amit.com/wcommerce/wp-admin/edit.php?post_type=product&amp;product_shipping_class="></a></td>	</tr>';

		jQuery("table.wc-shipping-classes tbody.wc-shipping-class-rows").append(html);

		evt.preventDefault();
	});

	jQuery(".wc-shipping-classes").on("click",".wc-shipping-class-name .wc-shipping-class-edit",function(evt){ 
			
		jQuery(".wc-shipping-class-save").removeAttr("disabled");

		jQuery(this).closest("tr").find(".view").hide();
		jQuery(this).closest("tr").find(".edit").show();

		evt.preventDefault();
	});

	jQuery(".wc-shipping-classes").on("click",".wc-shipping-class-cancel-edit",function(evt){
		jQuery(this).closest("tr").remove();
		evt.preventDefault();
	});

	jQuery("#ship_data").submit(function(evt){ 
		jQuery.ajax({
	        url : the_mpajax_shipping_script.shippingajaxurl,
	        type : "POST",
	        data : {"action":"add_shipping_class","data":jQuery("#ship_data").serialize(),"nonce" :the_mpajax_shipping_script.shippingNonce},
	        success : function(data) { 
	            window.location.reload();  
	        }
	    });		
	
		evt.preventDefault();
	});

	jQuery(".btn-save-cost").on("click",function(evt){  
		shipping_cost=jQuery(this).closest(".shipping-method-add-cost").find("input,select").serialize();
		jQuery.ajax({
	        url : the_mpajax_shipping_script.shippingajaxurl,
	        type : "POST",
	        data : {"action":"save_shipping_cost","ship_cost":shipping_cost,"nonce" :the_mpajax_shipping_script.shippingNonce},
	        success : function(data) {   
	            window.location.reload(); 
	        }
	    });		
	
		evt.preventDefault();

	});
 
 
    // Send ajax request to get country and state list
        jQuery.ajax({
            type: 'POST',
            url: the_mpajax_script.mpajaxurl,
            data: {"action": "get_all_countries","nonce":the_mpajax_script.nonce},
              success: function(data)
              {
                // append country list to unordered list
                  jQuery(".live-search-list").append(data);
                 }              
            });
    var rowNum = 0;
  

    jQuery(".wc-shipping-zones").on("click",".wc-shipping-zone-cancel-edit",function(evt){
        
        dynElm=jQuery(this).closest("tr.editing"); 
        dynElm.remove();
        evt.preventDefault();
    });
    jQuery(".wc-shipping-zones").on("click",".wc-shipping-zone-postcodes-toggle",function(evt){ 
        jQuery(this).hide();
        jQuery(this).next(".wc-shipping-zone-postcodes").show(); 
        evt.preventDefault();
    });
  
        // Show list of countries and states on focus input box

        jQuery(document).on("focusin","#unused_elm",function(){
            jQuery(this).siblings(".live-search-list").slideDown();
        });

        // On click to country or state show it on input box and save it on input type hidden

       jQuery(document).on("click",".live-search-list li",function(){
            jQuery(this).parent(".live-search-list").slideUp();  
            currentVal=jQuery(this).text().trim();  
            searched_term=jQuery(this).data("search-term"); 
            tag = jQuery('<div class="mp_ship_tags" data-value='+searched_term+'>' + currentVal + '<a class="mp_del_tag">x</a></div>'); 
            if (jQuery(this).parent().prev("#mp_set_zone_location").val()=='')
              jQuery(this).parent().prev("#mp_set_zone_location").val(jQuery(this).parent().prev("#mp_set_zone_location").val()+searched_term);
            else
              jQuery(this).parent().prev("#mp_set_zone_location").val(jQuery(this).parent().prev("#mp_set_zone_location").val()+','+searched_term);

             tag.insertBefore(jQuery(this).parent().siblings("#unused_elm"), jQuery(this).parent().siblings("#unused_elm"));
             
             jQuery(this).parent().siblings("#unused_elm").val('');
            
             
         });
        jQuery(document).on('click', '.mp_del_tag', function () {
              searched_term = jQuery(this).parent().data("value");
              if(searched_term){
                  nowReq=jQuery(this).parent().siblings("#mp_set_zone_location").val();
                  var new_term_1 = searched_term+',';
                  var new_term_2 = ','+searched_term;
                  if(nowReq.indexOf(new_term_1) !== -1){
                    var splitReq = nowReq.replace(searched_term+',',"");
                  }
                  else if(nowReq.indexOf(new_term_2) !== -1){
                    var splitReq = nowReq.replace(','+searched_term,"");
                  }
                  else {
                    var splitReq = nowReq.replace(searched_term,"");
                  }
                jQuery(this).parent().siblings("#mp_set_zone_location").val(splitReq);
                jQuery(this).parent().remove();
              }
            });

        // delete tags on click to delete button

        


        // Limit search country or state result on every charater input

        jQuery(document).on('keyup',".live-search-box", function(){
            var searchTerm = jQuery(this).val(); 
            str = searchTerm.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            }); 
            jQuery(this).siblings('.live-search-list').find("li").each(function(){ 
                if (jQuery(this).is(":contains("+str+")") || str.length < 1) {
                    jQuery(this).show();
                } else {
                    jQuery(this).hide();

                }

            });
          

        }); 
        
        // Add Shipping Method

        jQuery(".add-ship-method").on("click",function(){
            selectedZoneId=jQuery("#add_method_id").data('get-zone');
            selected_Shipmethod=jQuery("#add_method_id").val();
            
            if (selected_Shipmethod && selectedZoneId) {

                  jQuery.ajax({
                    type: 'POST',
                    url: the_mpajax_script.mpajaxurl,
                    data: {"action": "add_shipping_method","zone-id":selectedZoneId,"ship-method":selected_Shipmethod,"nonce":the_mpajax_script.nonce},
                      success: function(data)
                      {
                        if(data)
                          location.reload();
                        }              
                });
              
              }


        });  

  
      
      // Delete shipping Zones

      jQuery(".wc-shipping-zone-delete").on("click",function(evt){
          getElm=jQuery(this);
          if(typeof getElm.data("zone-id") !=='undefined'){
              del_zone=getElm.data('zone-id');
              if (del_zone) {

                  jQuery.ajax({
                    type: 'POST',
                    url: the_mpajax_script.mpajaxurl,
                    data: {"action": "del_zone","zone-id":del_zone,"nonce":the_mpajax_script.nonce},
                      success: function(data)
                      {
                        location.reload();
                         }              
                });
              
              }
          }

          evt.preventDefault();
        }); 

});