<?php
class MGLInstagramGallery_Controller_Liked extends MGLInstagramGallery_Controller_Base {

    public function __construct( $args ){
        $this->type = 'liked';
        parent::__construct( $args );
    }

    public function get_request_atts(){

        $request_atts =  array(
            'url' => "users/self/media/liked",
            'vars' => array(
                    'count' => $this->count
                )
        );
        if( $this->current_id != '' ) $request_atts['vars']['max_id'] = $this->current_id;

        return $request_atts;

    }

    public function configure_navigation(){
        if( !isset( $this->response->pagination->next_max_like_id ) ){
            $this->prevId = 'none';
            $this->nextId = 'none';
        }

        if( isset( $this->response->pagination->next_max_like_id ) ){
            $nextMaxId = $this->response->pagination->next_max_like_id;
            $this->nextId = $this->response->pagination->next_max_like_id;
        }else{
            $nextMaxId = 'none';
            $this->nextId = 'none';
        }

        $_SESSION[ $this->galleryId ] = $this->getActualNavigationHistory( $nextMaxId );
        $this->prevId = $this->getPrevIdFromNavigationHistory();
    }
}
