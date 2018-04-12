<h2><?php _e('Configuration', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></h2>
<div class="mgl_col_left">
    <form name="mgl_instagram_form" method="post" action="options.php">
        <?php settings_fields( $option_group ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Load jQuery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></th>
                <td>
                    <p class="description">
                        <?php $jquery_checked = ( isset( $settings_data['configuration']['jquery'] ) ) ? $settings_data['configuration']['jquery'] : false; ?>
                        <input type='hidden' value='0' name='<?php echo esc_attr( $option_name."[configuration][jquery]" ); ?>'>
                        <input type="checkbox"
                            name="<?php echo esc_attr( $option_name."[configuration][jquery]" ); ?>"
                            value="1"
                            <?php checked( $jquery_checked ); ?> />
                        <?php _e('Unmark this if you are already loading jQuery', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Custom templates', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></th>
                <td>
                    <input type="text"
                        name="<?php echo esc_attr( $option_name."[settings][custom_templates]" ); ?>"
                        value="<?php echo esc_attr( $settings_data['settings']['custom_templates'] ); ?>"  />
                    <p class="description">
                        <?php _e("Add the names of your custom templates sepparaded by commans, don't use spaces. If you've created a folder inside your theme for the template you don't need to add the name here", MGL_INSTAGRAM_GALLERY_DOMAIN); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Gallery observer', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></th>
                <td>
                    <p class="description">
                        <?php $observer_checked = ( isset( $settings_data['configuration']['observer'] ) ) ? $settings_data['configuration']['observer'] : false; ?>
                        <input type='hidden' value='0' name='<?php echo esc_attr( $option_name."[configuration][observer]" ); ?>'>
                        <input type="checkbox" name="<?php echo esc_attr( $option_name."[configuration][observer]" ); ?>"
                        value="1"
                        <?php checked( $observer_checked ); ?> />
                        <?php _e('Mark this if you have problems loading Instagram galleries via AJAX (It can affect the web performance)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Enable log', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></th>
                <td>
                    <p class="description">
                        <?php $log_checked = ( isset( $settings_data['configuration']['log'] ) ) ? $settings_data['configuration']['log'] : false; ?>
                        <input type='hidden' value='0' name='<?php echo esc_attr( $option_name."[configuration][log]" ); ?>'>
                        <input type="checkbox" name="<?php echo esc_attr( $option_name."[configuration][log]" ); ?>"
                        value="1"
                        <?php checked( $log_checked ); ?> />
                        <?php _e('Mark this to enable Instagram Gallery log (It will be stored inside uploads folder)', MGL_INSTAGRAM_GALLERY_DOMAIN); ?>
                    </p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="Submit" value="<?php _e('Save settings', MGL_INSTAGRAM_GALLERY_DOMAIN ) ?>" />
        </p>
    </form>
</div>
<div class="mgl_col_right">
    <a class="mgl_banner" href="http://codecanyon.net/user/MaGeekLab?ref=mageeklab" title="Follow us on CodeCanyon" target="_blank">
        <img  title="Follow us on CodeCanyon"  alt="Follow us on CodeCanyon" src="<?php echo MGL_INSTAGRAM_GALLERY_URL_BASE ; ?>assets/images/mageeklab_banner_codecanyon.png" />
    </a>
</div>
