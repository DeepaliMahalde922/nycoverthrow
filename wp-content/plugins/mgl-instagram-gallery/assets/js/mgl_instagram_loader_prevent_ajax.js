(function($){
	$(document).ready(function(){
		var instagramGalleryController = new mglInstagramController();

		$('body').on('DOMChanged', function (event) {
			setTimeout(function(){ 
				instagramGalleryController.initInstagramGallery();
			}, 1000);
		});
	});
})(jQuery);