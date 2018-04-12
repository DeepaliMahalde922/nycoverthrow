<?php
class MGLInstagramGallery_Controller_Location extends MGLInstagramGallery_Controller_Base {

    public function __construct( $args ){
        $this->type = 'location';
        parent::__construct( $args );
    }

    public function get_request_atts(){

        if($this->location_id == 0) {
            throw new Exception('You need to setup a location_id!');
        }

        $request_atts =  array(
            'url' => "locations/$this->location_id/media/recent",
            'vars' => array(
                    'count' => $this->count
                )
        );
        if( $this->current_id != '' ) $request_atts['vars']['max_id'] = $this->current_id;

        return $request_atts;
    }

}
