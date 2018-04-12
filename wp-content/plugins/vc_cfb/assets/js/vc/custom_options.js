(function($){
	$.fn.characteristicsInit = function(){
			this.find('.ch-wrap').each(function(){
				var $wrap 		= $(this),
					$list 		= $wrap.find('.ch-list'),
					$result 	= $wrap.find('input.ch-hidden'),
					$addBtn 	= $wrap.find('.ch-add'),
					itemClass 	= '.ch-item',
					$innerTemplate = $('<div></div>').append($wrap.find('.ch-template').html());

					$innerTemplate.find('input, select, textarea').addClass('ch-input');



					var $template 	= $('<div class="ch-item cfix">' +
										        ' <div class="ch-num"><span></span></div>' +
										        '<div class="ch-inner cfix">' +
										        $innerTemplate.html()+
										        ' <div class="ch-num"><a href="#" class="ch-remove"></a></div>' +
										        '</div>' +
										        
										        '</div>');



					var val = $result.val().length?$result.val():'[]';

					var itemsJSON = JSON.parse(decodeURIComponent(val));
						
						if(itemsJSON.length) {


							for (var i = 0; i < itemsJSON.length; i++) {
								var item = $template.clone();
								for(var key in itemsJSON[i]) {
									
									var input = item.find('[name="'+key+'"]');

									if(input.is('[type="checkbox"]') || input.is('[type="radio"]')) {
				

										input.prop('checked', itemsJSON[i][key])
									} else {
										input.val(itemsJSON[i][key]);
									}
									
								}

								item.find('.ch-num span').text(i+1);

								$list.append(item);
							};
						}
					


					$addBtn.on('click', function(e){
						e.preventDefault();
						var item = $template.clone();

						//item.find('.ch-inner')
						$list.append(item);
						enumerate();

					});


					$list.sortable({
						stop: function( event, ui ) {
							enumerate();
						}
					});

	   				//$list.disableSelection();

	   				$wrap.delegate('.ch-input','change', createJSON);

	   				$wrap.delegate('.ch-remove','click', function(e){
	   					e.preventDefault();
	   					$(this).closest(itemClass).remove();
	   					enumerate();
	   				});



					function enumerate() {
						$wrap.find(itemClass).each(function(i){
							$(this).find('.ch-num span').text(i+1);
						});

						createJSON();

					}

					function createJSON(){
						var res = [];

						$list.find(itemClass).each(function(){

							var item = {};

							$(this).find('.ch-input').each(function(){
								if($(this).is('[type="checkbox"]') || $(this).is('[type="radio"]') ) {
									item[$(this).attr('name')] = $(this).prop('checked');
								} else {
									item[$(this).attr('name')] = $(this).val();
								}
								
							});

							res.push(item);

						});

						$result.val(encodeURIComponent(JSON.stringify(res)));
					}
			})
		}


	$(document).ready(function(){


		$(document).characteristicsInit();

		
	})
})(jQuery)