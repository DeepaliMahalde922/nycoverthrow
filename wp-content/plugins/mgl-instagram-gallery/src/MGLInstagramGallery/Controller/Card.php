<?php
class MGLInstagramGallery_Controller_Card {
	
	private $model,
            $response,
            $args,
            $gallery;

	public  $template,
            $username,
            $user_id;

	public function __construct( $args ){

        $this->args     = $args;
        $this->template = 'basic';
        $this->username = $args['username'];
        $this->user_id  = $args['user_id'];
		
		$this->model 	= new MGLInstagramGallery_Model_Model();
		$this->templating = new MGLInstagramGallery_Template_Template( $this->template );

        if( $this->username != '' ){ 
            $this->user_id = $this->model->get_user_id( $this->username );
        } else {
            throw new Exception( __( 'Error: Username is empty', 'mgl_instagram_gallery') );
        }

        $this->gallery = new MGLInstagramGallery_Controller_User( $this->args );
        
        $this->response = $this->model->get_response( "users/$this->user_id/", null, 3600 );
    }
	
	public function render( ){
        $this->enqueue_styles_and_scripts();
    	
        $html = $this->templating->render('card', array(
            'user' => $this->response->data,
            'gallery' => $this->gallery->render(),
        ));
        return $html;
    }

    public function enqueue_styles_and_scripts( ){
        wp_enqueue_style('mgl_instagram_gallery');
    }
}