jQuery(document).ready(function($){

    jQuery( "#notify-customer .close, #notify-customer #wk-cancel-mail" ).on("click", function(){
      jQuery("#notify-customer").fadeOut("slow");
    });

    jQuery( "#save_account_details" ).on("click", function(e){
      e.preventDefault();
      var current_pass  = jQuery( "#password_current" );
      var new_pass      = jQuery( "#password_1" );
      var confirm_pass  = jQuery( "#password_2" );

      if( ! current_pass.val() )
        current_pass.focus();

      else if( ! new_pass.val() )
        new_pass.focus();

      else if( ! confirm_pass.val() )
        confirm_pass.focus();

      else
        jQuery("#mp-seller-change-password").submit();

    });

    // Popup to send email to customers

    jQuery(".mail-to-follower button").on("click",function(){

      customer_checked=[];

      customer_checked=jQuery(".shop-fol tbody tr").find(".icheckbox_square-blue input:checkbox:checked").map(function(){
        return jQuery(this).val();
       }).get();
      if(customer_checked.length > 0){
          jQuery("#notify-customer").fadeIn("slow");
      }
      else{
        alert("please select customer from the list");
      }

    });


    jQuery(document).on("click",".favourite-seller .icheckbox_square-blue .mass-action-checkbox",function(){

       jQuery(this).parent().toggleClass('checked');

       if(jQuery(this).parent().parent().hasClass('select-all-box')){
        if(jQuery(this).parent().hasClass('checked')){
          jQuery(".favourite-seller").find(".mass-action-checkbox").prop('checked', this.checked);
          jQuery(".favourite-seller").find(".mass-action-checkbox").parent().addClass('checked');
        }
        else{
          jQuery(".favourite-seller").find(".mass-action-checkbox").prop('checked', false);
          jQuery(".favourite-seller").find(".mass-action-checkbox").parent().removeClass('checked');
        }
      }

       if(jQuery('.shop-fol tbody input:checkbox:checked').length==jQuery('.shop-fol tbody input:checkbox').length){
          jQuery(".select-all-box .icheckbox_square-blue .mass-action-checkbox").prop('checked', this.checked);
          jQuery(".select-all-box .icheckbox_square-blue .mass-action-checkbox").parent().addClass('checked');
       }
       else{

        jQuery(".select-all-box .icheckbox_square-blue .mass-action-checkbox").prop('checked',false);
        jQuery(".select-all-box .icheckbox_square-blue .mass-action-checkbox").parent().removeClass('checked');
       }

    });

   jQuery('.nav-selector li').on('click', function() {
        var currentElm=jQuery(this);
        var currentElmRadio=jQuery(this).find('input:radio');
        currentElm.addClass("active").siblings().removeClass('active');
        if ( currentElm.data("target")==1) {
            jQuery('.show_if_seller').slideDown();
            jQuery(".show_if_seller").find(":input").removeAttr("disabled");
            if ( jQuery( '.tc_check_box' ).length > 0 )
                jQuery('input[name=register]').attr('disabled','disabled');
        } else {
            jQuery(".show_if_seller").find(":input").attr("disabled","disabled");
            jQuery('.show_if_seller').slideUp();
            if ( jQuery( '.tc_check_box' ).length > 0 )
                jQuery( 'input[name=register]' ).removeAttr( 'disabled' );
        }
    });

});

 // Place selected thumbnail ID into custom field to save as featured image
 jQuery(document).on('click', '#thumbs img', function() {

    jQuery('#thumbs img').removeClass('chosen');

    var thumb_ID = jQuery(this).attr('id').substring(3);

    jQuery('#wpuf_featured_img').val(thumb_ID);

    jQuery(this).addClass('chosen');
});

