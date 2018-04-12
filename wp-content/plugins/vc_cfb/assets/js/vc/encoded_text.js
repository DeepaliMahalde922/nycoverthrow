(function($){
	$.fn.encodeTextField = function(){
			this.find('.e-t-wrap').each(function(){
				var $wrap 		= $(this),
						$text 		= $wrap.find('.e-t-text'),
						$result 	= $wrap.find('.e-t-hidden');
					
				var val = $result.val().length?$result.val():'';
				var decodedText = decodeURIComponent(val);
				$text.val(decodedText);
	   		$wrap.delegate('.e-t-text','change', encodeText);
				
				function encodeText(){	
					$result.val(encodeURIComponent($text.val()));
				}
			})
		}
	$(document).ready(function(){
		$(document).encodeTextField();		
	})
})(jQuery)