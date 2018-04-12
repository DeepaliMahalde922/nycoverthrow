/*(function(wk){
	wk(document).on('change','#mp_seller_payment_method',function(){
		var payment_method=wk(this).val();
		if(payment_method=='Credit Card (Stripe Connect)'){
			var html = '<input type="button" name="stripe_connect_button" id="stripe_connect_button" value="Stripe Connect">';
			wk(this).parent('.social-seller-input').append(html);
		}else{
			wk('#stripe_connect_button').remove();
		}
		
	});
	wk(document).on('click','#stripe_connect_button',function(){

	});
})(jQuery);*/