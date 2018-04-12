<?php

/**
 * Class MGLInstagramGallery_Controller_Base
 * Abstract clase to be inherited on Specific gallery type classes
 * // TODO: standarize variable names
 */
abstract class MGLInstagramGallery_Controller_Base
{
    // Shortcode attributes
    public $givenArgs,
        $type,
        $count,
        $user_id,
        $username,
        $location_id,
        $tag,
        $cache,
        $pagination,
        $cols,
        $more_text,
        $next_text,
        $previous_text,
        $cut_text,
        $video,
        $directLink,
        $disable_js,
        $skin,
        $template,
        $responsive,
        $rtl,
        $debug;

    public $ajaxViewerUrl,
        $instagramAccount,
        $accessToken,
        $galleryId,
        $gallery,
        $nextId,
        $preId,
        $current_id;

    protected $response;

    public function __construct($args)
    {
        $this->givenArgs = $args;

        $this->count = $args['count'];
        $this->userId = $args['user_id'];
        $this->username = $args['username'];
        $this->location_id = $args['location_id'];
        $this->tag = $args['tag'];
        $this->cache = $args['cache'];
        $this->pagination = $args['pagination'];
        $this->cols = $args['cols'];
        $this->more_text = $args['more_text'];
        $this->next_text = $args['next_text'];
        $this->previous_text = $args['previous_text'];
        $this->cut_text = $args['cut_text'];
        $this->video = $args['video'];
        $this->directLink = $args['direct_link'];
        $this->disable_js = $args['disable_js'];
        $this->skin = $args['skin'];
        $this->template = $args['template'];
        if ($this->template == 'default' && $this->skin != '') $this->template = $this->skin; //Legacy
        $this->responsive = $args['responsive'];
        $this->debug = $args['debug'];
        $this->rtl = $args['rtl'];

        $this->ajaxViewerUrl = add_query_arg(array('action' => 'mgl_instagram_loadSingleItemContent'), admin_url('admin-ajax.php'));

        // CONTROLLER
        // TODO: Remove Controller functions from __construct()
        $this->galleryId = $this->getGalleryId();

        // Set nextId as empty
        $this->nextId = '';

        // Set prevId as null
        // TODO: Find out why nextId is empty string and prevId null
        $this->prevId = null;

        if (isset($args['pageIdToLoad']) && $args['pageIdToLoad'] != 'first') {
            // Set as current_id pageIdToLoad if its different to first
            $this->current_id = $args['pageIdToLoad'];
        } else {
            // Set current id as empty string
            $this->current_id = '';

            // Set a empty array as a value of a session var with the gallery Id as a key
            $_SESSION[$this->galleryId] = array();
        }

        // Get Instagram API model
        $this->model = new MGLInstagramGallery_Model_Model();

        // Get the template system / renderer
        $this->templating = new MGLInstagramGallery_Template_Template($this->template);

        // Get request attr
        $request_atts = $this->get_request_atts();

        // Get Instagram server response
        $this->response = $this->model->get_response($request_atts['url'], $request_atts['vars'], $this->cache);

        // Configure navigation and pagination system
        $this->configure_navigation();
    }

    /**
     * Build a unic gallery id by using different gallery parameters
     * @return string
     */
    public function getGalleryId()
    {
        // Build a unic gallery id by using different gallery parameters
        return "mgl_ins_" . $this->type . $this->username . $this->userId . $this->tag . $this->location_id
        . "_n" . $this->count . '_' . $this->cache;
    }

    /**
     * @return mixed
     */
    abstract protected function get_request_atts();