jQuery(document).ready(function(){

jQuery(document).on('click','.wkmp_nav > li',function(){
  jQuery(this).siblings('li').removeClass('wkmp_active');
  jQuery(this).addClass('wkmp_active');
  var offset=jQuery(this).index();
  jQuery(this).parent().siblings('.wkmp-tab-content').children('div').removeClass('wkmp-tab-pane-active');
  jQuery(this).parent().siblings('.wkmp-tab-content').children('.wkmp-tab-pane').eq(offset).addClass('wkmp-tab-pane-active');
  return false;
});
jQuery(document).on('mouseover','.help-tip',function(){
	jQuery(this).prev('div').css('display','block');
});
jQuery(document).on('mouseout','.help-tip',function(){
	jQuery(this).prev('div').css('display','none');
});


//banner trigger file upload
   jQuery('#wkmp_seller_banner').click(function(){
    jQuery('#wk_mp_shop_banner').trigger('click');
  });


  jQuery('#id_attribute_downloads_files').click(function(){
    jQuery('#attribute_downloads_files').trigger('click');
  });
// banner trigger file  upload end


//banner on mouse over effect
jQuery('.wkmp_shop_banner').on('mouseover',function(){
  jQuery('.wkmp-fade-banner').css('display','block');
});
jQuery('.wkmp_shop_banner').on('mouseout',function(){
  jQuery('.wkmp-fade-banner').css('display','none');
});
//banner on mouse over effect end


jQuery('#seller_sub_login').submit(function(){
var selleremail=jQuery('#username').val();
var sellerpass=jQuery('#password').val();
  if(selleremail.length<=0)
  {
    jQuery('#sellerusername_error').html('Please enter username ');
    return false;
  }
  else
  {
    jQuery('#sellerusername_error').html('');
  }
  if(sellerpass.length<=0)
  {
    jQuery('#sellerpassword_error').html('Please enter Password');
    return false;
  }
  else
  {
    jQuery('#sellerpassword_error').html('');
  }
});
// slider
jQuery(document).ready(function(){
  var size=jQuery('.view-port-mp-slider-absolute').find('img').size();
  var pos=1;
  // console.log(size);
  if(size>0){
    new_size=size*200;
    jQuery('.view-port-mp-slider-absolute').css('width',new_size);
    // var old_width=parseInt(jQuery('.view-port-mp-slider').css('width'));
  }
  jQuery('.wkmp-bx-next-slider').on('click',function(){
    if(pos>=1 && pos<(size-2)){
      pos++;
      jQuery('.view-port-mp-slider-absolute').animate({
        left:'-=200px',
      },'slow');
    }
  });
  jQuery('.wkmp-bx-prev-slider').on('click',function(){

    if(pos>1 && pos<=(size-2)){
      pos--;
      jQuery('.view-port-mp-slider-absolute').animate({
        left:'+=200px',
      },'slow');
    }
  });
});
//  getting parameters value from url
  var checkuser=1;
    jQuery.extend({
      getUrlVars: function(){
      var vars = [], hash;
      var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
      for(var i = 0; i < hashes.length; i++)
      {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
      }
      return vars;
      },
      getUrlVar: function(name){
      return jQuery.getUrlVars()[name];
      }
    });

    var page_id = jQuery.getUrlVar('page_id');
    var page = jQuery.getUrlVar('page');

    if(page=='To'){
    jQuery('<div class="wkmp-modal-backdrop">&nbsp;</div>').appendTo('body');
    }

//product validation
    jQuery('#add_product_sub').click(function() {
      if(jQuery(this).attr('type')=='submit'){
        var product_name=jQuery('#product_name').val();
        product_name=trim_wkmp_value(product_name);
        var product_sku=jQuery('#product_sku').val();
        var regu_price=jQuery('#regu_price').val();
        // var sale_price=jQuery('#sale_price').val();
        var long_desc = tinyMCE.get('product_desc').getContent();
        var short_desc = tinyMCE.get('short_desc').getContent();
        var ck_name = /^[A-Za-z0-9 _-]{1,40}$/;
        var price=/^\d+(\.\d{1,2})?$/;
        if(product_name.length==0)
        {
        jQuery('#pro_name_error').html('this field could not be left blank');
        return false;
        }
        if(!ck_name.test(product_name))
        {
          jQuery('#pro_name_error').html('the product name is not valid it shoud be larger than 3 character');
          return false;
        }
        // if(long_desc.length<10)
        // {
        // jQuery('#long_desc_error').html('About product field can not be left blank');
        // return false;
        // }
        // else{
        // jQuery('#long_desc_error').html('');
        // }
        if( typeof(product_sku) != 'undefined' && product_sku.length<3)
        {
        	jQuery('#pro_sku_error').css('color','red');
       		jQuery('#pro_sku_error').html('please enter valid product sku, it shoud be equal or larger than 3 characters');
			    return false;
        }
        console.log( jQuery('#product_type').val() );
        if(jQuery('#product_type').val()!='variable' && jQuery('#product_type').val()!='grouped'){
          if(!price.test(regu_price))
          {
          jQuery('#regl_pr_error').html('product price accepts numerical value only and shoud not be blank');
          return false;
          }
          else{
          jQuery('#regl_pr_error').html('');
          }
        }
        var sale_price=jQuery('#sale_price').val();
        // var price=/^\d+(\.\d{1,2})?$/;
        var regular=parseInt(jQuery('#regu_price').val());
        var sale=parseInt(jQuery('#sale_price').val());
        if(jQuery('#sale_price').val()!=''){
         if(!price.test(sale_price))
          {
            jQuery('#sale_pr_error').html('Product price accepts numerical value upto 2 decimal points');
            return false;
          }else if(sale>regular){
            jQuery('#sale_pr_error').html('Sale Price cannot be greater than Regular Price**');
            return false;
          }else
          {
            jQuery('#sale_pr_error').html('');
          }
        }
        var product_sku=jQuery('#product_sku').val();
        product_sku_validation(product_sku);
        // if(short_desc.length<10)
        // {
        // jQuery('#short_desc_error').html('About product field can not be left blank');
        // return false;
        // }
        // else{
        // jQuery('#short_desc_error').html('');
        // } ==product-form==
        jQuery('.wkmp_variable_sku').each(function(){
          var wkmp_variable_sku=jQuery(this).val();
          var this_sel=this;
          variation_sku_validation(wkmp_variable_sku,this_sel)
        });
        // return false;
        jQuery('#product-form').submit();
      }
      });
  function trim_wkmp_value (item) {
    item=jQuery.trim(item);
    return item;
  }
//sku validation
      var ps=jQuery('#product_sku').val();
      jQuery('#product_sku').blur(function(){
        var product_sku=jQuery('#product_sku').val();
        jQuery('#pro_sku_error').html('');
        if(product_sku!=ps)
          product_sku_validation(product_sku);
      });
      function product_sku_validation (argument) {
        var product_sku=argument;
        var reg_sku=/^[a-z0-9A-Z]{1,20}$/;
        jQuery('#pro_sku_error').css('color','red');
        if (product_sku=='') {
          jQuery('#pro_sku_error').html('Please Enter SKU');
            return false;
        }else if(!reg_sku.test(product_sku))
        {
        	jQuery('#pro_sku_error').html('special character and space are not allowed');
            return false;
        }else if( typeof( product_sku ) != 'undefined' &&  product_sku.length<3)
        {
        	jQuery('#pro_sku_error').css('color','red');
        	jQuery('#pro_sku_error').html('please enter valid product sku, it shoud be equal or larger than 3 characters');
        	return false;
        }
        else
        {
        	jQuery('#pro_sku_error').html('');
        }
        jQuery.ajax({
            type: 'POST',
            url: the_mpajax_script.mpajaxurl,
            data: {"action": "product_sku_validation", "psku":product_sku,"nonce":the_mpajax_script.nonce},
            success: function(data){
              if (data=='sku is ok') {
                jQuery('#pro_sku_error').css('color','green');
                jQuery('#pro_sku_error').html(data);
              }else{
                jQuery('#pro_sku_error').css('color','red');
                jQuery('#pro_sku_error').html(data);
                return false;
              }
            }
          });
      }
// variation sku validation
      jQuery(document).on('blur','.wkmp_variable_sku',function(){
        var wkmp_variable_sku=jQuery(this).val();
        var this_sel=this;
        jQuery(this).siblings('.wk_variable_sku_err').html('');
        if(jQuery(this).val()!=jQuery(this).attr('placeholder'))
          variation_sku_validation(wkmp_variable_sku,this_sel);
      });
      function variation_sku_validation (argument1,argument2){
        var wkmp_variable_sku=argument1;
        var reg_sku=/^[a-z0-9A-Z]{1,20}$/;
        var this_sel=argument2;
        jQuery(this_sel).siblings('.wk_variable_sku_err').css('color','red');
        if (wkmp_variable_sku=='') {
          jQuery(this_sel).siblings('.wk_variable_sku_err').html('Please Enter SKU');
            return false;
        }else if(!reg_sku.test(wkmp_variable_sku))
          {
            jQuery(this_sel).siblings('.wk_variable_sku_err').html('special character and space are not allowed');
            return false;
          }
          else
          {
            jQuery(this_sel).siblings('.wk_variable_sku_err').html('');
          }
          jQuery.ajax({
            type: 'POST',
            url: the_mpajax_script.mpajaxurl,
            data: {"action": "product_sku_validation", "psku":wkmp_variable_sku,"nonce":the_mpajax_script.nonce},
            success: function(data){
              if (data=='sku is ok') {
                jQuery(this_sel).siblings('.wk_variable_sku_err').css('color','green');
                jQuery(this_sel).siblings('.wk_variable_sku_err').html(data);
              }else{
                jQuery(this_sel).siblings('.wk_variable_sku_err').css('color','red');
                jQuery(this_sel).siblings('.wk_variable_sku_err').html(data);
                return false;
              }
            }
          });
      }
// variation regular price validation
      jQuery(document).on('keyup','.wc_input_price',function(){
        // var no=jQuery(this).val();
        // var no_int=parseInt(no);
        // var stock=/^\d+(\.\d{1,2})?$/;
        // a=no_int;
        // if(no==no_int)
        //   a=no_int;
        // if(jQuery(this).val()!=''){
        //   jQuery(this).val(a);
        // }else{
        //   jQuery(this).val('');
        //   a=0;
        // }
        // if(jQuery(this).val()=='NaN'){
        //   jQuery(this).val('');
        // }
      });
// variation sale price validation
      jQuery(document).on('keyup','.wc_input_price',function(){
        // var no=jQuery(this).val();
        // var no_int=parseInt(no);
        // var stock=/^\d+(\.\d{1,2})?$/;
        // a=no_int;
        // if(no==no_int)
        //   a=no_int;
        // if(jQuery(this).val()!=''){
        //   jQuery(this).val(a);
        // }else{
        //   jQuery(this).val('');
        //   a=0;
        // }
      });
// variation weight price validation
      jQuery(document).on('keyup','.wc_input_decimal',function(){
        var no=jQuery(this).val();
        var no_int=parseInt(no);
        var stock=/^\d+(\.\d{1,2})?$/;
        a=no_int;
        if(no==no_int)
          a=no_int;
        if(jQuery(this).val()!=''){
          jQuery(this).val(a);
        }else{
          jQuery(this).val('');
          a=0;
        }
      });
// variation weight price validation
      jQuery(document).on('keyup','.wkmp_variable_stock',function(){
        var no=jQuery(this).val();
        var no_int=parseInt(no);
        var stock=/^\d+(\.\d{1,2})?$/;
        a=no_int;
        if(no==no_int)
          a=no_int;
        if(jQuery(this).val()!=''){
          jQuery(this).val(a);
        }else{
          jQuery(this).val('');
          a=0;
        }
      });
//product name valdiation
      jQuery('#product_name').blur(function(){
        var product_name=jQuery('#product_name').val();
        var ck_name = /^[A-Za-z0-9 _-]{1,40}$/;
        if(product_name==''){
			jQuery('#pro_name_error').html('Please Enter Product Name!!!');
			return false;
        }else if(!ck_name.test(product_name))
        {
          jQuery('#pro_name_error').html('Product name should be larger than 3 character');
          return false;
        }
        else
        {
          jQuery('#pro_name_error').html('');
        }
      });

//product regular price validation
      jQuery('#regu_price').blur(function(){
          var regu_price=jQuery('#regu_price').val();
          var price=/^\d+(\.\d{1,2})?$/;
          if(!price.test(regu_price))
          {
          jQuery('#regl_pr_error').html('Product price accepts numerical value upto 2 decimal points');
          return false;
          }
          else
          {
          jQuery('#regl_pr_error').html('');
          }
      });

//product sale price validation
      jQuery('#sale_price').blur(function(){
        var sale_price=jQuery('#sale_price').val();
        var price=/^\d+(\.\d{1,2})?$/;
        var regular=parseInt(jQuery('#regu_price').val());
        var sale=parseInt(jQuery('#sale_price').val());
        if(jQuery('#sale_price').val()!=''){
          if(!price.test(sale_price))
          {
            jQuery('#sale_pr_error').html('Product price accepts numerical value upto 2 decimal points');
          return false;
          }else if(sale>regular){
            jQuery('#sale_pr_error').html('Sale Price cannot be greater than Regular Price**');
            return false;
          }else
          {
            jQuery('#sale_pr_error').html('');
          }
        }
      });
// product validtion end

// profile update validation
      jQuery('#update_profile_submit').click(function() {
        var user_name = /^[A-Za-z0-9_-]{1,40}$/;
        var first_name=jQuery('#wk_firstname').val();
        var last_name=jQuery('#wk_lastname').val();
        if(!user_name.test(first_name))
        {
        jQuery('#first_name_error').html('Name should be larger than 2 character');
        return false;
        }
        else
        {
        jQuery('#first_name_error').html('');
        }
        if(!user_name.test(last_name))
        {
        jQuery('#last_name_error').html('Last name is not valid');
        return false;
        }
        else
        {
        jQuery('#last_name_error').html('');
        }
        jQuery('#user_profile_form').submit();
      });
    //  stock quantity only integer
	jQuery('#wk-mp-stock-qty').on('blur',function(){
		var no=jQuery('#wk-mp-stock-qty').val();
    var format = /^[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/;
    var letters = /^[a-zA-Z]+$/;
    currentelm=jQuery(this);
    if(no.match(format) !=null){
      currentelm.val('');
    }
    else if(no.match(letters) !=null){
      currentelm.val('');
    }
    else{
  		var no_int=parseInt(no);
      a=no_int;
  		if(no==no_int)
  			a=no_int;
  		if(currentelm.val()!=''){
  			currentelm.val(a);
  		}else{
  			currentelm.val('0');
  			a=0;
  		}
    }
	});

//name validation alert on out focus
      jQuery('#wk_firstname').blur(function(){
        var firstn=jQuery('#wk_firstname').val();
        // var user_name = /^[A-Za-z0-9_-]{1,40}$/;
        if(firstn==''){
        	jQuery('#seller_first_name').html('Field left blank!!!');
            checkuser=0;
        }else{
            jQuery('#seller_first_name').html('');
          }
        });

//last name validation

jQuery('#wk_lastname').blur(function(){
        var lastn=jQuery('#wk_lastname').val();
        if(lastn==''){
        	jQuery('#seller_last_name').html('Field left blank!!!');
            checkuser=0;
        }else{
            jQuery('#seller_last_name').html('');
          }
      });

//existing user validation
      jQuery('#wk_username').blur(function(){
        var seller_login=jQuery('#wk_username').val();
        var a=0;
        if(seller_login==''){
        	jQuery('#seller_user_name').html('Seller User Name is not valid');
        }else{
          jQuery('#seller_user_name').html('');
        }
        jQuery.ajax({
          type: 'POST',
          url: the_mpajax_script.mpajaxurl,
          data: {"action": "existing_user", "exist_user":seller_login,"nonce":the_mpajax_script.nonce},
          success: function(data){
          if(data==1 && a==0)
          {
          	if(seller_login!=''){
          		jQuery('#seller_user_name').html('<span style="color:green;">user name available</span>');
        		checkuser=1;
          	}
          }
          else if(a==0)
          {
	          jQuery('#seller_user_name').html('User Name Already Taken');
	          checkuser=0;
          }
          }
        });
      });

      jQuery('#org-name').on('focusout', function() {
          var value = jQuery(this).val().toLowerCase().replace(/-+/g, '').replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
          jQuery('#seller-shop').val(value);
      });

//validation for email ids
	jQuery('#wk_useremail').blur(function()
	{
		var seller_email=jQuery('#wk_useremail').val();
        var email_reg= /^[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}$/igm;
        if(seller_email==''){
        	jQuery('#seller_email').html('Cannot Leave Field Blank');
        }else if(!email_reg.test(seller_email)) {
          jQuery('#seller_email').html('Email Adress is not Valid');
          checkuser=0;
        }else
        {
			jQuery('#seller_email').html('');
        }
        jQuery.ajax({
			type: 'POST',
			url: the_mpajax_script.mpajaxurl,
			data: {"action": "seller_email_availability", "seller_email":seller_email,"nonce":the_mpajax_script.nonce},
			success: function(data){
            //jQuery('#seller_email').html(data);
			if(data==1)
			{
				jQuery('#seller_email').html('Email Id Already Registered');
				checkuser=0;
			}
        }
        });
      });
//registration validation
	jQuery('#registration_form').submit(function()
	{
		var user_name=/^[A-Za-z0-9_-]{1,40}$/;
		var shop_name=/^[A-Za-z0-9_-]{1,40}$/;
		var login_name = /^[a-zA-Z](([\._\-][a-zA-Z0-9])|[a-zA-Z0-9])*[a-z0-9]$/;
		var email_reg=/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/;
		var seller_login=jQuery('#wk_username').val();
		var first_name=jQuery('#wk_firstname').val();
		var last_name=jQuery('#wk_lastname').val();
		var email=jQuery('#wk_useremail').val();
		var seller=jQuery("input[name='role']:checked").val();
    if (jQuery('#seller_user_name').text()=='user name already taken') {
      jQuery('#wk_username').trigger('blur');
      return false;
    }
			jQuery('#seller_user_name').html('');

		if (jQuery('#seller_email').html()!='') {
			jQuery('#wk_useremail').trigger('blur');
			return false;
		}


          jQuery('#seller_first_name').html('');


       	  jQuery('#seller_last_name').html('');

          if(!email_reg.test(email))
          {
          jQuery('#seller_email').html('email adress is not valid');
          return false;
          }
          else
          {
          jQuery('#seller_email').html('');
          }
          if(seller!=1 && seller!=0)
          {
          jQuery('#select-seller_access').html('select seller option');
          return false;
          }else
          {
          	jQuery('#select-seller_access').html('');
            if(seller==1)
            {
            var store=jQuery('#wk_storename').val();
            var user_address=jQuery('#wk_user_address').val();
              if(store.length<1)
              {
              jQuery('#seller_storename').html('seller store name is too short,contain white space or empty');
              return false;
              }
              else{
              jQuery('#seller_storename').html('');
              }
              if(user_address.length<1)
              {
              jQuery('#seller_user_address').html('address is too short or empty');
              return false;
              }
              else{
              jQuery('#seller_user_address').html('');
              }
            }
          jQuery('#select-seller_access').html('');
          }
	});
// Checking storename
	jQuery(document).on('blur','#wk_storename',function(){
		var store=jQuery('#wk_storename').val();
		if(store.length<1)
        {
        	jQuery('#seller_storename').html('seller store name is too short,contain white space or empty');
        	return false;
        }
	});


// ask to admin validation

      jQuery('.wkmp-close,.wk-ask-close').on('click',function(){

        jQuery('#ask-data').hide();

        base_url=jQuery('#base_url').val();

        var redirecturl = the_mpajax_script.site_url + '/' + the_mpajax_script.seller_page + '/profile';

        jQuery(location).attr('href',redirecturl);

      });

      jQuery('#resetbtn').on('click',function(){

      jQuery('#askesub_error').html('');

      jQuery('#askquest_error').html('');

      });

    jQuery('#asktoaddbtn').on('click',function(event)
    {
      event.preventDefault();
    	var sub_reg = /^[A-Za-z0-9 ]{1,100}$/;
    	var usersub=jQuery('#query_user_sub').val();
    	var userQuery=jQuery('#userquery').val();
    	var checkuser=0;
      usersub=jQuery.trim(usersub);
      userQuery=jQuery.trim(userQuery);
      jQuery('#askquest_error').html('');
      jQuery('#askesub_error').html('');
		if(!sub_reg.test(usersub))
	    {
	    	jQuery('#askesub_error').html('Subject field can not be blank');
        return false;
	    	/*checkuser=0;*/
	    }
	    if(userQuery.length<10 || userQuery.length>500)
	    {
	    	jQuery('#askquest_error').html('Ask Your Question (characters should be greater than 10)');
        return false;
	    	/*checkuser=0;*/
	    }
      jQuery('#ask-form').submit();
	});

//ask to admin end

//add product status

jQuery('.mp-toggle-sider-edit,.mp-toggle-save').on('click',function(){
  var status=jQuery('#product_post_status').val();
  if(status=='publish')
  {
    jQuery('.mp-toggle-selected-display').html('Online');
  }
  else
  {
    jQuery('.mp-toggle-selected-display').html(status);
  }

  jQuery('.wkmp-toggle-select-container').toggle()
  });
  jQuery('a.mp-toggle-cancel').on('click',function(){
  jQuery('.wkmp-toggle-select-container').hide();
});

//product type sidebar
  var product_type=jQuery('#product_type').val();
  var var_type=jQuery('#var_variation_display').val();
  if(product_type=='variable' && var_type=='yes')
  {
    jQuery( "#edit_product_tab li" ).eq(6).show();
  }




jQuery(document).on('change','body #product_type', function() {
  var product_type=jQuery('#product_type').val();
  var var_type=jQuery('#var_variation_display').val();
  if(product_type=='variable' && var_type=='yes')
    {
      jQuery( "#edit_product_tab li" ).eq(6).show();
    }
    else
    {
      	jQuery( "#edit_product_tab li" ).eq(6).hide();
    }

    if(product_type=='variable'){
        jQuery('#regu_price').attr("disabled", true);
        jQuery('#sale_price').attr("disabled", true);
    }else{
        jQuery('#regu_price').attr("disabled", false);
        jQuery('#sale_price').attr("disabled", false);
    }

    if(product_type=='grouped') {
        jQuery('#regu_price').attr("disabled", true);
        jQuery('#sale_price').attr("disabled", true);
    }else{
        jQuery('#regu_price').attr("disabled", false);
        jQuery('#sale_price').attr("disabled", false);
    }

    if(product_type=='external')
    {
      jQuery( "#edit_product_tab li" ).eq(5).show();
    }

    else {
      jQuery( "#edit_product_tab li" ).eq(5).hide();
    }
});
  jQuery('a.mp-toggle-type-cancel').on('click',function(){
  jQuery('.mp-toggle-select-type-container').css('display','none');
});
jQuery('.mp_value_asc').change(function(){
var str=jQuery(this).val();
var newUrl=window.location.href+'&'+str;
window.location=newUrl;
});
//downloadable check
jQuery('#_ckdownloadable').change(function(){
jQuery('.wk-mp-side-body').slideToggle( "slow");
});

/***********Seller multiple downloadable files starts***********/

jQuery( '.wk-mp-side-body' ).on( 'click','.downloadable_files a.insert', function() {
  jQuery( this ).closest( '.downloadable_files' ).find( 'tbody' ).append( jQuery( this ).data( 'row' ) );
  return false;
});

jQuery( '.wk-mp-side-body' ).on( 'click','.downloadable_files a.delete',function() {
  jQuery( this ).closest( 'tr' ).remove();
  return false;
});

/***********Seller multiple downloadable files ends***********/

jQuery(document).on('change','.checkbox_is_virtual',function(){
	jQuery(this).parents('tbody').children('tr').eq(0).find('.virtual').slideToggle('fast');
});
jQuery(document).on('change','.checkbox_is_downloadable',function(){
	jQuery(this).parents('tbody').children('tr').eq(0).find('.downloadable').slideToggle('fast');
});
jQuery(document).on('change','.checkbox_manage_stock',function(){
	jQuery(this).parents('tbody').children('tr').eq(0).find('.wkmp_stock_status').slideToggle('fast');
});
// // upload button to downloadable button file
// jQuery('.upload_downloadable_file').on('click',function(){
// jQuery('.upload_downloadable_file_type').trigger('click');
// });

// //  upload file name handler
// jQuery('.upload_downloadable_file_type').change(function () {
//     console.log(this.files[0].mozFullPath);
// });

// upload file name handler

//upload button for product image file
jQuery('.add-mp-product-images').on('click',function(event){
var file_frame;
var image_id=jQuery(this).attr('id');
var image_id_field=jQuery('#product_image_Galary_ids').val();
var galary_ids='';
if(image_id_field=='')
{
  galary_ids='';
}
else
{
galary_ids=image_id_field+',';
}
//var image_url_set_id='wkmp_variation_product_'+image_id;
event.preventDefault();
// If the media frame already exists, reopen it.
if ( file_frame ) {
  file_frame.open();
  return;
}

// Create the media frame.
file_frame = wp.media.frames.file_frame = wp.media({
  title: jQuery( this ).data( 'uploader_title' ),
  button: { text: jQuery( this ).data( 'uploader_button_text' ) },
  multiple: false  // Set to true to allow multiple files to be selected
});

   // When frame is open, select existing image attachments from custom field
file_frame.on( 'open', function() {
var selection = file_frame.state().get('selection');
 });
var query = wp.media.query();

query.filterWithIds = function(ids) {
    return _(this.models.filter(function(c) { return _.contains(ids, c.id); }));
};

  // When images are selected, place IDs in hidden custom field and show thumbnails.
file_frame.on( 'select', function() {

var selection = file_frame.state().get('selection');

// Place IDs in custom field

var attachment_ids = selection.map( function( attachment ) {
  attachment = attachment.toJSON();
  galary_ids=galary_ids+attachment.id;
  jQuery('#product_image_Galary_ids').val(galary_ids);
  // jQuery('#'+image_val_id).val(attachment.id);
 jQuery('#handleFileSelectgalaray').append("<img src='"+attachment.sizes.thumbnail.url+"' width='50' height='50'/>");
  return attachment.id;
})
//alert(attachment_ids);
});

// Finally, open the modal
file_frame.open();
});

/* mp thumb image */
jQuery('.mp_product_thumb_image').on('click',function(event){
var file_frame;
//var image_url_set_id='wkmp_variation_product_'+image_id;
event.preventDefault();
// If the media frame already exists, reopen it.
if ( file_frame ) {
  file_frame.open();
  return;
}

// Create the media frame.
file_frame = wp.media.frames.file_frame = wp.media({
  title: jQuery( this ).data( 'uploader_title' ),
  button: { text: jQuery( this ).data( 'uploader_button_text' ) },
  multiple: false  // Set to true to allow multiple files to be selected
});

   // When frame is open, select existing image attachments from custom field
file_frame.on( 'open', function() {
var selection = file_frame.state().get('selection');
 });
var query = wp.media.query();

query.filterWithIds = function(ids) {
    return _(this.models.filter(function(c) { return _.contains(ids, c.id); }));
};

  // When images are selected, place IDs in hidden custom field and show thumbnails.
file_frame.on( 'select', function() {

var selection = file_frame.state().get('selection');

// Place IDs in custom field

var attachment_ids = selection.map( function( attachment ) {
  attachment = attachment.toJSON();

  jQuery('#product_thumb_image_mp').val(attachment.id);
  // jQuery('#'+image_val_id).val(attachment.id);
 jQuery('#product_image').html("<img src='"+attachment.sizes.thumbnail.url+"' width='50' height='50'/>");
 return attachment.id;
})
//alert(attachment_ids);
});

// Finally, open the modal
file_frame.open();
});

/* mp thumb image end */


  // tabs on edit product page
  jQuery('#edit_product_tab li a:not(:first)').addClass('inactive');
  jQuery('.wkmp_container').hide();
  jQuery('.wkmp_container:first').show();

  jQuery('#edit_product_tab li a').click(function(){
    var t = jQuery(this).attr('id');
    if(jQuery(this).hasClass('inactive')){ //this is the start of our condition
      jQuery('#edit_product_tab li a').addClass('inactive');
      jQuery(this).removeClass('inactive');

      jQuery('.wkmp_container').hide();
      jQuery('#'+ t + 'wk').fadeIn('slow');
    }
  });
  jQuery('#edit_notification_tab li a').click(function(){
    var t = jQuery(this).attr('id');
    if(jQuery(this).hasClass('inactive')){ //this is the start of our condition
      jQuery('#edit_notification_tab li a').addClass('inactive');
      jQuery(this).removeClass('inactive');

      jQuery('.wkmp_container').hide();
      jQuery('#'+ t + 'wk').fadeIn('slow');
    }
  });
  //attribute dynamic fields
    var wrapper         = jQuery(".wk_marketplace_attributes"); //Fields wrapper
    var add_button      = jQuery(".add-variant-attribute"); //Add button ID
    var attribute_no   = jQuery("div.wk_marketplace_attributes > div.wkmp_attributes").length;
    var x = attribute_no;
    jQuery(document).on('click','.add-variant-attribute',function(e){ //on add input button click
	   e.preventDefault();
    jQuery(wrapper).append('<div class="wkmp_attributes"><div class="box-header attribute-remove"><input type="text" class="mp-attributes-name" placeholder="Attribute name" name="pro_att['+x+'][name]" value=""/><input type="text" class="option" title="attribue value by seprating comma eg. a|b|c" placeholder="Attribute Value eg. a|b|c" name="pro_att['+x+'][value]" /><input type="hidden" name="pro_att['+x+'][position]" class="attribute_position" value="1"/><span class="mp_actions"><button class="mp_attribute_remove btn btn-danger">Remove</button></span></div><div class="box-inside clearfix"><div class="wk-mp-attribute-config"><div class="wkmp-checkbox-inline"><input type="checkbox" class="checkbox" name="pro_att['+x+'][is_visible]" value="1"/>Visible on the product page</div>  <div class="wkmp-checkbox-inline"><input type="checkbox" class="checkbox" name="pro_att['+x+'][is_variation]" id="product_att_varition_'+x+'" value="1"/>Used for variations</div><input type="hidden" name="pro_att['+x+'][is_taxonomy]" value="0"/></div><div class="attribute-options"></div></div></div>');
    x++;
    });

    jQuery(wrapper).on("click",".mp_attribute_remove", function(e){ //user click on remove text
        e.preventDefault();
    jQuery(this).parent().parent().parent().remove();
    })

jQuery('.wkmp_variation_downloadable_file').on("click",'.mp_var_del',function(){
  var del_id=jQuery(this).attr('id');
jQuery('#'+del_id).parent().parent().remove();
});


jQuery('#mp_attribute_variations').on("click",".upload_image_button",function(){
var file_type_id=jQuery(this).attr('id')+'upload';
jQuery('#'+file_type_id).trigger('click');
})

jQuery(document).on("click",'#mp_attribute_variations div.wkmp_variation_downloadable_file .wkmp_downloadable_upload_file',function(event){
  event.preventDefault();
  var trigger_id=jQuery(this).attr('id');
  // var up_id=trigger_id.split('_');
  // var upload_file_id='downloadable_upload_file_'+up_id[0];
  var text_box_file_url='downloadable_upload_file_url_'+trigger_id;
  var file_frame;
 // If the media frame already exists, reopen it.
if ( file_frame ) {
  file_frame.open();
  return;
}

// Create the media frame.
file_frame = wp.media.frames.file_frame = wp.media({
  title: jQuery( this ).data( 'uploader_title' ),
  button: { text: jQuery( this ).data( 'uploader_button_text' ) },
  multiple: false  // Set to true to allow multiple files to be selected
});
   // When frame is open, select existing image attachments from custom field
file_frame.on( 'open', function() {
var selection = file_frame.state().get('selection');
//var attachment_ids = jQuery('#attachment_ids').val().split(',');
 });
var query = wp.media.query();

query.filterWithIds = function(ids) {
    return _(this.models.filter(function(c) { return _.contains(ids, c.id); }));
};

var res = query.filterWithIds([3]); // change these to your IDs

res.each(function(v){
    console.log( v.toJSON() );
});

  // When images are selected, place IDs in hidden custom field and show thumbnails.
file_frame.on( 'select', function() {

var selection = file_frame.state().get('selection');
//console.log(selection);
// Place IDs in custom field
var attachment_ids = selection.map( function( attachment ) {
  attachment = attachment.toJSON();
  jQuery('#'+text_box_file_url).val(attachment.url);
  return attachment.id;
});
});

// Finally, open the modal
file_frame.open();
});

  // variation attribute


  // multiple thumb image upload and view
  function handleFileSelect(evt)
  {
    jQuery('#product_image').empty();
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++)
  {
    // Only process image files.
    if (!f.type.match('image.*'))
    {
      continue;
    }
    var reader = new FileReader();
    // Closure to capture the file information.
    reader.onload = (function(theFile){
      return function(e)
      {
        // Render thumbnail.
        var div = document.createElement('div');
        //jQuery(div).attr({class:'ingdiv'});
        div.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/><span class="wkmp_image_over" ></span><input type="hidden" name ="mpthumbimg[]" value="',escape(theFile.name),'">'].join('');
        document.getElementById('product_image').insertBefore(div, null);
        jQuery('#product_image div').attr({class:'imgdiv'});
        wk_imgview();
      };
        })(f);
    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
    }
    function wk_imgview()
  {
    jQuery('div.imgdiv').mouseover(function(event){
        //alert('Hello div');
       jQuery(this).find(".wkmp_image_over").css({display:"block"});
        jQuery(this).find("img").css("opacity","0.4");    jQuery(this).find(".wkmp_image_over").on('click', function () {            jQuery(this).parent("div").remove();          });
    });

    jQuery("div.imgdiv").mouseout(function(event){
        jQuery(this).find(".wkmp_image_over").css({display:"none"});
        jQuery(this).find("img").css("opacity","1");
    });
  }
  }

   // multiple galary image upload and view
  function handleFilegalaray(evt)
  {
    jQuery('#handleFileSelectgalaray').empty();
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++)
  {
    // Only process image files.
    if (!f.type.match('image.*'))
    {
      continue;
    }
    var reader = new FileReader();
    // Closure to capture the file information.
    reader.onload = (function(theFile){
      return function(e)
      {
        // Render thumbnail.
        var div = document.createElement('div');        div.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/><span class="wkmp_image_over" ></span><input type="hidden" name ="mpproductgall[]" value="',escape(theFile.name),'">'].join('');
        document.getElementById('handleFileSelectgalaray').insertBefore(div, null);
        jQuery('#handleFileSelectgalaray div').attr({class:'imgdiv'});
        wk_imgview();
      };
        })(f);
    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
    }
    function wk_imgview()
  {
    jQuery('div.imgdiv').mouseover(function(event){
        //alert('Hello div');
        jQuery(this).find(".wkmp_image_over").css({display:"block"});
        jQuery(this).find("img").css({"opacity":"0.4"});
  // For Delete the image  Div at Click on Cross Icon
          jQuery(this).find(".wkmp_image_over").on('click', function () {
            jQuery(this).parent("div").remove();
          });

  });

    jQuery("div.imgdiv").mouseout(function(event){
       jQuery(this).find(".wkmp_image_over").css({display:"none"});
       jQuery(this).find("img").css({"opacity":"1"});
    });

  }
  }



  /* function to change profile image */
  function changeprofile_image(evt)
  {
    jQuery('#mp_seller_image').empty();
    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++)
  {
    if (!f.type.match('image.*'))
    {
      continue;
    }
    var reader = new FileReader();
    reader.onload = (function(theFile){
      return function(e)
      {
        var div = document.createElement('div');
        div.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/><span class="wkmp_image_over" ></span><input type="hidden" name ="mpthumbimg[]" value="',escape(theFile.name),'">'].join('');
        document.getElementById('mp_seller_image').insertBefore(div, null);
        jQuery('#mp_seller_image div').attr({class:'imgdiv'});
      };
        })(f);
    reader.readAsDataURL(f);
    }
  }
  /* change profile image change end */

  /* function to change banner image */
  function changeseller_bannerimage(evt)
  {
    jQuery('#wk_seller_banner').empty();

    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++)
  {
    if (!f.type.match('image.*'))
    {
      continue;
    }
    var reader = new FileReader();
    reader.onload = (function(theFile){
      return function(e)
      {
        var div = document.createElement('div');
        div.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/><span class="wkmp_image_over" ></span><input type="hidden" name ="mpthumbimg[]" value="',escape(theFile.name),'">'].join('');
        document.getElementById('wk_seller_banner').insertBefore(div, null);
        jQuery('#wk_seller_banner div').attr({class:'imgdiv'});
      };
        })(f);
    reader.readAsDataURL(f);
    }
  }
  /* change banner image change end */

  /* seller logo image */
  function seller_logo_image(evt)
  {
    jQuery('#seller_com_logo_img').empty();

    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++)
  {
    if (!f.type.match('image.*'))
    {
      continue;
    }
    var reader = new FileReader();
    reader.onload = (function(theFile){
      return function(e)
      {
        var div = document.createElement('div');
        div.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/><span class="wkmp_image_over" ></span><input type="hidden" name ="mpthumbimg[]" value="',escape(theFile.name),'">'].join('');
        if(document.getElementById('seller_com_logo_img')!=''){
          document.getElementById('seller_com_logo_img').insertBefore(div, null);
        }
        jQuery('#seller_com_logo_img div').attr({class:'imgdiv'});
      };
        })(f);
    reader.readAsDataURL(f);
    }
  }
  /* seller logo change */

  jQuery('.mp_product_images_file').click(function(){
  document.getElementById('upload_attachment_image').addEventListener('change', handleFilegalaray, false);
  });
    jQuery('.mp_product_thumb_image').click(function(){
      if(typeof document.getElementById('product_thumb_image') !== 'undefined' && document.getElementById('product_thumb_image') !== null){
         document.getElementById('product_thumb_image').addEventListener('change', handleFileSelect, false);
    }
    /*sdsd*/
    /*if(document.getElementById('product_thumb_image')!=''){
       document.getElementById('product_thumb_image').addEventListener('change', handleFileSelect, false);
    }*/
  });
  jQuery('.mp_seller_profile_img').click(function(){
  document.getElementById('mp_useravatar').addEventListener('change', changeprofile_image, false);
  });
  jQuery('.wkmp-fade-banner').click(function(){
  document.getElementById('wk_mp_shop_banner').addEventListener('change', changeseller_bannerimage, false);
  });
  jQuery('.Company_Logo').click(function(){
  document.getElementById('mp_company_logo').addEventListener('change', seller_logo_image, false);
  });

  //multiple image upload and view end

