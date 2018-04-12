jQuery(document).ready(function() {
	(function ($){   

		

		
				  
		/* Pre Select Product for Class */
		var urlstring = $(location).attr('href');
		if (urlstring.indexOf('book-brooklyn') > -1)
		{
			$( document ).ajaxSuccess(function( event, request, settings ) {
				var action_query1 = settings.data;
				if( action_query1.indexOf('booked_new_appointment_form') >= 0 ){
					$('#newAppointmentForm .booked-calendar-fields select option:eq(1)').attr('selected','selected');
				}
			});				  	
		}


	    if( jQuery('body').hasClass('single-portfolio_page') ){
   			var trainer = $('#booked-tcalender').attr('data-trainer');
   			var calendar_id = $('#booked-tcalender').attr('data-default');
   	
   			if(calendar_id){

   				booked_load_calendar_date_booking_options = {'action':'booked_calendar_date','date':trainer,'calendar_id':calendar_id};
   				$(document).trigger("booked-before-loading-calendar-booking-options");
   				
   				$.ajax({
   					url: booked_js_vars.ajax_url,
   					type: 'post',
   					data: booked_load_calendar_date_booking_options,
   					success: function( html ) {
   						if(html == 'false'){
   							$('#booking-calender_sect').find('.loader-section img, .loader-section h3').hide(0);
   							$('#booked-tcalender_trainer, #trainer-calender').show(0);
   							$('#booking-calender_sect .loader-section h3').show(0).text('There are no class time slots available for this trainer.');

   						}else{

   							$('.booked-calendar').append( html );	

							if($('#trainer-calender').find('.no-classes').length !== 0){
								console.log('error');
								$('#booking-calender_sect').find('.loader-section').hide(0);
								$('#booked-tcalender_trainer, #trainer-calender').show(0);
								$('#booking-calender_sect .loader-section h3').show(0).text('There are no class time slots available for this trainer.');
							}else{
								$('#booking-calender_sect').find('.loader-section').hide(0);
								$('#booked-tcalender_trainer').show(0);
							    jQuery('#trainer-calender').owlCarousel({
								 	items : 6,
								 	itemsDesktop : [1160,6],
									    itemsDesktopSmall : [1159,5],
									    itemsTablet: [865,3],
									    itemsTabletSmall: [540,3],
									    itemsMobile : [539,1],
								    autoPlay: false, //Set AutoPlay to 3 seconds
								    navigation : true,
								    loop:false,
								    pagination:false
								});

							}

   						}
   
   					}
   				});

   			}
   		}	

	

		if( jQuery('body').hasClass('page-id-38522')  || jQuery('body').hasClass('page-id-119946') ){

			jQuery(document).on('click', '#submit-edit-request-appointment', function(e){
				var appID = jQuery('body').find('input[name="app_id"]').val();
				jQuery.ajax({
					'url' 		: ajax_object.ajax_url,
					'async'     : false,
					'cache' 	: false,
					'method' 	: 'post',
					'data'		: {
						'action'     	:  'ovr-reschedule-appt',
						'appt_id'     	:  appID,
						'requestFor'    :  'ovr_reschedule_count',
					},
					success: function(data) {
						console.log(data);
					}
				});
			});
		}


		var old_href = $('.my_account_link > a').attr('href');

		var new_href = '';

		if(window.location.href.indexOf('redirect_to') >= 0){		
			var res = window.location.href.split('?redirect_to');
			new_href = res[0];
		}else{
			new_href = window.location.href;
		}

		var new_href_final = old_href+'?redirect_to='+new_href;
		$('.my_account_link > a').attr('href', new_href_final);

		$(document.body).on('click','.booking-cancel .cancel, .booked-cal-buttons .ovr_cancel',function(e){
			//ovr_cancel_appointment
			e.preventDefault();
			appt_id = $(this).attr('data-appt-id');

			$('body').css('cursor','wait');

			//booked_cancel_appt
			$.ajax({
				'url' 		: ajax_object.ajax_url,
				'method' 	: 'post',
				'data'		: {
					'action'     	: 'ovr_cancel_appointment',
					'appt_id'     	: appt_id
				},
				success: function(data) {
					alert('Appointment has been cancelled and funds refunded to your account');
					$('body').css('cursor','default');
					window.location.href = ajax_object.site_url+"/my-account/orders/";
				}
			});

		});

		$('#ship-to-different-address-checkbox').prop('checked', false); // Unchecks it

		
		if( $('body').hasClass('page-id-38522') ){
			$( document ).ajaxSuccess(function( event, request, settings ) {
				var action_query1 = settings.data;

				if( action_query1.indexOf('booked_new_appointment_form&date') >= 0 ){

					var current_callender = $('#newAppointmentForm').attr('data-calendar-id');

					$('.field-paid-service select option').each(function(e){
						var this_val = $(this).attr('value');

						if(this_val == ''){
							//do nothing
						}else{
							$(this).parent().val(this_val);
						}
					});

					if( $('input[name="guest_name"]').length && $('input[name="guest_email"]').length ){
						
						$('#newAppointmentForm .field').each(function(e){
							if( $(this).find('.field-label').length ){
								var inner_text = $(this).find('.field-label').html();
								if( inner_text.indexOf("Your Information") >= 0 ){
									$(this).addClass('hidden');
									return false;
								}
							}
						});

						$('input[name="guest_name"]').addClass('hidden');
						$('input[name="guest_email"]').addClass('hidden');
						$('input[name="guest_name"]').val('default');
						$('input[name="guest_email"]').val('default@default.com');

					}
				}

			});
		}

		$('table.shop_table tr.cart_item').each(function(e){
			var pro_name = $(this).find('.product-name').html();
			if( $(this).find('.product-name .booked-wc-checkout-section').length && ( pro_name.indexOf("Ring") >= 0 || pro_name.indexOf("Underground") >= 0 ) ){
				if( $(this).find('.product-name > b').length && $(this).find('.product-name > b').html() == 'Quantity:' ){
					if( $(this).find('.product-name .product-quantity').length ){
						$(this).find('.product-name .product-quantity').remove();
					}
					$(this).find('.product-name > b').remove();
				}
			}
		});

		$('#ywgc-delivery-date').datepicker({dateFormat: 'yy-mm-dd', minDate: +1, maxDate: '+1Y'});

		/*= Book with use pack */
		$(document).on('click', '#buy-pack', function(ev){
			ev.preventDefault();

			if( $(this).data('href') ){
				window.location.href = $(this).data('href');
				return false;
			}
		});

		/*Update HTML of add to cart button after clicking cancel gift card*/
		$(document).on('click', '#ywgc-cancel-gift-card', function(ev){
			ev.preventDefault();

			$('.variations_button .single_add_to_cart_button').html('Add to cart');
		});
		
		
		$(document).on('click', '#fwc-use-pack', function(ev){
			ev.preventDefault();

			if( $(this).data('href') ){
				window.location.href = $(this).data('href');
				return false;
			}

			$('form#newAppointmentForm p.status').show(0).html('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;&nbsp; Please wait');
	        resize_booked_modal();

			var form_data = $('#newAppointmentForm').serialize().split('&');

    		var data_obj = {};
			for(var key in form_data) {
				var parts = form_data[key].split('=');

				var data_key = parts[0].replace('%5B','');
				data_key = data_key.replace('%5D', '');

				data_obj[data_key] = parts[1];
			}

			data_obj['use_packk'] = 'yes';

			/*
			var dataa = {
				action: 'booked_add_appt',
				appoinment: 0,
				calendar_id	: 370,
				customer_type : 'current',
				date : '2017-01-27',
				is_fe_form : true,
				timeslot : '0615-0715',
				timestamp : 1484892900,
				user_id	: 513,
			};
			*/

			$.post(ajax_object.ajax_url, data_obj, function(response) {
				if(response.indexOf('success###') > -1) {
					//alert('Class successfully booked');

					window.location.href = ajax_object.site_url+'/confirm/';

					var modal = jQuery('.booked-modal');
					modal.fadeOut(200);
					modal.addClass('bm-closing');
					jQuery('body').removeClass('booked-noScroll');

					setTimeout(function(){
						modal.remove();
					}, 300);
				}else if(response.indexOf('error###') > -1) {
					var errors = response.split('###');

					alert(errors[1]);
				}else{
					var response_obj = JSON.parse(response);
					alert(response_obj.error);
				}

				$('form#newAppointmentForm p.status').hide(0).html('');

				resize_booked_modal();
			});

			return false;
		});

	}(jQuery));
});// - document ready

