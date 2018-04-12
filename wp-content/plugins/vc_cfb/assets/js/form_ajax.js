(function($) {
  $(document).ready(function() {
      $(document).on( "click", "form.vc_cfb_form button", function() {
        var form = $(this).closest( 'form' );
        if( form.data('ajax') )
        {
          wrapper = form.parent( ".vc_cfb_form_wrapper" );
          data = new FormData( form[0] );
          $.ajax({
                  type:         form.attr('method'),
                  url:          form.attr('action'),
                  data:         data,
                  processData:  false, 
                  contentType:  false,
                  beforeSend:   function(){
                    wrapper.find('.vc_cfb_animation').show();
                  },
                  success:      function( data ){
                    var result = $('<div />').append(data).find('.vc_cfb_form_wrapper').html();
                    wrapper.html(result);
                  }
                });

          return false;
        }
      });
  });
})(jQuery);