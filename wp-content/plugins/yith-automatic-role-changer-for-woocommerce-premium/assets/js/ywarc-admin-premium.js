/**
 * ywarc-admin.js
 *
 * @author Your Inspiration Themes
 * @version 1.0.0
 */
 
jQuery(document).ready( function($) {
    "use strict";

    var new_rules       = $( document ).find( '.ywarc_new_rules' ),
        rules_group     = $( document ).find( '.ywarc_rules_group' ),
        add_rule     = $( '#yith_ywarc_add_rule_button' ),
        new_rule_title  = $( '.ywarc_new_rule_title' ),
        creating_rule = $( '.ywarc_creating_rule' );


    new_rule_title.focus();
    new_rule_title.keydown( function( event ){
        if( event.which == 13){
            add_rule.focus();
            add_rule.trigger( 'click' );
        }
    });

    add_rule.on( 'click', function( e ) {
        e.preventDefault();

        var title = new_rule_title.val().trim();
        var titles_array = [];
        new_rules.find( '.rule_title' ).each( function () {
            titles_array.push( $( this ).text() );
        });
        if ( $.inArray( title, titles_array ) != '-1' ) {
            alert( localize_js_ywarc_admin.duplicated_name_msg );
            new_rule_title.val( '' );
            new_rule_title.focus();
        } else if ( title == '' ) {
            alert( localize_js_ywarc_admin.empty_name_msg );
            new_rule_title.focus();
        } else {
            creating_rule.block( { message: null, overlayCSS:{ background: "#f1f1f1", opacity: .6 } } );
            $.post( localize_js_ywarc_admin.ajax_url, { action: 'ywarc_add_rule', title: title }, function( resp ) {
                creating_rule.unblock();
                new_rule_title.val( '' );
                if ( resp === 'duplicated_name_error' ) {
                    alert( localize_js_ywarc_admin.duplicated_name_msg );
                    new_rule_title.focus();
                } else {
                    var isNewRule = true;
                    instantiateRuleBlock( resp, isNewRule );
                }
            });
        }
    });

    $( '.rule_block' ).each( function( index, element ) {
        instantiateRuleBlock( element, false );
    });
    
    $( '#show_all_rules' ).on( 'click', function ( e ) {
        e.preventDefault();
        rules_group.find( '.rule_options' ).slideDown();
    });

    $( '#hide_all_rules' ).on( 'click', function ( e ) {
        e.preventDefault();
        rules_group.find( '.rule_options' ).slideUp();
    });

    $( '#delete_all_rules' ).on( 'click', function ( e ) {
        e.preventDefault();
        var message = confirm( localize_js_ywarc_admin.delete_all_rules_msg );
        if ( message == true ) {
            creating_rule.block( { message: null, overlayCSS:{ background: "#f1f1f1", opacity: .6 } } );
            $.post( localize_js_ywarc_admin.ajax_url, { 'action': 'ywarc_delete_all_rules' }, function( resp ) {
                creating_rule.unblock();
                rules_group.find( '.rule_block' ).remove();
                $( '#my_rules_header' ).addClass( 'no_rules' );
            });
        }
        check_new_rules_block();
    } );


    function instantiateRuleBlock( element, isNewRule ) {
        var rule_block = $( element );

        var rule_id = rule_block.data( 'rule_id' );

        var rule_head = rule_block.find( '.rule_head' ),
            rule_options = rule_block.find( '.rule_options' ),
            title = rule_head.find( 'label.rule_title' ).text();

        if ( isNewRule ) {
            new_rules.append( rule_block );
            rule_options.show();
            check_new_rules_block();
        }

        rule_head.on( 'click', function( event ){
            rule_options.slideToggle();
        });

        // Re-initialize all tooltips
        rule_block.trigger( 'init_tooltips' );

        //////////////// LOCALIZING BLOCKS ////////////////

        var role_selector_block = rule_options.find( 'div.role_selector_block' );

        var rule_radio_group = rule_options.find( 'div.rule_radio_group' );

        var specific_product_block = rule_options.find( 'div.specific_product_block' );
        specific_product_block.hide();

        var specific_range_block = rule_options.find( 'div.specific_range_block' );
        specific_range_block.hide();

        var specific_taxonomy_block = rule_options.find( 'div.specific_taxonomy_block' );
        specific_taxonomy_block.hide();

        var category_search_block = specific_taxonomy_block.find( 'div.category_search_block' );
        category_search_block.hide();

        var tag_search_block = specific_taxonomy_block.find( 'div.tag_search_block' );
        tag_search_block.hide();

        var date_range_block = rule_options.find( 'div.date_range_block' );
        date_range_block.hide();

        var duration_block = rule_options.find( 'div.duration_block' );
        duration_block.hide();

        var role_filter_selector_block = rule_options.find( 'div.role_filter_selector_block' );
        role_filter_selector_block.hide();

        var submit_block = rule_options.find( 'div.submit_block' );
        submit_block.hide();


        //////////////// INPUT FIELDS ////////////////

        var $price_range_from = specific_range_block.find( 'input[name="price_range_from"]' ),
            $price_range_to = specific_range_block.find( 'input[name="price_range_to"]' ),
            $date_from = date_range_block.find( '.sale_price_dates_from' ),
            $date_to = date_range_block.find( '.sale_price_dates_to' ),
            $duration = duration_block.find( '.ywarc_duration' );



        //////////////// REMOVE EMPTY FIELD CLASS ////////////////

        var remove_empty_field_class = function ( target ) {
            if ( 'role_selector' == target || 'all' == target ) {
                role_selector_block.removeClass( 'empty_field' );
            }
            if ( 'product_selector' == target || 'all' == target ) {
                specific_product_block.removeClass( 'empty_field' );
            }
            if ( 'price_range' == target || 'all' == target ) {
                specific_range_block.removeClass( 'empty_field' );
                specific_range_block.removeClass( 'ywarc_from_gt_to' );
            }
            if ( 'category' == target || 'all' == target ) {
                category_search_block.removeClass( 'empty_field' );
                specific_taxonomy_block.removeClass( 'empty_field' );
            }
            if ( 'tag' == target || 'all' == target ) {
                tag_search_block.removeClass( 'empty_field' );
                specific_taxonomy_block.removeClass( 'empty_field' );
            }
        };

        $price_range_from.on( 'change', function() { remove_empty_field_class( 'price_range' ) } );
        $price_range_to.on( 'change', function() { remove_empty_field_class( 'price_range' ) } );





        //////////////// INSTANTIATE DATE-PICKERS ////////////////

        rule_options.find( '.sale_price_dates_fields' ).each( function() {
            var dates = $( this ).find( 'input' ).datepicker({
                defaultDate: '',
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true,
                onSelect: function( selectedDate ) {
                    var option   = $( this ).is( '.sale_price_dates_from' ) ? 'minDate' : 'maxDate';
                    var instance = $( this ).data( 'datepicker' );
                    var date     = $.datepicker.parseDate( instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings );
                    dates.not( this ).datepicker( 'option', option, date );
                }
            });
        });




        //////////////// RADIO GROUPS ////////////////

        rule_options.find( 'input.ywarc_rule_radio_button' ).change( function() {
            remove_empty_field_class( 'all' );
            var radio = rule_options.find( 'input.ywarc_rule_radio_button:checked' );
            if ( radio.val() == 'product' ) {
                specific_range_block.hide();
                specific_taxonomy_block.hide();
                specific_product_block.slideDown();
                date_range_block.slideDown();
                duration_block.slideDown();
                role_filter_selector_block.slideDown();
                submit_block.slideDown();
            } else if ( radio.val() == 'range' ) {
                specific_product_block.hide();
                specific_taxonomy_block.hide();
                specific_range_block.slideDown();
                date_range_block.slideDown();
                duration_block.slideDown();
                role_filter_selector_block.slideDown();
                submit_block.slideDown();
            } else if ( radio.val() == 'taxonomy' ) {
                specific_product_block.hide();
                specific_range_block.hide();
                specific_taxonomy_block.slideDown();
                date_range_block.slideDown();
                duration_block.slideDown();
                role_filter_selector_block.slideDown();
                submit_block.slideDown();
            }
        }).change();

        specific_taxonomy_block.find( 'input.ywarc_tax_radio_button' ).change( function () {
            remove_empty_field_class( 'all' );
            var radio = specific_taxonomy_block.find( 'input.ywarc_tax_radio_button:checked' );
            if ( radio.val() == 'category' ) {
                tag_search_block.hide();
                category_search_block.show();
            } else if ( radio.val() == 'tag' ) {
                category_search_block.hide();
                tag_search_block.show();
            }
        }).change();


        //////////////// SELECT2 ////////////////
        var select2_role_selector_args;
        if ( localize_js_ywarc_admin.before_2_7 ) {
            select2_role_selector_args = {
                maximumSelectionSize: 1
            };
        } else {
            select2_role_selector_args = {
                maximumSelectionLength: 1
            };
        }
        rule_options.find( '.ywarc_role_selector' ).select2( select2_role_selector_args ).on( 'change', function () {
            remove_empty_field_class( 'role_selector' )
        } );

        role_filter_selector_block.find( '.role_filter_selector' ).select2();

        $( document.body ).trigger( 'wc-enhanced-select-init' );
        $( ':input.wc-product-search' ).on( 'change', function () {
            remove_empty_field_class( 'product_selector' )
        } );

        // Shared function by categories and tags
        var results = function (data) {
            var terms = [];
            if ( data ) {
                $.each( data, function( id, text ) {
                    terms.push( { id: id, text: text } );
                });
            }
            return {
                results: terms
            };
        };

        var initSelection = function( element, callback ) {
            var data     = $.parseJSON( element.attr( 'data-selected' ) );
            var selected = [];

            $( element.val().split( ',' ) ).each( function( i, val ) {
                selected.push({
                    id: val,
                    text: data[ val ]
                });
            });
            return callback( selected );
        };
        var formatSelection = function( data ) {
            return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
        };

        // Arguments for categories select2
        $( ':input.ywarc-category-search' ).filter( ':not(.enhanced)' ).each( function() {
            var ajax = {
                url: localize_js_ywarc_admin.ajax_url,
                dataType: 'json',
                quietMillis: 250,
                data: function (term) {
                    return {
                        term: term,
                        action: 'ywarc_category_search',
                        security: localize_js_ywarc_admin.search_categories_nonce
                    };
                },
                cache: true
            };

            if ( localize_js_ywarc_admin.before_2_7 ) {
                ajax.results = results;
            } else {
                ajax.processResults = results;
            }
            var select2_args = {
                initSelection: localize_js_ywarc_admin.before_2_7 ? initSelection : null,
                formatSelection: localize_js_ywarc_admin.before_2_7 ? formatSelection : null,
                multiple: $(this).data('multiple'),
                allowClear: $(this).data('allow_clear') ? true : false,
                placeholder: $(this).data('placeholder'),
                minimumInputLength: $(this).data('minimum_input_length') ? $(this).data('minimum_input_length') : '3',
                escapeMarkup: function (m) {
                    return m;
                },
                ajax: ajax
            };
            $( this ).select2( select2_args ).addClass('enhanced').on( 'change', function () {
                remove_empty_field_class( 'category' )
            } );
        });

        // Arguments for tags select2
        $( ':input.ywarc-tag-search' ).filter( ':not(.enhanced)' ).each( function() {
            var ajax = {
                url: localize_js_ywarc_admin.ajax_url,
                dataType: 'json',
                quietMillis: 250,
                data: function (term) {
                    return {
                        term: term,
                        action: 'ywarc_tag_search',
                        security: localize_js_ywarc_admin.search_tags_nonce
                    };
                },
                cache: true
            };
            if ( localize_js_ywarc_admin.before_2_7 ) {
                ajax.results = results;
            } else {
                ajax.processResults = results;
            }
            var select2_args = {
                initSelection: localize_js_ywarc_admin.before_2_7 ? initSelection : null,
                formatSelection: localize_js_ywarc_admin.before_2_7 ? formatSelection : null,
                multiple: $(this).data('multiple'),
                allowClear: $(this).data('allow_clear') ? true : false,
                placeholder: $(this).data('placeholder'),
                minimumInputLength: $(this).data('minimum_input_length') ? $(this).data('minimum_input_length') : '3',
                escapeMarkup: function (m) {
                    return m;
                },
                ajax: ajax
            };
            $( this ).select2( select2_args ).addClass('enhanced').on( 'change', function () {
                remove_empty_field_class( 'tag' )
            } );
        });




        //////////////// SAVE DATA ////////////////

        var submit_button = submit_block.find( 'input' );

        submit_button.on( 'click', function( event ){
            event.preventDefault();
            rule_block.block( { message: null, overlayCSS:{ background: "#fff", opacity: .6 } } );
            var role_selected = rule_options.find( 'select.ywarc_role_selector' ).val(),
                radio_group = rule_options.find( 'input.ywarc_rule_radio_button:checked' ).val(),
                product_selected = specific_product_block.find( ':input[id^="ywarc_product_selector"]' ).val(),
                price_range_from = $price_range_from.val(),
                price_range_to = $price_range_to.val(),
                tax_radio_group = specific_taxonomy_block.find( 'input.ywarc_tax_radio_button:checked' ).val(),
                categories_selected = category_search_block.find( ':input[id^="ywarc_category_selector"]' ).val(),
                tags_selected = tag_search_block.find( ':input[id^="ywarc_tag_selector"]' ).val(),
                date_from = $date_from.val(),
                date_to = $date_to.val(),
                duration = $duration.val(),
                role_filter = role_filter_selector_block.find( 'select.role_filter_selector' ).val();



            ///// CHECK FOR EMPTY FIELDS /////

            var fields_filled = true;

            if ( ! role_selected ) {
                fields_filled = false;
                role_selector_block.addClass( 'empty_field' );
            }

            if ( 'product' == radio_group ) {
                if ( ! product_selected ) {
                    fields_filled = false;
                    specific_product_block.addClass( 'empty_field' );
                }
            } else if ( 'range' == radio_group ) {
                if ( ! price_range_from && ! price_range_to ) {
                    fields_filled = false;
                    specific_range_block.addClass( 'empty_field' );
                }
                if ( price_range_to && parseInt( price_range_from ) >= parseInt( price_range_to ) ) {
                    fields_filled = false;
                    specific_range_block.addClass( 'ywarc_from_gt_to' );
                }
            } else if ( 'taxonomy' == radio_group ) {
                if ( 'category' == tax_radio_group ) {
                    if ( ! categories_selected ) {
                        fields_filled = false;
                        category_search_block.addClass( 'empty_field' );
                    }
                } else if ( 'tag' == tax_radio_group ) {
                    if ( ! tags_selected ) {
                        fields_filled = false;
                        tag_search_block.addClass( 'empty_field' );
                    }
                } else {
                    fields_filled = false;
                    specific_taxonomy_block.addClass( 'empty_field' );
                }
            } else {
                fields_filled = false;
                rule_radio_group.addClass( 'empty_field' );
            }

            if ( fields_filled ) {

                // Duration value validation
                duration = parseInt( duration, 10 );
                duration = ( duration > 0 ) ? duration : '';

                var data = {
                    'action': 'ywarc_save_rule',
                    'title': title,
                    'rule_id': rule_id,
                    'role_selected': role_selected,
                    'radio_group': radio_group,
                    'product_selected': radio_group == 'product' ? product_selected : '',
                    'price_range_from': radio_group == 'range' ? price_range_from : '',
                    'price_range_to': radio_group == 'range' ? price_range_to: '',
                    'tax_radio_group': radio_group == 'taxonomy' ? tax_radio_group : '',
                    'categories_selected': radio_group == 'taxonomy' ? tax_radio_group == 'category' ? categories_selected : '' : '',
                    'tags_selected': radio_group == 'taxonomy' ? tax_radio_group == 'tag' ? tags_selected : '' : '',
                    'date_from': date_from,
                    'date_to': date_to,
                    'duration': duration,
                    'role_filter': role_filter
                };
                
                $.post( localize_js_ywarc_admin.ajax_url, data, function( resp ) {
                    rule_block.unblock();
                    if ( isNewRule ) {
                        rules_group.append( rule_block );
                        rule_options.hide();
                        isNewRule = false;
                    }
                    if ( rules_group.find( '.rule_block' ).length > 0  ) {
                        $( '#my_rules_header' ).removeClass( 'no_rules' );
                    }
                    check_new_rules_block();
                });
            } else {
                rule_block.unblock();
            }

            check_new_rules_block();
        });


        //////////////// DELETE DATA ////////////////

        var deleteButton = submit_block.find( 'a.delete_rule' );

        deleteButton.on( 'click', function( event ){
            event.preventDefault();
            var message = confirm( localize_js_ywarc_admin.delete_rule_msg );
            if ( message == true ) {
                if ( isNewRule ) {
                    rule_block.remove();
                } else {
                    rule_block.block( { message: null, overlayCSS:{ background: "#fff", opacity: .6 } } );
                    var data = {
                        'action': 'ywarc_delete_rule',
                        'rule_id': rule_id
                    };
                    $.post( localize_js_ywarc_admin.ajax_url, data, function( resp ) {
                        rule_block.unblock();
                        rule_block.remove();
                        if ( rules_group.find( '.rule_block' ).length == 0  ) {
                            $( '#my_rules_header' ).addClass( 'no_rules' );
                        }
                    });
                }
                if ( rules_group.find( '.rule_block' ).length == 0  ) {
                    $( '#my_rules_header' ).addClass( 'no_rules' );
                }

            }
            check_new_rules_block();
            
        });

        //////////////// END OF FUNCTION ////////////////

    }

    function check_new_rules_block() {
        if ( new_rules.find( '.rule_block' ).length > 0 ) {
            $( '#ywarc_new_rules_row' ).removeClass( 'no_rules' );
        } else {
            $( '#ywarc_new_rules_row' ).addClass( 'no_rules' );
        }
    }

});
