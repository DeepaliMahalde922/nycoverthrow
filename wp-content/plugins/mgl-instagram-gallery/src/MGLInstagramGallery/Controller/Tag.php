<?php

class MGLInstagramGallery_Controller_Tag extends MGLInstagramGallery_Controller_Base {

    public function __construct( $args ){
        $this->type = 'tag';
        parent::__construct( $args );
    }

    function get_request_atts()
    {
        if( $this->tag == '') throw new Exception('You need to setup a tag!');

        $request_atts =  array(
            'url' => 'tags/'. $this->tag .'/media/recent',
            'vars' => array()
        );
        if( $this->current_id != '' ) $request_atts['vars']['max_tag_id'] = $this->current_id;

        return $request_atts;
    }

    public function configure_navigation()
    {
        if( !isset( $this->response->pagination->next_max_tag_id ) ){
            $this->prevId = 'none';
            $this->nextId = 'none';
        }

        if( isset( $this->response->pagination->next_max_tag_id ) ){
            $nextMaxId = $this->response->pagination->next_max_tag_id;
            $this->nextId = $this->response->pagination->next_max_tag_id;
        }else{
            $nextMaxId = 'none';
            $this->nextId = 'none';
        }

        global $mgl_ig;
        $mgl_ig['logger']->warning( 'Original: ' . $nextMaxId );
        $mgl_ig['logger']->warning( json_encode( $this->getActualNavigationHistory( $nextMaxId ) ));

        $_SESSION[ $this->galleryId ] = $this->getActualNavigationHistory( $nextMaxId );
        $this->prevId = $this->getPrevIdFromNavigationHistory();
    }
}