    /**
     * Set nextId and prevId and update navigation history
     */
    public function configure_navigation()
    {
        if (isset($this->response->pagination->next_max_id)) {
            // Get next max id
            $nextMaxId = $this->response->pagination->next_max_id;

            // Remove characters from underscore on
            $this->nextId = $this->response->pagination->next_max_id;
        } else {
            // Set prevId as none
            $this->prevId = 'none';

            // Set nextId as none
            $this->nextId = 'none';

            // Set nextMaxId as none
            $nextMaxId = 'none';
        }

        // Get actual history removing page ids after current page if necessary
        $_SESSION[$this->galleryId] = $this->getActualNavigationHistory($nextMaxId);

        // Get prevId from navigation history
        $this->prevId = $this->getPrevIdFromNavigationHistory();
    }

    /**
     * Update history setting curent page id as a final history step an return the navigation history
     * @param $nextMaxId
     * @return array
     */
    public function getActualNavigationHistory($nextMaxId)
    {
        // Create dummy history array
        $dummyHistory = array();

        if ($nextMaxId != 'none') {
            // If gallery session var does not exist, create one
            // TODO: seems to be already defined on the consctructor
            if (!isset($_SESSION[$this->galleryId])) $_SESSION[$this->galleryId] = array();

            // Cut navigation history after current position
            foreach ($_SESSION[$this->galleryId] as $id) {
                // Add Id to the history array
                $dummyHistory[] = $id;

                // If current Id is matched, break the loop
                if ($this->current_id == $id) break;
            }

            // Add next mas id after cleaned
            // TODO: Find out if it could be removed, already executed on configure_navigation
            $dummyHistory[] = $nextMaxId;

            return $dummyHistory;
        }

        // Set session gallery history as dummyHistory
        $dummyHistory = $_SESSION[$this->galleryId];

        // Add 'last' as last gallery history step
        $dummyHistory[] = 'last';

        return $dummyHistory;
    }

    public function getPrevIdFromNavigationHistory()
    {
        $galleryHistorySize = count($_SESSION[$this->galleryId]);

        if (isset($_SESSION[$this->galleryId][$galleryHistorySize - 3])) {
            return $_SESSION[$this->galleryId][$galleryHistorySize - 3];

        } elseif (count($_SESSION[$this->galleryId]) == 2) {
            return 'first';
        } else {
            //return $_SESSION[ $this->galleryId ][ $galleryHistorySize - 1 ];
            return 'none';
        }
    }

    public function render()
    {
        $this->enqueueStylesAndScripts();

        $html = $this->templating->render('gallery', array(
            'gallery_attributes' => $this->get_gallery_html_attributes(),
            'pagination' => $this->render_pagination()
        ));

        return $html;

    }

    public function enqueueStylesAndScripts()
    {
        wp_enqueue_style('mgl_instagram_gallery');
        wp_enqueue_script('mgl_instagram_gallery_magnific');
        wp_enqueue_style('mgl_instagram_gallery_magnific');

        if ($this->video == true) {
            wp_enqueue_script('mgl_instagram_gallery_video');
            wp_enqueue_style('mgl_instagram_gallery_video');
            wp_enqueue_script('mgl_instagram_gallery_video');
            wp_enqueue_style('mgl_instagram_gallery_video');
        }

        // Get options and check value for gallery observer
        $instagram_gallery_options = get_option('MGLInstagramGallery_option_name');
        $gallery_observer = 0;
        if(isset($instagram_gallery_options['configuration']['observer']) && $instagram_gallery_options['configuration']['observer'] == '1') {
          $gallery_observer = 1;
        }

        if (!is_admin() && $gallery_observer != 1) {
            wp_enqueue_script('mgl_instagram_gallery_loader');
        }

    }

