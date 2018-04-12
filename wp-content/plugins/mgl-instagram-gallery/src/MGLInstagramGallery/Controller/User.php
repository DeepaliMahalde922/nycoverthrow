<?php
class MGLInstagramGallery_Controller_User extends MGLInstagramGallery_Controller_Base {

    public function __construct( $args ){
        $this->type = 'user';
        parent::__construct( $args );
    }

    function get_request_atts()
    {
        if( $this->username != '' ){ 
            $this->user_id = $this->model->get_user_id( $this->username );
        }

        $request_atts =  array(
            'url' => "users/$this->user_id/media/recent",
            'vars' => array(
                    'count' => $this->count
                )
        );
        if( $this->current_id != '' ) $request_atts['vars']['max_id'] = $this->current_id;

        return $request_atts;
    }
}