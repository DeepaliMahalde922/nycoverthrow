jQuery(document).ready(function($){

    // Remove seller from favourite list
    jQuery(".favourite-seller .remove-icon").on("click",function(){
      currentElm=jQuery(this);
      seller = currentElm.data("seller-id");
      customer_acc = currentElm.data("customer-id");
      var retVal = confirm("Are You sure you want to delete this Seller..?");
      if( retVal == true ){
          jQuery.ajax({
              type: 'POST',
              url: the_mpajax_script.mpajaxurl,
              data: {"action": "delete_favourite_seller", "seller":seller,"customer_acc":customer_acc,"nonce":the_mpajax_script.nonce},
                success: function(data)
                {

                  if(data==1){

                    currentElm.closest("tr").remove();

                  }
                  else{

                    alert('there was some issue in process. Please try again.!');

                  }
                }
          });
        }
    });


    /*----------*/ /*---------->>> Bulk Delete Shop Followers Seller End <<<----------*/ /*----------*/
    jQuery(".action-delete").on("click",function(){

      customer_checked='';
      temp_arr=[];
      customer_checked=jQuery(".shop-fol tbody tr").find(".icheckbox_square-blue input:checkbox:checked").map(function(){
         currentElm=jQuery(this);
          customer_id = jQuery(this).closest("tr").find("td:last span.remove-icon").data("customer-id");
          seller_id   = jQuery(this).closest("tr").find("td:last span.remove-icon").data("seller-id");
          temp_arr=seller_id+","+customer_id;
          // console.log(temp_arr);
          return temp_arr;
       }).get();


        if(customer_checked.length > 0){

          var retVal = confirm("Are You sure you want to delete this Customer from list..?");
          if(retVal==true){
            jQuery.ajax({
                type: 'POST',
                url: the_mpajax_script.mpajaxurl,
                data: {"action": "change_favorite_status","nonce":the_mpajax_script.nonce,"customer_selected":customer_checked},

                success: function(response)
                {
                     // No work done
                     currentElm.closest("tr").remove();

                 }
              });
           }
        }
        else{

          alert("select customers to delete from list.!");

        }

    });


    /*----------*/ /*---------->>> Send Mail To Shop Followers <<<----------*/ /*----------*/
    jQuery("#wk-send-mail").on("click",function(evt){

       var datastring = jQuery("#snotifier").serializeArray();

       var customer_checked='';

        var temp_arr=[];

        customer_checked=jQuery(".shop-fol tbody tr").find(".icheckbox_square-blue input:checkbox:checked").map(function(){
           currentElm=jQuery(this);
            customer_email = jQuery(this).closest("tr").find("td.c-mail").data("cmail");
            temp_arr=customer_email;
            return temp_arr;
         }).get();

       jQuery.ajax({
          type: 'POST',
          url: the_mpajax_script.mpajaxurl,
          data: {"action": "send_mail_to_customers","nonce":the_mpajax_script.nonce,"form_serialized":datastring,"customer_list":customer_checked},

          success: function(response)
          {
              if(response='sent'){

                jQuery("#notify-customer .modal-footer .final-result").append("<div class='alert alert-success send-success text-center alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Email Sent Successfully</div>");
                jQuery("#notify-customer .alert-success").delay(5000).fadeOut();
                // setTimeout(function(){
                //     jQuery('#notify-customer').modal('hide');
                //  }, 2000);

                jQuery('input:checkbox').removeAttr('checked');
                jQuery('input:checkbox').parent(".icheckbox_square-blue").removeClass("checked");
              }
              else{
                jQuery("#notify-customer .modal-footer .final-result").append("<div class='alert alert-danger send-danger text-center alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Error sending Email</div>");
                jQuery("#notify-customer .alert-danger").delay(5000).fadeOut();
              }

           }
        });

       evt.preventDefault();

    });


    /*----------*/ /*---------->>> Seller Shop Options Check <<<----------*/ /*----------*/
    jQuery('#seller-shop').on('focusout', function() {
        var self = jQuery(this);
        jQuery.ajax({
                    type: 'POST',
                    url: the_mpajax_script.mpajaxurl,
                    data: {"action": "wk_check_myshop","shop_slug":self.val(),"nonce":the_mpajax_script.nonce},
                  success: function(response)
                  {
                      if ( response == 0){
                          jQuery('#seller-shop-alert').removeClass('text-success').addClass('text-danger');
                          jQuery('#seller-shop-alert-msg').removeClass('text-success').addClass('text-danger').text("Not Available");
                        }
                      else if(response == 2){
                          jQuery('#seller-shop-alert').removeClass('text-success').addClass('text-danger');
                          jQuery('#seller-shop-alert-msg').removeClass('text-success').addClass('text-danger').text("Already Exists");
                        }
                      else {
                              jQuery('#seller-shop-alert').removeClass('text-danger').addClass('text-success');
                              jQuery('#seller-shop-alert-msg').removeClass('text-danger').addClass('text-success').text("Available");
                        }
                  }
            });

    });



    // deleting image
    jQuery('a.mp-img-delete_gal').click(function(){
          jQuery('#'+this.id).parent().parent().parent().remove();
            jQuery.ajax({
            type: 'POST',
            url: the_mpajax_script.mpajaxurl,
            data: {"action": "productgallary_image_delete", "img_id":this.id,"nonce":the_mpajax_script.nonce},
            success: function(data){
            jQuery('#product_image_Galary_ids').val(data);
            }
            });
        });


    // variation attribute
    jQuery(document).on('click','#mp_var_attribute_call',function(event){
      event.preventDefault();
      var pid=jQuery('#sell_pr_id').val();
              jQuery.ajax({
              type: 'POST',
              url: the_mpajax_script.mpajaxurl,
              data: {"action": "marketplace_attributes_variation","product":pid,"nonce":the_mpajax_script.nonce},
              beforeSend: function(){
                jQuery('#mp-loader').css('display', 'block');
              },
              success: function(data){
              jQuery('#mp-loader').css('display', 'none');
              jQuery('#mp_attribute_variations').append(data);
              }
              });
          });


    jQuery(document).on('click','.wkmp_var_btn',function(){
      var var_att_id=jQuery(this).attr('id');
      jQuery(this).parent().parent().remove();
      jQuery.ajax({
              type: 'POST',
              url: the_mpajax_script.mpajaxurl,
              data: {"action": "mpattributes_variation_remove","var_id":var_att_id,"nonce":the_mpajax_script.nonce},
              success: function(data){
              }
              });

    });


    jQuery('#mp_attribute_variations').on("click",".mp_varnew_file",function(){
    var var_did=jQuery(this).attr('id');
    var variation_count=jQuery("div#variation_downloadable_file_"+var_did+" > div").length;
    var wrapper='#variation_downloadable_file_'+var_did;
    jQuery.ajax({
            type: 'POST',
            url: the_mpajax_script.mpajaxurl,
            data: {"action": "mp_downloadable_file_add","var_id":var_did,"eleme_no":variation_count,"nonce":the_mpajax_script.nonce},
            success: function(data){
            jQuery(data).appendTo(wrapper);
            }
        });
    });


    /* login with face book function start */
    jQuery('#mp-fb-login-btn').on('click',function(){
            function updateButton(response)
            {
            if (response.authResponse)
              {
              //user is already logged in and connected
              FB.api('/me?fields=id,name,email', function(info) {
                login(response, info);
                });
              }
              else
              {
                FB.login(function(response) {
                if (response.authResponse!==null) {

                  var url = window.location;
                  url += '?checkpoint=1&key='+response.authResponse.accessToken;
                  window.location.href = url;

                  }
                }, {scope: 'email', return_scopes: true});
              }
            }
            FB.getLoginStatus(updateButton);
            // FB.Event.subscribe('auth.statusChange', updateButton);


        function login(response, info)
        {
            if ( Object.keys(response.authResponse).length !== 0) {
                var name=info.name;
                var fb_id=info.id;
                var email=info.email;
                jQuery.ajax({
                    type: 'POST',
                    url: the_mpajax_script.mpajaxurl,
                    data: {"action": "mp_login_with_facebook","name":name,"username":email,"email":email,"facebook_info":response,"facebook_id":fb_id,"nonce":the_mpajax_script.nonce},
                  success: function(data)
                  {
                    var url = window.location;
                    url += '?check=1';
                    window.location.href = url;
                  }
                });
              /*Write Your Ajax call Here to store the data in database */
            }

        }
    });


    jQuery('#check-group').on("keyup",function(){
        var character=jQuery(this).val();
        jQuery.ajax({
          type: 'POST',
          dataType: 'json',
          url: the_mpajax_script.mpajaxurl,
          data: {"action": "wk_search_group","group_char":character,"nonce":the_mpajax_script.nonce},
          success: function(data){
            jQuery(".group-selected,.selected").empty();
            if(data==""){
              jQuery(".group-selected").append("<a><span>No Group found</span></a>");
            }
            else{
              for(var i=0;i<Object.keys(data._sku).length;i++)
                jQuery(".group-selected").append("<a data-group-id="+data.id[i]+"><span>"+data._sku[i]+"-"+data.post_title[i]+"</span></a>");
            }
          },
           error: function (xhr, ajaxOptions, thrownError) {
                jQuery(".group-selected").append("<a><span>No Group found</span></a>");
              }
        });
      });

});
