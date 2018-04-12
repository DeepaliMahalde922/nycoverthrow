<?php

add_action('vc_before_init', 'mgl_instagram_map_shortcodes_in_vc');

function mgl_instagram_map_shortcodes_in_vc()
{

	$default_params = array(
		array(
			"type" => "textfield",
			"heading" => __("Number", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "number",
			"value" => 12, //Default Red color
			"description" => __("Number of media to display", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "dropdown",
			"heading" => __("Columns", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "cols",
			"value" => mgl_instagram_cols(true),
			"std" => 4,
			"description" => __("Number of columns of the gallery", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "dropdown",
			"heading" => __("Pagination", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "pagination",
			"value" => array(
				__("Yes", MGL_INSTAGRAM_GALLERY_DOMAIN) => "true",
				__("No", MGL_INSTAGRAM_GALLERY_DOMAIN) => "false"
			),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN),
		),
		array(
			"type" => "textfield",
			"heading" => __("Cache", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "cache",
			"value" => 3600,
			"description" => __("Time in seconds gallery will be cached, by default 3600 seconds (1 hour)", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN),
		),
		array(
			"type" => "checkbox",
			"heading" => __("Video", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "video",
			"value" => array(__('Exclude videos from the gallery?', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'false'),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			'type' => 'checkbox',
			'heading' => __('Direct link', MGL_INSTAGRAM_GALLERY_DOMAIN),
			'param_name' => 'direct_link',
			'std' => 'false',
			'value' => array(__('Link images directly to Instagram?', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'true'),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			'type' => 'checkbox',
			'heading' => __('Javascript', MGL_INSTAGRAM_GALLERY_DOMAIN),
			'param_name' => 'disable_js',
			'value' => array(__('Disable javascript', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'true'),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "checkbox",
			"heading" => __("Responsive", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "responsive",
			"value" => array(__('Prevent gallery to auto-resize depending on device size?', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'false'),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"heading" => __("Next page text", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "next_text",
			"value" => __("Next", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"description" => __("The text for the next page link", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"heading" => __("Previous page text", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "previous_text",
			"value" => __("Previous", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"description" => __("The text for the previous page link", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"heading" => __("Cut text", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "cut_text",
			"value" => 80,
			"description" => __("The max number of characters allowed in the thumbnail", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			'type' => 'checkbox',
			'heading' => __('RTL Mode', MGL_INSTAGRAM_GALLERY_DOMAIN),
			'param_name' => 'rtl',
			'value' => array(__('Enable rtl mode', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'true'),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			'type' => 'checkbox',
			'heading' => __('Debug', MGL_INSTAGRAM_GALLERY_DOMAIN),
			'param_name' => 'debug',
			'value' => array(__('Enable debug mode', MGL_INSTAGRAM_GALLERY_DOMAIN) => 'true'),
			"group" => __("Advanced", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),

	);

	$gallery_params = array(
		array(
			"type" => "dropdown",
			"holder" => "span",
			"heading" => __("Type", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "type",
			"value" => mgl_instagram_types(true),
			"std" => "_user",
			"description" => __("Choose the type of gallery", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("Username", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "username",
			"dependency" => array('element' => 'type', 'value' => array('user')),
			"description" => __("Username to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("User ID", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "user_id",
			"dependency" => array('element' => 'type', 'value' => array('user')),
			"description" => __("User ID to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("Tag", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "tag",
			"dependency" => array('element' => 'type', 'value' => array('tag')),
			"description" => __("Tag to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("Location ID", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "location_id",
			"dependency" => array('element' => 'type', 'value' => array('location')),
			"description" => __("Location ID to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "dropdown",
			"heading" => __("Template", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "template",
			"value" => mgl_instagram_templates(),
			"description" => __("Choose the gallery you most like to display your media", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"group" => __("Style", MGL_INSTAGRAM_GALLERY_DOMAIN),
		)
	);

	$card_params = array(
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("Username", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "username",
			"description" => __("Username to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
		array(
			"type" => "textfield",
			"holder" => "strong",
			"heading" => __("User ID", MGL_INSTAGRAM_GALLERY_DOMAIN),
			"param_name" => "user_id",
			"description" => __("User ID to pull media from", MGL_INSTAGRAM_GALLERY_DOMAIN)
		),
	);


	vc_map(array(
		"name" => __("Instagram Gallery", MGL_INSTAGRAM_GALLERY_DOMAIN),
		"description" => __("By MaGeek Lab", MGL_INSTAGRAM_GALLERY_DOMAIN),
		"base" => 'mgl_instagram_gallery',
		"class" => "mgl_instagram",
		"controls" => "full",
		"icon" => "mgl_instagram",
		"category" => __('Social', MGL_INSTAGRAM_GALLERY_DOMAIN),
		'admin_enqueue_css' => array(plugins_url('assets/css/mgl_vc_extend_admin.css', __FILE__)),
		"params" => array_merge(
			$gallery_params,
			$default_params
		)
	));

	vc_map(array(
		"name" => __("Instagram Card", MGL_INSTAGRAM_GALLERY_DOMAIN),
		"description" => __("By MaGeek Lab", MGL_INSTAGRAM_GALLERY_DOMAIN),
		"base" => 'mgl_instagram_card',
		"class" => "mgl_instagram",
		"controls" => "full",
		"icon" => "mgl_instagram",
		"category" => __('Social', MGL_INSTAGRAM_GALLERY_DOMAIN),
		'admin_enqueue_css' => array(plugins_url('assets/css/mgl_vc_extend_admin.css', __FILE__)),
		"params" => array_merge(
			$card_params,
			$default_params
		)
	));

}

?>