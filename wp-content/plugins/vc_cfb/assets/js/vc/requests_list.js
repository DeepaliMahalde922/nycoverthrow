(function($){
  $.fn.requestsListActions = function(){
      $(document).on( "click", ".rl-wrap a.rl-button.rl-see-current", function(e) {
        e.preventDefault();
        var wrap = $(this).closest('.rl-item');

        wrap.parent().find('.rl-item-full:visible').each(function(){ $(this).hide(); });
        wrap.find('.rl-item-full').show();
        $(this).blur();
      });

      this.find(".vc_ui-panel-window[data-vc-shortcode=cfb_form] .vc_ui-panel-header-content li.vc_edit-form-tab-control button").click(function(e) {
      }).on('mouseup',function(){
        var wrapper = $( '.vc_ui-panel-window[data-vc-shortcode=cfb_form] .vc_ui-panel-content-container ' + $(this).attr('data-vc-ui-element-target') + ' .rl-wrap' );
        if( wrapper.length == 0 )
          return;

        var formName = $(this).closest('.vc_ui-panel-window').find('.vc_ui-panel-content-container input.wpb_vc_param_value.wpb-textinput[name="name"]').val();

        _ajax( wrapper, formName );

        return false;
      });

      $(document).on( "click", ".rl-wrap .rl-download-links a.vc_btn-primary-warning", function(e) {
        e.preventDefault();
        _ajax( $(this).closest('.rl-wrap'), $(this).closest('.vc_ui-panel-content-container').find('input.wpb_vc_param_value.wpb-textinput[name="name"]').val() , 'remove=all' );
      });

      $(document).on( "click", ".rl-wrap a.rl-button.rl-remove", function(e) {
        e.preventDefault();
        var item = $(this).closest('.rl-item');
        var wrapper = $(this).closest('.rl-wrap');
        var formName = $(this).closest('.vc_ui-panel-content-container').find('input.wpb_vc_param_value.wpb-textinput[name="name"]').val();
        $.ajax({
                type:         'get',
                url:          wrapper.attr('data-url'),
                data:         "form=" + formName + '&remove=item&item=' + item.attr( 'data-id' ),
                processData:  false, 
                contentType:  false,
                beforeSend:   function(){
                    item.remove();
                  }
              });
      });

      $('.vc_ui-panel-window[data-vc-shortcode=cfb_form] .vc_ui-panel-content-container').scroll(function(){
        var wrap = $(this).find('.vc_edit-form-tab.vc_active .rl-list');
        if( wrap.length == 0 )
          return;
        if( $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight )
          wrap.find('.rl-item:hidden').first().show( 'slow' );
      });

      function _ajax( wrapper, formName, data )
      {
        data = typeof data !== 'undefined' ? '&' + data : '';

        $.ajax({
                type:         'get',
                url:          wrapper.attr('data-url'),
                data:         "form=" + formName + data,
                processData:  false, 
                contentType:  false,
                beforeSend:   function(){
                    wrapper.find('.rl-list').html('<div class="rl-animation"></div>');
                  },
                success:      function( data ){
                  wrapper.find('.rl-list').html(data);
                }
              });
      }
    }

  $(document).ready(function(){
    $(document).requestsListActions();    
  })
})(jQuery)