<?php

/**
 * Class MGLInstagramGallery_Shortcode_Controller
 */
class MGLInstagramGallery_Shortcode_Controller
{

    /**
     * MGLInstagramGallery_Shortcode_Controller constructor. Register shortcode hooks and controll ajax responses for
     * gallery pagination
     */
    public function __construct()
    {
        // Add query vars
        // TODO Change this by QUERY args => http://codex.wordpress.org/Function_Reference/add_query_arg
        add_filter('query_vars', array($this, 'mgl_instagram_query_vars'), 10, 1);

        //Register session if not
        // TODO: Find out if other hook for AJAX can make this be removed
        add_action('init', array($this, 'register_session'));

        // Register shortcodes
        $this->registerShortcodes();

        // Register AJAX actions
        $this->registerAjaxActions();
    }

    /**
     * Register plugins shortcodes to make them availables across the site
     */
    public function registerShortcodes()
    {
        // Official shortcode for load galleries
        add_shortcode('mgl_instagram_gallery', array($this, 'galleryShortcodes'));

        // Helper shortcode. Renders locations IDs for a given location
        add_shortcode('mgl_instagram_location_search', array($this, 'renderLocationsID'));

        // Card shortcode
        add_shortcode('mgl_instagram_card', array($this, 'cardShortcodes'));

        // Old User gallery shortcode. Kept for backward compatibility
        add_shortcode('mgl_instagram_user', array($this, 'galleryShortcodes'));

        // Old Liked gallery shortcode. Kept for backward compatibility
        add_shortcode('mgl_instagram_liked', array($this, 'galleryShortcodes'));

        // Old Tag gallery shortcode. Kept for backward compatibility
        add_shortcode('mgl_instagram_tag', array($this, 'galleryShortcodes'));

        // Old Location gallery shortcode. Kept for backward compatibility
        add_shortcode('mgl_instagram_location', array($this, 'galleryShortcodes'));
    }

    /**
     * Register Gallery AJAX actions related with the plugin's shortcodes
     */
    public function registerAjaxActions()
    {
        // Register loadInstagramGalleryContent. Return a gallery items page
        add_action('wp_ajax_mgl_instagram_loadContent', array($this, 'loadInstagramGalleryContent'));

        // Register loadInstagramGalleryContent. Return a gallery items page for non registered users
        add_action('wp_ajax_nopriv_mgl_instagram_loadContent', array($this, 'loadInstagramGalleryContent'));

        // Register loadInstagramGallerySingleItemContent. Return gallery single items content (used for lightbox)
        add_action('wp_ajax_mgl_instagram_loadSingleItemContent', array($this, 'loadInstagramGallerySingleItemContent'));

        // Register loadInstagramGallerySingleItemContent.
        // Return gallery single items content (used for lightbox) for non registered users
        add_action('wp_ajax_nopriv_mgl_instagram_loadSingleItemContent', array($this, 'loadInstagramGallerySingleItemContent'));
    }

    public function mgl_instagram_query_vars($qvars)
    {
        array_push($qvars, 'media');
        array_push($qvars, 'title');
        return $qvars;
    }

    /**
     * Start browser session if it was not already started
     * TODO: Look for a better way to do it with other hook to avoid using this function
     */
    public function register_session()
    {
        if (!session_id()) {
            // Start browser session
            session_start();
        }
    }

    /**
     * Generate gallery and render it
     * @param $atts Shortcode attributes
     * @param null $content Content inside the shortcode
     * @param $tagName Gallery name. Useful to call the same function for all shortcode
     * @return string
     */
    public function galleryShortcodes($atts, $content = null, $tagName)
    {
        if (!session_id()) {
            // Start session if is not already started
            session_start();
        }

        // Prepare shortcode arguments to be used on the gallery
        $args = $this->prepareArguments($atts);

        try {
            // Get the proper gallery to be shown
            $gallery = $this->getGalleryByShortcodeName($tagName, $args);

            // Render the gallery
            return $gallery->render();
        } catch (Exception $e) {
            // Returns a message to be shown instead of gallery
            return 'Instagram Gallery Exception: ' . $e->getMessage();
        }
    }

