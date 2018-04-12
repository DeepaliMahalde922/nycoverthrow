<?php

/**
 * mgl_instagram_types Return an array of the different gallery types the plugin has
 *
 * @param bool $inverse If true, types are returned on an inversed order
 * @return array
 */
function mgl_instagram_types($inverse = false)
{
    // Available types plugin has
    $types = array(
        'user' => __('User', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'tag' => __('Tag', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'liked' => __('Liked', MGL_INSTAGRAM_GALLERY_DOMAIN),
        'location' => __('Location', MGL_INSTAGRAM_GALLERY_DOMAIN),
    );

    if ($inverse) {
        // Inverse the type list order
        $types = array_flip($types);
    }

    return $types;
}

/**
 * mgl_instagram_cols Return column options to be used on a select array
 *
 * @param bool $inverse If true, return colum options reversed
 * @return array Columns options
 */
function mgl_instagram_cols($inverse = false)
{
    $column_options = array();
    // Generate options for 12 columns
    for ($i = 1; $i <= 12; $i++) {
        // Set option title, return singular or plural depending on columns number
        $option_title = _n('%d column', '%d columns', $i, MGL_INSTAGRAM_GALLERY_DOMAIN);
        // Add the number of columns on the string
        $column_options[$i] = sprintf($option_title, $i);
    }

    if ($inverse) {
        // Reverse options
        $column_options = array_flip($column_options);
    }

    return $column_options;
}

/**
 * mgl_instagram_print_select Prints a select with the options passed. Allow empty option selection and selected value
 *
 * @param $options Options to be shown on the select
 * @param $selectedOption Preselected option on the select dropdown
 * @param $fieldId Dropdown HTML ID
 * @param $fieldName  Dropdown field name
 * @param bool|string $emptyOption If string set, Custom message for emtpy option will be shown
 * @param bool $withKey If true, print array with array keys as option value
 */
function mgl_instagram_print_select($options, $selectedOption, $fieldId, $fieldName, $emptyOption = false, $withKey = true)
{
    // Set empty option message if $emptyOption is a string
    $emptyOption = (!$emptyOption) ? 'Select Instagram gallery type' : $emptyOption;
    ?>

    <select class="widefat mgl_instagram_gallery_widget_type_selector" id="<?php echo $fieldId; ?>"
            name="<?php echo $fieldName; ?>">
        <option value="none"><?php _e($emptyOption); ?></option>
        <?php
        if ($withKey) {
            // Print dropdown options with key as option value if $withKey is true
            foreach ($options as $key => $option) {
                // Get the option name to be shown on the dropdown option
                $option = (is_array($option)) ? $option['name'] : $option;
                // Check if it is the current selected option
                $selected = ($key == $selectedOption) ? ' selected="selected"' : '';
                // Render the dropdown option
                echo '<option' . $selected . ' value="' . $key . '">' . $option . '</option>';
            }
        } else {
            // Print dropdown option using array value as option value and option name
            foreach ($options as $option) {
                // Check if option is selected
                $selected = ($option == $selectedOption) ? ' selected="selected"' : '';
                // Render dropdown option
                echo '<option' . $selected . ' value="' . $option . '">' . $option . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

/**
 * mgl_instagram_templates Get All available templates list from builtin templates, child themplates and custom templates
 *
 * @param bool $withKey
 * @return array
 */
function mgl_instagram_templates($withKey = false)
{
    // Define the builtin templates
    $builtinTemplates = array('default', 'instagram', 'dark', 'elegant', 'whiteslide', 'darkslide', 'basic');
    // Set theme templates, custom templates and child theme templates lists as empty array
    $themeTemplates = $customTemplates = $childThemeTemplates = array();
    // Get child theme templates directory
    $childThemeTemplatesDirectory = get_stylesheet_directory() . '/mgl-instagram-gallery';
    // Get theme templates directory
    $themeTemplatesDirectory = get_template_directory() . '/mgl-instagram-gallery';
    // Get gallery options
    $instagramGalleryOptions = get_option('MGLInstagramGallery_option_name');
    // Define files to ignore when searching for templates
    $weeds = array('.', '..', '.DS_Store');

    // If there are custom templates stored, get them
    // TODO: Change $instagramGalleryOptions['settings']['custom_templates'] and get it as a param
    if (isset($instagramGalleryOptions['settings']['custom_templates'])) {
        // Get custom template list from stored custom templates
        $customTemplates = explode(',', $instagramGalleryOptions['settings']['custom_templates']);
    }

    if (file_exists($childThemeTemplatesDirectory)) {
        // Find child theme templates
        // TODO: Check why we are not using this child theme templates
        $childThemeTemplates = array_diff(scandir($childThemeTemplatesDirectory), $weeds);
    }

    if (file_exists($themeTemplatesDirectory)) {
        // Find theme templates
        $themeTemplates = array_diff(scandir($themeTemplatesDirectory), $weeds);
    }
    // Merge all templates in one list
    $templates = array_merge($builtinTemplates, $themeTemplates, $childThemeTemplates, $customTemplates);
    // Trim template names to avoid file names errors
    $templates = array_map('trim', $templates);
    // Delete duplicated names on the list. It prevents get template names twice if custom templates overwrite previous ones
    $templates = array_unique($templates);
    // Delete empty values on templates list
    $templates = array_filter( $templates );

    if ($withKey) {
        // Add template values as template keys as well
        $templates = array_combine( $templates, $templates);
    }

    return $templates;
}

/**
 * mgl_instagram_format_number Returns a number with the Instagram format
 * @param $number
 * @return string
 */
function mgl_instagram_instagram_format_number($number ) {
    // Set divisor divisor and short letter by number
    if( $number < 1000 ) {
        // If number is less than 1000 do nothing
        return $number;

    }else if ($number > 1000 && $number < 1000000) {
        // Anything between 1000 and a million
        $divisor = 1000;
        $letter = 'k';

    } else if ($number > 1000000 && $number < 1000000000) {
        // Anything between a million and a billion
        $divisor = 1000000;
        $letter = 'm';

    } else {
        // Anything greater than a billion
        $divisor = 1000000000;
        $letter = 'G';
    }

    // Set a decimal precision to 1
    $precision = 1;
    // Set dec point to ','
    // TODO: translate dec point by language
    $dec_point = ',';
    // Set thousand sep to '.'
    // TODO: translate thousand sep by language
    $thousand_sep = '.';
    // Remove extra decimals. Trick because of number_format below also always round up and causes weird results
    // More information: http://php.net/manual/es/function.number-format.php#88424
    $short_number = bcdiv( ( $number / $divisor ), 1, 1 );
    // Get formatted number
    $n_format = trim(number_format( $short_number, $precision, $dec_point, $thousand_sep), 0 . $dec_point) . $letter;

    return $n_format;
}

/**
 * mgl_instagram_format_link return the link without http:// or https://
 *
 * @param $str
 * @return mixed
 */
function mgl_instagram_format_link($str)
{
    // Remove http:// and https:// from url
    $str = preg_replace('#^https?://#', '', $str);
    return $str;
}