    private function get_gallery_html_attributes()
    {

        $rand_id = rand(1000, 9999);

        $gallery_css_classes = array(
            'mgl_instagram_gallery',
            'cols' . $this->cols,
            'mgl_instagram_template_' . $this->template,
        );

        if ($this->responsive) $gallery_css_classes[] = 'mgl_instagram_gallery_responsive';
        if ($this->rtl === true) $gallery_css_classes[] = 'mgl_instagram_gallery__rtl';

        $attributes = array(
            'id' => $this->galleryId . '_' . $rand_id,
            'data-mgl-instagram-gallery-type' => $this->type,
            'data-mgl-instagram-parameters' => $this->getUrlEncodedArgs(),
            'data-mgl-gallery-video' => ($this->video) ? 'true' : 'false',
            'data-mgl-gallery-disablejs' => ($this->disable_js) ? 'true' : 'false',
            'data-mgl-gallery-directlink' => ($this->directLink) ? 'true' : 'false',
            'class' => implode(' ', $gallery_css_classes)
        );

        $return = '';

        foreach ($attributes as $key => $attribute) {
            $return .= sprintf(' %s="%s"', $key, $attribute);
        }

        return $return;

    }

    public function getUrlEncodedArgs()
    {
        return http_build_query($this->givenArgs);
    }

    public function render_pagination()
    {
        $html = '';

        if ($this->pagination != false && (!empty($this->response->pagination))) {
            $html .= '<div class="mgl_instagram_pagination mgl_instagram_template_' . $this->template . '">';
            $html .= '<div class="mgl_instagram_pagination_item mgl_instagram_pagination_item_prev"><a class="mgl_instagram_pagination_prev" href="#">' . $this->numeric2character($this->previous_text) . '</a></div>';
            $html .= '<div class="mgl_instagram_pagination_item mgl_instagram_pagination_item_next"><a class="mgl_instagram_pagination_next" href="#">' . $this->numeric2character($this->next_text) . '</a></div>';
            $html .= '</div>';
        }

        return $html;
    }

    public function numeric2character($text)
    {
        $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
        return mb_decode_numericentity($text, $convmap, 'UTF-8');
    }

    public function renderGalleryContent()
    {
        $loop_index = 0;
        $gallery_data = $this->response->data;
        ob_start();

        foreach ($gallery_data as $galleryItem) {
            if ($loop_index < $this->count) {
                $this->renderGalleryItem($galleryItem);
                $loop_index++;
            }
        }
        return ob_get_clean();
    }

    public function renderGalleryItem($galleryItem)
    {

        $description = '';
        $descriptionShort = '';
        if (isset($galleryItem->caption->text)) {
            $description = $galleryItem->caption->text;
            $descriptionShort = $this->getShortDescription($description);
        }

        $target = ($this->directLink) ? ' target="_blank"' : '';
        $url_big = $this->getGalleryItemUrlBig($galleryItem, $description);

        $html = $this->templating->render('gallery-item', array(
            'target' => $target,
            'url_big' => $url_big,
            'description' => $description,
            'descriptionShort' => $descriptionShort,
            'galleryItem' => $galleryItem,
        ));
        echo $html;
    }

    public function getShortDescription($string)
    {
        if ($this->cut_text == 0) {
            return '';
        }
        return (mb_strlen($string) > $this->cut_text) ? mb_substr($string, 0, $this->cut_text - 3) . '...' : $string;
    }

    public function getGalleryItemUrlBig($galleryItem)
    {
        if (isset($galleryItem->caption->text)) {
            $current_encoding = mb_detect_encoding($galleryItem->caption->text, 'auto');
            $description = iconv($current_encoding, 'UTF-8', $galleryItem->caption->text);
            //$description = $galleryItem->caption->text;
        } else {
            $description = '';
        }

        if (!$this->directLink) {
            if ($this->video) {
                if (isset($galleryItem->videos->standard_resolution->url)) {
                    $urlVideo = $galleryItem->videos->standard_resolution->url;
                    return $this->ajaxViewerUrl . '&media=' . base64_encode($urlVideo) . '&title=' . urlencode(base64_encode($description));

                } else {
                    $urlImage = $galleryItem->images->standard_resolution->url;
                    return $this->ajaxViewerUrl . '&media=' . base64_encode($urlImage) . '&title=' . urlencode(base64_encode($description));
                }

            } else {
                return $galleryItem->images->standard_resolution->url;
            }
        }

        return $galleryItem->link;
    }

}