    /**
     * Prepare arguments to be used by the gallery controller. Sanatize them, fill with default values if necessary
     * and support some backward compatibilities
     * @param $atts
     * @return array
     */
    public function prepareArguments($atts)
    {
        // Sanatize arguments
        $atts = $this->sanatizeShortcodeArguments($atts);

        // Merge passed arguments with default ones an extract them as new variables
        $atts = shortcode_atts($this->getDefaultShortcodeAtts(), $atts);

        // Backward compatibility with 'number' argument.
        // TODO: Remove on next version
        $atts['count'] = $atts['number'];

        // Remove 'number' argument
        unset($atts['number']);

        return $atts;
    }


    /**
     * Clean emtpy parameters and transform boolean string into real booleans.
     * @param array $args Arguments to be sanatized
     * @return array Sanatized arguments
     */
    public function sanatizeShortcodeArguments($args = array())
    {
        if (!is_array($args)) {
            // Return an empty array if no arguments are passed
            return array();
        }

        // Remove empty values
        $args = array_filter($args);

        // Transform string booleans in real ones
        $args = array_map(function ($value) {
            //Return false if it is 'false'
            if ($value === 'false') return false;

            // Return true if it is 'true'
            if ($value === 'true') return true;

            // Return normal value
            return $value;
        }, $args);

        // Return sanatized values
        return $args;
    }

    /**
     * Return default shortcode attributes
     * @return array
     */
    public function getDefaultShortcodeAtts()
    {
        return array(
            // Indicate the type of gallery to be generated
            'type' => 'user',

            // Number of images to be shown on the gallery
            'number' => '12',

            // Time on milisecond the images will be cached
            'cache' => 3600,

            // Gallery pagination
            'pagination' => true,

            // Gallery username
            'username' => '',

            // User ID whose the photos belong to
            'user_id' => 0,

            // Location ID where the photos was taken
            'location_id' => 0,

            // Tag the photos have
            'tag' => '',

            // Number of columns the gallery will have
            'cols' => 4,

            // View more link text
            'more_text' => 'View more',

            // Next page button text
            'next_text' => 'Next',

            // Previous page button text
            'previous_text' => 'Previous',

            // Allow videos on the gallery
            'video' => true,

            // Cut description text after this character amount
            'cut_text' => 80,

            // Go directly to the image on Instagram web without lightbox
            'direct_link' => false,

            // Disable gallery javascript
            'disable_js' => false,

            // Skin
            'skin' => 'default',

            // Template
            'template' => 'default',

            // Make tha gallery responsive
            'responsive' => true,

            // Right to left reading for non-western languages
            'rtl' => false,

            // Activate debug to make easy develop and solve problems
            'debug' => false
        );
    }

    /**
     * Return a gallery ready to be rendered
     * @param $shortcodeName Shortcode name wich
     * @param $args Shortcode arguments
     * @return bool|MGLInstagramGallery_Controller_Liked|MGLInstagramGallery_Controller_Location|MGLInstagramGallery_Controller_Tag|MGLInstagramGallery_Controller_User
     * @throws Exception
     */
    public function getGalleryByShortcodeName($shortcodeName, $args)
    {
        // TODO: DRY and clean this function.
        switch ($shortcodeName) {
            // Current shortcode. Generate a gallery by
            case 'mgl_instagram_gallery':
                // Check if it is an allowed gallery type
                if (array_key_exists($args['type'], mgl_instagram_types())) {
                    // Call the function same function to load a gallery type padding the type argument as shortcode name
                    $gallery = $this->getGalleryByShortcodeName($args['type'], $args);
                } else {
                    // Throw an exception if the gallery type is not valid
                    throw new Exception('Gallery type is not valid or undefined');
                }
                break;

            // Load gallery by type + backward compatibility
            case 'mgl_instagram_feed':
            case 'feed':
                // Throw an exeption about the drepecated Feed gallery
                throw new Exception("Deprecated. Feed Gallery type is no longer supported by Instagram");
                break;

            case 'mgl_instagram_user':
            case 'user':
                // Generate a User gallery
                $gallery = new MGLInstagramGallery_Controller_User($args);
                break;

            case 'mgl_instagram_liked':
            case 'liked':
                // Generate a Liked Gallery
                $gallery = new MGLInstagramGallery_Controller_Liked($args);
                break;

            case 'mgl_instagram_tag':
            case 'tag':
                // Generate a Tag gallery
                $gallery = new MGLInstagramGallery_Controller_Tag($args);
                break;

            case 'mgl_instagram_location':
            case 'location':
                // Generate a Location gallery
                $gallery = new MGLInstagramGallery_Controller_Location($args);
                break;

            default:
                // Return false if the shortcode name or type is not valid
                $gallery = false;
        }

        return $gallery;
    }

