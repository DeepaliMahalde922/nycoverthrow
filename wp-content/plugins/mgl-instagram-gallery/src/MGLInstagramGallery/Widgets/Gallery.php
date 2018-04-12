<?php

class MGLInstagramGallery_Widgets_Gallery extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'mgl_instagram_gallery', // Base ID
            __( 'Instagram Gallery', MGL_INSTAGRAM_GALLERY_DOMAIN ), // Name
            array( 
                'description' => __('By MaGeek Lab', MGL_INSTAGRAM_GALLERY_DOMAIN)
            ) // Args
        );
    }

    /* Display Widget */
    function widget( $args, $instance ) {
        
        extract( $args );

        $title = $instance['title'];

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if ( $title )
            echo $before_title . $title . $after_title;

        $shortcodeAttributes = '';
        foreach( $instance as $argKey => $argVal ){
            $shortcodeAttributes .= " $argKey" . '="'. (string)$argVal . '"';
        }

        echo do_shortcode( '[mgl_instagram_gallery ' . $shortcodeAttributes . ' ]' );

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function form( $instance ){
        
        /* Set up some default widget settings. */
        $defaults = array(
        'title'         => __('My Instagram gallery', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'type'          => '',
        'username'      => '',
        'user_id'       => '',
        'location_id'   => '',
        'number'        => 4,
        'tag'           => '',
        'cols'          => 2,
        'cache'         => 3600,
        'skin'          => 'default',
        'video'         => 'true',
        'responsive'    => 'true',
        'pagination'    => 'true',
        'direct_link'   => 'false',
        'disable_js'    => 'false',
        'next_text'     => __('Next', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'previous_text' => __('Previous', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'rtl' => 'false'
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'mgl_instagram_gallery') ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
            </p>   
            
            <p>
                <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type', MGL_INSTAGRAM_GALLERY_DOMAIN ); ?>:</label> 
                <?php 
                    mgl_instagram_print_select(
                        mgl_instagram_types(), 
                        esc_attr( $instance['type'] ), 
                        $this->get_field_id( 'type' ), 
                        $this->get_field_name( 'type' )
                    );
                ?>
            </p>
            
            <p class="mgl_instagram_gallery_widget_field_username mgl_instagram_gallery_widget_field_optional">
                <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Username', 'mgl_instagram_gallery') ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
            </p>

             <p class="mgl_instagram_gallery_widget_field_user_id  mgl_instagram_gallery_widget_field_optional">
                <label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e('User ID', 'mgl_instagram_gallery') ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'user_id' ); ?>" name="<?php echo $this->get_field_name( 'user_id' ); ?>" value="<?php echo $instance['user_id']; ?>" />
            </p>

            <p class="mgl_instagram_gallery_widget_field_tag mgl_instagram_gallery_widget_field_optional">
                <label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e('Tag', 'mgl_instagram_gallery') ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo $instance['tag']; ?>" />
            </p>

            <p class="mgl_instagram_gallery_widget_field_location_id mgl_instagram_gallery_widget_field_optional">
                <label for="<?php echo $this->get_field_id( 'location_id' ); ?>"><?php _e('Location ID', 'mgl_instagram_gallery') ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'location_id' ); ?>" name="<?php echo $this->get_field_name( 'location_id' ); ?>" value="<?php echo $instance['location_id']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
            </p>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e('Columns', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        mgl_instagram_cols(), 
                        esc_attr( $instance['cols'] ), 
                        $this->get_field_id( 'cols' ), 
                        $this->get_field_name( 'cols' ),
                        'Select the number of columns'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'cache' ); ?>"><?php _e('Cache', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" value="<?php echo $instance['cache']; ?>" />
            </p>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'skin' ); ?>"><?php _e('Skin', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php $skins = mgl_instagram_templates(); ?>
                <select class="widefat" id="<?php echo $this->get_field_id( 'skin' ); ?>" name="<?php echo $this->get_field_name( 'skin' ); ?>" >
                    <?php foreach ($skins as $key => $skin) { ?>
                        <option value="<?php echo $skin; ?>" <?php if($instance['skin'] == $skin) { echo 'selected="selected"'; } ?>><?php echo $skin; ?></option>
                    <?php } ?>
                </select>
                
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('pagination'); ?>"><?php _e('Pagination', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['pagination'] ), 
                        $this->get_field_id( 'pagination' ), 
                        $this->get_field_name( 'pagination' ),
                        'Select if you want to show pagination'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('video'); ?>"><?php _e('Include videos', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['video'] ), 
                        $this->get_field_id( 'video' ), 
                        $this->get_field_name( 'video' ),
                        'Select if you want include videos too'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('direct_link'); ?>"><?php _e('Direct link to Instagram', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['direct_link'] ), 
                        $this->get_field_id( 'direct_link' ), 
                        $this->get_field_name( 'direct_link' ),
                        'Select if you want direct links to instagram'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('disable_js'); ?>"><?php _e('Disable javascript', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['disable_js'] ), 
                        $this->get_field_id( 'disable_js' ), 
                        $this->get_field_name( 'disable_js' ),
                        'Select if you want disable javascript'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('responsive'); ?>"><?php _e('Responsive', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['responsive'] ), 
                        $this->get_field_id( 'responsive' ), 
                        $this->get_field_name( 'responsive' ),
                        'Select if you want activate responsive mode'
                    );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'next_text' ); ?>"><?php _e('Next page text', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'next_text' ); ?>" name="<?php echo $this->get_field_name( 'next_text' ); ?>" value="<?php echo $instance['next_text']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'previous_text' ); ?>"><?php _e('Previous page text', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'previous_text' ); ?>" name="<?php echo $this->get_field_name( 'previous_text' ); ?>" value="<?php echo $instance['previous_text']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('rtl'); ?>"><?php _e('RTL Language', MGL_INSTAGRAM_GALLERY_DOMAIN) ?>:</label>
                <?php 
                    mgl_instagram_print_select(
                        array(
                            'true'  => __('Yes', MGL_INSTAGRAM_GALLERY_DOMAIN),
                            'false' => __('No', MGL_INSTAGRAM_GALLERY_DOMAIN)
                        ), 
                        esc_attr( $instance['rtl'] ), 
                        $this->get_field_id( 'rtl' ), 
                        $this->get_field_name( 'rtl' ),
                        'Select if you want to show gallery with RTL mode'
                    );
                ?>
            </p>

        <?php
    }

    /* Update Widget */
    function update( $new_instance, $old_instance ) {
        $instance = array();

        foreach ($new_instance as $key => $value) {
            $instance[$key] = ( ! empty( $new_instance[$key] ) ) ? strip_tags( $new_instance[$key] ) : '';
        }

        return $instance;
    }
}