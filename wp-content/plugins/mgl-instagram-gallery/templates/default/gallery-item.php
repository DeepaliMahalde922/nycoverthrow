<?php
    $profilePicture = false;
    if(isset($galleryItem->caption->from->profile_picture)){
        $profilePicture = $galleryItem->caption->from->profile_picture;
    }

    $captionFromUsername = false;
    if( isset( $galleryItem->caption->from->username ) ){
        $captionFromUsername = $galleryItem->caption->from->username;
    }
?>

<a href="<?php echo $url_big; ?>" class="mgl_instagram_photo" title="<?php echo $description; ?>"<?php echo $target ?>>
    <span class="mgl_instagram_photo_container">
        <span class="mgl_instagram_mask">
            <span class="mgl_instagram_user">
                    
                <span class="mgl_instagram_avatar">
                    <?php if($profilePicture) { ?>
                        <img src="<?php echo $profilePicture; ?>" alt="<?php echo $captionFromUsername; ?>" />
                    <?php } ?>
                </span>

                <?php if( $captionFromUsername ) { ?>
                    <span class="mgl_instagram_name"><?php echo $captionFromUsername ?></span>
                <?php } ?>

            </span>

            <span class="mgl_instagram_info">
                <span class="mgl_instagram_counts">
                    <?php if( isset($galleryItem->likes->count) && $galleryItem->likes->count > 0 ) { ?>
                    <span class="mgl_instagram_likes <?php if( isset($galleryItem->comments->count) && $galleryItem->comments->count > 0 ) { echo 'mgl_instagram_withComments'; } ?>">
                        <i class="mgl_instagram_icon mgl_instagram_icon-heart"></i>
                        <span class="mgl_number"><?php echo $galleryItem->likes->count; ?></span>
                        <span class="mgl_text"><?php _e('Likes', 'mgl_instagram_gallery'); ?></span>
                    </span>
                    <?php } ?>
                    <?php if(isset($galleryItem->comments->count) && $galleryItem->comments->count > 0 ) { ?>
                        <span class="mgl_instagram_comments">
                            <i class="mgl_instagram_icon mgl_instagram_icon-comment"></i>
                            <span class="mgl_number"><?php echo $galleryItem->comments->count; ?></span>
                            <span class="mgl_text"><?php _e('Comments', 'mgl_instagram_gallery'); ?></span>
                        </span>
                    <?php } ?>
                </span>
                <span class="mgl_instagram_text"><?php echo $descriptionShort;  ?></span>
            </span>
            
        </span>
        <img class="mgl_instagram_image" src="<?php echo $galleryItem->images->low_resolution->url ?>" />
    </span>
</a>