jQuery(window).load(function() {	
	(function ($){

		if( jQuery('body').hasClass('woocommerce-account') ){		  
			jQuery('#profile-appointments .booked-profile-appt-list .appt-block.approved').each(function() {
				var txtt = jQuery(this).text()
			  	var red_href = jQuery(this).find('.booked-cal-buttons a.edit').attr('href');

			  
			  	if(txtt.indexOf('NY') != -1){
			    	if(red_href){
			    	 	if(red_href.indexOf('book-newyork') == -1){
			     	  		jQuery(this).find('.booked-cal-buttons a').attr('href', '/book-newyork/' + red_href);
			     		}
			     	}

			     	else if(txtt.indexOf('BK') != -1){
	     			 	if(red_href.indexOf('book-brooklyn') == -1){
	     		 	  		jQuery(this).find('.booked-cal-buttons a').attr('href', '/book-brooklyn' + red_href);
	     		 		}
			     	}

			     	else if(txtt.indexOf('FIGHTING') != -1){
	     			 	if(red_href.indexOf('whatareyoufightingfor') == -1){
	     		 	  		jQuery(this).find('.booked-cal-buttons a').attr('href', '/whatareyoufightingfor' + red_href);
	     		 		}
			     	}

			  	}

			});
		}

		/*jQuery(document).on('click', '.trainer_bookie', function(e){
			jQuery(this).find('button.new-appt').addClass('deepu');
		});
		    */

		var this_href = window.location.href;

		if( this_href.indexOf('my-account') != -1 && this_href.indexOf('members-area') != -1 ){			
			$('.membership-booking').addClass('active');
			$('.booked-tab-content').hide();
			$('#profile-membership').show();
		}

		setTimeout(function(){

			$('table.shop_table.woocommerce-checkout-review-order-table tr.cart_item').each(function(e){
				var pro_name = $(this).find('.product-name').html();
				if( $(this).find('.product-name .booked-wc-checkout-section').length && ( pro_name.indexOf("Ring") >= 0 || pro_name.indexOf("Underground") >= 0 ) ){
					$(this).find('td.product-quantity div.quantity').hide();
					if( $(this).find('.product-name > b').length && $(this).find('.product-name > b').html() == 'Quantity:' ){
						if( $(this).find('.product-name .product-quantity').length ){
							$(this).find('.product-name .product-quantity').remove();
						}
						$(this).find('.product-name > b').remove();
					}
				}		
			});

			jQuery('tr.membership').each(function(){
				var membershipStatus = jQuery(this).children('.membership-status').text().toLowerCase();
				if(membershipStatus.indexOf('active') !== -1){
					var nonceVal = jQuery(this).find('td p.ovr_membership_cancel_nonce').text();
					var cancelBtn = jQuery(this).find('td a.button.cancel').text();
					if(cancelBtn.length == 0){
						var currURL = window.location.href;
						currURL = currURL.split('#')[0];
						var membershipID = jQuery(this).find('td p.ovr_membership_user_spec_id').text();
						var cancelHTML = '<a href="'+ currURL +'?cancel_membership=' + membershipID + '&amp;_wpnonce='+ nonceVal +'" class="button cancel">Cancel</a>';
						jQuery(this).children('td.membership-actions').prepend(cancelHTML);
					}
				} else if( membershipStatus.indexOf('cancelled') !== -1 ){
				var cancelBtn = jQuery(this).find('td a.button.cancel').text();
					if(cancelBtn.length > 0){
						jQuery(this).find('td a.button.cancel').detach();
					}
				}
			});

			jQuery(document).on('click', '.button.ovr_cancel', function(e){

				e.preventDefault();

				jQuery.ajax({
					'url' 		: ajax_object.ajax_url,
					'async'     : false,
					'cache' 	: false,
					'method' 	: 'post',
					'data'		: {
						'action'     	 			:  'ovr-cancel-membership-subs',
						'user_membership_id'     	:  jQuery(this).siblings('.ovr_membership_user_spec_id').text()
					},
					success: function(data) {
						console.log(data);
						location.reload();
						/*if(data == 'true'){	
							location.reload();
						}else{
							location.reload();*/
							//alert('No subscription has been assigned to this member!');
							/*jQuery('.membership-actions .button.ovr_cancel').append('<span id="subscription-error">No subscription has been assigned to this member</span>');

							setTimeout(function(){ 
								jQuery('#subscription-error').fadeOut('slow');
							}, 500);*/
						//}
						//location.reload();
					}
				});
			});


		},2000);		



		/*Update Place Order with Processing Order*/
		
		$(document).on( 'click','#proces_order', function(){
			console.log(1);
			$('#proces_order').text('Processing Order...').addClass('process_order_btn');
			$('#proces_order').attr('disabled','disabled');
			$('form.woocommerce-checkout').append('<div class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; opacity: 0.6; cursor: default; position: absolute; display: block;"></div>')
			
			setTimeout(function(){
				$('#place_order').trigger("click");
				
				setTimeout(function(){
					if (jQuery('#wc-stripe-cc-form').find("ul.woocommerce_error").length > 0) {
						$('#proces_order').text('Place Order').removeAttr('disabled').removeClass('process_order_btn');
					}
				},2000);		

			},4000);


		});



		if( jQuery('body').hasClass('woocommerce-checkout') ){

			$( document ).ajaxSuccess(function( event, request, settings ) {
				var action_query1 = settings.data;
				
				if( action_query1.indexOf('update_order_review') >= 0 ){
					$('#proces_order').text('Place Order').removeClass('process_order_btn');

					var result1 = request.responseText;
					var obj = $.parseJSON(result1);

					$.each(obj, function(sub_key, sub_value) {
						
						if(sub_key == 'result' && sub_value == 'failure'){
							setTimeout(function(){
								$('#proces_order').text('Place Order').removeAttr('disabled').removeClass('process_order_btn');
							},1500);
						}

					});
				}else{
					$('#place_order').css('opacity', '0');
					$('#place_order').css('float', 'left');
					$('#place_order').after('<a rel="" href="javascript:void(0);" style="float: right;" data-value="Place order1" id="proces_order" name="woocommerce_checkout_place_orde" class="button alt">Place Order</a>');
					$('#proces_order').removeClass('process_order_btn');
				}

			});
		}

/*
	if($('#trainer-calender').find('.no-classes').length !== 0){
		
		$('#booking-calender_sect').find('.loader-section').hide(0);
		$('#booked-tcalender_trainer, #trainer-calender').show(0);
	}else{
		$('#booking-calender_sect').find('.loader-section').hide(0);
		$('#booked-tcalender_trainer').show(0);
	    jQuery('#trainer-calender').owlCarousel({
		 	items : 6,
		 	itemsDesktop : [1160,6],
			    itemsDesktopSmall : [1159,5],
			    itemsTablet: [865,3],
			    itemsTabletSmall: [540,3],
			    itemsMobile : [539,1],
		    autoPlay: false, //Set AutoPlay to 3 seconds
		    navigation : true,
		    loop:false,
		    pagination:false
		});

	}*/
		
	}(jQuery));
});// - Wondow load