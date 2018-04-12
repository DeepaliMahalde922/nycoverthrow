<h2><?php _e('Shortcodes', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h2>
<div class="mgl_col_left">
    <div class="mgl_instagram_box">
        <h3><?php _e('User gallery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <div class="mgl_instagram_box_inside">
            <p><?php _e('If you want to show an user\'s stream simply put', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>:</p>
            [mgl_instagram_gallery type="user" username=""] or [mgl_instagram_gallery type="user" user_id=""]
            <p><em><?php _e('You can show the user photos by his username or user_id, you don\'t need them both!', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></em></p>
        </div>
    </div>
    <div class="mgl_instagram_box">    
        <h3><?php _e('Tag gallery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <div class="mgl_instagram_box_inside">
            <p><?php _e('If you want to show some photos from a specific tag use', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>:</p>
            [mgl_instagram_gallery type="tag" tag="whitecats"]
            <p><em><?php _e('Without the #', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></em></p>
        </div>
    </div>
    <div class="mgl_instagram_box">
        <h3><?php _e('Liked gallery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <div class="mgl_instagram_box_inside">
            <p><?php _e('Show the last liked media from the current user', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>:</p>
            [mgl_instagram_gallery type="liked"]
            <p><em><?php _e('Only works for the authorized user', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></em></p>
        </div>
    </div>
    <div class="mgl_instagram_box">
        <h3><?php _e('Location gallery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <div class="mgl_instagram_box_inside">
            <p><?php _e('Show the last media from a location', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>:</p>
            [mgl_instagram_gallery type="location" location_id=""]
            <p><em><?php _e('Requires location_id parameter', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></em></p>
        </div>
    </div>
    <div class="mgl_instagram_box">
        <h3><?php _e('Search location', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <div class="mgl_instagram_box_inside">
            <p><?php _e('Use this shortcode to find the Instagram\'s ID of a location by it\'s latitude and longitude if you don\'t know it', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>:</p>
            [mgl_instagram_location_search lat="" lng=""]
            <p><em><?php _e('This will print a list of current locations near the latitude and longitude you define, take the ID you need', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></em></p>
        </div>
    </div>
</div>
<div class="mgl_col_right">
    <div class="mgl_instagram_box">
        <h3><?php _e('All parameters', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <table class="mgl_instagram_table">
            <tr>
                <td><strong>username</strong></td>
                <td><?php _e('Username of the user to display photos from, only available in user shortcode', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
                <td><strong>user_id</strong></td>
                <td><?php _e('Id of the user to display photos from, only available in user shortcode', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
                <td><strong>location_id</strong></td>
                <td><?php _e('Id of the location to display photos from, only available in location shortcode', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
                <td><strong>tag</strong></td>
                <td><?php _e('Tag to display photos from, only available in tag shortcode', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
                <td><strong>number</strong></td>
                <td><?php _e('Number of photos to display', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>pagination</strong></td>
               <td><?php _e('Hide or display the "View More" link (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>next_text</strong></td>
               <td><?php _e('Change the default text for the next page link', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>previous_text</strong></td>
               <td><?php _e('Change the default text for the previous page link', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>cols</strong></td>
               <td><?php _e('Columns of the gallery, by default 4', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>cache</strong></td>
               <td><?php _e('Time in seconds while the gallery will not reaload photos, by default is 3600 seconds (one hour)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>video</strong></td>
               <td><?php _e('Include videos in the gallery (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>cut_text</strong></td>
               <td><?php _e('Number of characters to display from the media description (80 by default)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>direct_link</strong></td>
               <td><?php _e('Media will link directly to Instagram\'s web page (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>disable_js</strong></td>
               <td><?php _e('Disable the lightbox of the gallery (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>skin</strong></td>
               <td><?php _e('Choose one of the premade skins for the gallery, see below', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>responsive</strong></td>
               <td><?php _e('Enabled by default, resize the number of the columns of the gallery depending on the size of the screen (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>debug</strong></td>
               <td><?php _e('Set it to true will show debug code, only for developers (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
            <tr>
               <td><strong>rtl</strong></td>
               <td><?php _e('Set the gallery in rtl mode (true/false)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></td>
            </tr>
        </table>
    </div>
    <div class="mgl_instagram_box">
        <h3><?php _e('Templates', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h3>
        <table class="mgl_instagram_table">
            <tr>
                <td><strong>default</strong></td>
            </tr>
            <tr>
                <td><strong>basic</strong></td>
            </tr>
            <tr>
                <td><strong>instagram</strong></td>
            </tr>
            <tr>
                <td><strong>whiteslide</strong></td>
            </tr>
            <tr>
                <td><strong>darkslide</strong></td>
            </tr>
            <tr>
                <td><strong>elegant</strong></td>
            </tr>
            <tr>
                <td><strong>dark</strong></td>
            </tr>
        </table>
    </div>
</div>