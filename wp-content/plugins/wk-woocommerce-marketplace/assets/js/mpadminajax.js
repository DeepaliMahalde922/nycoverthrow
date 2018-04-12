jQuery(document).ready(function()
{
	jQuery(".pay-rem-amt").on("click",function(){
 		var amt_rem=jQuery(this).closest(".payment-modelbox").find(".thickbox_amt_rem").val();
		var paid_amt=jQuery(this).closest(".payment-modelbox").find(".thickbox_paid_amt").val();
		var seller_main=jQuery(this).closest(".payment-modelbox").find(".seller_main").val();
		var isChecked=jQuery(this).closest(".payment-modelbox").find(".notify_seller").is(':checked');
		if (isChecked)
			notify_seller=isChecked;
		else
			notify_seller=false;
		 jQuery.ajax({
          type: 'POST',
          url: the_mpadminajax_script.mpajaxurl,
          data: {"action": "wk_commission_resetup","amt_rem":amt_rem,"paid_amt":paid_amt,"seller_main":seller_main,"notify_seller":notify_seller,"nonce":the_mpadminajax_script.nonce},
          success: function(data){
	          	if(data){
	          		location.reload();
	          	}
          	}
          });

	});


          var name_regex = /^[a-zA-Z\s-, ]+$/;
           var contact_regex = /^[0-9]+$/;

         jQuery(document).on("blur","#tmplt_name",function(){
                if(jQuery("#tmplt_name").val()==''){
                    jQuery("#tmplt_name").next("span.name_err").text('This field cannot be left blank');
                }
                else{if(!jQuery("#tmplt_name").val().match(name_regex)){
                       jQuery("#tmplt_name").next("span.name_err").text('Please enter the valid template name');
                   }
                   else{
                       jQuery("#tmplt_name").next("span.name_err").text('');
                  }
                }

        });
         jQuery(document).on("blur","#page-width",function(){
            if(jQuery("#page-width").val()==''){
             jQuery("#page-width").next("span.width_err").text('This field cannot be left blank');

            }
             else{if(!jQuery("#page-width").val().match(contact_regex)){
                       jQuery("#page-width").next("span.width_err").text('Please enter the valid page width');
                 }
                else{
                    jQuery("#page-width").next("span.width_err").text('');
                }
           }
        });
          jQuery(document).on("blur","#img_url",function(){
            if(jQuery("#img_url").val()==''){
            jQuery("#img_url").next("span.url_err").text('This field cannot be left blank');

             }
             else{
                jQuery("#img_url").next("span.url_err").text('');
             }
        });

        jQuery(document).on("blur","#clr1",function(){
            if(jQuery("#clr1").val()==''){
            jQuery("#clr1").next("span.bsclr_err").text('This field cannot be left blank');

             }
             else{
                jQuery("#clr1").next("span.bsclr_err").text('');
             }
        });

        jQuery(document).on("blur","#clr2",function(){
            if(jQuery("#clr2").val()==''){
            jQuery("#clr2").next("span.bdclr_err").text('This field cannot be left blank');

             }
             else{
                jQuery("#clr2").next("span.bdclr_err").text('');
             }
        });

        jQuery(document).on("blur","#clr3",function(){
            if(jQuery("#clr3").val()==''){
            jQuery("#clr3").next("span.bkclr_err").text('This field cannot be left blank');

             }
             else{
                jQuery("#clr3").next("span.bkclr_err").text('');
             }
        });
           jQuery(document).on("blur","#clr4",function(){
            if(jQuery("#clr4").val()==''){
            jQuery("#clr4").next("span.txclr_err").text('This field cannot be left blank');

             }
             else{
                jQuery("#clr4").next("span.txclr_err").text('');
             }
        });


jQuery("form#emailtemplate input").on('focus',function(evt) {
		jQuery('span.required').remove();
});

 jQuery("form#emailtemplate").on('submit',function(evt){

          var t_name=jQuery('.tmplt_name').val();
          var t_url=jQuery('#img_url').val();
          var t_clr1=jQuery('#clr1').val();
          var t_clr2=jQuery('#clr2').val();
          var t_clr3=jQuery('#clr3').val();
          var t_clr4=jQuery('#clr4').val();
          var t_width=jQuery('#page-width').val();


          var name_regex = /^[a-zA-Z\s-, ]+$/;
          var contact_regex = /^[0-9]+$/;

					jQuery('span.required').remove();
          if(t_name=='' || t_url=='' || t_clr1=='' || t_clr2=='' || t_clr3=='' || t_clr4=='' || t_width==''){
							if(t_name==''){
                  jQuery('.tmplt_name').after('<span class="required">Please enter template name.</span>');
									return false;
               }
               else{

                 if(!t_name.match(name_regex)){
	                   jQuery('.tmplt_name').after('<span class="required">Please enter the valid template name.</span>');
										 return false;
                 }

               }
               if(t_url==''){
                  jQuery('#img_url').after('<br><span class="required">Please upload the logo.</span>');
									return false;
              }

               if(t_clr1==''){
                  jQuery('#clr1').after('<span class="required">Please select the base color.</span>');
									return false;
              }
               if(t_clr2==''){
                  jQuery('#clr2').after('<span class="required">Please select the body color.</span>');
									return false;
              }
               if(t_clr3==''){
                  jQuery('#clr3').after('<span class="required">Please select the background color.</span>');
									return false;
              }
              if(t_clr4==''){
                  jQuery('#clr4').after('<span class="required">Please select the text color.</span>');
									return false;
              }
							if(t_width==''){
                  jQuery('.width_err').after('<br><span class="required">Please enter the page width.</span>');
									return false;
              }
              else{
                  if((!b_contact.match(contact_regex))){
	                   jQuery('.width_err').after('<span class="required">Please enter the valid page width.</span>');
	                   return false;
                  }
              }
							evt.preventDefault();
          }
          else{
              if(!t_name.match(name_regex)){
                   jQuery('.tmplt_name').after('<span class="required">Please enter the valid template name.</span>');
									 evt.preventDefault();
              }
              if((!t_width.match(contact_regex))){
                   jQuery('.width_err').after('<span class="required">Please enter the valid page width.</span>');
									 evt.preventDefault();
              }
          }
 });
	jQuery('a.wk_seller_app_button').click(function(){
		var seller_status=this.id;
          jQuery.ajax({
          type: 'POST',
          url: the_mpadminajax_script.mpajaxurl,
          data: {"action": "wk_admin_seller_approve", "seller_app":seller_status},
          success: function(data){

          var sel_data=data.split(':');
              if(sel_data[1]==0)
              {
              var this_sel_id='wk_seller_approval_mp'+sel_data[0]+'_mp1';
              this_sel_id=this_sel_id.replace(/\s+/g, '');
                jQuery('#'+seller_status).text('Disapprove');
                 jQuery('#'+seller_status).addClass("active");
               jQuery('#'+seller_status).attr('id',this_sel_id);

              }
              else
              {
              var this_sel_id='wk_seller_approval_mp'+sel_data[0]+'_mp0';
              this_sel_id=this_sel_id.replace(/\s+/g, '');
                jQuery('#'+seller_status).text('Approve');
                jQuery('#'+seller_status).removeClass("active");
                jQuery('#'+seller_status).attr('id',this_sel_id);

              }
          }
          });
      });/* seller product sorting */

	jQuery(".return-seller .dropdown-toggle").on("click",function(){
		jQuery(this).parent().toggleClass("open");
	});

	jQuery('#check-seller').on("keyup",function(){
		var character=jQuery(this).val();
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: the_mpadminajax_script.mpajaxurl,
			data: {"action": "wk_search_seller","seller_char":character,"nonce":the_mpadminajax_script.nonce},
			success: function(data){
				jQuery(".search-selected,.selected").empty();
				if(data==""){
					jQuery(".search-selected").append("<a><span>No seller found</span></a>");
				}
				else{
					for(var i=0;i<Object.keys(data.user_id).length;i++)
						jQuery(".search-selected").append("<a data-seller-id="+data.user_id[i]+"><span>"+data.first_name[i]+"</span></a>");
				}
			},
			 error: function (xhr, ajaxOptions, thrownError) {
		        jQuery(".search-selected").append("<a><span>No seller found</span></a>");
		      }
		});
	});

	jQuery(document).on("click",".selected a,.search-selected a" ,function() {
		if(jQuery(".return-seller .dropdown-menu").hasClass('open'))
			jQuery(".return-seller .bootstrap-select").removeClass('open');
		var attr = jQuery(this).data('seller-id');
		$sell_name = jQuery(this).text().trim();

		if (typeof attr !== typeof undefined && attr !== false) {
  			$val = attr;
  			jQuery("input[name='seller_id']").val($val);
		}

		jQuery("span.filter-option").text($sell_name);
	});

	jQuery('select#role').on('change', function() {

		if (jQuery(this).val() == 'wk_marketplace_seller') {
			jQuery('.mp-seller-details').show();
			jQuery('#org-name').focus();
		}
		else {
			jQuery('.mp-seller-details').hide();
		}

	});

	jQuery('#org-name').on('focusout', function() {
        var value = jQuery(this).val().toLowerCase().replace(/-+/g, '').replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
        if (value == '') {
        	jQuery('#seller-shop-alert-msg').removeClass('text-success').addClass('text-danger').text("Please fill shop name.");
        	jQuery('#org-name').focus();
        }
        else {
        	jQuery('#seller-shop-alert-msg').text("");
        }
        jQuery('#seller-shop').val(value);
    });

    jQuery('#seller-shop').on('focusout', function() {
      	var self = jQuery(this);
      	jQuery.ajax({
            type: 'POST',
            url: the_mpadminajax_script.mpajaxurl,
            data: {"action": "wk_check_myshop","shop_slug":self.val(),"nonce":the_mpadminajax_script.nonce},
            success: function(response)
            {
                if ( response == 0){
                    jQuery('#seller-shop-alert').removeClass('text-success').addClass('text-danger');
                    jQuery('#seller-shop-alert-msg').removeClass('text-success').addClass('text-danger').text("Not Available");
                  }
                else if(response == 2){
                    jQuery('#seller-shop-alert').removeClass('text-success').addClass('text-danger');
                    jQuery('#seller-shop-alert-msg').removeClass('text-success').addClass('text-danger').text("Already Exists");
                    jQuery('#org-name').focus();
                  }
                else {
                    jQuery('#seller-shop-alert').removeClass('text-danger').addClass('text-success');
                    jQuery('#seller-shop-alert-msg').removeClass('text-danger').addClass('text-success').text("Available");
                }
            }
        });

  	});

			   jQuery(document).ready(function($){

       jQuery("#uploadButton").click(function(event) {

            var frame = wp.media({
            title: 'Select or Upload Media Of Your Chosen Persuasion',
            button: {
            text: 'Use this media'
            },
            multiple: false
            });

            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                jQuery("#img_url").val(attachment.url);
            });
            frame.open();
		});

});
jQuery(document).ready(function($){

    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('#clr1').wpColorPicker();
				$('#clr2').wpColorPicker();
				$('#clr3').wpColorPicker();
				$('#clr4').wpColorPicker();
    });

});


		/* commission payment and seller payment */
		//jQuery('.alternate').on("click",".column-pay_action",function(){
		jQuery('tbody').on("click",".column-pay_action",function(){
		  var seller_com_id=jQuery(this).find('div.pay').attr('id');
		  jQuery.ajax({
		    type: 'POST',
		    url: the_mpajax_script.mpajaxurl,
		    data: {"action": "marketplace_statndard_payment", "seller_id":seller_com_id,"nonce":the_mpajax_script.nonce},
		    success: function(data)
		    {
		    jQuery('#com-pay-ammount').html(data);
		    jQuery('#com-pay-ammount').css('display','block');
		    jQuery('<div class="standard-pay-backdrop">&nbsp;</div>').appendTo('body');
		    }
		  });


		});

		  jQuery('#com-pay-ammount').on('click','.standard-pay-close',function(){
		          jQuery('#com-pay-ammount').hide();
		          jQuery( "div" ).remove( ".standard-pay-backdrop" );
		        });

		  jQuery('#com-pay-ammount').on('click','#MakePaymentbtn',function(){
		    var remain_ammount=jQuery('#com-pay-ammount').find('#mp_remain_ammount').val();
		    var pay_ammount=jQuery('#com-pay-ammount').find('#mp_paying_ammount').val();
		    var seller_acc=jQuery('#com-pay-ammount').find('#mp_paying_acc_id').val();
		    if(remain_ammount<pay_ammount)
		    {
		      jQuery('#com-pay-ammount').find('#mp_paying_ammount_error').text('Sorry Account Balance is Low');
		    }
		    else
		    {
		      var pay_url=jQuery('#com-pay-ammount').find('#mp_payment_url').val();
		      if(pay_url!='')
		      {
		        jQuery('#Standard-Payment-form').submit();
		      }
		      else
		      {
		        jQuery.ajax({
		        type: 'POST',
		        url: the_mpajax_script.mpajaxurl,
		        data: {"action": "marketplace_mp_make_payment", "seller_acc":seller_acc,"remain":remain_ammount,"pay":pay_ammount,"nonce":the_mpajax_script.nonce},
		          success: function(data)
		          {
		            jQuery('#com-pay-ammount').html(data);
		            jQuery('#com-pay-ammount').hide();
		            jQuery( "div" ).remove( ".standard-pay-backdrop" );
		            window.location.reload();
		          }
		        });
		      }
		    }
		  })

		setTimeout(function(){
		  jQuery('#wk_payment_success').remove();
		}, 5000);
		/* commission payment and seller payment */
});
