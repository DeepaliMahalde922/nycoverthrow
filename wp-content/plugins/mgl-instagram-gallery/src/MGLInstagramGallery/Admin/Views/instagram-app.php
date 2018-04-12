<?php global $mgl_ig; ?>

<div class="wpbody-content">
    <div class="wrap">
        <h2><?php _e('Instagram application', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h2>
        <div class="mgl_col_left">
            <?php if( false == $access_token ): ?>
                <form name="mgl_instagram_form" method="post" action="options.php">
                    <?php settings_fields( $option_group ); ?>
                    <p><?php _e('Type your Envato purchase code to connect with instagram servers', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></p>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Envato item purchase code', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></th>
                            <td>
                                <input class="regular-text" type="text"
                                name="<?php echo esc_attr( $option_name."[configuration][purchase_code]" ); ?>"
                                value="<?php echo esc_attr( $settings_data['configuration']['purchase_code'] ); ?>"  />
                                <p class="description"><?php _e("You don't know how to get your item purchase code?", MGL_INSTAGRAM_GALLERY_DOMAIN); ?> <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><?php _e("Check here", MGL_INSTAGRAM_GALLERY_DOMAIN); ?></a></p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <input class="button" type="submit" name="Submit" value="<?php _e('Save the purchase code', MGL_INSTAGRAM_GALLERY_DOMAIN ) ?>" />
                        <?php if( isset($authorize_url) ): ?>
                            <a class="button button-primary" href="<?php echo $authorize_url; ?>"><?php _e('Authorize app', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></a>
                        <?php endif; ?>
                    </p>
                </form>
            <?php else: ?>
                <div class="mgl_instagram_loggedin_card">
                    <img src="<?php echo MGL_INSTAGRAM_GALLERY_URL_ASSETS; ?>/images/mgl_instagram_glyph.png" alt="Instagram Icon">
                    <p><?php _e('You are currently logged in', MGL_INSTAGRAM_GALLERY_DOMAIN ); ?></p>
                    <?php
                        $tab_url = menu_page_url( $mgl_ig['settings_page_properties']['menu_slug'], false );
                        $logout_url = add_query_arg( array(
                            'delete_instagram_account' => 'true',
                            'tab' => 'instagram-app'
                        ), $tab_url );
                    ?>
                    <a class="button" href="<?php echo $logout_url; ?>"><?php _e('Logout', MGL_INSTAGRAM_GALLERY_DOMAIN ) ?></a>
                </div>
            <?php endif; ?>
        </div>
        <div class="mgl_col_right">
            <a class="mgl_banner" href="http://codecanyon.net/user/MaGeekLab?ref=mageeklab" title="Follow us on CodeCanyon" target="_blank">
                <img  title="Follow us on CodeCanyon"  alt="Follow us on CodeCanyon" src="<?php echo MGL_INSTAGRAM_GALLERY_URL_BASE ; ?>assets/images/mageeklab_banner_codecanyon.png" />
            </a>
        </div>
    </div>
</div>
