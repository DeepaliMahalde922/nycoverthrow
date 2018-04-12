<?php

class MGLInstagramGallery_Template_Template{
    private $template;

    public function __construct( $template )
    {
        $this->template = $template;
    }
    
    public function get_part( $part, $accessibleVars = array() )
    {
        $accessibleVars['template'] = $this->template;
        extract( $accessibleVars );

        $childThemeTemplateUrl   = get_stylesheet_directory() . '/mgl-instagram-gallery/' . $this->template . '/' . $part .'.php';
        $themeTemplateUrl   = get_template_directory() . '/mgl-instagram-gallery/' . $this->template . '/' . $part .'.php';
        $pluginTemplateUrl  = MGL_INSTAGRAM_GALLERY_FILEPATH . '/templates/' . $this->template . '/' . $part .'.php';
        $defaultTemplateUrl = MGL_INSTAGRAM_GALLERY_FILEPATH . '/templates/default/' . $part .'.php';

        if( file_exists( $childThemeTemplateUrl ) ){
            include( $childThemeTemplateUrl );
        }elseif( file_exists( $themeTemplateUrl ) ){
            include( $themeTemplateUrl );
        }elseif( file_exists( $pluginTemplateUrl ) ){
            include( $pluginTemplateUrl );
        } else {
            include( $defaultTemplateUrl );
        }
    }

    public function render( $part, $accessibleVars = array() )
    {
        ob_start();
        $this->get_part( $part, $accessibleVars );
        return ob_get_clean();
    }
}