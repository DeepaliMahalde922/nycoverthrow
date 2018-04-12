(function($){
	$.fn.VCCssElementPadding = function(){
			this.each(function(){
				var $wrap 		= $(this),
						$result 	= $wrap.find('input.cf-hidden');

						var val = $result.val().length?$result.val():'[]';
						var itemsJSON = JSON.parse(decodeURIComponent(val));	
							
							if(itemsJSON.length) {
								for (var i = 0; i < itemsJSON.length; i++) {
									var input = $wrap.find('[data-name="'+itemsJSON[i].name+'"]');

									if(input.is('[type="checkbox"]') || input.is('[type="radio"]')) {
										input.prop('checked', JSON.parse(itemsJSON[i].value))
									} else {
										input.val(itemsJSON[i].value);
									}
								}
							}
						
						$wrap.find('.cf-input').unbind('change.cfChange');
		   			$wrap.delegate('.cf-input','change.cfChange', createJSON);

						function createJSON(){
							var res = [];

							$wrap.find('.cf-input').each(function(){
								var item = {};
								item['name'] = $(this).data('name');

								if($(this).is('[type="checkbox"]') || $(this).is('[type="radio"]') ) {
									item['value'] = $(this).prop('checked');
								} else {
									item['value'] = $(this).val();
								}
								res.push(item)
							});
							$result.val(encodeURIComponent(JSON.stringify(res)));
						}
				})
			}

		$(document).find().ready(function(){
			$('.vc_css-element_padding').VCCssElementPadding();
		})
})(jQuery)