/* user login from seller profile to commect seller profile   */
jQuery('a.wk_mpsocial_feedback').on('click',function(){
var seller_shop = jQuery('#feedbackloged_in_status').val();
  if(jQuery('#feedbackloged_in_status').val())
  {
    var redirecturl = the_mpajax_script.site_url+'/'+the_mpajax_script.seller_page+'/feedback/'+seller_shop;
    jQuery(location).attr('href',redirecturl);
  }
  else{
    jQuery('.wkmp_feedback_popup').css('display','block');
    jQuery('<div class="wkmp-modal-backdrop">&nbsp;</div>').appendTo('body');
  }
//jQuery('.wk_feedback_popup_back').css('display','block');
});
  jQuery('.Give_feedback,.wkmp_login_to_feedback').click(function()
  {
    jQuery(this).css('display','none');
    jQuery('#Mp_feedback').css('display','block');
  });
jQuery('.wkmp_feedback_popup .wkmp_cross_login').on('click',function(){
  jQuery('#username_error').html('');
  jQuery('#wk_password_error').html('');
jQuery('div.wkmp-modal-backdrop').remove();
jQuery('.wkmp_feedback_popup').css('display','none');

});
});


/*administrator ajax---------------------*/

jQuery(document).ready(function(){

/* seller product sorting */
      jQuery('.mp_value_asc').change(function()
      {
      var hashes = window.location.href.split('&str=');
      var str=jQuery(this).val();
      if(hashes[0]!='')
      {
      window.location=hashes[0]+'&str='+str;
      }
      else
      {
      window.location=window.location.href+'&str='+str;
      }
      });

      jQuery('#submit-btn-feedback').on('click',function(){
        jQuery('#username_error').html('');
        jQuery('#wk_password_error').html('');
        if(jQuery('#username').val()=='')
        {
          html = '<span class="error">Please Enter username or email address</span>';
          jQuery('#username_error').html(html);
          jQuery('#username').focus();
          return false;
        }
        else if(jQuery('#password').val()=='')
        {
          html = '<span class="error">Please Enter username or email address</span>';
          jQuery('#wk_password_error').html(html);
          jQuery('#password').focus();
          return false;
        }
      });



      jQuery(document).ready(function(){

        var fb_app_id=jQuery('#wkfb_mp_key_app_idID').val();
        var fb_app_key=jQuery('#wkfb_mp_app_secret_kekey').val();
          window.fbAsyncInit = function()
            {
              FB.init({ appId:fb_app_id,
              status: true,
              cookie: true,
              xfbml: true,
              oauth: true});


              };
              (function() {
                elmFb=document.getElementById('fb-root');
                var e = document.createElement('script'); e.async = true;
                e.src = document.location.protocol+ '//connect.facebook.net/en_US/all.js';
                if(elmFb != null)
                  elmFb.appendChild(e);
              }());
      });



    /* hide downloadable area on edit product page */

    var download_check = jQuery(".checkbox_is_downloadable");
    download_check.each(function() {
    });
    if(jQuery('#wkmp_variable_is_downloadable:checkbox:checked'))
    {
      jQuery('.mpshow_if_variation_downloadable').css('display','table-row');
    }
    else
    {
      jQuery('.mpshow_if_variation_downloadable').css('display','none');
    }


    jQuery('#wkmp_variable_is_downloadable').change(function(){
     if(this.checked){
           jQuery('.mpshow_if_variation_downloadable').css('display','table-row');
     }
     else
     {
           jQuery('.mpshow_if_variation_downloadable').css('display','none');
     }

    });
    /* hide downloadable area on edit product page */




    /* hide virtual area on edit product page */

    if(jQuery('#wkmp_variable_is_virtual:checkbox:checked'))
    {

      jQuery('.mp_hide_if_variation_virtual').css('display','table-cell');
    }
     else
     {
           jQuery('.mp_hide_if_variation_virtual').css('display','none');

     }


    jQuery('#wkmp_variable_is_virtual').change(function(){
     if(this.checked){
           jQuery('.mp_hide_if_variation_virtual').css('display','table-cell');
     }
     else
     {
           jQuery('.mp_hide_if_variation_virtual').css('display','none');
     }

    });
    /* hide virtual area on edit product page */


/* hide manage stock area on edit product page */
/*
    if(jQuery('#wkmp_variable_manage_stock:checkbox:checked'))
    {
      jQuery('.mpshow_if_variation_manage_stock').css('display','table-row');
    }
    else
    {
		jQuery('.mpshow_if_variation_manage_stock').css('display','none');
    }

    jQuery('#wkmp_variable_manage_stock').change(function(){
     if(this.checked){
           jQuery('.mpshow_if_variation_manage_stock').css('display','table-row');
     }
     else
     {
           jQuery('.mpshow_if_variation_manage_stock').css('display','none');
     }
    });*/
    /* hide manage stock area on edit product page */

    /* show sale schedule*/
    jQuery(document).on("click",'.mp_sale_schedule',function(){
      // jQuery('.mp_sale_schedule').css('display','none');
      jQuery(this).css('display','none');
      // jQuery('.mp_cancel_sale_schedule').css('display','block');
      jQuery(this).siblings('.mp_cancel_sale_schedule').css('display','block');
      // jQuery('.mp_sale_price_dates_fields').css('display','block');
      jQuery(this).parents('tr').siblings('.mp_sale_price_dates_fields').css('display','table-row');
    });
    jQuery(document).on("click",'.mp_cancel_sale_schedule',function(){
      // jQuery('.mp_cancel_sale_schedule').css('display','none');
      jQuery(this).css('display','none');
      // jQuery('.mp_sale_schedule').css('display','block');
      jQuery(this).siblings('.mp_sale_schedule').css('display','block');
      // jQuery('.mp_sale_price_dates_fields').css('display','none');
      jQuery(this).parents('tr').siblings('.mp_sale_price_dates_fields').css('display','none');
    });
/* ------------------------------------------downloadable product Image----------------------------------*/

jQuery('#mp_attribute_variations').on('click','td.wkmp_upload_image_variation a.upload_var_image_button', function( event ){
var file_frame;
var image_id=jQuery(this).attr('id');
var image_val_id='upload_'+image_id;
var image_url_set_id='wkmp_variation_product_'+image_id;
event.preventDefault();
// If the media frame already exists, reopen it.
if ( file_frame ) {
  file_frame.open();
  return;
}

// Create the media frame.
file_frame = wp.media.frames.file_frame = wp.media({
  title: jQuery( this ).data( 'uploader_title' ),
  button: { text: jQuery( this ).data( 'uploader_button_text' ) },
  multiple: false  // Set to true to allow multiple files to be selected
});

   // When frame is open, select existing image attachments from custom field
file_frame.on( 'open', function() {
var selection = file_frame.state().get('selection');
 });
var query = wp.media.query();

query.filterWithIds = function(ids) {
    return _(this.models.filter(function(c) { return _.contains(ids, c.id); }));
};

  // When images are selected, place IDs in hidden custom field and show thumbnails.
file_frame.on( 'select', function() {

var selection = file_frame.state().get('selection');

// Place IDs in custom field
var attachment_ids = selection.map( function( attachment ) {
  attachment = attachment.toJSON();
  jQuery('#'+image_val_id).val(attachment.id);
  jQuery('#'+image_url_set_id).attr("src", attachment.sizes.thumbnail.url);
  return attachment.id;
})
});

// Finally, open the modal
file_frame.open();
});

/* product status downloadable file */

var file_path_field;

jQuery( '.wk-mp-side-body' ).on( "click", '.upload_downloadable_file', function( event )
{
    var file_frame;

    var $el = jQuery( this );

    file_path_field = $el.closest( 'tr' ).find( 'td.file_url input' );

    event.preventDefault();

   // If the media frame already exists, reopen it.
   if ( file_frame )
   {
      file_frame.open();
      return;
   }

   // Create the media frame.
   file_frame = wp.media.frames.file_frame = wp.media({
       title: $el.data('choose'),
       button: {
          text: $el.data('update')
       },
       multiple: false  // Set to true to allow multiple files to be selected
   });

   // When frame is open, select existing image attachments from custom field

   file_frame.on( 'open', function() {
      var selection = file_frame.state().get('selection');
      //var attachment_ids = jQuery('#attachment_ids').val().split(',');
   });

   var query = wp.media.query();

   query.filterWithIds = function(ids) {
      return _(this.models.filter(function(c) { return _.contains(ids, c.id); }));
   };

   var res = query.filterWithIds([3]); // change these to your IDs

   res.each(function(v){
      console.log( v.toJSON() );
   });

   // When images are selected, place IDs in hidden custom field and show thumbnails.

   file_frame.on( 'select', function() {
      var file_path = '';
      var selection = file_frame.state().get('selection');

      // Place IDs in custom field

      var attachment_ids = selection.map( function( attachment ) {
          attachment = attachment.toJSON();
          if ( attachment.url ) {
  					file_path = attachment.url;
  				}
          file_path_field.val( file_path ).change();
          return attachment.id;
      });
   });

   // Finally, open the modal

   file_frame.open();
});

jQuery(".select-group .dropdown-togle").on("click",function(){

    jQuery(this).parent().toggleClass("open");

});

jQuery(document).on("click",".group-selected a" ,function() {
    if(jQuery(".select-group .group-dropdown-menu").hasClass('open'))
      jQuery(".select-group .group-select").removeClass('open');
    var attr = jQuery(this).data('group-id');
    $grp_name = jQuery(this).text().trim();

    if (typeof attr !== typeof undefined && attr !== false) {
        $val = attr;
        jQuery("input[name='group_id']").val($val);
    }

    jQuery("span.filter-option").text($grp_name);
  });


});


/*----------->>> Select 2 <<<----------*/
jQuery(document).on("ready", function(){
    if(jQuery("#mp_seller_product_categories").length ) {
        jQuery("#mp_seller_product_categories").select2();
    }
});
