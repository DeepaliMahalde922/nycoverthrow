jQuery(document).ready(function($){
    
        // Create link (Account) infront name of the user in backend calendar.
        jQuery( document ).ajaxSuccess(function( event, xhr, settings ) {
            var dataAction = JSON.stringify(settings.data);
            if(dataAction.indexOf('booked_admin_calendar_date') > 0){
                jQuery('.booked-admin-calendar-wrap tr.entryBlock .user').each(function(index, element){
                    var data_user_id = jQuery(this).attr("data-user-id");
                    jQuery(this).after(" | <a href='https://overthrownyc.com/wp-admin/user-edit.php?user_id="+data_user_id+"'>Account </a>");
                });
            }
        });

    	$(document.body).on('click','.action.column-action .cancel',function(e){
        //ovr_cancel_appointment

        appt_id = $(this).attr('data-appt-id');

        $('body').css('cursor','wait');

        //booked_cancel_appt
        $.ajax({
            'url'       : ajax_object.ajax_url,
            'method'    : 'post',
            'data'      : {
                'action'        : 'ovr_cancel_appointment',
                'appt_id'       : appt_id
            },
            success: function(data) {
                //alert(data);
                alert('Appointment has been cancelled and funds refunded to the user');
                $('body').css('cursor','default');

                var redirect_to = ajax_object.site_url+'/wp-admin/edit.php?post_type=booked_appointments';

                if( $('body').hasClass('wp-admin') && ( $('body').hasClass('profile-php') || $('body').hasClass('user-edit-php') ) ){
                    if( $('body').hasClass('profile-php') ){
                        redirect_to = ajax_object.site_url+'/wp-admin/profile.php';    
                    }
                    if( $('body').hasClass('user-edit-php') ){
                        redirect_to = $('#wp-admin-canonical').attr('href');
                    }
                }
                //
                window.location.href = redirect_to;
            }
        });

    });

    //add on off attendance button in backend appointments

    $( document ).ajaxSuccess(function( event, request, settings ) {

        if( $('body').hasClass('toplevel_page_booked-appointments') && $('body').hasClass('wp-admin') ){
            
            /*var request_url = settings.url;

            console.log(event.currentTarget);
            console.log(request);
            console.log(settings.data);
            */

            var target = event.currentTarget;
            var action_query_string = settings.data;

            //alert(action_query_string);

            if( action_query_string.indexOf("booked_admin_calendar_date") >= 0 ){

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

            }
        }

    });

    $(document.body).on('click','.ovr_cancel_backend',function(e){
        //ovr_cancel_appointment
        e.preventDefault();

        if( !confirm('Are you sure to cancel booking?') ){
            return false;
        }

        appt_id = $(this).attr('data-appt-id');
        var appt_refund = $(this).attr('data-refund');

        $('body').css('cursor','wait');

        //booked_cancel_appt
        $.ajax({
            'url'       : ajax_object.ajax_url,
            'method'    : 'post',
            'data'      : {
                'action'        : 'ovr_cancel_appointment',
                'appt_id'       : appt_id,
                'refund'        : appt_refund,
                'from_backend'  : 'yess'
            },
            success: function(data) {
                if(appt_refund == 'yes'){
                    alert('Class has been cancelled and amount refunded to user account');
                }else{
                    alert('Class has been cancelled');
                }

                $('body').css('cursor','default');

                window.location.href = ajax_object.site_url+'/wp-admin/admin.php?page=booked-appointments';
            }
        });

    });


    $(document).on('click','.username .cgc_ub_edit_badges',function(e){
        //ovr_cancel_appointment
        e.preventDefault();

        var userid = $(this).attr('data-user_id');
        jQuery('#'+userid+'_instant-info').bPopup({
            closeClass:'b-close'
        });

    });   

});