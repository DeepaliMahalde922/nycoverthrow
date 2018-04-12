/**
 * mglInstagramController
 * Instagram gallery controller. Manage all Instagram galleries.
 * Main functions:
 *  - Gallery pagination
 *  - Lightbox configuration
 *
 * @type {object}
 */
var mglInstagramController = {};

(function ($) {
    mglInstagramController = function (settings) {
        // TODO: Change var that and use .bind( this )
        var that = this;

        // Properties which can be overwritten by the user on the constructor
        settings = settings || {};

        // Default settings
        var defaultSettings = {
            // Instagram gallery container selector
            gallerySelector: '.mgl_instagram_gallery',

            // Instagram gallery next page button selector
            paginationNextSelector: '.mgl_instagram_pagination_next',

            // Instagram gallery previous page button selector
            paginationPrevSelector: '.mgl_instagram_pagination_prev'
        };

        // Overwrite settings it they are matched
        that.options = $.extend({}, defaultSettings, settings);

        /**
         * Init is the main function of the class. This function is executed when the class is instantiated
         */
        that.init = function () {
            // Start Instagram gallery processes
            that.initInstagramGallery();
        };

        /**
         * Initialize every inactive Instagram Gallery, include  galleries withing Instagram user's card
         */
        that.initInstagramGallery = function () {
            // TODO: Split into different functions
            // Iterate over all found galleries
            $(that.options.gallerySelector).each(function () {
                // Current gallery
                var $gallery = $(this);

                if ($gallery.data('gallery-active') != 'true') {
                    // Turn the gallery into an active one. Tell the system gallery is initiated
                    $gallery.data('gallery-active', 'true');

                    // Add functionality to next page button
                    $(that.options.paginationNextSelector, $gallery).click(function (e) {
                        // Stop default element behaviour
                        e.preventDefault();

                        // Load next page images
                        that.loadGalleryContent($gallery, $gallery.data('mgl-instagram-nextid'));
                    });

                    $(that.options.paginationPrevSelector, $gallery).click(function (e) {
                        // Stop default element behaviour
                        e.preventDefault();

                        // Load previous page images
                        that.loadGalleryContent($gallery, $gallery.data('mgl-instagram-previd'));
                    });

                    // Load first gallery page
                    that.loadGalleryContent($gallery);
                }
            });
        };

        /**
         * Load a gallery page
         * @param $gallery HTMLObject Specific gallery where page will be loaded
         * @param pageIdToLoad string Page to be loaded. If no page id given, first page will be loaded
         */
        that.loadGalleryContent = function ($gallery, pageIdToLoad) {
            // TODO: Set spinner template as a setting option. Use some kind of templating system instead
            // Istagram gallery spinner to show as the gallery content is loading
            var $spinner = '<div class="mgl_instagram_spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';

            // Request data to set and get the new gallery page
            var data = {
                // Execute a PHP functions managed by WordPress AJAX system
                'action': 'mgl_instagram_loadContent',

                // Gallery params to retrieve gallery, the same params shortcode gallery needs
                'parameters': $gallery.data('mgl-instagram-parameters'),

                // Gallery type to be loaded. Required because of the way shortcode galleries are loaded
                'galleryType': $gallery.data('mgl-instagram-gallery-type'),
            };

            // Add to request data the page ID to load if it exists
            if (pageIdToLoad != undefined) {
                data.pageIdToLoad = pageIdToLoad;
            }

            // TODO: Set gallery content selector as a setting option
            // Get gallery content
            var $galleryContent = $('.mgl_instagram_gallery_content', $gallery);

            // Fade out gallery content as the new page is loading
            $galleryContent.css('opacity', '0.2');

            // Append spinner to gallery
            $galleryContent.append($spinner);

            // Retrive the new gallery page to load
            jQuery.post(ajax_object.ajax_url, data)
                .done(function (response) {
                    // Parse the response as a JSON
                    try{
                        // parse response and turn it into a JSON object
                        response = $.parseJSON(response);
                    }catch(err) {
                        // If the response was a malformed JSON throw a console.log message to debug it
                        console.error( response );
                    }

                    // TODO: Set a limit of attempts. Show a message if after X attempts gallery page cannot be loaded
                    if (response == null) {
                        // Try to load the page again
                        that.loadGalleryContent($gallery, pageIdToLoad);

                        // TODO: Find out what this is necessary
                        return false;
                    }

                    // Empty gallery content and fill it with the new one
                    $galleryContent.empty().html(response.galleryContent);

                    // Set the new next page ID
                    $gallery.data('mgl-instagram-nextid', response.nextId);

                    // Set the new previous page id
                    $gallery.data('mgl-instagram-previd', response.prevId);

                    // TODO: Split into a new funtion
                    if (response.prevId == 'none') {
                        // Hide previous page button
                        $gallery.find('.mgl_instagram_pagination_prev').hide();
                    } else {
                        // Show previous page button
                        $gallery.find('.mgl_instagram_pagination_prev').show();
                    }

                    if (response.nextId == 'none') {
                        // Hide previous page button
                        $gallery.find('.mgl_instagram_pagination_next').hide();
                    } else {
                        // Show previous page button
                        $gallery.find('.mgl_instagram_pagination_next').show();
                    }

                    // Configure elements to open lightbox with an specific setup
                    that.configureLightBox($gallery);

                    // Fade in back gallery content
                    $galleryContent.css('opacity', '1');
                })
                .fail(function (response) {
                    console.log(response);
                })
            ;
        };

        that.configureLightBox = function ($gallery) {
            // Flag. LightBox active o direct link intead
            var directLink = $gallery.data('mgl-gallery-directlink'),
            // Flag. Disabled Javascript
                disableJS = $gallery.data('mgl-gallery-disablejs');

            if (directLink === false && disableJS === false) {
                // Lightbox options for this gallery elements
                var options = {
                    // Set the gallery type
                    type: ( $gallery.data('mgl-gallery-video') === true ) ? 'ajax' : 'image',

                    // Set a main class to lightbox. Useful to avoid CSS conflicts
                    mainClass: 'mgl_instagram_lightbox',

                    // Closing lightbox delay
                    removalDealy: 500,

                    // Actions triggered when different events happen
                    callbacks: {
                        open: function () {
                            // TODO: Hide by configuration or CSS
                            // Hide image title
                            $('.mfp-title').hide();
                        },
                        close: function () {
                            // Hide image title
                            $('.mfp-title').hide();
                        }
                    }
                };

                if ($gallery.data('mgl-gallery-video') === true) {
                    // Set specific video gallery options
                    options.gallery = {
                        enabled: true,
                        navigateByImgClick: false
                    };

                    // Add callback to load ajax content
                    options.callbacks.ajaxContentAdded = function () {
                        // Get magnific popup instance
                        var magnificPopup = $.magnificPopup.instance;

                        // Show and item index along the collection. => 3 of 5 items
                        $(this.content).find('.mfp-counter').append(magnificPopup.index + 1 + ' of ' + magnificPopup.items.length);

                        // Show video title
                        $('.mfp-title').show();
                    }
                } else {
                    // Options for a non video gallery
                    options.gallery = {
                        enabled: true
                    };

                    // Show image title
                    options.callbacks.imageLoadComplete = function () {
                        $('.mfp-title').show();
                    };

                    // TODO: Set template in a better way. i.e undrescore library templates
                    // Image item template
                    options.image = {
                        titleSrc: 'title',
                        markup: '<div class="mfp-figure">' +
                        '<div class="mfp-close"></div>' +
                        '<div class="mfp-img"></div>' +
                        '<div class="mfp-bottom-bar">' +
                        '<div class="mfp-title"></div>' +
                        '<div class="mfp-counter"></div>' +
                        '</div>' +
                        '</div>',
                    }
                }

                // Disable gallery Magnific Popup functionalty to make sure any lightbox configuration is removed
                $(".mgl_instagram_photo", $gallery).off('click.magnificPopup');

                // Reset Magnific Popup configuration
                $('.mgl_instagram_photo', $gallery).magnificPopup(options);
            }
        };

        // Init Instagram Controller
        that.init();
    };
})(jQuery);