    /**
     * Generate and render a user card
     * @param $atts Shortcode attributes
     * @param null $content Content between shortcode tags. Not used
     * @return string Generated user card
     */
    public function cardShortcodes($atts, $content = null)
    {
        // Prepare shortcode attributes
        $args = $this->prepareArguments($atts);

        try {
            // Generate User card
            $card = new MGLInstagramGallery_Controller_Card($args);

            // Return the rendered user card
            return $card->render();

        } catch (Exception $e) {
            // Show an error if something was wrong
            return 'Instagram Gallery Exception: ' . $e->getMessage();
        }
    }


    /**
     * Generate location IDs to be used on location gallery
     * TODO: Change it for something more user friendly
     * @param array $atts Shortcode attributes
     * @param null $content Content between shortcode tags. Not use
     */
    public function renderLocationsID($atts = array())
    {
        // Sanatize shortcode attributes before they be used
        $args = $this->sanatizeShortcodeArguments($atts);

        // Generate location IDs to be used on location gallery
        $locationSearchObject = new MGL_Instagram_LocationSearch($args);
    }

    /**
     * Load and generate Instagram gallery page content. Used as a AJAX Action
     * @throws Exception
     */
    public function loadInstagramGalleryContent()
    {
        if (!session_id()) {
            // Start browser session
            session_start();
        }

        // Get gallery parameters
        $parametersString = (string)$_POST['parameters'];

        // Get gallery type
        $galleryType = (string)$_POST['galleryType'];

        // Parameters array
        $parameters = array();

        // Parse parameters string and turn them into a parameters array
        parse_str($parametersString, $parameters);

        if (isset($_POST['pageIdToLoad'])) {
            // Add page ID to parameters if exists
            $parameters['pageIdToLoad'] = $_POST['pageIdToLoad'];
        }

        // Emulate shortcode name to get the gallery
        // TODO: Change this way to generate the gallery
        $shortcodeTag = 'mgl_instagram_' . $galleryType;

        // Get a gallery instance
        $gallery = $this->getGalleryByShortcodeName($shortcodeTag, $parameters);

        // Throw an error if the gallery is not generated
        // TODO: Generate a error JSON intead of throw 'error'
        if (!$gallery) die('error');

        // Throw error if the gallery is a wp error
        // TODO: Generate a error JSON intead of throw 'error'
        if (is_wp_error($gallery)) {
            die('error');
        }

        // Generate a gallery content response
        $response = array(
            // Gallery page content
            'galleryContent' => wp_encode_emoji($gallery->renderGalleryContent()),

            // Next page ID
            'nextId' => $gallery->nextId,

            // Previous page ID
            'prevId' => $gallery->prevId
        );

        global $mgl_ig;
        $mgl_ig['logger']->success( 'Next ID:' . $response['nextId'] . ' - Prev id: ' . $response['nextId'] );

        // Generate JSON Response
        die(json_encode($response));
    }

    /**
     * Generate a single item content to be shown on lightbox
     */
    public function loadInstagramGallerySingleItemContent()
    {
        // Generate a single item content to be shown on lightbox
        include(MGL_INSTAGRAM_GALLERY_FILEPATH . '/single-gallery.php');
    }
}
