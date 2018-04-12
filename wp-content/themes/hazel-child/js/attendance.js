jQuery(document).ready(function($){
	$('.onoffswitch-checkbox').change(function() {
        var attendance_status = 'no';
        var booking_id = $(this).attr('booking-id');
        if($(this).is(":checked")) {         
            attendance_status = 'yes';  
        }else{        	
        	attendance_status = 'no';
        }
        var data = {
			'action': 'ovr_update_attendance',
			'attendace_status': attendance_status,
			'booking_id': booking_id
		};		
		jQuery.post( ajax_object.ajax_url, data, function(response) {
            
        });
    });
});