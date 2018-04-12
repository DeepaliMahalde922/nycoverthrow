(function( $ ){
    var displayMap = {
        hiddenBase : [ 'username', 'user_id', 'tag', 'location_id'],
        user : ['username', 'user_id' ],
        feed : [],
        liked : [],
        tag : [ 'tag' ],
        location : [ 'location_id' ]
    }

    var hide_fields = function( $widget ){
        var $widget_content = $('.widget-inside', $widget );

        for( x in displayMap.hiddenBase ){
            var field_class = displayMap.hiddenBase[ x ];
            $( 'p.mgl_instagram_gallery_widget_field_' + field_class, $widget_content ).hide();
        }
    }

    var show_fields_by_type = function( $widget, type ){
        hide_fields( $widget );

        var $widget_content = $('.widget-inside', $widget );

        for( x in displayMap[ type ] ){
            var field_class = displayMap[ type ][ x ];
            $( 'p.mgl_instagram_gallery_widget_field_' + field_class, $widget_content ).show();
        }
    }

    $(document).ready(function(){

        $('.widget[id*="mgl_instagram_gallery"]', '#widgets-right').each(function(){
           var $widget = $( this );
           var $type_selector = $('.mgl_instagram_gallery_widget_type_selector', $widget );

           var type = $type_selector.val();
           
           if( type != 'none' ){
               show_fields_by_type( $widget, type );
           }
        });

        $( "#widgets-right" ).on( "change", ".mgl_instagram_gallery_widget_type_selector", function() {
            var $widget = $( this ).closest( '.widget[id*="mgl_instagram_gallery"]' );
            var type = $(this).val();

            if( type !== 'none' ){
                show_fields_by_type( $widget, type );
            }else{
                hide_fields( $widget );
            }
            
        } );
    });
})(jQuery);