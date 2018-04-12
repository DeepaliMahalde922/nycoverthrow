<?php

$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

if ( file_exists( $root.'/wp-load.php' ) ) {
    require_once( $root.'/wp-load.php' );
//    require_once( $root.'/wp-config.php' );
} else {
	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	if ( file_exists( $root.'/wp-load.php' ) ) {
    require_once( $root.'/wp-load.php' );
//    require_once( $root.'/wp-config.php' );
	}
}

$header_bottom_border_width = 1;
header("Content-type: text/css; charset=utf-8");

?>

<?php if (!empty($qode_options['selection_color'])) { ?>
    /* Webkit */
    ::selection {
    background: <?php echo $qode_options['selection_color'];  ?>;
    }
<?php } ?>
<?php if (!empty($qode_options['selection_color'])) { ?>
    /* Gecko/Mozilla */
    ::-moz-selection {
    background: <?php echo $qode_options['selection_color'];  ?>;
    }
<?php } ?>

<?php if (!empty($qode_options['first_color'])) { ?>

	h1 a:hover,
	h2 a:hover,
	h3 a:hover,
	h4 a:hover,
	h5 a:hover,
	h6 a:hover,
	.blog_holder article.sticky .post_text h3 a,
	.blog_holder.masonry article.sticky .post_text h5 a,
	.blog_holder.masonry_full_width article.sticky .post_text h5 a,
	.blog_holder article .post_info,
	.blog_holder article .post_info a,
	.blog_holder article.format-quote .post_text:hover .post_info .social_share_dropdown span,
	.blog_holder article.format-link .post_text:hover .post_info .social_share_dropdown span,
	.latest_post_inner .post_comments:hover i,
	.blog_holder article .post_description a:hover,
	.blog_holder article .post_description .post_comments:hover,
	.blog_like a:hover i,
	.blog_like a.liked i,
	.blog_like a:hover span,
	.blog_holder article.format-quote .post_text i.qoute_mark,
	.blog_holder article.format-link .post_text i.link_mark,
	.single_tags  a,
	.widget .tagcloud a,
	.comment_holder .comment .text .replay,
	.comment_holder .comment .text .comment-reply-link,
	.comment_holder .comment .text .replay:hover,
	.comment_holder .comment .text .comment-reply-link:hover,
	div.comment_form form p.logged-in-as a,
	.blog_holder.masonry .post_author a,
	.blog_holder.masonry_full_width .post_author a,
	.blog_holder.masonry article .post_info a:hover,
	.blog_holder.masonry_full_width article .post_info a:hover,
	.q_masonry_blog article .q_masonry_blog_post_info a:hover,
	.latest_post_holder .post_info_section.date_hour_holder:hover,
	.latest_post_holder.boxes .latest_post_author_holder a,
	.latest_post_inner .post_infos a:hover,
	.latest_post_holder .post_info_section:hover .latest_post_info_icon,
	header:not(.with_hover_bg_color) nav.main_menu > ul > li:hover > a,
	nav.main_menu>ul>li.active > a,
	.drop_down .second .inner > ul > li > a:hover,
	.drop_down .second .inner ul li.sub ul li a:hover,
	nav.mobile_menu ul li a:hover,
	nav.mobile_menu ul li.active > a,
	.side_menu_button > a:hover,
	.mobile_menu_button span:hover,
	.vertical_menu ul li a:hover,
	.vertical_menu_toggle .second .inner ul li a:hover,
	.header_top #lang_sel ul li ul li a:hover,
	.header_top #lang_sel_click ul li ul li a:hover,
	.header_top #lang_sel_list ul li a.lang_sel_sel,
	.header_top #lang_sel_list ul li a:hover,
	aside .widget #lang_sel a.lang_sel_sel:hover,
	aside .widget #lang_sel_click a.lang_sel_sel:hover,
	aside .widget #lang_sel ul ul a:hover,
	aside .widget #lang_sel_click ul ul a:hover,
	aside .widget #lang_sel_list li a.lang_sel_sel,
	aside .widget #lang_sel_list li a:hover,
	.portfolio_detail .info .info_section_title,
	.portfolio_detail .info .info_section_title a,
	.portfolio_detail .social_share_icon,
	.portfolio_detail .social_share_holder:hover .social_share_title,
	.portfolio_navigation .portfolio_prev a:hover,
	.portfolio_navigation .portfolio_next a:hover,
	.portfolio_navigation .portfolio_button a:hover,
	.projects_holder article .portfolio_description .project_category,
	.projects_holder.hover_text article .project_category,
	.portfolio_slider li.item .project_category,
	.q_accordion_holder.accordion .ui-accordion-header:hover,
	.q_accordion_holder.accordion.with_icon .ui-accordion-header i,
	.q_accordion_holder.boxed .ui-state-active .tab-title,
	blockquote.with_quote_icon i,
	.q_dropcap,
	.testimonials .testimonial_text_inner p.testimonial_author span.author_company,
	.testimonial_content_inner .testimonial_author .company_position,
	.q_tabs .tabs-nav li.active a:hover,
	.q_tabs .tabs-nav li a:hover,
	.q_tabs.horizontal .tabs-nav li.active a,
	.price_in_table .value,
	.price_in_table .price,
	.q_font_elegant_holder.q_icon_shortcode:hover,
	.q_font_awsome_icon_holder.q_icon_shortcode:hover,
	.q_icon_with_title.normal_icon .icon_holder:hover .icon_text_icon,
	.box_holder_icon_inner.normal_icon .icon_holder_inner:hover .icon_text_icon,
	.q_progress_bars_icons_inner.square .bar.active i,
	.q_progress_bars_icons_inner.circle .bar.active i,
	.q_progress_bars_icons_inner.normal .bar.active i,
	.q_progress_bars_icons_inner .bar.active i.fa-circle,
	.q_progress_bars_icons_inner.square .bar.active .q_font_elegant_icon,
	.q_progress_bars_icons_inner.circle .bar.active .q_font_elegant_icon,
	.q_progress_bars_icons_inner.normal .bar.active .q_font_elegant_icon,
	.q_list.number ul>li:before,
	.social_share_list_holder ul li i:hover,
	.service_table_inner li.service_table_title_holder .service_table_icon,
	.qbutton:not(.white):hover,
	.load_more a:hover,
	.blog_load_more_button a:hover,
	#submit_comment:hover,
	.drop_down .wide .second ul li .qbutton:hover,
	.drop_down .wide .second ul li ul li .qbutton:hover,
	nav.content_menu ul li.active:hover i,
	nav.content_menu ul li:hover i,
	nav.content_menu ul li.active:hover a,
	nav.content_menu ul li:hover a,
	.more_facts_button:hover,
	aside.sidebar .widget a:hover,
	.header-widget.widget_nav_menu ul.menu li a:hover,
	.breadcrumb a:hover,
	.title.breadcrumbs_title .breadcrumb a:hover,
	.title.breadcrumbs_title .breadcrumb span.current,
    input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover{
	    color: <?php echo $qode_options['first_color']; ?>;
	}

	.header_top #lang_sel > ul > li > a:hover,
	.header_top #lang_sel_click > ul > li> a:hover,
	.filter_holder ul li.active span,
	.filter_holder ul li:hover span,
	.q_social_icon_holder.normal_social:hover .simple_social,
	.q_steps_holder .circle_small:hover span,
	.q_steps_holder .circle_small:hover .step_title,
    .social_share_holder:hover > a{
		color: <?php echo $qode_options['first_color']; ?> !important;
	}

	.blog_holder article .post_text .post_info .post_author,
	.blog_holder article .post_text .post_info .time,
	.blog_holder article .post_text .post_info .post_category,
	.blog_holder article .post_text .post_info .post_comments,
	.blog_holder.masonry article .post_text .post_info .post_comments a,
	.blog_holder article .post_text .post_info .blog_like,
	.blog_holder article .post_text .post_info .blog_share,
	.drop_down .second,
    .drop_down .narrow .second .inner ul li ul,
	.header_top #lang_sel ul ul ,
	.header_top #lang_sel_click ul ul,
	.portfolio_slides .hover_feature_holder_inner .qbutton:hover,
	.q_accordion_holder.accordion .ui-accordion-header.ui-state-active .accordion_mark,
	.q_accordion_holder.accordion.boxed .ui-accordion-header.ui-state-active,
	.testimonials_holder.light .flex-direction-nav a:hover,
	.q_font_awsome_icon_square:hover,
	.q_font_awsome_icon_circle:hover,
	.q_font_elegant_holder.circle:hover,
	.q_font_elegant_holder.square:hover,
	.q_font_elegant_holder.circle:hover,
	.q_font_awsome_icon_circle:hover,
	.q_progress_bars_icons_inner.circle .bar .bar_noactive,
	.q_progress_bars_icons_inner.square .bar .bar_noactive,
	.page_share,
	.q_steps_holder .circle_small_wrapper,
	.animated_icon_inner span.animated_icon_back .animated_icon,
	.qbutton,
	.load_more a,
	.blog_load_more_button a,
	#submit_comment,
    #submit_comment:hover,
	.drop_down .wide .second ul li .qbutton,
	.drop_down .wide .second ul li ul li .qbutton,
	.qbutton.white:hover,
	.qbutton.solid_color,
	.qbutton:hover,
	.header-widget.widget_nav_menu ul ul,
    #back_to_top:hover > span,
    input.wpcf7-form-control.wpcf7-submit,
    input.wpcf7-form-control.wpcf7-submit[disabled],
    input.wpcf7-form-control.wpcf7-submit:not([disabled]),
    input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover{
		border-color: <?php echo $qode_options['first_color']; ?>;
	}

	.q_icon_with_title .icon_holder:hover .icon_holder_inner,
	.q_icon_with_title .icon_holder:hover .fa-stack,
	.q_icon_with_title .icon_holder:hover .q_font_elegant_holder,
	.box_holder_icon_inner.circle .icon_holder_inner:hover,
	.box_holder_icon_inner.square .icon_holder_inner:hover,
	.box_holder_icon_inner.square .icon_holder_inner:hover .fa-stack,
	.box_holder_icon_inner.circle .icon_holder_inner:hover .fa-stack,
	.box_holder_icon_inner .icon_holder_inner:hover .q_font_elegant_holder.circle,
	.box_holder_icon_inner .icon_holder_inner:hover .q_font_elegant_holder.square,
	.q_circles_holder .q_circle_inner:hover .q_circle_inner2,
	.q_circles_holder .q_circle_inner:hover {
		border-color: <?php echo $qode_options['first_color']; ?> !important;
	}

	#respond textarea:focus,
	#respond input[type='text']:focus,
	.contact_form input[type='text']:focus,
	.contact_form  textarea:focus,
	.q_masonry_blog article.format-link:hover,
	.q_masonry_blog article.format-quote:hover,
	.latest_post_holder .latest_post_date .post_publish_day,
	.mejs-controls .mejs-time-rail .mejs-time-current,
	.mejs-controls .mejs-time-rail .mejs-time-handle,
	.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
	.popup_menu:hover .line,
	.popup_menu:hover .line:after,
	.popup_menu:hover .line:before,
	.projects_holder .hover_icon_holder .hover_icon,
	.portfolio_slider .hover_icon_holder .hover_icon,
	.projects_holder .hover_icon_holder.portfolio_like,
	.portfolio_slider .hover_icon_holder.portfolio_like,
	.projects_masonry_holder .hover_icon_holder.portfolio_like,
	.q_accordion_holder.accordion .ui-accordion-header.ui-state-active .accordion_mark_icon,
	.q_dropcap.circle,
	.q_dropcap.square,
	.gallery_holder ul li .gallery_hover i,
	.testimonials_holder.light .flex-direction-nav a:hover,
	.q_tabs.vertical .tabs-nav li.active a,
	.q_tabs.boxed .tabs-nav li.active a,
	.q_message,
	.q_price_table.active .active_text,
	.price_table_inner .price_button a:hover,
	.active .price_table_inner .price_button a,
	.q_list.circle ul>li:before,
	.q_list.number.circle_number ul>li:before,
	.vc_text_separator.full div,
	.q_pie_graf_legend ul li .color_holder,
	.q_line_graf_legend ul li .color_holder,
	.q_team .q_team_text_inner .separator,
	.circle_item .circle:hover,
	.qode_call_to_action.container,
	.animated_icon_inner span.animated_icon_back .animated_icon,
	.q_progress_bar .progress_content,
	.q_progress_bars_vertical .progress_content_outer .progress_content,
	.qbutton,
	.load_more a,
	.blog_load_more_button a,
	#submit_comment,
	.drop_down .wide .second ul li .qbutton,
	.drop_down .wide .second ul li ul li .qbutton,
	.qbutton.white:hover,
	.qbutton.solid_color,
	#wp-calendar td#today,
    #back_to_top:hover > span,
    .latest_post_holder.boxes .q_font_elegant_holder.circle,
    .blog_holder.masonry article .q_font_elegant_holder.circle,
    input.wpcf7-form-control.wpcf7-submit,
    div.wpcf7-response-output.wpcf7-mail-sent-ok,
    div.wpcf7-response-output.wpcf7-validation-errors{
		background-color: <?php echo $qode_options['first_color']; ?>;
	}

	.blog_holder article.format-link .post_text:hover .post_text_inner,
	.blog_holder article.format-quote .post_text:hover .post_text_inner,
	.q_icon_with_title .icon_holder:hover .fa-stack,
	.q_icon_with_title.circle .icon_holder:hover .q_font_elegant_holder,
	.q_icon_with_title.square .icon_holder:hover .q_font_elegant_holder,
	.box_holder_icon_inner.square .icon_holder_inner:hover .fa-stack,
	.box_holder_icon_inner.circle .icon_holder_inner:hover .fa-stack,
	.box_holder_icon_inner .icon_holder_inner:hover .q_font_elegant_holder.circle,
	.box_holder_icon_inner .icon_holder_inner:hover .q_font_elegant_holder.square,
	.q_circles_holder .q_circle_inner:hover .q_circle_inner2 {
		background-color: <?php echo $qode_options['first_color']; ?> !important;
	}

	<?php if(function_exists('is_woocommerce')) { ?>
		.myaccount_user a,
		.woocommerce .button:hover,
		.woocommerce-page .button:hover,
		.woocommerce #submit:hover,
		.woocommerce ul.products li.product a.qbutton:hover,
		.woocommerce-page ul.products li.product a.qbutton:hover,
		.woocommerce ul.products li.product .added_to_cart:hover,
		.woocommerce .select2-results li.select2-highlighted,
		.woocommerce-page .select2-results li.select2-highlighted,
		.woocommerce-checkout .chosen-container .chosen-results li.active-result.highlighted,
		.woocommerce-account .chosen-container .chosen-results li.active-result.highlighted,
		.woocommerce ins, .woocommerce-page ins,
		.woocommerce ul.products li.product .price,
		.woocommerce ul.products li.product:hover h6,
		.woocommerce .product .woocommerce-product-rating .woocommerce-review-link:hover,
		.woocommerce div.product div.product_meta > span a:hover,
		.woocommerce div.cart-collaterals div.cart_totals table tr.order-total strong span.amount,
		.woocommerce-page div.cart-collaterals div.cart_totals table tr.order-total strong span.amount,
		.woocommerce div.cart-collaterals div.cart_totals table tr.order-total strong,
		.woocommerce .checkout-opener-text a,
		.woocommerce form.checkout table.shop_table tfoot tr.order-total th,
		.woocommerce form.checkout table.shop_table tfoot tr.order-total td span.amount,
		.woocommerce aside ul.product_list_widget li > a:hover,
		aside ul.product-categories li > a:hover,
		.woocommerce aside ul.product_list_widget li span.amount,
		aside ul.product_list_widget li span.amount,
		.woocommerce .widget_shopping_cart_content p.buttons a.button:hover,
		.woocommerce aside .widget ul.product-categories a:hover,
		aside .widget ul.product-categories a:hover,
		.woocommerce-page aside .widget ul.product-categories a:hover,
		.woocommerce .widget_shopping_cart_content .total .amount,
		.woocommerce-page .widget_shopping_cart_content .total .amount,
		.shopping_cart_header .header_cart:hover i,
		.shopping_cart_dropdown ul li a:hover,
		.shopping_cart_dropdown span.total span,
		.shopping_cart_dropdown .cart_list span.quantity {
			color: <?php echo $qode_options['first_color']; ?>;
		}

		.woocommerce .button,
		.woocommerce-page .button,
		.woocommerce-page input[type="submit"],
		.woocommerce input[type="submit"],
		.woocommerce ul.products li.product .added_to_cart,
		.woocommerce .quantity .minus:hover,
		.woocommerce #content .quantity .minus:hover,
		.woocommerce-page .quantity .minus:hover,
		.woocommerce-page #content .quantity .minus:hover,
		.woocommerce .quantity .plus:hover,
		.woocommerce #content .quantity .plus:hover,
		.woocommerce-page .quantity .plus:hover,
		.woocommerce-page #content .quantity .plus:hover,
		.shopping_cart_dropdown {
			border-color: <?php echo $qode_options['first_color']; ?>;
		}

		.woocommerce input[type='text']:not(.qode_search_field):focus,
		.woocommerce input[type='password']:focus,
		.woocommerce input[type='email']:focus,
		.woocommerce input[type='tel']:focus,
		.woocommerce-page input[type='text']:not(.qode_search_field):focus,
		.woocommerce-page input[type='password']:focus,
		.woocommerce-page input[type='email']:focus,
		.woocommerce-page input[type='tel']:focus,
		.woocommerce textarea:focus,
		.woocommerce-page textarea:focus,
		.woocommerce table.cart div.coupon .input-text:focus,
		.woocommerce-page table.cart div.coupon .input-text:focus,
		.woocommerce.woocommerce-checkout div.coupon .input-text:focus,
		.woocommerce-page.woocommerce-checkout div.coupon .input-text:focus,
		.woocommerce .button,
		.woocommerce-page .button,
		.woocommerce-page input[type="submit"],
		.woocommerce input[type="submit"],
		.woocommerce ul.products li.product .added_to_cart,
		.woocommerce .product .onsale,
		.woocommerce .product .single-onsale,
		.woocommerce .quantity .minus:hover,
		.woocommerce #content .quantity .minus:hover,
		.woocommerce-page .quantity .minus:hover,
		.woocommerce-page #content .quantity .minus:hover,
		.woocommerce .quantity .plus:hover,
		.woocommerce #content .quantity .plus:hover,
		.woocommerce-page .quantity .plus:hover,
		.woocommerce-page #content .quantity .plus:hover,
		.woocommerce .woocommerce-accordion .ui-accordion-header.ui-state-active,
		.woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range,
		.woocommerce-page .widget_price_filter .ui-slider-horizontal .ui-slider-range {
			background-color: <?php echo $qode_options['first_color']; ?>;
		}
	<?php } ?>


<?php } ?>

<?php if (!empty($qode_options['second_color'])) { ?>
	h1 a,
	h2 a,
	h3 a,
	h4 a,
	h5 a,
	h6 a,
	a,
	p a,
	.blog_holder article .post_text h5,
	.blog_holder article.format-quote .post_text .post_title p,
	.blog_holder article.format-link .post_text .post_title p,
	.blog_holder article.format-quote .post_text .post_title p a,
	.blog_holder article.format-link .post_text .post_title p a,
	.blog_holder article.format-quote .post_text .quote_author,
	.comment_holder .comment .text .comment_date,
	.comment_holder .comment .text .name,
	#respond textarea,
	#respond input[type='text'],
	.contact_form input[type='text'],
	.contact_form  textarea,
	.blog_holder.masonry .post_author a:hover,
	.blog_holder.masonry_full_width .post_author a:hover,
	.blog_holder.masonry article .post_info,
	.blog_holder.masonry_full_width article .post_info,
	.blog_holder.masonry article .post_info > a,
	.blog_holder.masonry_full_width article .post_info > a,
	.blog_holder.masonry article .post_info .social_share_holder > a,
	.blog_holder.masonry_full_width article .post_info .social_share_holder > a,
	.latest_post_holder.boxes .latest_post_author_holder a:hover,
	.latest_post_inner .post_infos,
	.latest_post_inner .post_infos a,
	.mejs-container .mejs-controls .mejs-time,
	.mejs-container .mejs-controls .mejs-time span,
	.side_menu_button > a,
	.mobile_menu_button span,
	.portfolio_detail .social_share_title,
	.testimonial_content_inner .testimonial_author .website,
	.price_table_inner ul,
	.price_in_table .mark,
	.q_counter_holder span.counter,
	nav.content_menu ul li.active i,
	nav.content_menu ul li.active a,
	.title.breadcrumbs_title .breadcrumb a,
	.title.breadcrumbs_title .breadcrumb span {
		color: <?php echo $qode_options['second_color']; ?>;
	}

	.blog_holder.masonry article .post_text .post_info .post_comments,
	.blog_holder.masonry_full_width article .post_text .post_info .post_comments,
	.blog_holder.masonry article .post_text .post_info .blog_share,
	.blog_holder.masonry_full_width article .post_text .post_info .blog_share,
	.author_description_inner .image,
	.comment_holder .comment .image img,
	.comment_holder .comment .text .comment-reply-link {
		border-color: <?php echo $qode_options['second_color']; ?>;
	}

	.footer_top_holder,
	.qode_search_form,
	.qode_search_form input,
	.qode_search_form input:focus,
	.popup_menu .line:after, .popup_menu .line:before,
	.popup_menu .line,
	.testimonials_holder .flex-direction-nav a:hover {
		background-color: <?php echo $qode_options['second_color']; ?>;
	}

	<?php if(function_exists('is_woocommerce')) { ?>
		.woocommerce div.message,
		.woocommerce .woocommerce-message,
		.woocommerce .woocommerce-error,
		.woocommerce .woocommerce-info,
		.myaccount_user,
		aside.sidebar .woocommerce.widget .product-categories a,
		.woocommerce li.product del,
		.woocommerce-page li.product del,
		aside.sidebar .woocommerce.widget h5,
		.woocommerce aside ul.product_list_widget li a,
		aside ul.product_list_widget li a,
		.woocommerce aside ul.product_list_widget li del span.amount,
		aside ul.product_list_widget li del span.amount,
		.woocommerce .widget_shopping_cart_content .total strong,
		.woocommerce .widget_price_filter .price_label,
		.woocommerce-page .widget_price_filter .price_label,
		.shopping_cart_header .header_cart i,
		.shopping_cart_header .header_cart span,
		.shopping_cart_dropdown ul li a,
		.shopping_cart_dropdown span.total {
			color: <?php echo $qode_options['second_color']; ?>;
		}

		.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
		.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
			border-color: <?php echo $qode_options['second_color']; ?>;
		}


	<?php } ?>

<?php } ?>

<?php if (!empty($qode_options['spinner_color'])) { ?>
    .ajax_loader .pulse,
    .ajax_loader .double_pulse .double-bounce1, .ajax_loader .double_pulse .double-bounce2,
    .ajax_loader .cube,
    .ajax_loader .rotating_cubes .cube1, .ajax_loader .rotating_cubes .cube2,
    .ajax_loader .stripes > div,
    .ajax_loader .wave > div,
    .ajax_loader .two_rotating_circles .dot1, .ajax_loader .two_rotating_circles .dot2,
    .ajax_loader .five_rotating_circles .container1 > div, .ajax_loader .five_rotating_circles .container2 > div, .ajax_loader .five_rotating_circles .container3 > div{
    background-color: <?php echo $qode_options['spinner_color']; ?>;
    }
<?php } ?>
<?php if (!empty($qode_options['background_color']) || !empty($qode_options['text_color']) || !empty($qode_options['text_fontsize']) || !empty($qode_options['text_fontweight']) || $qode_options['google_fonts'] != "-1") { ?>
    body{
    	<?php if($qode_options['google_fonts'] != "-1"){ ?>
    	<?php $font = str_replace('+', ' ', $qode_options['google_fonts']); ?>
    	font-family: '<?php echo $font; ?>', sans-serif;
    	<?php } ?>
    	<?php if (!empty($qode_options['text_color'])) { ?> color: <?php echo $qode_options['text_color'];  ?>; <?php } ?>
    	<?php if (!empty($qode_options['text_fontsize'])) { ?> font-size: <?php echo $qode_options['text_fontsize']; ?>px; <?php } ?>
    	<?php if (!empty($qode_options['text_fontweight'])) { ?>font-weight: <?php echo $qode_options['text_fontweight'];  ?>;<?php } ?>
    }
    <?php if (!empty($qode_options['background_color'])) { ?>
        body,
		.wrapper,
        .content,
        .full_width,
		.content .container,
		.more_facts_holder,
		.comment_holder .comment #respond textarea,
		.comment_holder .comment #respond input[type='text']
		{
        	background-color:<?php echo $qode_options['background_color'];  ?>;
        }
    <?php } ?>
<?php } ?>
<?php if (!empty($qode_options['background_color_box'])) { ?>
    .wrapper{
    	<?php if (!empty($qode_options['background_color_box'])) { ?> background-color:<?php echo $qode_options['background_color_box'];  ?>; <?php } ?>
    }
<?php } ?>
<?php
$boxed = "no";
if (isset($qode_options['boxed']))
	$boxed = $qode_options['boxed'];
?>
<?php if($boxed == "yes"){ ?>
body.boxed .wrapper{
	<?php if (!empty($qode_options['background_color_box'])) { ?> background-color:<?php echo $qode_options['background_color_box'];  ?>; <?php } ?>

	<?php if($qode_options['pattern_background_image'] != ""){  ?>
		background-image: url('<?php echo $qode_options['pattern_background_image'] ?>');
		background-position: 0px 0px;
		background-repeat: repeat;
	<?php } ?>

	<?php if($qode_options['background_image'] != ""){  ?>
		background-image: url('<?php echo $qode_options['background_image'] ?>');
		background-attachment: fixed;
		background-position: center 0px;
		background-repeat: no-repeat;
	<?php } ?>
}
body.boxed .content{
	<?php if (!empty($qode_options['background_color'])) { ?> background-color:<?php echo $qode_options['background_color'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['background_color_boxes'])) { ?>
.projects_holder article .portfolio_description,
.blog_holder.masonry article .post_text .post_text_inner,
.q_team,
.price_table_inner,
.latest_post_holder.boxes > ul > li,
.q_counter_holder.boxed_counter {
	background-color: <?php echo $qode_options['background_color_boxes'];  ?>;
}
<?php } ?>
<?php if (!empty($qode_options['highlight_color'])) { ?>
span.highlight {
	background-color: <?php echo $qode_options['highlight_color'];  ?>;
}
<?php } ?>

<?php if (!empty($qode_options['header_background_color']) || $qode_options['header_background_transparency_initial'] != "") {
	if(!empty($qode_options['header_background_color'])){
		$bg_color = qode_hex2rgb($qode_options['header_background_color']);
	}else{
		$bg_color = qode_hex2rgb('#ffffff');
	}
	if ($qode_options['header_background_transparency_initial'] != "") {
		$bg_color_transparency = $qode_options['header_background_transparency_initial'];
	}else{
		$bg_color_transparency = 1;
	}
?>
.header_bottom,
.header_top {
	background-color: rgba(<?php echo $bg_color[0]; ?>,<?php echo $bg_color[1]; ?>,<?php echo $bg_color[2]; ?>,<?php echo $bg_color_transparency; ?>);
}

<?php if(isset($bg_color_transparency) && $bg_color_transparency == 0) { ?>

.header_bottom,
.header_top {
    border-bottom: 0;
}

.header_bottom {
    box-shadow: none;
}

.header_top .right .inner > div:first-child,
.header_top .right .inner > div,
.header_top .left .inner > div:last-child,
.header_top .left .inner > div {
    border: none;
}

<?php } ?>

<?php } ?>

<?php if (!empty($qode_options['header_separator_color'])) { ?>

.header_top,
.header_bottom,
.title,
.drop_down .second .inner ul li,
.header-widget.widget_nav_menu ul.menu li ul li a,
.header_top #lang_sel ul li ul li a,
.header_top #lang_sel ul li ul li a:visited,
.header_top #lang_sel_click ul li ul li a,
.header_top #lang_sel_click ul li ul li a:visited,
.drop_down .second .inner > ul,
.drop_down .second .inner>ul,
li.narrow .second .inner ul,
.drop_down .wide .second ul li,
.drop_down .second ul li
	{
	border-color:<?php echo $qode_options['header_separator_color'];  ?>;
}

<?php } ?>
<?php if (isset($qode_options['border_bottom_title_area']) && $qode_options['border_bottom_title_area'] == "yes") { ?>
	.title{
		border-bottom-width:1px;
		border-bottom-style:solid;
	}
<?php } ?>
<?php if (!empty($qode_options['border_bottom_title_area_color']) ) { ?>
	.title{
	border-bottom-color:<?php echo $qode_options['border_bottom_title_area_color'];  ?>;
	}
<?php } ?>
<?php if (isset($qode_options['margin_after_title']) && $qode_options['margin_after_title'] !== '') { ?>
	.content .container .container_inner.default_template_holder,
	body.page-template-blog-masonry-full-width-php .content .full_width {
	padding-top:<?php echo $qode_options['margin_after_title'];  ?>px;
	}
<?php } ?>
<?php if (!empty($qode_options['breadcrumbs_color']) ) { ?>
	.breadcrumbs,
	.breadcrumb .current,
	.breadcrumb a{
	color:<?php echo $qode_options['breadcrumbs_color'];  ?>;
	}
<?php } ?>
<?php
if (!empty($qode_options['header_background_color_scroll']) || $qode_options['header_background_transparency_scroll'] != "") {

	if(!empty($qode_options['header_background_color_scroll'])){
		$bg_color_scroll = qode_hex2rgb($qode_options['header_background_color_scroll']);
	}else{
		$bg_color_scroll = qode_hex2rgb('#ffffff');
	}

	if ($qode_options['header_background_transparency_scroll'] != "") {
		$bg_color_scroll_transparency = $qode_options['header_background_transparency_scroll'];
	}else{
		$bg_color_scroll_transparency = 1;
	}
?>
header.fixed.scrolled .header_bottom,
header.fixed_hiding.scrolled .header_bottom,
header.fixed.scrolled .header_top{
	background-color: rgba(<?php echo $bg_color_scroll[0]; ?>,<?php echo $bg_color_scroll[1]; ?>,<?php echo $bg_color_scroll[2]; ?>,<?php echo $bg_color_scroll_transparency; ?>) !important;
}
<?php } ?>

<?php if($qode_options['header_background_transparency_scroll'] != "" && $qode_options['header_background_transparency_scroll'] == 0) { ?>

header.scrolled .header_bottom,
header.scrolled .header_top {
    border-bottom: 0;
}

header.scrolled .header_bottom {
    box-shadow: none;
}

header.scrolled .header_top .right .inner > div:first-child,
header.scrolled .header_top .right .inner > div,
header.scrolled .header_top .left .inner > div:last-child,
header.scrolled .header_top .left .inner > div {
    border: none;
}
<?php } ?>



<?php
if (!empty($qode_options['header_background_color_sticky']) || $qode_options['header_background_transparency_sticky'] != "") {

	if(!empty($qode_options['header_background_color_sticky'])){
		$bg_color_sticky = qode_hex2rgb($qode_options['header_background_color_sticky']);
	}else{
		$bg_color_sticky = qode_hex2rgb('#ffffff');
	}

	if ($qode_options['header_background_transparency_sticky'] != "") {
		$bg_color_sticky_transparency = $qode_options['header_background_transparency_sticky'];
	}else{
		$bg_color_sticky_transparency = 1;
	}
?>
header.sticky .header_bottom{
	background-color: rgba(<?php echo $bg_color_sticky[0]; ?>,<?php echo $bg_color_sticky[1]; ?>,<?php echo $bg_color_sticky[2]; ?>,<?php echo $bg_color_sticky_transparency; ?>) !important;
}
<?php } ?>

<?php if (!empty($qode_options['header_top_background_color']) || $qode_options['header_background_transparency_initial'] != "") {
	if(!empty($qode_options['header_top_background_color'])) {
		$bg_color_top = qode_hex2rgb($qode_options['header_top_background_color']);
	} else {
        $bg_color_top = qode_hex2rgb('#fff');
    }

	if ($qode_options['header_background_transparency_initial'] != "") {
		$bg_color_transparency = $qode_options['header_background_transparency_initial'];
	} else{
		$bg_color_transparency = 1;
	}
?>

.header_top{
	background-color: rgba(<?php echo $bg_color_top[0]; ?>,<?php echo $bg_color_top[1]; ?>,<?php echo $bg_color_top[2]; ?>,<?php echo $bg_color_transparency; ?>);
}
<?php } ?>
<?php if (!empty($qode_options['header_bottom_border_color'])) {
	if (!empty($qode_options['header_botom_border_transparency'])) {
		$header_border_transparency = qode_hex2rgb($qode_options['header_bottom_border_color']);
		?>

	header:not(.sticky):not(.scrolled) .header_bottom{
		border-bottom: 1px solid rgba(<?php echo $header_border_transparency[0]; ?>,<?php echo $header_border_transparency[1]; ?>,<?php echo $header_border_transparency[2]; ?>,<?php echo $qode_options['header_botom_border_transparency']; ?>);
	}
	<?php } else { ?>
	header:not(.sticky):not(.scrolled) .header_bottom{
		border-bottom: 1px solid <?php echo $qode_options['header_bottom_border_color'];  ?>;
	}

<?php } }?>
<?php
if (!empty($qode_options['header_top_background_color']) || $qode_options['header_background_transparency_scroll'] != "") {

	if(!empty($qode_options['header_top_background_color'])){
		$bg_color_scroll_top = qode_hex2rgb($qode_options['header_top_background_color']);
	}else{
		$bg_color_scroll_top = qode_hex2rgb('#000000');
	}

	if ($qode_options['header_background_transparency_scroll'] != "") {
		$bg_color_scroll_transparency = $qode_options['header_background_transparency_scroll'];
	}else{
		$bg_color_scroll_transparency = 0.7;
	}
?>
header.sticky .header_top{
	background-color: rgba(<?php echo $bg_color_scroll_top[0]; ?>,<?php echo $bg_color_scroll_top[1]; ?>,<?php echo $bg_color_scroll_top[2]; ?>,<?php echo $bg_color_scroll_transparency; ?>);
}
<?php } ?>

<?php
$header_bottom_appearance = "fixed";
if (isset($qode_options['header_bottom_appearance'])) {
    $header_bottom_appearance = $qode_options['header_bottom_appearance'];
}
?>

<?php
	$display_header_top = "yes";
	if(isset($qode_options['header_top_area'])){
		$display_header_top = $qode_options['header_top_area'];
	}
	if (!empty($_SESSION['qode_theme19_header_top'])){
		$display_header_top = $_SESSION['qode_theme19_header_top'];
	}

	if($display_header_top == "no"){
		$margin_top_add = 0;
	}else{
		$margin_top_add = 33;
	}
	if (!empty($qode_options['header_height'])) {
		$header_height = $qode_options['header_height'];
	} else {
		$header_height = 100;
	}
	if (!empty($qode_options['header_bottom_border_color'])) {
		$header_height = $header_height + 1;
	}
	if($header_bottom_appearance == "stick menu_bottom") {
		$menu_bottom = 60; // border 1px
		if ($qode_options['center_logo_image'] == "yes") {
			if(is_active_sidebar('header_fixed_right')){
				$menu_bottom = $menu_bottom + 26; // 26 is for right widget in header bottom (line height of text)
			}
		}
	} else {
		$menu_bottom = 0;
	}
	$header_height = $header_height + $menu_bottom;
?>

<?php if ($header_bottom_appearance != "fixed" && $header_bottom_appearance != "fixed_hiding") {?>
	<?php if ($qode_options['center_logo_image'] != "yes") { ?>
		<?php if($header_bottom_appearance == "stick menu_bottom") { ?>
		.content{
			margin-top: <?php echo '-'.($margin_top_add + $header_height); // 30 is top and bottom margin of centered logo  + 6 is neagitve margin on header?>px;
		}
		<?php }  else { ?>
			.content{
				margin-top: <?php echo '-'.($header_height + $margin_top_add); ?>px;
			}
		<?php } ?>

	<?php } else {
			$height = 0;
		?>
		<?php if(isset($qode_options['logo_image'])){
			if (!empty($qode_options['logo_image'])) {
				$logo_url_obj = parse_url($qode_options['logo_image']);
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$logo_url_obj['path']);
			}
		} ?>
		<?php if($header_bottom_appearance == "stick menu_bottom") { ?>
		.content{
			margin-top: <?php echo '-'.(30 + $height + $menu_bottom + $margin_top_add); // 30 is top and bottom margin of centered logo ?>px;
		}
		<?php }  else { ?>
			.content{
				margin-top: <?php echo '-'.(30 + $height + $header_height + $margin_top_add -1); // 30 is top and bottom margin of centered logo, 1 is border ?>px;
			}
		<?php } ?>
	<?php } ?>
<?php } else { ?>
.content{
	margin-top: 0;
}
<?php } ?>

<?php if(!empty($qode_options['search_background_color'])){ ?>
    .qode_search_form,
    .qode_search_form input,
    .qode_search_form input:focus{
        background-color: <?php echo $qode_options['search_background_color']; ?>;
    }
<?php } ?>

<?php if (!empty($qode_options['header_height'])) { ?>
.logo_wrapper,
.side_menu_button,
.shopping_cart_inner
{
	height: <?php echo $qode_options['header_height'];  ?>px;
}
.content.content_top_margin{
	margin-top: <?php echo $qode_options['header_height'] + $margin_top_add;  ?>px !important;
}

header:not(.centered_logo) .header_fixed_right_area {
    line-height: <?php echo $qode_options['header_height'];  ?>px;
}

<?php } ?>

<?php if (!empty($qode_options['header_height_scroll'])) { ?>
header.scrolled .logo_wrapper,
header.scrolled .side_menu_button{
	height: <?php echo $qode_options['header_height_scroll'];  ?>px;
}

header.scrolled nav.main_menu ul li a {
	line-height: <?php echo $qode_options['header_height_scroll'];  ?>px;
}

header.scrolled .drop_down .second{
	top: <?php echo $qode_options['header_height_scroll'];  ?>px;
}
<?php } ?>

<?php if (!empty($qode_options['header_height_sticky'])) { ?>
header.sticky .logo_wrapper,
header.sticky.centered_logo .logo_wrapper,
header.sticky .side_menu_button,
header.sticky .shopping_cart_inner
	{
	height: <?php echo $qode_options['header_height_sticky'];  ?>px !important;
}

header.sticky nav.main_menu > ul > li > a,
.light.sticky nav.main_menu > ul > li > a,
.light.sticky nav.main_menu > ul > li > a:hover,
.light.sticky nav.main_menu > ul > li.active > a,
.dark.sticky nav.main_menu > ul > li > a,
.dark.sticky nav.main_menu > ul > li > a:hover,
.dark.sticky nav.main_menu > ul > li.active > a {
	line-height: <?php echo $qode_options['header_height_sticky'];  ?>px;
}

header.sticky .drop_down .second{
	top: <?php echo $qode_options['header_height_sticky'] + $header_bottom_border_width;  ?>px;
}
<?php } ?>

<?php if(isset($qode_options['header_height_scroll_hidden']) && $qode_options['header_height_scroll_hidden'] !== "") { ?>
    @media only screen and (min-width: 1000px){
        header.fixed_hiding.centered_logo.fixed_hiding .header_inner_left,
        header.fixed_hiding .q_logo_hidden a{
            height: <?php echo $qode_options['header_height_scroll_hidden'];  ?>px;
        }
    }
<?php } ?>

<?php if(isset($qode_options['logo_image'])){
    if (!empty($qode_options['logo_image'])) {
        $logo_url_obj = parse_url($qode_options['logo_image']);
        list($logo_width, $logo_height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$logo_url_obj['path']);
        ?>
        @media only screen and (min-width: 1000px){
            header.fixed_hiding .q_logo a,
            header.fixed_hiding .q_logo{
                max-height: <?php echo $logo_height/2; ?>px;
            }
        }

    <?php
    }
} ?>

<?php
$parallax_onoff = "on";
if (isset($qode_options['parallax_onoff']))
	$parallax_onoff = $qode_options['parallax_onoff'];
if ($parallax_onoff == "off"){
?>
    .touch section.parallax_section_holder{
		height: auto !important;
		min-height: 300px;
		background-position: center top !important;
		background-attachment: scroll;
        background-size: cover;
	}
<?php } ?>
<?php if (!empty($qode_options['header_height'])) { ?>
nav.main_menu > ul > li > a{
	line-height: <?php echo $qode_options['header_height'];  ?>px;
}
<?php } ?>

<?php
if((isset($qode_options['dropdown_background_color']) && $qode_options['dropdown_background_color'] != "") ||
    (isset($qode_options['dropdown_background_transparency'])) && $qode_options['dropdown_background_transparency'] != "") {

    //dropdown background and transparency styles
    $dropdown_bg_styles                 = '';
    $dropdown_bg_color                  = '';
    $dropdown_bg_color_initial          = '#FAFAFA';
    $dropdown_bg_transparency           = '';
    $dropdown_bg_transparency_initial   = 1;

    $dropdown_bg_color        = $qode_options['dropdown_background_color'] != "" ? $qode_options['dropdown_background_color'] : $dropdown_bg_color_initial;
    $dropdown_bg_transparency = $qode_options['dropdown_background_transparency'] != "" ? $qode_options['dropdown_background_transparency'] : $dropdown_bg_transparency_initial;

    $dropdown_bg_color_rgb    = qode_hex2rgb($dropdown_bg_color);

    ?>

    .drop_down .second .inner ul,
    .drop_down .second .inner ul li ul,
	.shopping_cart_dropdown,
    li.narrow .second .inner ul {
    background-color: <?php echo $dropdown_bg_color;  ?>;
    background-color: rgba(<?php echo $dropdown_bg_color_rgb[0]; ?>,<?php echo $dropdown_bg_color_rgb[1]; ?>,<?php echo $dropdown_bg_color_rgb[2]; ?>,<?php echo $dropdown_bg_transparency; ?>);
    }

<?php  } //end dropdown background and transparency styles ?>

<?php
if(!empty($qode_options['dropdown_separator_color'])){
?>
	.drop_down .wide .second ul li{
        border-color: <?php echo $qode_options['dropdown_separator_color']; ?>;
    }
<?php
}
?>

<?php if (!empty($qode_options['menu_color']) || !empty($qode_options['menu_fontsize']) || !empty($qode_options['menu_fontstyle']) || !empty($qode_options['menu_fontweight']) || !empty($qode_options['menu_letter_spacing']) || $qode_options['menu_google_fonts'] != "-1" || $qode_options['menu_letterspacing'] != '') { ?>
nav.main_menu > ul > li > a{
	<?php if (!empty($qode_options['menu_color'])) { ?> color: <?php echo $qode_options['menu_color'];  ?>; <?php } ?>
	<?php if($qode_options['menu_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['menu_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['menu_fontsize'])) { ?> font-size: <?php echo $qode_options['menu_fontsize'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['menu_lineheight'])) { ?> line-height: <?php echo $qode_options['menu_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['menu_fontstyle'])) { ?> font-style: <?php echo $qode_options['menu_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['menu_fontweight'])) { ?> font-weight: <?php echo $qode_options['menu_fontweight'];  ?>; <?php } ?>
	<?php if ($qode_options['menu_letterspacing'] != '') { ?> letter-spacing: <?php echo $qode_options['menu_letterspacing'];  ?>px; <?php } ?>
}

<?php } ?>
<?php if (!empty($qode_options['menu_hovercolor'])) { ?>
nav.main_menu > ul > li:hover > a,
header:not(.with_hover_bg_color) nav.main_menu > ul > li:hover > a{

	color: <?php echo $qode_options['menu_hovercolor'];  ?>;
}
<?php } ?>

<?php if(isset($qode_options['menu_activecolor']) && $qode_options['menu_activecolor'] !== '') { ?>
    nav.main_menu>ul>li.active > a{
	    color: <?php echo $qode_options['menu_activecolor']; ?>;
	}
<?php } ?>

<?php if(!empty($qode_options['menu_separator_color']) || !empty($qode_options['menu_fontsize'])) { ?>
    nav.main_menu > ul > li:not(:first-child):before,
    header.stick_with_left_right_menu.sticky nav.main_menu.right_side > ul > li:first-child:before{
        <?php if (!empty($qode_options['menu_separator_color'])) { ?> color: <?php echo $qode_options['menu_separator_color']; ?>; <?php } ?>
        <?php if (!empty($qode_options['menu_fontsize'])) { ?> font-size: <?php echo $qode_options['menu_fontsize'];  ?>px; <?php } ?>
    }
<?php } ?>

<?php if(isset($qode_options['menu_remove_separator_between_items'])) { ?>
	nav.main_menu > ul > li:not(:first-child):before { content: none; }
<?php } ?>

<?php if(isset($qode_options['menu_hover_background_color']) && $qode_options['menu_hover_background_color'] !== '') {
	$menu_hover_background_color = $qode_options['menu_hover_background_color'];

	if(isset($qode_options['menu_hover_background_color_transparency']) && $qode_options['menu_hover_background_color_transparency'] !== '') {
		$menu_hover_background_color_rgb = qode_hex2rgb($menu_hover_background_color);
		$menu_hover_background_color = 'rgba('.$menu_hover_background_color_rgb[0].', '.$menu_hover_background_color_rgb[1].', '.$menu_hover_background_color_rgb[2].', '.$qode_options['menu_hover_background_color_transparency'].')';
	}
	?>
	nav.main_menu > ul > li:hover > a,
	header.sticky nav.main_menu > ul > li:hover > a {
	<?php if($qode_options['menu_hover_background_color'] !== '') { ?>
		background-color: <?php echo $menu_hover_background_color; ?>;
	<?php } ?>
	}

	<?php

	if(isset($qode_options['menu_hovercolor']) && $qode_options['menu_hovercolor'] !== '') {
		?>
		nav.main_menu > ul > li:hover > a,
		header.sticky nav.main_menu > ul > li:hover > a,
		.dark nav.main_menu > ul > li:hover > a,
		.light header.sticky nav.main_menu > ul > li:hover > a {
		color: <?php echo $qode_options['menu_hovercolor']; ?> !important;
		}
	<?php
	}
	?>
<?php } ?>

<?php if(!empty($qode_options['dropdown_color']) || !empty($qode_options['dropdown_fontsize']) || !empty($qode_options['dropdown_lineheight']) || !empty($qode_options['dropdown_fontstyle']) || !empty($qode_options['dropdown_fontweight']) || $qode_options['dropdown_google_fonts'] != "-1" || !empty($qode_options['dropdown_texttransform'])  || $qode_options['dropdown_letterspacing'] != ''){ ?>
.drop_down .second .inner > ul > li > a,
.drop_down .second .inner > ul > li > h3,
.drop_down .wide .second .inner > ul > li > h3,
.drop_down .wide .second .inner > ul > li > a,
.drop_down .wide .second ul li ul li.menu-item-has-children > a,
.drop_down .wide .second .inner ul li.sub ul li.menu-item-has-children > a,
.drop_down .wide .second .inner > ul li.sub .flexslider ul li  h5 a,
.drop_down .wide .second .inner > ul li .flexslider ul li  h5 a,
.drop_down .wide .second .inner > ul li.sub .flexslider ul li  h5,
.drop_down .wide .second .inner > ul li .flexslider ul li  h5{
	<?php if (!empty($qode_options['dropdown_color'])) { ?> color: <?php echo $qode_options['dropdown_color']; ?>; <?php } ?>
	<?php if($qode_options['dropdown_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['dropdown_google_fonts']) ?>', sans-serif !important;
	<?php } ?>
	<?php if (!empty($qode_options['dropdown_fontsize'])) { ?> font-size: <?php echo $qode_options['dropdown_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['dropdown_lineheight'])) { ?> line-height: <?php echo $qode_options['dropdown_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['dropdown_fontstyle'])) { ?> font-style: <?php echo $qode_options['dropdown_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['dropdown_fontweight'])) { ?>font-weight: <?php echo $qode_options['dropdown_fontweight'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['dropdown_texttransform'])) { ?> text-transform: <?php echo $qode_options['dropdown_texttransform'];  ?>;  <?php } ?>
	<?php if ($qode_options['dropdown_letterspacing'] != '') { ?> letter-spacing: <?php echo $qode_options['dropdown_letterspacing'];  ?>px;  <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['dropdown_hovercolor'])) { ?>
.drop_down .second .inner > ul > li > a:hover,
.drop_down .wide .second ul li ul li.menu-item-has-children > a:hover,
.drop_down .wide .second .inner ul li.sub ul li.menu-item-has-children > a:hover{
	color: <?php echo $qode_options['dropdown_hovercolor'];  ?> !important;
}
.drop_down .second{
    border-color: <?php echo $qode_options['dropdown_hovercolor'];  ?>;
}
<?php } ?>
<?php if(!empty($qode_options['dropdown_padding_top_bottom'])){ ?>
	.drop_down .wide .second>.inner>ul>li.sub>ul>li>a,
	.drop_down .second .inner ul li a,
	.drop_down .wide .second ul li a,
	.drop_down .second .inner ul.right li a
	{
	<?php if (!empty($qode_options['dropdown_padding_top_bottom'])) { ?> padding-top: <?php echo $qode_options['dropdown_padding_top_bottom']; ?>px; <?php } ?>
	<?php if (!empty($qode_options['dropdown_padding_top_bottom'])) { ?> padding-bottom: <?php echo $qode_options['dropdown_padding_top_bottom']; ?>px; <?php } ?>

	}
<?php } ?>
<?php if(isset($qode_options['dropdown_separator_beetwen_items']) && $qode_options['dropdown_separator_beetwen_items'] == "yes"){ ?>
	.drop_down .second ul li{
		border-bottom-style:solid;
	}
	li.narrow .second .inner ul{
		padding-top:0;
		padding-bottom:0;
	}
	.drop_down .second .inner ul li ul{
		top:0;
	}
<?php } ?>
<?php if(isset($qode_options['dropdown_border_around']) && $qode_options['dropdown_border_around'] == "yes"){ ?>
	.drop_down .second .inner>ul, li.narrow .second .inner ul{
	border-style:solid;
	border-width:1px;
	}

<?php } ?>
<?php if(!empty($qode_options['dropdown_wide_color']) || !empty($qode_options['dropdown_wide_fontsize']) || !empty($qode_options['dropdown_wide_lineheight']) || !empty($qode_options['dropdown_wide_fontstyle']) || !empty($qode_options['dropdown_wide_fontweight']) || $qode_options['dropdown_wide_google_fonts'] != "-1" || !empty($qode_options['dropdown_wide_texttransform'])  || $qode_options['dropdown_wide_letterspacing'] != ''){ ?>
	.drop_down .wide .second .inner>ul>li>a
	{
	<?php if (!empty($qode_options['dropdown_wide_color'])) { ?> color: <?php echo $qode_options['dropdown_wide_color']; ?>; <?php } ?>
	<?php if($qode_options['dropdown_wide_google_fonts'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['dropdown_wide_google_fonts']) ?>', sans-serif !important;
	<?php } ?>
	<?php if (!empty($qode_options['dropdown_wide_fontsize'])) { ?> font-size: <?php echo $qode_options['dropdown_wide_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['dropdown_wide_lineheight'])) { ?> line-height: <?php echo $qode_options['dropdown_wide_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['dropdown_wide_fontstyle'])) { ?> font-style: <?php echo $qode_options['dropdown_wide_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['dropdown_wide_fontweight'])) { ?>font-weight: <?php echo $qode_options['dropdown_wide_fontweight'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['dropdown_wide_texttransform'])) { ?> text-transform: <?php echo $qode_options['dropdown_wide_texttransform'];  ?>;  <?php } ?>
	<?php if ($qode_options['dropdown_wide_letterspacing'] != '') { ?> letter-spacing: <?php echo $qode_options['dropdown_wide_letterspacing'];  ?>px;  <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['dropdown_wide_hovercolor'])) { ?>
	.drop_down .wide .second .inner>ul>li>a:hover{
	color: <?php echo $qode_options['dropdown_wide_hovercolor'];  ?> !important;
	}
<?php } ?>
<?php if(!empty($qode_options['dropdown_color_thirdlvl']) || !empty($qode_options['dropdown_fontsize_thirdlvl']) || !empty($qode_options['dropdown_lineheight_thirdlvl']) || !empty($qode_options['dropdown_fontstyle_thirdlvl']) || !empty($qode_options['dropdown_fontweight_thirdlvl']) || $qode_options['dropdown_google_fonts_thirdlvl'] != "-1" || !empty($qode_options['dropdown_texttransform_thirdlvl']) || $qode_options['dropdown_letterspacing_thirdlvl'] != ''){ ?>
.drop_down .wide .second .inner ul li.sub ul li a,
.drop_down .wide .second ul li ul li a,
.drop_down .second .inner ul li.sub ul li a,
.drop_down .wide .second ul li ul li a,
.drop_down .wide .second .inner ul li.sub .flexslider ul li .menu_recent_post,
.drop_down .wide .second .inner ul li .flexslider ul li .menu_recent_post a,
.drop_down .wide .second .inner ul li .flexslider ul li .menu_recent_post,
.drop_down .wide .second .inner ul li .flexslider ul li .menu_recent_post a{
	<?php if (!empty($qode_options['dropdown_color_thirdlvl'])) { ?> color: <?php echo $qode_options['dropdown_color_thirdlvl'];  ?>;  <?php } ?>
	<?php if($qode_options['dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['dropdown_google_fonts_thirdlvl']) ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['dropdown_fontsize_thirdlvl'])) { ?> font-size: <?php echo $qode_options['dropdown_fontsize_thirdlvl'];  ?>px;  <?php } ?>
	<?php if (!empty($qode_options['dropdown_lineheight_thirdlvl'])) { ?> line-height: <?php echo $qode_options['dropdown_lineheight_thirdlvl'];  ?>px;  <?php } ?>
	<?php if (!empty($qode_options['dropdown_fontstyle_thirdlvl'])) { ?> font-style: <?php echo $qode_options['dropdown_fontstyle_thirdlvl'];  ?>;   <?php } ?>
	<?php if (!empty($qode_options['dropdown_fontweight_thirdlvl'])) { ?> font-weight: <?php echo $qode_options['dropdown_fontweight_thirdlvl'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['dropdown_texttransform_thirdlvl'])) { ?> text-transform: <?php echo $qode_options['dropdown_texttransform_thirdlvl'];  ?>;  <?php } ?>
	<?php if ($qode_options['dropdown_letterspacing_thirdlvl'] != '') { ?> letter-spacing: <?php echo $qode_options['dropdown_letterspacing_thirdlvl'];  ?>;  <?php } ?>
}
.drop_down .wide.icons .second i{
    <?php if (!empty($qode_options['dropdown_color_thirdlvl'])) { ?> color: <?php echo $qode_options['dropdown_color_thirdlvl'];  ?>;  <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['dropdown_hovercolor_thirdlvl'])) { ?>
.drop_down .second .inner ul li.sub ul li a:hover,
.drop_down .second .inner ul li ul li a:hover,
.drop_down .wide.icons .second a:hover i
{
	color: <?php echo $qode_options['dropdown_hovercolor_thirdlvl'];  ?> !important;
}
<?php } ?>


<?php if(!empty($qode_options['fixed_color']) || !empty($qode_options['fixed_fontsize']) || !empty($qode_options['fixed_lineheight']) || !empty($qode_options['fixed_fontstyle']) || !empty($qode_options['fixed_fontweight']) || $qode_options['fixed_google_fonts'] != "-1"){ ?>
header.scrolled nav.main_menu > ul > li > a,
header.light.scrolled nav.main_menu > ul > li > a,
header.dark.scrolled nav.main_menu > ul > li > a{
	<?php if (!empty($qode_options['fixed_color'])) { ?> color: <?php echo $qode_options['fixed_color']; ?>; <?php } ?>
	<?php if($qode_options['fixed_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['fixed_google_fonts']) ?>', sans-serif !important;
	<?php } ?>
	<?php if (!empty($qode_options['fixed_fontsize'])) { ?> font-size: <?php echo $qode_options['fixed_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['fixed_lineheight'])) { ?> line-height: <?php echo $qode_options['fixed_lineheight'];  ?>px !important; <?php } ?>
	<?php if (!empty($qode_options['fixed_fontstyle'])) { ?> font-style: <?php echo $qode_options['fixed_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['fixed_fontweight'])) { ?>font-weight: <?php echo $qode_options['fixed_fontweight'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['fixed_color'])) { ?>
header.scrolled .side_menu_button a {
    <?php if (!empty($qode_options['fixed_color'])) { ?> color: <?php echo $qode_options['fixed_color']; ?> !important; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['fixed_hovercolor'])) { ?>
header.scrolled nav.main_menu > ul > li > a:hover > span,
header.scrolled nav.main_menu > ul > li:hover > a > span,
header.scrolled nav.main_menu > ul > li.active > a > span,
header.scrolled nav.main_menu > ul > li > a:hover > i,
header.scrolled nav.main_menu > ul > li:hover > a > i,
header.scrolled nav.main_menu > ul > li.active > a > i,
header.scrolled .side_menu_button a:hover,
.light.scrolled nav.main_menu > ul > li > a:hover,
.light.scrolled nav.main_menu > ul > li.active > a,
.light.scrolled .side_menu_button a:hover,
.dark.scrolled nav.main_menu > ul > li > a:hover,
.dark.scrolled nav.main_menu > ul > li.active > a,
.dark.scrolled .side_menu_button a:hover {
	color: <?php echo $qode_options['fixed_hovercolor'];  ?> !important;
}
<?php } ?>

<?php if(!empty($qode_options['sticky_color']) || !empty($qode_options['sticky_fontsize']) || !empty($qode_options['sticky_lineheight']) || !empty($qode_options['sticky_fontstyle']) || !empty($qode_options['sticky_fontweight']) || $qode_options['sticky_google_fonts'] != "-1"){ ?>
header.sticky nav.main_menu > ul > li > a,
header.light.sticky nav.main_menu > ul > li > a,
header.dark.sticky nav.main_menu > ul > li > a{
	<?php if (!empty($qode_options['sticky_color'])) { ?> color: <?php echo $qode_options['sticky_color']; ?>; <?php } ?>
	<?php if($qode_options['sticky_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['sticky_google_fonts']) ?>', sans-serif !important;
	<?php } ?>
	<?php if (!empty($qode_options['sticky_fontsize'])) { ?> font-size: <?php echo $qode_options['sticky_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['sticky_lineheight'])) { ?> line-height: <?php echo $qode_options['sticky_lineheight'];  ?>px !important; <?php } ?>
	<?php if (!empty($qode_options['sticky_fontstyle'])) { ?> font-style: <?php echo $qode_options['sticky_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['sticky_fontweight'])) { ?>font-weight: <?php echo $qode_options['sticky_fontweight'];  ?>; <?php } ?>
}
<?php } ?>

<?php if (!empty($qode_options['sticky_color'])) { ?>
header.sticky .side_menu_button a,
header.sticky .side_menu_button a:hover{
    <?php if (!empty($qode_options['sticky_color'])) { ?> color: <?php echo $qode_options['sticky_color']; ?>; <?php } ?>
}
<?php } ?>

<?php if (!empty($qode_options['sticky_hovercolor'])) { ?>
header.sticky nav.main_menu > ul > li > a:hover span,
header.sticky nav.main_menu > ul > li.active > a span,
header.sticky nav.main_menu > ul > li:hover > a > span,
header.sticky nav.main_menu > ul > li > a:hover > i,
header.sticky nav.main_menu > ul > li:hover > a > i,
header.sticky nav.main_menu > ul > li.active > a > i,
.light.sticky nav.main_menu > ul > li > a:hover,
.light.sticky nav.main_menu > ul > li.active > a,
.dark.sticky nav.main_menu > ul > li > a:hover,
.dark.sticky nav.main_menu > ul > li.active > a{
	color: <?php echo $qode_options['sticky_hovercolor'];  ?> !important;
}
<?php } ?>

<?php if (!empty($qode_options['mobile_color']) || !empty($qode_options['mobile_fontsize']) || !empty($qode_options['mobile_lineheight']) || !empty($qode_options['mobile_fontstyle']) || !empty($qode_options['mobile_fontweight']) || !empty($qode_options['mobile_letter_spacing']) || $qode_options['mobile_google_fonts'] != "-1") { ?>
nav.mobile_menu ul li a,
nav.mobile_menu ul li h3{
	<?php if (!empty($qode_options['mobile_color'])) { ?> color: <?php echo $qode_options['mobile_color'];  ?>; <?php } ?>
	<?php if($qode_options['mobile_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['mobile_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['mobile_fontsize'])) { ?> font-size: <?php echo $qode_options['mobile_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['mobile_lineheight'])) { ?> line-height: <?php echo $qode_options['mobile_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['mobile_fontstyle'])) { ?> font-style: <?php echo $qode_options['mobile_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['mobile_fontweight'])) { ?> font-weight: <?php echo $qode_options['mobile_fontweight'];  ?>; <?php } ?>
	<?php if(!empty($qode_options['mobile_letter_spacing'])){ ?>
	letter-spacing: <?php echo $qode_options['mobile_letter_spacing'];  ?>px;
	<?php } ?>
}

<?php } ?>
<?php if (!empty($qode_options['mobile_hovercolor'])) { ?>
nav.mobile_menu ul li a:hover,
nav.mobile_menu ul li.active > a,
nav.mobile_menu ul li.current-menu-item > a{
	color: <?php echo $qode_options['mobile_hovercolor'];  ?>;
}
<?php } ?>
<?php if (!empty($qode_options['mobile_separator_color'])) { ?>
	nav.mobile_menu ul li,
	nav.mobile_menu ul li,
	nav.mobile_menu ul li ul li,
    nav.mobile_menu ul li.open_sub > ul{
		border-color: <?php echo $qode_options['mobile_separator_color'];  ?>;
	}
<?php } ?>

<?php if (!empty($qode_options['mobile_background_color'])) { ?>
	@media only screen and (max-width: 1000px){
		.header_bottom,
		nav.mobile_menu{
			background-color: <?php echo $qode_options['mobile_background_color'];  ?> !important;
		}
	}
<?php } ?>
<?php if (!empty($qode_options['input_background_color']) || !empty($qode_options['input_border_color']) || !empty($qode_options['input_text_color'])) { ?>
	#respond textarea,
	#respond input[type='text'],
	.contact_form input[type='text'],
	.contact_form  textarea,
	.comment_holder #respond textarea,
	.comment_holder #respond input[type='text'],
    input.wpcf7-form-control.wpcf7-text,
    input.wpcf7-form-control.wpcf7-number,
    input.wpcf7-form-control.wpcf7-date,
    textarea.wpcf7-form-control.wpcf7-textarea,
    select.wpcf7-form-control.wpcf7-select,
    input.wpcf7-form-control.wpcf7-quiz,
    .post-password-form input[type='password']
	{
	<?php if (!empty($qode_options['input_background_color'])) { ?>background-color: <?php echo $qode_options['input_background_color'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['input_border_color'])) { ?>border: 1px solid <?php echo $qode_options['input_border_color'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['input_text_color'])) { ?>color:<?php echo $qode_options['input_text_color'];  ?>; <?php } ?>
	}
<?php } ?>

<?php

if(!empty($qode_options['input_focus_text_color']) || !empty($qode_options['input_focus_background_color'])) { ?>
	#respond textarea:focus,
	#respond input[type='text']:focus,
	.contact_form input[type='text']:focus,
	.contact_form  textarea:focus,
	.comment_holder #respond textarea:focus,
	.comment_holder #respond input[type='text']:focus {
		<?php if (!empty($qode_options['input_focus_text_color'])) { ?>color: <?php echo $qode_options['input_focus_text_color'];  ?>; <?php } ?>
		<?php if (!empty($qode_options['input_focus_background_color'])) { ?>background-color: <?php echo $qode_options['input_focus_background_color'];  ?>; <?php } ?>
	}
<?php }

if(!empty($qode_options['input_focus_text_color']) || !empty($qode_options['input_focus_background_color'])) { ?>
	#respond textarea:focus::-webkit-input-placeholder,
	#respond input[type='text']:focus::-webkit-input-placeholder,
	.contact_form input[type='text']:focus::-webkit-input-placeholder,
	.contact_form  textarea:focus::-webkit-input-placeholder {
	<?php if (!empty($qode_options['input_focus_text_color'])) { ?>color: <?php echo $qode_options['input_focus_text_color'];  ?>; <?php } ?>
	}

	#respond textarea:focus:-moz-placeholder,
	#respond input[type='text']:focus:-moz-placeholder,
	.contact_form input[type='text']:focus:-moz-placeholder,
	.contact_form  textarea:focus:-moz-placeholder {
	<?php if (!empty($qode_options['input_focus_text_color'])) { ?>color: <?php echo $qode_options['input_focus_text_color'];  ?>; <?php } ?>
	}

	#respond textarea:focus::-moz-placeholder,
	#respond input[type='text']::-moz-placeholder,
	.contact_form input[type='text']:focus::-moz-placeholder,
	.contact_form  textarea:focus::-moz-placeholder {
	<?php if (!empty($qode_options['input_focus_text_color'])) { ?>color: <?php echo $qode_options['input_focus_text_color'];  ?>; <?php } ?>
	}
<?php } ?>

<?php if (!empty($qode_options['smooth_background_color'])) { ?>
#ascrail2000{
	background-color: <?php echo $qode_options['smooth_background_color'];  ?>;
}
<?php } ?>
<?php if (!empty($qode_options['smooth_bar_color'])) {
?>
#ascrail2000 div{
	background-color: <?php echo $qode_options['smooth_bar_color'];  ?> !important;
}
<?php } ?>
<?php if (!empty($qode_options['h1_color']) || !empty($qode_options['h1_fontsize']) || !empty($qode_options['h1_lineheight']) || !empty($qode_options['h1_fontstyle']) || !empty($qode_options['h1_fontweight']) || !empty($qode_options['h1_letterspacing']) || $qode_options['h1_google_fonts'] != "-1" || !empty($qode_options['h1_texttransform'])) { ?>
h1{
	<?php if (!empty($qode_options['h1_color'])) { ?>	color: <?php echo $qode_options['h1_color'];  ?>; <?php } ?>
	<?php if($qode_options['h1_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['h1_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h1_fontsize'])) { ?>font-size: <?php echo $qode_options['h1_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h1_lineheight'])) { ?>line-height: <?php echo $qode_options['h1_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h1_fontstyle'])) { ?>font-style: <?php echo $qode_options['h1_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['h1_fontweight'])) { ?>font-weight: <?php echo $qode_options['h1_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['h1_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h1_letterspacing'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['h1_texttransform'])) { ?>text-transform: <?php echo $qode_options['h1_texttransform'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['page_title_color']) || !empty($qode_options['page_title_fontsize']) || !empty($qode_options['page_title_lineheight']) || !empty($qode_options['page_title_fontstyle']) || !empty($qode_options['page_title_fontweight']) || $qode_options['page_title_google_fonts'] != "-1" || isset($qode_options['page_title_letter_spacing']) && $qode_options['page_title_letter_spacing'] !== '') { ?>
.title h1{
	<?php if (!empty($qode_options['page_title_color'])) { ?>color: <?php echo $qode_options['page_title_color'];  ?>; <?php } ?>
	<?php if($qode_options['page_title_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['page_title_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['page_title_fontsize'])) { ?>font-size: <?php echo $qode_options['page_title_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['page_title_lineheight'])) { ?>line-height: <?php echo $qode_options['page_title_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['page_title_fontstyle'])) { ?>font-style: <?php echo $qode_options['page_title_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['page_title_fontweight'])) { ?>font-weight: <?php echo $qode_options['page_title_fontweight'];  ?>; <?php } ?>
	<?php if ($qode_options['page_title_letter_spacing'] !== '') { ?>letter-spacing: <?php echo $qode_options['page_title_letter_spacing'];  ?>px; <?php } ?>
}

<?php } ?>

<?php if (!empty($qode_options['h2_color']) || !empty($qode_options['h2_fontsize']) || !empty($qode_options['h2_lineheight']) || !empty($qode_options['h2_fontstyle']) || !empty($qode_options['h2_fontweight']) || !empty($qode_options['h2_letterspacing']) || $qode_options['h2_google_fonts'] != "-1" || !empty($qode_options['h2_texttransform'])) { ?>
h2,
h2 a{
	<?php if (!empty($qode_options['h2_color'])) { ?>color: <?php echo $qode_options['h2_color'];  ?>; <?php } ?>
	<?php if($qode_options['h2_google_fonts'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['h2_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h2_fontsize'])) { ?>font-size: <?php echo $qode_options['h2_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h2_lineheight'])) { ?>line-height: <?php echo $qode_options['h2_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h2_fontstyle'])) { ?>font-style: <?php echo $qode_options['h2_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['h2_fontweight'])) { ?>font-weight: <?php echo $qode_options['h2_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['h2_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h2_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h2_texttransform'])) { ?>text-transform: <?php echo $qode_options['h2_texttransform'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['h3_color']) || !empty($qode_options['h3_fontsize']) || !empty($qode_options['h3_lineheight']) || !empty($qode_options['h3_fontstyle']) || !empty($qode_options['h3_fontweight']) || !empty($qode_options['h3_letterspacing']) || $qode_options['h3_google_fonts'] != "-1" || !empty($qode_options['h3_texttransform'])) { ?>
h3,h3 a{
	<?php if (!empty($qode_options['h3_color'])) { ?>color: <?php echo $qode_options['h3_color'];  ?>; <?php } ?>
	<?php if($qode_options['h3_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['h3_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h3_fontsize'])) { ?>font-size: <?php echo $qode_options['h3_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h3_lineheight'])) { ?>line-height: <?php echo $qode_options['h3_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h3_fontstyle'])) { ?>font-style: <?php echo $qode_options['h3_fontstyle'];?>; <?php } ?>
	<?php if (!empty($qode_options['h3_fontweight'])) { ?>font-weight: <?php echo $qode_options['h3_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['h3_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h3_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h3_texttransform'])) { ?>text-transform: <?php echo $qode_options['h3_texttransform'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['h4_color']) || !empty($qode_options['h4_fontsize']) || !empty($qode_options['h4_lineheight']) || !empty($qode_options['h4_fontstyle']) || !empty($qode_options['h4_fontweight']) || !empty($qode_options['h4_letterspacing']) || $qode_options['h4_google_fonts'] != "-1" || !empty($qode_options['h4_texttransform'])) { ?>
h4,
h4 a{
	<?php if (!empty($qode_options['h4_color'])) { ?>color: <?php echo $qode_options['h4_color'];  ?>; <?php } ?>
	<?php if($qode_options['h4_google_fonts'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['h4_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h4_fontsize'])) { ?>font-size: <?php echo $qode_options['h4_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h4_lineheight'])) { ?>line-height: <?php echo $qode_options['h4_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h4_fontstyle'])) { ?>font-style: <?php echo $qode_options['h4_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['h4_fontweight'])) { ?>font-weight: <?php echo $qode_options['h4_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['h4_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h4_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h4_texttransform'])) { ?>text-transform: <?php echo $qode_options['h4_texttransform'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['h5_color']) || !empty($qode_options['h5_fontsize']) || !empty($qode_options['h5_lineheight']) || !empty($qode_options['h5_fontstyle']) || !empty($qode_options['h5_fontweight']) || !empty($qode_options['h5_letterspacing']) || $qode_options['h5_google_fonts'] != "-1" || !empty($qode_options['h5_texttransform'])) { ?>
h5,
h5 a{
	<?php if (!empty($qode_options['h5_color'])) { ?>color: <?php echo $qode_options['h5_color'];  ?>; <?php } ?>
	<?php if($qode_options['h5_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['h5_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h5_fontsize'])) { ?>font-size: <?php echo $qode_options['h5_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h5_lineheight'])) { ?>line-height: <?php echo $qode_options['h5_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h5_fontstyle'])) { ?>font-style: <?php echo $qode_options['h5_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['h5_fontweight'])) { ?>font-weight: <?php echo $qode_options['h5_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['h5_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h5_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h5_texttransform'])) { ?>text-transform: <?php echo $qode_options['h5_texttransform'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['h6_color']) || !empty($qode_options['h6_fontsize']) || !empty($qode_options['h6_lineheight']) || !empty($qode_options['h6_fontstyle']) || !empty($qode_options['h6_fontweight']) || !empty($qode_options['h6_letterspacing']) || $qode_options['h6_google_fonts'] != "-1" || !empty($qode_options['h6_texttransform'])) { ?>
h6{
	<?php if (!empty($qode_options['h6_color'])) { ?>color: <?php echo $qode_options['h6_color'];  ?>; <?php } ?>
	<?php if($qode_options['h6_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['h6_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['h6_fontsize'])) { ?>font-size: <?php echo $qode_options['h6_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h6_lineheight'])) { ?>line-height: <?php echo $qode_options['h6_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h6_fontstyle'])) { ?>font-style: <?php echo $qode_options['h6_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['h6_fontweight'])) { ?>font-weight: <?php echo $qode_options['h6_fontweight'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['h6_letterspacing'])) { ?>letter-spacing: <?php echo $qode_options['h6_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['h6_texttransform'])) { ?>text-transform: <?php echo $qode_options['h6_texttransform'];  ?>; <?php } ?>
}

<?php } ?>
<?php if (!empty($qode_options['text_color']) || !empty($qode_options['text_fontsize']) || !empty($qode_options['text_lineheight']) || !empty($qode_options['text_fontstyle']) || !empty($qode_options['text_fontweight']) || $qode_options['text_google_fonts'] != "-1" || !empty($qode_options['text_margin'])) { ?>
    p{
    	<?php if (!empty($qode_options['text_color'])) { ?>color: <?php echo $qode_options['text_color'];  ?>;<?php } ?>
    	<?php if($qode_options['text_google_fonts'] != "-1"){ ?>
    		font-family: '<?php echo str_replace('+', ' ', $qode_options['text_google_fonts']); ?>', sans-serif;
    	<?php } ?>
    	<?php if (!empty($qode_options['text_fontsize'])) { ?>font-size: <?php echo $qode_options['text_fontsize'];  ?>px;<?php } ?>
    	<?php if (!empty($qode_options['text_lineheight'])) { ?>line-height: <?php echo $qode_options['text_lineheight'];  ?>px;<?php } ?>
    	<?php if (!empty($qode_options['text_fontstyle'])) { ?>font-style: <?php echo $qode_options['text_fontstyle'];  ?>;<?php } ?>
    	<?php if (!empty($qode_options['text_fontweight'])) { ?>font-weight: <?php echo $qode_options['text_fontweight'];  ?>;<?php } ?>
    	<?php if (!empty($qode_options['text_margin'])) { ?>margin-top: <?php echo $qode_options['text_margin'];  ?>px;<?php } ?>
    	<?php if (!empty($qode_options['text_margin'])) { ?>margin-bottom: <?php echo $qode_options['text_margin'];  ?>px;<?php } ?>
    }
    .breadcrumb a,
    .filter_holder ul li span,
    blockquote h5,
    .q_social_icon_holder i.simple_social,
    .header-widget.widget_nav_menu ul.menu li a,
    .side_menu a,
    .side_menu li,
    .side_menu span,
    .side_menu p,
    .side_menu .widget.widget_rss li a.rsswidget,
    .side_menu #wp-calendar caption,
    .side_menu #wp-calendar th,
    .side_menu #wp-calendar td,
    aside .widget #lang_sel_list li a,
    aside .widget #lang_sel li a,
    aside .widget #lang_sel_click li a,
    section.side_menu #lang_sel_list li a,
    section.side_menu #lang_sel li a,
    section.side_menu #lang_sel_click li a,
    footer #lang_sel_list li a,
    footer #lang_sel li a,
    footer #lang_sel_click li a,
    footer #lang_sel_list.lang_sel_list_horizontal a,
    footer #lang_sel_list.lang_sel_list_vertical a,
    .side_menu #lang_sel_list.lang_sel_list_horizontal a,
    .side_menu #lang_sel_list.lang_sel_list_vertical a,
    #lang_sel_footer a{
    	<?php if (!empty($qode_options['text_color'])) { ?>color: <?php echo $qode_options['text_color'];  ?>;<?php } ?>
    }

    footer #lang_sel ul li a,
    footer #lang_sel ul ul a,
    footer #lang_sel_click ul li a,
    footer #lang_sel_click ul ul a,
    footer #lang_sel_click ul ul a span,
    section.side_menu #lang_sel ul li a,
    section.side_menu #lang_sel ul ul a,
    section.side_menu #lang_sel ul ul a:visited,
    section.side_menu #lang_sel_click > ul > li > a,
    section.side_menu #lang_sel_click ul ul a,
    section.side_menu #lang_sel_click ul ul a:visited{
    	<?php if (!empty($qode_options['text_color'])) { ?>color: <?php echo $qode_options['text_color'];  ?> !important;<?php } ?>
    }
    <?php if(function_exists("is_woocommerce") && !empty($qode_options['text_color'])){ ?>
        .woocommerce del,
        .woocommerce-page del,
        .woocommerce input[type='text']:not(.qode_search_field),
        .woocommerce input[type='password'],
        .woocommerce input[type='email'],
		.woocommerce input[type='tel'],
        .woocommerce-page input[type='text']:not(.qode_search_field),
        .woocommerce-page input[type='password'],
        .woocommerce-page input[type='email'],
		.woocommerce-page input[type='tel'],
        .woocommerce textarea,
        .woocommerce-page textarea,
        .woocommerce .select2-container .select2-choice,
        .woocommerce-page .select2-container .select2-choice,
        .woocommerce .select2-dropdown-open.select2-drop-above .select2-choice,
        .woocommerce .select2-dropdown-open.select2-drop-above .select2-choices,
        .woocommerce-page .select2-dropdown-open.select2-drop-above .select2-choice,
        .woocommerce-page .select2-dropdown-open.select2-drop-above .select2-choices,
        .woocommerce .chosen-container.chosen-container-single .chosen-single,
        .woocommerce-page .chosen-container.chosen-container-single .chosen-single,
        .woocommerce-checkout .form-row .chosen-container-single .chosen-single,
        .woocommerce ul.products li.product h4,
        .woocommerce div.product p[itemprop='price'] del,
        .woocommerce div.product p[itemprop='price'] del span.amount,
        .woocommerce div.product div.product_meta > span span,
        .woocommerce div.product div.product_meta > span a,
        .woocommerce aside ul.product_list_widget li > a,
        .woocommerce aside ul.product-categories li > a,
        .woocommerce aside ul.product_list_widget li del span.amount,
        .shopping_cart_dropdown ul li a,
        .select2-drop{
            color: <?php echo $qode_options['text_color']; ?>;
        }
    <?php } ?>
<?php } ?>
<?php if (!empty($qode_options['link_color']) || !empty($qode_options['link_fontstyle']) || !empty($qode_options['link_fontweight']) || !empty($qode_options['link_fontdecoration'])) { ?>
a, p a{
	<?php if (!empty($qode_options['link_color'])) { ?>color: <?php echo $qode_options['link_color'];  ?>;<?php } ?>
	<?php if (!empty($qode_options['link_fontstyle'])) { ?>font-style: <?php echo $qode_options['link_fontstyle'];  ?>;<?php } ?>
	<?php if (!empty($qode_options['link_fontweight'])) { ?>font-weight: <?php echo $qode_options['link_fontweight'];  ?>;<?php } ?>
	<?php if (!empty($qode_options['link_fontdecoration'])) { ?>text-decoration: <?php echo $qode_options['link_fontdecoration'];  ?>;<?php } ?>
}

h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover,
.q_icon_with_title .icon_with_title_link,
.blog_holder article .post_description a:hover,
.blog_holder.masonry article .post_info a:hover,
.breadcrumb .current,
.breadcrumb a:hover,
.portfolio_social_holder a:hover,
.latest_post_inner .post_infos a:hover{
    <?php if (!empty($qode_options['link_color'])) { ?>color: <?php echo $qode_options['link_color'];  ?>;<?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['link_hovercolor']) || !empty($qode_options['link_fontdecoration'])) { ?>
a:hover,p a:hover,
h1 a,h2 a,h3 a,h4 a,h5 a,h6 a,
.q_tabs .tabs-nav li a:hover,
.q_icon_with_title .icon_with_title_link:hover,
.blog_holder article .post_description a,
.blog_holder.masonry article .post_info a,
.portfolio_social_holder a,
.latest_post_inner .post_infos a{
	<?php if (!empty($qode_options['link_hovercolor'])) { ?>color: <?php echo $qode_options['link_hovercolor'];  ?>;<?php } ?>
	<?php if (!empty($qode_options['link_fontdecoration'])) { ?>text-decoration: <?php echo $qode_options['link_fontdecoration'];  ?>;<?php } ?>
}
<?php } ?>

<?php
	$blockquote_text_styles = array();

	if(isset($qode_options['blockquote_font_font_family']) && $qode_options['blockquote_font_font_family'] !== '-1') {
	$blockquote_text_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['blockquote_font_font_family']).'", sans-serif';
	}

	if(isset($qode_options['blockquote_font_font_size']) && $qode_options['blockquote_font_font_size'] !== '') {
	$blockquote_text_styles[] = 'font-size: '.$qode_options['blockquote_font_font_size'].'px';
	}

	if(isset($qode_options['blockquote_font_letter_spacing']) && $qode_options['blockquote_font_letter_spacing'] !== '') {
	$blockquote_text_styles[] = 'letter-spacing: '.$qode_options['blockquote_font_letter_spacing'].'px';
	}

	if(isset($qode_options['blockquote_font_font_weight']) && $qode_options['blockquote_font_font_weight'] !== '') {
	$blockquote_text_styles[] = 'font-weight: '.$qode_options['blockquote_font_font_weight'];
	}

	if(isset($qode_options['blockquote_font_text_transform']) && $qode_options['blockquote_font_text_transform'] !== '') {
	$blockquote_text_styles[] = 'text-transform: '.$qode_options['blockquote_font_text_transform'];
	}

	if(isset($qode_options['blockquote_font_color']) && $qode_options['blockquote_font_color'] !== '') {
	$blockquote_text_styles[] = 'color: '.$qode_options['blockquote_font_color'];
	}

	if(isset($qode_options['blockquote_font_font_style']) && $qode_options['blockquote_font_font_style'] !== '') {
	$blockquote_text_styles[] = 'font-style: '.$qode_options['blockquote_font_font_style'];
	}

	if(is_array($blockquote_text_styles) && count($blockquote_text_styles)) { ?>
		.blog_holder article .post_text h5.blockquote_text,
		blockquote .blockquote_text {
<?php echo implode(';', $blockquote_text_styles); ?>
	}
<?php } ?>

<?php if (!empty($qode_options['blockquote_background_color']) && !empty($qode_options['blockquote_border_color'])) { ?>
	blockquote{
		border-color: <?php echo $qode_options['blockquote_border_color'];  ?>;
		background-color: <?php echo $qode_options['blockquote_background_color'];  ?>;
	}
<?php } ?>
<?php if(!empty($qode_options['blockquote_quote_icon_color'])) { ?>
    blockquote i.pull-left {
        color: <?php echo $qode_options['blockquote_quote_icon_color']; ?>;
    }
<?php } ?>
<?php if (!empty($qode_options['page_subtitle_color']) || !empty($qode_options['page_subtitle_fontsize']) || $qode_options['page_subtitle_google_fonts'] != "-1" || !empty($qode_options['page_subtitle_lineheight']) || !empty($qode_options['page_subtitle_fontstyle']) || !empty($qode_options['page_subtitle_fontweight'])) { ?>
	.subtitle{
    <?php if (!empty($qode_options['page_subtitle_color'])) { ?>color: <?php echo $qode_options['page_subtitle_color'];  ?>; <?php } ?>
    <?php if($qode_options['page_subtitle_google_fonts'] != "-1"){ ?>
        font-family: '<?php echo str_replace('+', ' ', $qode_options['page_subtitle_google_fonts']); ?>', sans-serif;
    <?php } ?>
    <?php if (!empty($qode_options['page_subtitle_fontsize'])) { ?>font-size: <?php echo $qode_options['page_subtitle_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['page_subtitle_lineheight'])) { ?>line-height: <?php echo $qode_options['page_subtitle_lineheight'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['page_subtitle_fontstyle'])) { ?>font-style: <?php echo $qode_options['page_subtitle_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['page_subtitle_fontweight'])) { ?>font-weight: <?php echo $qode_options['page_subtitle_fontweight'];  ?>; <?php } ?>
	}
<?php } ?>

<?php if (!empty($qode_options['page_breadcrumb_color']) || !empty($qode_options['page_breadcrumb_fontsize']) || $qode_options['page_breadcrumb_google_fonts'] != "-1" || !empty($qode_options['page_breadcrumb_lineheight']) || !empty($qode_options['page_breadcrumb_fontstyle']) || !empty($qode_options['page_breadcrumb_fontweight']) || (isset($qode_options['page_breadcrumb_letter_spacing']) && $qode_options['page_breadcrumb_letter_spacing'] !== '')) { ?>
    .title.breadcrumbs_title .breadcrumb a, .title.breadcrumbs_title .breadcrumb span{
    <?php if (!empty($qode_options['page_breadcrumb_color'])) { ?>color: <?php echo $qode_options['page_breadcrumb_color'];  ?>; <?php } ?>
    <?php if($qode_options['page_breadcrumb_google_fonts'] !== "-1"){ ?>
        font-family: '<?php echo str_replace('+', ' ', $qode_options['page_breadcrumb_google_fonts']); ?>', sans-serif;
    <?php } ?>
    <?php if (!empty($qode_options['page_breadcrumb_fontsize'])) { ?>font-size: <?php echo $qode_options['page_breadcrumb_fontsize'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['page_breadcrumb_lineheight'])) { ?>line-height: <?php echo $qode_options['page_breadcrumb_lineheight'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['page_breadcrumb_fontstyle'])) { ?>font-style: <?php echo $qode_options['page_breadcrumb_fontstyle'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['page_breadcrumb_fontweight'])) { ?>font-weight: <?php echo $qode_options['page_breadcrumb_fontweight'];  ?>; <?php } ?>
    <?php if (isset($qode_options['page_breadcrumb_letter_spacing']) && $qode_options['page_breadcrumb_letter_spacing'] !== '') { ?>letter-spacing: <?php echo $qode_options['page_breadcrumb_letter_spacing'];  ?>px; <?php } ?>
    }
<?php } ?>

<?php if (!empty($qode_options['page_breadcrumb_color'])){ ?>
    .title.breadcrumbs_title .breadcrumb a:hover, .title.breadcrumbs_title .breadcrumb span.current{
        color: <?php echo $qode_options['page_breadcrumb_hovercolor'];  ?>;
    }
<?php } ?>

<?php if (!empty($qode_options['separator_thickness']) || !empty($qode_options['separator_topmargin']) || !empty($qode_options['separator_bottommargin']) || !empty($qode_options['separator_color']) || !empty($qode_options['separator_type'])) { ?>
.separator.normal{
<?php if (!empty($qode_options['separator_thickness'])) { ?>	border-bottom-width: <?php echo $qode_options['separator_thickness'];  ?>px; <?php } ?>
<?php if (!empty($qode_options['separator_topmargin'])) { ?>	margin-top: <?php echo $qode_options['separator_topmargin'];  ?>px; <?php } ?>
<?php if (!empty($qode_options['separator_bottommargin'])) { ?>	margin-bottom: <?php echo $qode_options['separator_bottommargin'];  ?>px; <?php } ?>
<?php if (!empty($qode_options['separator_color'])) { ?>	border-color: <?php echo $qode_options['separator_color'];  ?>; <?php } ?>
<?php if (!empty($qode_options['separator_type'])) { ?>	border-style: <?php echo $qode_options['separator_type'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['separator_small_thickness']) || !empty($qode_options['separator_small_topmargin']) || !empty($qode_options['separator_small_bottommargin']) || !empty($qode_options['separator_small_color'])  || !empty($qode_options['separator_small_width']) || !empty($qode_options['separator_small_type'])) { ?>
.separator.small,
.wpb_column>.wpb_wrapper .separator.small
{
	<?php if (!empty($qode_options['separator_small_thickness'])) { ?>	border-bottom-width: <?php echo $qode_options['separator_small_thickness'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['separator_small_topmargin'])) { ?>	margin-top: <?php echo $qode_options['separator_small_topmargin'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['separator_small_bottommargin'])) { ?>	margin-bottom: <?php echo $qode_options['separator_small_bottommargin'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['separator_small_color'])) { ?>	border-color: <?php echo $qode_options['separator_small_color'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['separator_small_width'])) { ?>	width: <?php echo $qode_options['separator_small_width'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['separator_small_type'])) { ?>	border-style: <?php echo $qode_options['separator_small_type'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['separator_color'])) { ?>
	.blog_holder article,
	.author_description,
	aside .widget,
	section.section,
	.animated_icons_with_text .animated_icon_with_text_inner:after,
	.animated_icons_with_text .animated_icon_with_text_inner:before{
		border-color:<?php echo $qode_options_satellite['separator_color'];  ?>;
	}
<?php } ?>
<?php if (!empty($qode_options['message_backgroundcolor'])) { ?>
.q_message{
	<?php if (!empty($qode_options['message_backgroundcolor'])) { ?>background-color: <?php echo $qode_options['message_backgroundcolor'];  ?><?php } ?>;
}
<?php } ?>
<?php if (!empty($qode_options['message_title_color']) || !empty($qode_options['message_title_fontsize']) || !empty($qode_options['message_title_lineheight']) || !empty($qode_options['message_title_fontstyle']) || !empty($qode_options['message_title_fontweight']) || $qode_options['message_title_google_fonts'] != "-1") { ?>
.q_message .message_text{
<?php if (!empty($qode_options['message_title_color'])) { ?>	color: <?php echo $qode_options['message_title_color'];  ?>; <?php } ?>
	<?php if($qode_options['message_title_google_fonts'] != "-1"){ ?>
	font-family: '<?php echo str_replace('+', ' ', $qode_options['message_title_google_fonts']); ?>', sans-serif;
	<?php } ?>
<?php if (!empty($qode_options['message_title_fontsize'])) { ?>	font-size: <?php echo $qode_options['message_title_fontsize'];  ?>px; <?php } ?>
<?php if (!empty($qode_options['message_title_lineheight'])) { ?>	line-height: <?php echo $qode_options['message_title_lineheight'];  ?>px; <?php } ?>
<?php if (!empty($qode_options['message_title_fontstyle'])) { ?>	font-style: <?php echo $qode_options['message_title_fontstyle'];  ?>; <?php } ?>
<?php if (!empty($qode_options['message_title_fontweight'])) { ?>	font-weight: <?php echo $qode_options['message_title_fontweight'];  ?>; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['message_icon_fontsize']) && !empty($qode_options['message_icon_color'])) { ?>
.q_message.with_icon .q_message_icon_inner > i,
.q_message.with_icon .q_message_icon_inner > .q_font_elegant_icon {
   <?php if (!empty($qode_options['message_icon_color'])) { ?> color:  <?php echo $qode_options['message_icon_color'];  ?>; <?php } ?>
   <?php if (!empty($qode_options['message_icon_fontsize'])) { ?> font-size: <?php echo $qode_options['message_icon_fontsize'];  ?>px; <?php } ?>
}
<?php } ?>
<?php if (!empty($qode_options['social_icon_background_color']) || !empty($qode_options['social_icon_background_color'])) { ?>
    .q_social_icon_holder .fa-stack {
        <?php if(!empty($qode_options['social_icon_background_color']) && !empty($qode_options['social_icon_background_color'])) { ?>
		background-color: <?php echo $qode_options['social_icon_background_color'];  ?>;
        <?php } ?>

        <?php if(!empty($qode_options['social_icon_border_color'])) { ?>

        border: 1px solid <?php echo $qode_options['social_icon_border_color']; ?>

        <?php } ?>
	}
<?php } ?>

<?php if(!empty($qode_options['social_icon_color'])) { ?>
    .q_social_icon_holder .fa-stack i,
	.q_social_icon_holder .fa-stack span {
        color: <?php echo $qode_options['social_icon_color']; ?>
    }
<?php } ?>

<?php

/* ==========================================================================
   Buttons custom styles
   ========================================================================== */
//generate button styles
$default_btn_styles 		= '';
$default_btn_hover_styles 	= '';

$small_btn_styles			= '';

$large_btn_styles			= '';

$extra_large_btn_styles		= '';

$white_btn_styles			= '';
$white_btn_hover_styles		= '';

//generate default button styles
if(isset($qode_options['button_title_color']) && $qode_options['button_title_color'] !== '') {
	$default_btn_styles .= 'color: '.$qode_options['button_title_color'].';';
}

if(isset($qode_options['button_title_fontsize']) && $qode_options['button_title_fontsize'] !== '') {
	$default_btn_styles .= 'font-size: '.$qode_options['button_title_fontsize'].'px;';
}

if(isset($qode_options['button_title_lineheight']) && $qode_options['button_title_lineheight'] !== '') {
	$default_btn_styles .= 'line-height: '.$qode_options['button_title_lineheight'].'px;';
	$default_btn_styles .= 'height: '.$qode_options['button_title_lineheight'].'px;';
}

if(isset($qode_options['button_title_fontstyle']) && $qode_options['button_title_fontstyle'] !== '') {
	$default_btn_styles .= 'font-style: '.$qode_options['button_title_fontstyle'].';';
}

if(isset($qode_options['button_title_fontweight']) && $qode_options['button_title_fontweight'] !== '') {
	$default_btn_styles .= 'font-weight: '.$qode_options['button_title_fontweight'].';';
}

if(isset($qode_options['button_title_google_fonts']) && $qode_options['button_title_google_fonts'] !== '-1') {
	$default_btn_styles .= 'font-family: "'.str_replace('+', ' ', $qode_options['button_title_google_fonts']).'";';
}

if(isset($qode_options['button_title_letter_spacing']) && $qode_options['button_title_letter_spacing'] !== '') {
	$default_btn_styles .= 'letter-spacing: '.$qode_options['button_title_letter_spacing'].'px;';
}

if(isset($qode_options['button_border_color']) && $qode_options['button_border_color'] !== '') {
	$default_btn_styles .= 'border-color: '.$qode_options['button_border_color'].';';
}

if(isset($qode_options['button_border_width']) && $qode_options['button_border_width'] !== '') {
	$default_btn_styles .= 'border-width: '.$qode_options['button_border_width'].'px;';
}

if(isset($qode_options['button_border_radius']) && $qode_options['button_border_radius'] !== '') {
	$default_btn_styles .= 'border-radius: '.$qode_options['button_border_radius'].'px;';
	$default_btn_styles .= '-moz-border-radius: '.$qode_options['button_border_radius'].'px;';
	$default_btn_styles .= '-webkit-border-radius: '.$qode_options['button_border_radius'].'px;';
}

if(isset($qode_options['button_backgroundcolor']) && $qode_options['button_backgroundcolor'] !== '') {
	$default_btn_styles .= 'background-color: '.$qode_options['button_backgroundcolor'].';';
}

//print default button styles
if($default_btn_styles !== '') {
	?>
	.qbutton,
	.qbutton.medium,
	#submit_comment,
	.load_more a,
	.blog_load_more_button a,
	.woocommerce .button,
	.woocommerce-page .button,
	.woocommerce-page input[type="submit"],
	.woocommerce input[type="submit"],
	.woocommerce ul.products li.product .added_to_cart,
    input.wpcf7-form-control.wpcf7-submit,
    input.wpcf7-form-control.wpcf7-submit:not([disabled]){
		<?php echo $default_btn_styles; ?>
	}
	<?php
}

//generate default button hover styles
if(isset($qode_options['button_title_hovercolor']) && $qode_options['button_title_hovercolor'] !== '') {
	$default_btn_hover_styles .= 'color: '.$qode_options['button_title_hovercolor'].';';
}

if(isset($qode_options['button_backgroundcolor_hover']) && $qode_options['button_backgroundcolor_hover'] !== '') {
	$default_btn_hover_styles .= 'background-color: '.$qode_options['button_backgroundcolor_hover'].';';
}

if(isset($qode_options['button_border_hover_color']) && $qode_options['button_border_hover_color'] !== '') {
	$default_btn_hover_styles .= 'border-color: '.$qode_options['button_border_hover_color'].';';
}

//print default button hover styles
if($default_btn_hover_styles !== '') {
	?>
	.qbutton:hover,
	.qbutton:not(.white):hover,
	.qbutton.medium:hover,
	#submit_comment:hover,
	.load_more a:hover,
	.blog_load_more_button a:hover,
	.woocommerce .button:hover,
	.woocommerce-page .button:hover,
	.woocommerce #submit:hover,
	.woocommerce ul.products li.product a.qbutton:hover,
	.woocommerce-page ul.products li.product a.qbutton:hover,
	.woocommerce ul.products li.product .added_to_cart:hover,
    .post-password-form input[type='submit']:hover,
    input.wpcf7-form-control.wpcf7-submit:not([disabled]):hover{
	    <?php echo $default_btn_hover_styles; ?>
	}
	<?php
}

//generate small button styles
if(isset($qode_options['small_button_fontsize']) && $qode_options['small_button_fontsize'] !== '') {
	$small_btn_styles .= 'font-size: '.$qode_options['small_button_fontsize'].'px;';
}

if(isset($qode_options['small_button_lineheight']) && $qode_options['small_button_lineheight'] !== '') {
	$small_btn_styles .= 'line-height: '.$qode_options['small_button_lineheight'].'px;';
	$small_btn_styles .= 'height: '.$qode_options['small_button_lineheight'].'px;';
}

if(isset($qode_options['small_button_fontweight']) && $qode_options['small_button_fontweight'] !== '') {
	$small_btn_styles .= 'font-weight: '.$qode_options['small_button_fontweight'].';';
}

if(isset($qode_options['small_button_padding']) && $qode_options['small_button_padding'] !== '') {
	$small_btn_styles .= 'padding-left: '.$qode_options['small_button_padding'].'px;';
	$small_btn_styles .= 'padding-right: '.$qode_options['small_button_padding'].'px;';
}

if(isset($qode_options['small_button_border_radius']) && $qode_options['small_button_border_radius'] !== '') {
	$small_btn_styles .= '-webkit-border-radius: '.$qode_options['small_button_border_radius'].'px;';
	$small_btn_styles .= '-moz-border-radius: '.$qode_options['small_button_border_radius'].'px;';
	$small_btn_styles .= 'border-radius: '.$qode_options['small_button_border_radius'].'px;';
}

//print small button styles
if($small_btn_styles !== '') {
	?>
	.qbutton.small {
		<?php echo $small_btn_styles; ?>
	}
	<?php
}

//generate large button styles
if(isset($qode_options['large_button_fontsize']) && $qode_options['large_button_fontsize'] !== '') {
	$large_btn_styles .= 'font-size: '.$qode_options['large_button_fontsize'].'px;';
}

if(isset($qode_options['large_button_lineheight']) && $qode_options['large_button_lineheight'] !== '') {
	$large_btn_styles .= 'line-height: '.$qode_options['large_button_lineheight'].'px;';
	$large_btn_styles .= 'height: '.$qode_options['large_button_lineheight'].'px;';
}

if(isset($qode_options['large_button_fontweight']) && $qode_options['large_button_fontweight'] !== '') {
	$large_btn_styles .= 'font-weight: '.$qode_options['large_button_fontweight'].';';
}

if(isset($qode_options['large_button_padding']) && $qode_options['large_button_padding'] !== '') {
	$large_btn_styles .= 'padding-left: '.$qode_options['large_button_padding'].'px;';
	$large_btn_styles .= 'padding-right: '.$qode_options['large_button_padding'].'px;';
}

if(isset($qode_options['large_button_border_radius']) && $qode_options['large_button_border_radius'] !== '') {
	$large_btn_styles .= '-webkit-border-radius: '.$qode_options['large_button_border_radius'].'px;';
	$large_btn_styles .= '-moz-border-radius: '.$qode_options['large_button_border_radius'].'px;';
	$large_btn_styles .= 'border-radius: '.$qode_options['large_button_border_radius'].'px;';
}

//print large button styles
if($large_btn_styles !== '') {
	?>
	.qbutton.large {
		<?php echo $large_btn_styles; ?>
	}
	<?php
}

//generate extra large button styles
if(isset($qode_options['big_large_button_fontsize']) && $qode_options['big_large_button_fontsize'] !== '') {
	$extra_large_btn_styles .= 'font-size: '.$qode_options['big_large_button_fontsize'].'px;';
}

if(isset($qode_options['big_large_button_lineheight']) && $qode_options['big_large_button_lineheight'] !== '') {
	$extra_large_btn_styles .= 'line-height: '.$qode_options['big_large_button_lineheight'].'px;';
	$extra_large_btn_styles .= 'height: '.$qode_options['big_large_button_lineheight'].'px;';
}

if(isset($qode_options['big_large_button_fontweight']) && $qode_options['big_large_button_fontweight'] !== '') {
	$extra_large_btn_styles .= 'font-weight: '.$qode_options['big_large_button_fontweight'].';';
}

if(isset($qode_options['big_large_button_padding']) && $qode_options['big_large_button_padding'] !== '') {
	$extra_large_btn_styles .= 'padding-left: '.$qode_options['big_large_button_padding'].'px;';
	$extra_large_btn_styles .= 'padding-right: '.$qode_options['big_large_button_padding'].'px;';
}

if(isset($qode_options['big_large_button_border_radius']) && $qode_options['big_large_button_border_radius'] !== '') {
	$extra_large_btn_styles .= '-webkit-border-radius: '.$qode_options['big_large_button_border_radius'].'px;';
	$extra_large_btn_styles .= '-moz-border-radius: '.$qode_options['big_large_button_border_radius'].'px;';
	$extra_large_btn_styles .= 'border-radius: '.$qode_options['big_large_button_border_radius'].'px;';
}

//print extra large button styles
if($extra_large_btn_styles !== '') {
	?>
	.qbutton.big_large,
	.qbutton.big_large_full_width,
	.wpb_row .qbutton.big_large_full_width {
	<?php echo $extra_large_btn_styles; ?>
	}
<?php
}

//generate white button styles
if(isset($qode_options['button_white_border_color']) && $qode_options['button_white_border_color'] !== '') {
	$white_btn_styles .= 'border-color: '.$qode_options['button_white_border_color'].';';
}

if(isset($qode_options['button_white_text_color']) && $qode_options['button_white_text_color'] !== '') {
	$white_btn_styles .= 'color: '.$qode_options['button_white_text_color'].';';
}

if(isset($qode_options['button_white_background_color']) && $qode_options['button_white_background_color'] !== '') {
	$white_btn_styles .= 'background-color: '.$qode_options['button_white_background_color'].';';
}

//print white button styles
if($white_btn_styles !== '') {
	?>
	.qbutton.white {
		<?php echo $white_btn_styles; ?>
	}
	<?php
}

//generate white button hover styles
if(isset($qode_options['button_white_border_color_hover']) && $qode_options['button_white_border_color_hover'] !== '') {
	$white_btn_hover_styles .= 'border-color: '.$qode_options['button_white_border_color_hover'].';';
}

if(isset($qode_options['button_white_text_color_hover']) && $qode_options['button_white_text_color_hover'] !== '') {
	$white_btn_hover_styles .= 'color: '.$qode_options['button_white_text_color_hover'].';';
}

if(isset($qode_options['button_white_background_color_hover']) && $qode_options['button_white_background_color_hover'] !== '') {
	$white_btn_hover_styles .= 'background-color: '.$qode_options['button_white_background_color_hover'].';';
}

if($white_btn_hover_styles !== '') {
	?>
	.qbutton.white:hover {
		<?php echo $white_btn_hover_styles; ?>
	}
	<?php
}

//disable double borders
if(isset($qode_options['button_remove_double_border']) && $qode_options['button_remove_double_border'] == 'yes') {
	?>
	.qbutton:before,
	.load_more a:before,
	.blog_load_more_button a:before,
	#submit_comment:before,
	.drop_down .wide .second ul li .qbutton:before,
	.drop_down .wide .second ul li ul li .qbutton:before {
	content: none;
	}
<?php
}

//generate styles for double borders
if(!isset($qode_options['button_remove_double_border'])) {
	$default_btn_dbl_border_styles = '';
	$default_btn_dbl_hover_border_styles = '';

	$white_btn_double_border_styles = '';
	$white_btn_double_hover_border_styles = '';

	//generate double border styles for default button
	if(isset($qode_options['button_border_color']) && $qode_options['button_border_color'] !== '') {
		$default_btn_dbl_border_styles .= 'border-color: '.$qode_options['button_border_color'].';';
	}

	if(isset($qode_options['button_border_width']) && $qode_options['button_border_width'] !== '') {
		$default_btn_dbl_border_styles .= 'border-width: '.$qode_options['button_border_width'].'px;';

		//change it's position so it doesn't get to close to original border
		$default_btn_dbl_border_styles .= 'top: -'.($qode_options['button_border_width'] + 6).'px;';
		$default_btn_dbl_border_styles .= 'right: -'.($qode_options['button_border_width'] + 6).'px;';
		$default_btn_dbl_border_styles .= 'bottom: -'.($qode_options['button_border_width'] + 6).'px;';
		$default_btn_dbl_border_styles .= 'left: -'.($qode_options['button_border_width'] + 6).'px;';
	}

	if(isset($qode_options['button_border_radius']) && $qode_options['button_border_radius'] !== '') {
		$default_btn_dbl_border_styles .= '-webkit-border-radius: '.$qode_options['button_border_radius'].'px;';
		$default_btn_dbl_border_styles .= '-moz-border-radius: '.$qode_options['button_border_radius'].'px;';
		$default_btn_dbl_border_styles .= 'border-radius: '.$qode_options['button_border_radius'].'px;';
	}

	if($default_btn_dbl_border_styles !== '') {
		?>
		.qbutton:before,
		.load_more a:before,
		.blog_load_more_button a:before,
		#submit_comment:before,
		.drop_down .wide .second ul li .qbutton:before,
		.drop_down .wide .second ul li ul li .qbutton:before {
			<?php echo $default_btn_dbl_border_styles; ?>
		}
		<?php
	}

	if(isset($qode_options['button_border_hover_color']) && $qode_options['button_border_hover_color'] !== '') {
		$default_btn_dbl_hover_border_styles .= 'border-color: '.$qode_options['button_border_hover_color'].';';
	}

	if($default_btn_dbl_hover_border_styles !== '') {
		?>
		.qbutton:hover:before,
		.load_more a:hover:before,
		.blog_load_more_button a:hover:before,
		#submit_comment:hover:before,
		.drop_down .wide .second ul li .qbutton:hover:before,
		.drop_down .wide .second ul li ul li .qbutton:hover:before {
			<?php echo $default_btn_dbl_hover_border_styles; ?>
		}
		<?php
	}

	if(isset($qode_options['button_white_border_color']) && $qode_options['button_white_border_color'] !== '') {
		$white_btn_double_border_styles .= 'border-color: '.$qode_options['button_white_border_color'].';';
	}

	if($white_btn_double_border_styles !== '') {
		?>
		.qbutton.white:before {
			<?php echo $white_btn_double_border_styles; ?>
		}
		<?php
	}

	if(isset($qode_options['button_white_border_color_hover']) && $qode_options['button_white_border_color_hover'] !== '') {
		$white_btn_double_hover_border_styles .= 'border-color: '.$qode_options['button_white_border_color_hover'].';';
	}

	if($white_btn_double_hover_border_styles !== '') {
		?>
		.qbutton.white:hover:before {
		<?php echo $white_btn_double_hover_border_styles; ?>
		}
	<?php
	}
}
/* ==========================================================================
   End of button custom styles
   ========================================================================== */
?>

<?php

$testimonials_text_styles = array();

if(isset($qode_options['testimonaials_font_family']) && $qode_options['testimonaials_font_family'] !== '-1') {
	$testimonials_text_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['testimonaials_font_family']).'", sans-serif';
}

if(isset($qode_options['testimonaials_font_size']) && $qode_options['testimonaials_font_size'] !== '') {
	$testimonials_text_styles[] = 'font-size: '.$qode_options['testimonaials_font_size'].'px';
}

if(isset($qode_options['testimonaials_letter_spacing']) && $qode_options['testimonaials_letter_spacing'] !== '') {
	$testimonials_text_styles[] = 'letter-spacing: '.$qode_options['testimonaials_letter_spacing'].'px';
}

if(isset($qode_options['testimonaials_font_weight']) && $qode_options['testimonaials_font_weight'] !== '') {
	$testimonials_text_styles[] = 'font-weight: '.$qode_options['testimonaials_font_weight'];
}

if(isset($qode_options['testimonaials_text_transform']) && $qode_options['testimonaials_text_transform'] !== '') {
	$testimonials_text_styles[] = 'text-transform: '.$qode_options['testimonaials_text_transform'];
}

if(isset($qode_options['testimonaials_color']) && $qode_options['testimonaials_color'] !== '') {
	$testimonials_text_styles[] = 'color: '.$qode_options['testimonaials_color'];
}

if(isset($qode_options['testimonaials_font_style']) && $qode_options['testimonaials_font_style'] !== '') {
	$testimonials_text_styles[] = 'font-style: '.$qode_options['testimonaials_font_style'];
}

if(is_array($testimonials_text_styles) && count($testimonials_text_styles)) { ?>
	.testimonials .testimonial_text_inner p:not(.testimonial_author) {
	<?php echo implode(';', $testimonials_text_styles); ?>
	}
<?php } ?>

<?php

$testimonials_author_style = array();

if(isset($qode_options['testimonials_author_font_family']) && $qode_options['testimonials_author_font_family'] !== '-1') {
	$testimonials_author_style[] = 'font-family: "'.str_replace('+', ' ', $qode_options['testimonials_author_font_family']).'", sans-serif';
}

if(isset($qode_options['testimonials_author_font_size']) && $qode_options['testimonials_author_font_size'] !== '') {
	$testimonials_author_style[] = 'font-size: '.$qode_options['testimonials_author_font_size'].'px';
}

if(isset($qode_options['testimonials_author_letter_spacing']) && $qode_options['testimonials_author_letter_spacing'] !== '') {
	$testimonials_author_style[] = 'letter-spacing: '.$qode_options['testimonials_author_letter_spacing'].'px';
}

if(isset($qode_options['testimonials_author_font_weight']) && $qode_options['testimonials_author_font_weight'] !== '') {
	$testimonials_author_style[] = 'font-weight: '.$qode_options['testimonials_author_font_weight'];
}

if(isset($qode_options['testimonials_author_text_transform']) && $qode_options['testimonials_author_text_transform'] !== '') {
	$testimonials_author_style[] = 'text-transform: '.$qode_options['testimonials_author_text_transform'];
}

if(isset($qode_options['testimonials_author_color']) && $qode_options['testimonials_author_color'] !== '') {
	$testimonials_author_style[] = 'color: '.$qode_options['testimonials_author_color'];
}

if(isset($qode_options['testimonials_author_font_style']) && $qode_options['testimonials_author_font_style'] !== '') {
	$testimonials_author_style[] = 'font-style: '.$qode_options['testimonials_author_font_style'];
}

if(is_array($testimonials_author_style) && count($testimonials_author_style)) { ?>
	.testimonials .testimonial_text_inner p.testimonial_author {
	<?php echo implode(';', $testimonials_author_style); ?>
	}
<?php } ?>


<?php if (isset($qode_options['testimonaials_navigation_border_radius']) && $qode_options['testimonaials_navigation_border_radius'] !== '') { ?>
	.testimonials_holder .flex-control-paging li a {
		border-radius: <?php echo $qode_options['testimonaials_navigation_border_radius'];  ?>px;
		-moz-border-radius: <?php echo $qode_options['testimonaials_navigation_border_radius'];  ?>px;
		-webkit-border-radius: <?php echo $qode_options['testimonaials_navigation_border_radius'];  ?>px;
	}
<?php } ?>


<?php if (isset($qode_options['testimonaials_text_alignment']) && $qode_options['testimonaials_text_alignment'] !== '') { ?>
	.testimonials_holder  {
		text-align: <?php echo $qode_options['testimonaials_text_alignment'];  ?>;
	}
<?php } ?>

<?php if (!empty($qode_options['counter_color']) || !empty($qode_options['counters_fontweight']) || !empty($qode_options['counters_font_size']) || (isset($qode_options['counters_font_family']) && $qode_options['counters_font_family'] !== '-1') || (isset($qode_options['counters_font_style']) && $qode_options['counters_font_style'] !== '')) { ?>
	.q_counter_holder span.counter{
	<?php if (!empty($qode_options['counter_color'])) { ?>	color: <?php echo $qode_options['counter_color'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['counters_fontweight'])) { ?>	font-weight: <?php echo $qode_options['counters_fontweight'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['counters_font_size'])) { ?>	font-size: <?php echo $qode_options['counters_font_size'];  ?>px; <?php } ?>
	<?php if ($qode_options['counters_font_family'] !== '-1') { ?>	font-family: '<?php echo str_replace('+', ' ', $qode_options['counters_font_family']);  ?>', sans-serif; <?php } ?>
	<?php if (isset($qode_options['counters_font_style']) && $qode_options['counters_font_style'] !== '') { ?>	font-style: <?php echo $qode_options['counters_font_style']; ?><?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['counter_text_color']) || !empty($qode_options['counters_text_fontweight']) || !empty($qode_options['counters_text_texttransform']) || !empty($qode_options['counters_text_letterspacing']) || !empty($qode_options['counters_text_font_size']) || (isset($qode_options['counters_text_font_family']) && $qode_options['counters_text_font_family'] !== '-1') || (isset($qode_options['counters_text_font_style']) && $qode_options['counters_text_font_style'] !== '')) { ?>
	.q_counter_holder p.counter_text{
	<?php if (!empty($qode_options['counter_text_color'])) { ?>	color: <?php echo $qode_options['counter_text_color'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['counters_text_fontweight'])) { ?>	font-weight: <?php echo $qode_options['counters_text_fontweight'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['counters_text_texttransform'])) { ?>	text-transform: <?php echo $qode_options['counters_text_texttransform'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['counters_text_letterspacing'])) { ?>	letter-spacing: <?php echo $qode_options['counters_text_letterspacing'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['counters_text_font_size'])) { ?>	font-size: <?php echo $qode_options['counters_text_font_size'];  ?>px; line-height: 1.25em; <?php } ?>
	<?php if (isset($qode_options['counters_text_font_style']) && $qode_options['counters_text_font_style'] !== '') { ?>	font-style: <?php echo $qode_options['counters_text_font_style']; ?>;<?php } ?>
	<?php
	if(isset($qode_options['counters_text_font_family']) && $qode_options['counters_text_font_family'] !== '-1') { ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['counters_text_font_family']); ?>', sans-serif;
	<?php }
	?>

	}
<?php } ?>
<?php if (!empty($qode_options['counter_separator_color'])) { ?>
	.wpb_column>.wpb_wrapper .q_counter_holder .separator.small
	{
	<?php if (!empty($qode_options['counter_separator_color'])) { ?>	border-color: <?php echo $qode_options['counter_separator_color'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['progress_bar_horizontal_fontsize']) || !empty($qode_options['progress_bar_horizontal_fontweight'])) { ?>
	.q_progress_bar .progress_number{
	<?php if (!empty($qode_options['progress_bar_horizontal_fontsize'])) { ?>	font-size: <?php echo $qode_options['progress_bar_horizontal_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['progress_bar_horizontal_fontweight'])) { ?>	font-weight: <?php echo $qode_options['progress_bar_horizontal_fontweight'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['pie_charts_fontsize']) || !empty($qode_options['pie_charts_fontweight'])) { ?>
	.q_pie_chart_holder .tocounter{
	<?php if (!empty($qode_options['pie_charts_fontsize'])) { ?>	font-size: <?php echo $qode_options['pie_charts_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['pie_charts_fontweight'])) { ?>	font-weight: <?php echo $qode_options['pie_charts_fontweight'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_border_color'])) { ?>
	.q_tabs.boxed .tabs-nav li.active,
	.q_tabs.boxed .tabs-nav li.active,
	.q_tabs.boxed .tabs-container,
	.q_tabs.vertical .tabs-nav li.active
	{
	<?php if (!empty($qode_options['tabs_border_color'])) { ?>border-color: <?php echo $qode_options['tabs_border_color'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_border_color'])) { ?>
	.q_tabs.vertical.left .tabs-container{
	<?php if (!empty($qode_options['tabs_border_color'])) { ?>border-left-color: <?php echo $qode_options['tabs_border_color'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_border_color'])) { ?>
	.q_tabs.vertical.right .tabs-container {
	<?php if (!empty($qode_options['tabs_border_color'])) { ?>border-right-color: <?php echo $qode_options['tabs_border_color'];  ?>; <?php } ?>
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_border_radius'])) { ?>
	.q_tabs.vertical.left .tabs-nav li.active a{
		border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		border-bottom-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-bottom-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-bottom-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
	}
	.q_tabs.boxed .tabs-nav li.active a{
		border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-top-left-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
	}
	.q_tabs.vertical.right .tabs-nav li.active a{
		border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-top-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		border-bottom-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-moz-border-bottom-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
		-webkit-border-bottom-right-radius: <?php echo $qode_options['tabs_border_radius'];  ?>px;
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_border_width'])) { ?>
	.q_tabs.boxed .tabs-nav li.active,
	.q_tabs.vertical .tabs-nav li.active
		{
			border-width: <?php echo $qode_options['tabs_border_width'];  ?>px;
		}

	.q_tabs.vertical.left .tabs-container{
		border-left-width: <?php echo $qode_options['tabs_border_width'];  ?>px;
		left: -<?php echo $qode_options['tabs_border_width'];  ?>px;
	}
	.q_tabs.vertical.right .tabs-container{
		border-right-width: <?php echo $qode_options['tabs_border_width'];  ?>px;
		right: -<?php echo $qode_options['tabs_border_width'];  ?>px;
	}
	.q_tabs.boxed .tabs-container{
		border-top-width: <?php echo $qode_options['tabs_border_width'];  ?>px;
		top: -<?php echo $qode_options['tabs_border_width'];  ?>px;
	}
<?php } ?>
<?php if (!empty($qode_options['tabs_text_size']) || !empty($qode_options['tabs_fontweight']) || (isset($qode_options['tabs_nav_font_family']) && $qode_options['tabs_nav_font_family'] != '-1') || (isset($qode_options['tabs_nav_letter_spacing']) && $qode_options['tabs_nav_letter_spacing'] !== '')) { ?>
	.q_tabs .tabs-nav li a{
	<?php if (!empty($qode_options['tabs_text_size'])) { ?>font-size: <?php echo $qode_options['tabs_text_size'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['tabs_fontweight'])) { ?>font-weight: <?php echo $qode_options['tabs_fontweight'];  ?>; <?php } ?>
	<?php if($qode_options['tabs_nav_font_family'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['tabs_nav_font_family']); ?>', sans-serif;
	<?php } ?>
	<?php if(isset($qode_options['tabs_nav_letter_spacing']) && $qode_options['tabs_nav_letter_spacing'] !== '') { ?>letter-spacing: <?php echo $qode_options['tabs_nav_letter_spacing']; ?>px; <?php } ?>
	}
<?php } ?>
<?php
if(isset($qode_options['google_maps_height'])){
	if (intval($qode_options['google_maps_height']) > 0) {
?>
.google_map{
	height: <?php echo intval($qode_options['google_maps_height']); ?>px;
}
<?php
	}
}
?>
<?php if (!empty($qode_options['footer_top_background_color'])) { ?>
	.footer_top_holder,	footer #lang_sel > ul > li > a,	footer #lang_sel_click > ul > li > a{
		background-color: <?php echo $qode_options['footer_top_background_color']; ?>;
	}
	footer #lang_sel ul ul a,footer #lang_sel_click ul ul a,footer #lang_sel ul ul a:visited,footer #lang_sel_click ul ul a:visited{
		background-color: <?php echo $qode_options['footer_top_background_color']; ?> !important;
	}
<?php } ?>

<?php

if(isset($qode_options['footer_top_border_color']) && $qode_options['footer_top_border_color'] !== '') {
	$footer_top_border_thickness = 1;
	if(isset($qode_options['footer_top_border_thickness']) && $qode_options['footer_top_border_thickness'] !== '') {
		$footer_top_border_thickness = $qode_options['footer_top_border_thickness'];
	}

	?>
	.footer_top_holder { border-top: <?php echo $footer_top_border_thickness; ?>px solid <?php echo $qode_options['footer_top_border_color']; ?> }
<?php } ?>

<?php if (!empty($qode_options['footer_top_title_color'])) { ?>
.footer_top .column_inner > div h2,
.footer_top .column_inner > div h3,
.footer_top .column_inner > div h4,
.footer_top .column_inner > div h5,
.footer_top .column_inner > div h6 {
	color:<?php echo $qode_options['footer_top_title_color'];  ?>;
}
<?php } ?>
<?php if (!empty($qode_options['footer_top_text_color'])) { ?>
	.footer_top,
	.footer_top p,
    .footer_top span,
    .footer_top li,
    .footer_top .textwidget,
    .footer_top .widget_recent_entries>ul>li>span {
		color: <?php echo $qode_options['footer_top_text_color'];  ?>;
	}
<?php } ?>
<?php if (!empty($qode_options['footer_link_color'])) { ?>
    .footer_top a,
	.footer_top .q_social_icon_holder i.simple_social
	{
        color: <?php echo $qode_options['footer_link_color']; ?> !important;
    }
<?php } ?>
<?php if (!empty($qode_options['footer_link_hover_color'])) { ?>
    .footer_top a:hover,
	.footer_top .q_social_icon_holder:hover i.simple_social
	{
        color: <?php echo $qode_options['footer_link_hover_color']; ?> !important;
    }
<?php } ?>
<?php if (!empty($qode_options['footer_bottom_background_color'])) { ?>
	.footer_bottom_holder, #lang_sel_footer{
		background-color:<?php echo $qode_options['footer_bottom_background_color'];  ?>;
	}
<?php } ?>

<?php

if(isset($qode_options['footer_bottom_top_border_color']) && $qode_options['footer_bottom_top_border_color'] !== '') {
	$footer_top_border_thickness = 1;
	if(isset($qode_options['footer_bottom_top_border_thickness']) && $qode_options['footer_bottom_top_border_thickness'] !== '') {
		$footer_top_border_thickness = $qode_options['footer_bottom_top_border_thickness'];
	}

	?>
	.footer_bottom_holder { border-top: <?php echo $footer_top_border_thickness; ?>px solid <?php echo $qode_options['footer_bottom_top_border_color']; ?> }
<?php } ?>

<?php if (!empty($qode_options['footer_bottom_text_color']) || isset($qode_options['footer_bottom_font_family']) && $qode_options['footer_bottom_font_family'] !== -1) { ?>
.footer_bottom, .footer_bottom span, .footer_bottom p, .footer_bottom p a, #lang_sel_footer ul li a,
footer #lang_sel > ul > li > a,
footer #lang_sel_click > ul > li > a,
footer #lang_sel a.lang_sel_sel,
footer #lang_sel_click a.lang_sel_sel,
footer #lang_sel ul ul a,
footer #lang_sel_click ul ul a,
footer #lang_sel ul ul a:visited,
footer #lang_sel_click ul ul a:visited,
footer #lang_sel_list.lang_sel_list_horizontal a,
footer #lang_sel_list.lang_sel_list_vertical a,
#lang_sel_footer a{
	<?php if(!empty($qode_options['footer_bottom_text_color'])) { ?>
		color: <?php echo $qode_options['footer_bottom_text_color']; ?>
	<?php } ?>

	<?php if($qode_options['footer_bottom_font_family'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['footer_bottom_font_family']); ?>', sans-serif;
	<?php } ?>
}
<?php } ?>

<?php

$footer_title_styles = array();

if(isset($qode_options['footer_title_font_family']) && $qode_options['footer_title_font_family'] !== '-1') {
	$footer_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['footer_title_font_family']).'", sans-serif';
}

if(isset($qode_options['footer_title_font_size']) && $qode_options['footer_title_font_size'] !== '') {
	$footer_title_styles[] = 'font-size: '.$qode_options['footer_title_font_size'].'px';
}

if(isset($qode_options['footer_title_letter_spacing']) && $qode_options['footer_title_letter_spacing'] !== '') {
	$footer_title_styles[] = 'letter-spacing: '.$qode_options['footer_title_letter_spacing'].'px';
}

if(isset($qode_options['footer_title_font_weight']) && $qode_options['footer_title_font_weight'] !== '') {
	$footer_title_styles[] = 'font-weight: '.$qode_options['footer_title_font_weight'];
}

if(isset($qode_options['footer_title_text_transform']) && $qode_options['footer_title_text_transform'] !== '') {
	$footer_title_styles[] = 'text-transform: '.$qode_options['footer_title_text_transform'];
}

if(isset($qode_options['footer_title_color']) && $qode_options['footer_title_color'] !== '') {
	$footer_title_styles[] = 'color: '.$qode_options['footer_title_color'];
}

if(isset($qode_options['footer_title_font_style']) && $qode_options['footer_title_font_style'] !== '') {
	$footer_title_styles[] = 'font-style: '.$qode_options['footer_title_font_style'];
}

if(is_array($footer_title_styles) && count($footer_title_styles)) { ?>
	.footer_top h5 {
		<?php echo implode(';', $footer_title_styles); ?>
	}
<?php } ?>

<?php if(isset($qode_options['footer_bottom_height']) && $qode_options['footer_bottom_height'] !== '') { ?>
	.footer_bottom { height: <?php echo $qode_options['footer_bottom_height']; ?>px; }
<?php } ?>

<?php if (!empty($qode_options['content_bottom_background_color'])) { ?>
	.content_bottom{
		background-color:<?php echo $qode_options['content_bottom_background_color'];  ?>;
	}
<?php } ?>
<?php if (isset($qode_options['side_area_background_color']) && !empty($qode_options['side_area_background_color'])) { ?>
	.side_menu,
	.side_menu #lang_sel,
	.side_menu #lang_sel_click,
	.side_menu #lang_sel ul ul,
	.side_menu #lang_sel_click ul ul{
		background-color:<?php echo $qode_options['side_area_background_color'];  ?>;
	}
<?php } ?>
<?php if (isset($qode_options['side_area_text_color']) && !empty($qode_options['side_area_text_color'])) { ?>
	.side_menu .widget,
	.side_menu .widget.widget_search form,
	.side_menu .widget.widget_search form input[type="text"],
	.side_menu .widget.widget_search form input[type="submit"],
	.side_menu .widget h6,
	.side_menu .widget h6 a,
	.side_menu .widget p,
	.side_menu .widget li a,
	.side_menu .widget.widget_rss li a.rsswidget,
	.side_menu #wp-calendar caption,
	.side_menu .widget li,
	.side_menu_title h3,
	.side_menu .widget.widget_archive select,
	.side_menu .widget.widget_categories select,
	.side_menu .widget.widget_text select,
	.side_menu .widget.widget_search form input[type="submit"],
	.side_menu #wp-calendar th,
	.side_menu #wp-calendar td,
	.side_menu .q_social_icon_holder i.simple_social
	{
		color: <?php echo $qode_options['side_area_text_color'];  ?>;
	}
<?php } ?>

<?php
$side_area_title_styles = array();

if(isset($qode_options['side_area_title_font_family']) && $qode_options['side_area_title_font_family'] !== '-1') {
	$side_area_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['side_area_title_font_family']).'", sans-serif';
}

if(isset($qode_options['side_area_title_font_size']) && $qode_options['side_area_title_font_size'] !== '') {
	$side_area_title_styles[] = 'font-size: '.$qode_options['side_area_title_font_size'].'px';
}

if(isset($qode_options['side_area_title_letter_spacing']) && $qode_options['side_area_title_letter_spacing'] !== '') {
	$side_area_title_styles[] = 'letter-spacing: '.$qode_options['side_area_title_letter_spacing'].'px';
}

if(isset($qode_options['side_area_title_font_weight']) && $qode_options['side_area_title_font_weight'] !== '') {
	$side_area_title_styles[] = 'font-weight: '.$qode_options['side_area_title_font_weight'];
}

if(isset($qode_options['side_area_title_text_transform']) && $qode_options['side_area_title_text_transform'] !== '') {
	$side_area_title_styles[] = 'text-transform: '.$qode_options['side_area_title_text_transform'];
}

if(isset($qode_options['side_area_title_color']) && $qode_options['side_area_title_color'] !== '') {
	$side_area_title_styles[] = 'color: '.$qode_options['side_area_title_color'];
}

if(isset($qode_options['side_area_title_font_style']) && $qode_options['side_area_title_font_style'] !== '') {
	$side_area_title_styles[] = 'font-style: '.$qode_options['side_area_title_font_style'];
}

if(is_array($side_area_title_styles) && count($side_area_title_styles)) { ?>
	.side_menu h5, .side_menu h6 { <?php echo implode(';', $side_area_title_styles); ?> }
<?php } ?>

<?php if (isset($qode_options['blog_quote_link_box_color']) && !empty($qode_options['blog_quote_link_box_color'])) { ?>
	.blog_holder.masonry article.format-quote .post_text .post_text_inner,
	.blog_holder.masonry article.format-link .post_text .post_text_inner,
	.blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner,
	.blog_holder.masonry_full_width article.format-link .post_text .post_text_inner {
		background-color: <?php echo $qode_options['blog_quote_link_box_color'];  ?>;
	}
	.blog_holder article.format-link .post_text:hover .post_text_inner,
	.blog_holder article.format-quote .post_text:hover .post_text_inner{
		border-color: <?php echo $qode_options['blog_quote_link_box_color'];  ?>;
	}
<?php } ?>
<?php if (isset($qode_options['blog_large_image_text_in_box']) && $qode_options['blog_large_image_text_in_box'] == "no") { ?>
	.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) .post_text .post_text_inner{
		padding-left:0;
		padding-right:0;
		background-color:transparent;
	}
<?php } ?>
<?php if (isset($qode_options['blog_large_image_border']) && $qode_options['blog_large_image_border'] == "yes") { ?>
	.blog_holder.blog_large_image article .post_text .post_text_inner{
		border:1px solid #f6f6f6;

	}
	.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) .post_text .post_text_inner{
		border-top:none;
	}
<?php } ?>
<?php if (!empty($qode_options['blog_large_image_border_color']) && (isset($qode_options['blog_large_image_text_in_box']) && $qode_options['blog_large_image_text_in_box'] == "yes")) { ?>
	.blog_holder.blog_large_image article .post_text .post_text_inner {
		border-color: <?php echo $qode_options['blog_large_image_border_color'];  ?>;
	}
<?php } ?>
<?php if (!empty($qode_options['blog_large_image_background_color']) && (isset($qode_options['blog_large_image_text_in_box']) && $qode_options['blog_large_image_text_in_box'] == "yes")) { ?>
	.blog_holder.blog_large_image article:not(.format-quote):not(.format-link) .post_text .post_text_inner {
	background-color: <?php echo $qode_options['blog_large_image_background_color'];  ?>;
	}
<?php } ?>
<?php if (!empty($qode_options['blog_large_image_border_width']) && (isset($qode_options['blog_large_image_text_in_box']) && $qode_options['blog_large_image_text_in_box'] == "yes")) { ?>
	.blog_holder.blog_large_image article .post_text .post_text_inner {
	border-width: <?php echo $qode_options['blog_large_image_border_width'];  ?>px;
	}
<?php } ?>

<?php if (!empty($qode_options['blog_masonry_border_color'])) { ?>
	.blog_holder.masonry article,
	.latest_post_holder.boxes>ul>li
	{
		border: 1px solid <?php echo $qode_options['blog_masonry_border_color'];  ?>;
	}

	.blog_holder.masonry article .post_text,
	.latest_post_holder.boxes>ul>li .latest_post {
		padding: 10px 33px;
	}

	.latest_post_holder.boxes>ul>li .latest_post {
		border-bottom: none;
	}

	.latest_post_holder.boxes .boxes_image {
		border-bottom: none;
	}
<?php } ?>

<?php if (isset($qode_options['blog_masonry_border']) && $qode_options['blog_masonry_border'] == 'no') { ?>
    .latest_post_holder.boxes>ul>li .latest_post,
    .latest_post_holder.boxes .boxes_image,
    .blog_holder.masonry article,
    .latest_post_holder.boxes>ul>li{
        border: none;
    }

    .blog_holder.masonry article .post_text,
    .latest_post_holder.boxes>ul>li .latest_post {
        padding: 0px;
    }
<?php } ?>

<?php if (!empty($qode_options['blog_masonry_border_radius'])) { ?>

	.blog_holder.masonry article .post_text .post_text_inner{
		border-radius: 0 0 <?php echo $qode_options['blog_masonry_border_radius'];  ?>px <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
		-moz-border-radius: 0 0 <?php echo $qode_options['blog_masonry_border_radius'];  ?>px <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
		-webkit-border-radius: 0 0 <?php echo $qode_options['blog_masonry_border_radius'];  ?>px <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
	}
	.blog_holder.masonry article.format-audio,
	.blog_holder.masonry article.format-quote .post_text .post_text_inner,
	.blog_holder.masonry article.format-link .post_text .post_text_inner{
		border-radius: <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
		-moz-border-radius: <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
		-webkit-border-radius: <?php echo $qode_options['blog_masonry_border_radius'];  ?>px;
	}
<?php } ?>


<?php if (isset($qode_options['blog_masonry_background_color']) && $qode_options['blog_masonry_background_color'] != '') { ?>

    .blog_holder.masonry article,
    .blog_holder.masonry article .post_text .post_text_inner,
    .blog_holder.masonry_full_width article .post_text .post_text_inner,
    .blog_holder.masonry article.format-quote .post_text .post_text_inner,
    .blog_holder.masonry article.format-link .post_text .post_text_inner,
    .blog_holder.masonry_full_width article.format-quote .post_text .post_text_inner,
    .blog_holder.masonry_full_width article.format-link .post_text .post_text_inner{
    background-color: <?php echo $qode_options['blog_masonry_background_color'];  ?>;
    }
<?php } ?>



<?php
//generate header buttons styles
$header_buttons_styles          = '';
$header_buttons_hover_styles    = '';
if(isset($qode_options['header_buttons_color']) && $qode_options['header_buttons_color']) {
    $header_buttons_styles .= 'color: '.$qode_options['header_buttons_color'].';';
}

if(isset($qode_options['header_buttons_font_size']) && $qode_options['header_buttons_font_size']) {
    $header_buttons_styles .= 'font-size: '.$qode_options['header_buttons_font_size'].'px'.';';
}

if(isset($qode_options['header_buttons_hover_color']) && $qode_options['header_buttons_hover_color'] != '') {
    $header_buttons_hover_styles .= 'color: '.$qode_options['header_buttons_hover_color'].';';
}

if($header_buttons_styles != ""){ ?>
    .side_menu_button > a,
    .mobile_menu_button span{ <?php echo $header_buttons_styles; ?> }

    .popup_menu .line,
    .popup_menu .line:after, .popup_menu .line:before{
        background-color: <?php echo $qode_options['header_buttons_color']; ?>;
    }
<?php }

if($header_buttons_hover_styles != "") { ?>
    .side_menu_button > a:hover,
    .mobile_menu_button span:hover,
    .popup_menu:hover .line,
    .popup_menu:hover .line:after,
    .popup_menu:hover .line:before{ <?php echo $header_buttons_hover_styles; ?> }

    .popup_menu:hover .line,
    .popup_menu:hover .line:after, .popup_menu:hover .line:before{
        background-color: <?php echo $qode_options['header_buttons_hover_color']; ?>;
    }
 <?php } ?>
<?php if(!empty($qode_options['vertical_area_background'])){ ?>
	aside.vertical_menu_area{
        background-color: <?php echo $qode_options['vertical_area_background']; ?>;
	}
<?php } ?>



<?php if(!empty($qode_options['vertical_area_text_color'])){ ?>
	aside .vertical_menu_area_widget_holder,
	aside .vertical_menu_area_widget_holder p,
	aside .vertical_menu_area_widget_holder span
	{
		color: <?php echo $qode_options['vertical_area_text_color']; ?>;
	}

<?php } ?>
<?php if (!empty($qode_options['vertical_menu_color']) || !empty($qode_options['vertical_menu_fontsize']) || !empty($qode_options['vertical_menu_lineheight']) || !empty($qode_options['vertical_menu_fontstyle']) || !empty($qode_options['vertical_menu_fontweight']) || !empty($qode_options['vertical_menu_letter_spacing']) || $qode_options['vertical_menu_google_fonts'] != "-1") { ?>
	nav.vertical_menu > ul > li > a{
	<?php if (!empty($qode_options['vertical_menu_color'])) { ?> color: <?php echo $qode_options['vertical_menu_color'];  ?>; <?php } ?>
	<?php if($qode_options['vertical_menu_google_fonts'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['vertical_menu_google_fonts']); ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['vertical_menu_fontsize'])) { ?> font-size: <?php echo $qode_options['vertical_menu_fontsize'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['vertical_menu_lineheight'])) { ?> line-height: <?php echo $qode_options['vertical_menu_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['vertical_menu_fontstyle'])) { ?> font-style: <?php echo $qode_options['vertical_menu_fontstyle'];  ?>; <?php } ?>
	<?php if (!empty($qode_options['vertical_menu_fontweight'])) { ?> font-weight: <?php echo $qode_options['vertical_menu_fontweight'];  ?>; <?php } ?>
	}
<?php } ?>

<?php if (!empty($qode_options['vertical_menu_hovercolor'])) { ?>
	nav.vertical_menu > ul > li.active > a,
	nav.vertical_menu > ul > li:hover > a{
	color: <?php echo $qode_options['vertical_menu_hovercolor'];  ?>;
	}
<?php } ?>

<?php if(!empty($qode_options['vertical_dropdown_color']) || !empty($qode_options['vertical_dropdown_fontsize']) || !empty($qode_options['vertical_dropdown_lineheight']) || !empty($qode_options['vertical_dropdown_fontstyle']) || !empty($qode_options['vertical_dropdown_fontweight']) || $qode_options['vertical_dropdown_google_fonts'] != "-1"){ ?>
	.vertical_menu .second .inner > ul > li > a,
	.vertical_menu .wide .second .inner > ul > li > a{
	<?php if (!empty($qode_options['vertical_dropdown_color'])) { ?> color: <?php echo $qode_options['vertical_dropdown_color']; ?>; <?php } ?>
	<?php if($qode_options['vertical_dropdown_google_fonts'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['vertical_dropdown_google_fonts']) ?>', sans-serif !important;
	<?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontsize'])) { ?> font-size: <?php echo $qode_options['vertical_dropdown_fontsize'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_lineheight'])) { ?> line-height: <?php echo $qode_options['vertical_dropdown_lineheight'];  ?>px; <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontstyle'])) { ?> font-style: <?php echo $qode_options['vertical_dropdown_fontstyle'];  ?>;  <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontweight'])) { ?>font-weight: <?php echo $qode_options['vertical_dropdown_fontweight'];  ?>; <?php } ?>
	}
<?php } ?>

<?php if (!empty($qode_options['vertical_dropdown_hovercolor'])) { ?>
	.vertical_menu .second .inner > ul > li > a:hover{
	color: <?php echo $qode_options['vertical_dropdown_hovercolor'];  ?> !important;
	}
<?php } ?>

<?php if(!empty($qode_options['vertical_dropdown_color_thirdlvl']) || !empty($qode_options['vertical_dropdown_fontsize_thirdlvl']) || !empty($qode_options['vertical_dropdown_lineheight_thirdlvl']) || !empty($qode_options['vertical_dropdown_fontstyle_thirdlvl']) || !empty($qode_options['vertical_dropdown_fontweight_thirdlvl']) || $qode_options['vertical_dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
	.vertical_menu .second .inner ul li.sub ul li a{
	<?php if (!empty($qode_options['vertical_dropdown_color_thirdlvl'])) { ?> color: <?php echo $qode_options['vertical_dropdown_color_thirdlvl'];  ?>;  <?php } ?>
	<?php if($qode_options['vertical_dropdown_google_fonts_thirdlvl'] != "-1"){ ?>
		font-family: '<?php echo str_replace('+', ' ', $qode_options['vertical_dropdown_google_fonts_thirdlvl']) ?>', sans-serif;
	<?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontsize_thirdlvl'])) { ?> font-size: <?php echo $qode_options['vertical_dropdown_fontsize_thirdlvl'];  ?>px;  <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_lineheight_thirdlvl'])) { ?> line-height: <?php echo $qode_options['vertical_dropdown_lineheight_thirdlvl'];  ?>px;  <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontstyle_thirdlvl'])) { ?> font-style: <?php echo $qode_options['vertical_dropdown_fontstyle_thirdlvl'];  ?>;   <?php } ?>
	<?php if (!empty($qode_options['vertical_dropdown_fontweight_thirdlvl'])) { ?> font-weight: <?php echo $qode_options['vertical_dropdown_fontweight_thirdlvl'];  ?>;  <?php } ?>
	}
<?php } ?>

<?php if (!empty($qode_options['vertical_dropdown_hovercolor_thirdlvl'])) { ?>
	.vertical_menu .second .inner ul li.sub ul li a:hover{
	color: <?php echo $qode_options['vertical_dropdown_hovercolor_thirdlvl'];  ?> !important;
	}
<?php } ?>

<?php if ((isset($qode_options['popup_menu_color']) && !empty($qode_options['popup_menu_color'])) ||
          (isset($qode_options['popup_menu_google_fonts']) && $qode_options['popup_menu_google_fonts'] != "-1") ||
          (isset($qode_options['popup_menu_fontsize']) && !empty($qode_options['popup_menu_fontsize'])) ||
          (isset($qode_options['popup_menu_lineheight']) && !empty($qode_options['popup_menu_lineheight'])) ||
          (isset($qode_options['popup_menu_fontstyle']) && !empty($qode_options['popup_menu_fontstyle'])) ||
          (isset($qode_options['popup_menu_fontweight']) && !empty($qode_options['popup_menu_fontweight'])) ||
          (isset($qode_options['popup_menu_letterspacing']) && !empty($qode_options['popup_menu_letterspacing']))) { ?>

    nav.popup_menu > ul > li > a,
    nav.popup_menu > ul > li > h6{
    <?php if (!empty($qode_options['popup_menu_color'])) { ?> color: <?php echo $qode_options['popup_menu_color'];  ?>; <?php } ?>
    <?php if($qode_options['popup_menu_google_fonts'] != "-1"){ ?>
        font-family: '<?php echo str_replace('+', ' ', $qode_options['popup_menu_google_fonts']); ?>', sans-serif;
    <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontsize'])) { ?> font-size: <?php echo $qode_options['popup_menu_fontsize'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_lineheight'])) { ?> line-height: <?php echo $qode_options['popup_menu_lineheight'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontstyle'])) { ?> font-style: <?php echo $qode_options['popup_menu_fontstyle'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontweight'])) { ?> font-weight: <?php echo $qode_options['popup_menu_fontweight'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_letterspacing'])) { ?> letter-spacing: <?php echo $qode_options['popup_menu_letterspacing'];  ?>px; <?php } ?>
    }
<?php } ?>

<?php if (isset($qode_options['popup_menu_color']) && !empty($qode_options['popup_menu_color'])) { ?>
    .popup_menu.opened .line:after,
    .popup_menu.opened .line:before{
        background-color: <?php echo $qode_options['popup_menu_color'];  ?>;
    }

<?php } ?>

<?php if ((isset($qode_options['popup_menu_hover_color']) && !empty($qode_options['popup_menu_hover_color'])) || (isset($qode_options['popup_menu_hover_background_color']) && !empty($qode_options['popup_menu_hover_background_color']))) { ?>
    nav.popup_menu > ul > li > a:hover,
    nav.popup_menu > ul > li > h6:hover{
    <?php if (!empty($qode_options['popup_menu_hover_color'])) { ?>  color: <?php echo $qode_options['popup_menu_hover_color'];  ?>;<?php } ?>
        <?php if (!empty($qode_options['popup_menu_hover_background_color'])) { ?> background-color: <?php echo $qode_options['popup_menu_hover_background_color'];  ?>; <?php } ?>
    }

<?php } ?>

<?php if ((isset($qode_options['popup_menu_color_2nd']) && !empty($qode_options['popup_menu_color_2nd'])) ||
    (isset($qode_options['popup_menu_google_fonts_2nd']) && $qode_options['popup_menu_google_fonts_2nd'] != "-1") ||
    (isset($qode_options['popup_menu_fontsize_2nd']) && !empty($qode_options['popup_menu_fontsize_2nd'])) ||
    (isset($qode_options['popup_menu_lineheight_2nd']) && !empty($qode_options['popup_menu_lineheight_2nd'])) ||
    (isset($qode_options['popup_menu_fontstyle_2nd']) && !empty($qode_options['popup_menu_fontstyle_2nd'])) ||
    (isset($qode_options['popup_menu_fontweight_2nd']) && !empty($qode_options['popup_menu_fontweight_2nd'])) ||
    (isset($qode_options['popup_menu_letterspacing_2nd']) && !empty($qode_options['popup_menu_letterspacing_2nd']))) { ?>

    nav.popup_menu ul li ul li a,
    nav.popup_menu ul li ul li h6{
    <?php if (!empty($qode_options['popup_menu_color_2nd'])) { ?> color: <?php echo $qode_options['popup_menu_color_2nd'];  ?>; <?php } ?>
    <?php if($qode_options['popup_menu_google_fonts_2nd'] != "-1"){ ?>
        font-family: '<?php echo str_replace('+', ' ', $qode_options['popup_menu_google_fonts_2nd']); ?>', sans-serif;
    <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontsize_2nd'])) { ?> font-size: <?php echo $qode_options['popup_menu_fontsize_2nd'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_lineheight_2nd'])) { ?> line-height: <?php echo $qode_options['popup_menu_lineheight_2nd'];  ?>px; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontstyle_2nd'])) { ?> font-style: <?php echo $qode_options['popup_menu_fontstyle_2nd'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_fontweight_2nd'])) { ?> font-weight: <?php echo $qode_options['popup_menu_fontweight_2nd'];  ?>; <?php } ?>
    <?php if (!empty($qode_options['popup_menu_letterspacing_2nd'])) { ?> letter-spacing: <?php echo $qode_options['popup_menu_letterspacing_2nd'];  ?>px; <?php } ?>
    }
<?php } ?>

<?php if ((isset($qode_options['popup_menu_hover_color_2nd']) && !empty($qode_options['popup_menu_hover_color_2nd'])) || (isset($qode_options['popup_menu_hover_background_color_2nd']) && !empty($qode_options['popup_menu_hover_background_color_2nd']))) { ?>
    nav.popup_menu ul li ul li a:hover,
    nav.popup_menu ul li ul li h6:hover{
    <?php if (!empty($qode_options['popup_menu_hover_color_2nd'])) { ?>  color: <?php echo $qode_options['popup_menu_hover_color_2nd'];  ?>;<?php } ?>
    <?php if (!empty($qode_options['popup_menu_hover_background_color_2nd'])) { ?> background-color: <?php echo $qode_options['popup_menu_hover_background_color_2nd'];  ?>; <?php } ?>
    }

<?php } ?>

<?php if(!empty($qode_options['popup_menu_background_color']) || !empty($qode_options['pattern_image_popup'])) { ?>
    <?php $popup_menu_background_color = qode_hex2rgb($qode_options['popup_menu_background_color']); ?>

    .popup_menu_holder{
        <?php if (!empty($qode_options['popup_menu_background_color'])) { ?>  background-color: rgba(<?php echo $popup_menu_background_color[0]; ?>,<?php echo $popup_menu_background_color[1]; ?>,<?php echo $popup_menu_background_color[2]; ?>,0.9); <?php } ?>
        <?php if (!empty($qode_options['pattern_image_popup'])) { ?> background-image:url('<?php echo $qode_options['pattern_image_popup']; ?>');  <?php } ?>
    }

<?php } ?>

<?php
//generate top header styles
$top_header_font_styles 		= '';
$top_header_font_hover_styles 	= '';
$top_header_border_styles 		= '';
if(isset($qode_options['top_header_text_color']) && $qode_options['top_header_text_color'] !== '') {
    $top_header_font_styles .= 'color: '.$qode_options['top_header_text_color'].';';
}

if(isset($qode_options['top_header_text_hover_color']) && $qode_options['top_header_text_hover_color'] !== '') {
    $top_header_font_hover_styles .= 'color: '.$qode_options['top_header_text_hover_color'].';';
}

if(isset($qode_options['top_header_text_font_family']) && $qode_options['top_header_text_font_family'] !== '-1') {
    $top_header_font_styles .= 'font-family: "'.str_replace('+', ' ', $qode_options['top_header_text_font_family']).'";';
}

if(isset($qode_options['top_header_text_font_size']) && $qode_options['top_header_text_font_size'] !== '') {
    $top_header_font_styles .= 'font-size: '.$qode_options['top_header_text_font_size'].'px;';
}

if(isset($qode_options['top_header_text_line_height']) && $qode_options['top_header_text_line_height'] !== '') {
    $top_header_font_styles .= 'line-height: '.$qode_options['top_header_text_line_height'].'px;';
}

if(isset($qode_options['top_header_text_font_style']) && $qode_options['top_header_text_font_style'] !== '') {
    $top_header_font_styles .= 'font-style: '.$qode_options['top_header_text_font_style'].';';
}

if(isset($qode_options['top_header_text_font_weight']) && $qode_options['top_header_text_font_weight'] !== '') {
    $top_header_font_styles .= 'font-weight: '.$qode_options['top_header_text_font_weight'].';';
}

if(isset($qode_options['top_header_text_letter_spacing']) && $qode_options['top_header_text_letter_spacing'] !== '') {
    $top_header_font_styles .= 'letter-spacing: '.$qode_options['top_header_text_letter_spacing'].'px;';
}

if(isset($qode_options['top_header_border_color']) && $qode_options['top_header_border_color'] !== '') {
    $top_header_border_styles .= 'border-bottom: 1px solid '.$qode_options['top_header_border_color'].';';
}

if(isset($qode_options['top_header_border_weight']) && $qode_options['top_header_border_weight'] !== '') {
    $top_header_border_styles .= 'border-width: '.$qode_options['top_header_border_weight'].'px;';
}

if($top_header_font_styles !== '') {
    ?>
    .header_top .q_social_icon_holder i.simple_social,
    .header_top .header-widget,
    .header_top .header-widget.widget_nav_menu ul.menu>li>a,
    .header_top .header-widget p,
    .header_top .header-widget a,
    .header_top .header-widget span {
    <?php echo $top_header_font_styles; ?>
    }

<?php
}

if($top_header_font_hover_styles !== '') {
    ?>
    .header_top .q_social_icon_holder i.simple_social:hover,
    .header_top .header-widget:hover,
    .header_top .header-widget.widget_nav_menu ul.menu>li>a:hover,
    .header_top .header-widget p:hover,
    .header_top .header-widget a:hover,
    .header_top .header-widget span:hover {
    <?php echo $top_header_font_hover_styles; ?>
    }

<?php
}

if($top_header_border_styles !== '') {
    ?>
    .header_top {
    <?php echo $top_header_border_styles; ?>
    }

<?php
}
?>

<?php
//generate categories filter styles
$portfolio_filter_font_styles = '';
$portfolio_filter_font_hover_styles = '';
if(isset($qode_options['portfolio_filter_color']) && $qode_options['portfolio_filter_color'] !== '') {
$portfolio_filter_font_styles .= 'color: '.$qode_options['portfolio_filter_color'].';';
}

if(isset($qode_options['portfolio_filter_hovercolor']) && $qode_options['portfolio_filter_hovercolor'] !== '') {
    $portfolio_filter_font_hover_styles .= 'color: '.$qode_options['portfolio_filter_hovercolor'].' !important;';
}

if(isset($qode_options['portfolio_filter_font_family']) && $qode_options['portfolio_filter_font_family'] !== '-1') {
$portfolio_filter_font_styles .= 'font-family: "'.str_replace('+', ' ', $qode_options['portfolio_filter_font_family']).'";';
}

if(isset($qode_options['portfolio_filter_font_size']) && $qode_options['portfolio_filter_font_size'] !== '') {
$portfolio_filter_font_styles .= 'font-size: '.$qode_options['portfolio_filter_font_size'].'px;';
}

if(isset($qode_options['portfolio_filter_line_height']) && $qode_options['portfolio_filter_line_height'] !== '') {
$portfolio_filter_font_styles .= 'line-height: '.$qode_options['portfolio_filter_line_height'].'px;';
}

if(isset($qode_options['portfolio_filter_font_style']) && $qode_options['portfolio_filter_font_style'] !== '') {
$portfolio_filter_font_styles .= 'font-style: '.$qode_options['portfolio_filter_font_style'].';';
}

if(isset($qode_options['portfolio_filter_font_weight']) && $qode_options['portfolio_filter_font_weight'] !== '') {
$portfolio_filter_font_styles .= 'font-weight: '.$qode_options['portfolio_filter_font_weight'].';';
}

if(isset($qode_options['portfolio_filter_letter_spacing']) && $qode_options['portfolio_filter_letter_spacing'] !== '') {
$portfolio_filter_font_styles .= 'letter-spacing: '.$qode_options['portfolio_filter_letter_spacing'].'px;';
}

if(isset($qode_options['portfolio_filter_text_transform']) && $qode_options['portfolio_filter_text_transform'] !== '') {
$portfolio_filter_font_styles .= 'text-transform: '.$qode_options['portfolio_filter_text_transform'].';';
}

if($portfolio_filter_font_styles !== '') {
?>
.filter_holder ul li span {
<?php echo $portfolio_filter_font_styles; ?>
    }
<?php
    if($qode_options['portfolio_filter_color'] !== ''){
?>
    .filter_holder ul li span:after{
        color: <?php echo $qode_options['portfolio_filter_color']; ?>;
    }
<?php
    }
}
if($portfolio_filter_font_hover_styles !== '') {
    ?>
    .filter_holder ul li.current span,
    .filter_holder ul li:hover span{
    <?php echo $portfolio_filter_font_hover_styles; ?>
    }

<?php
}
?>

<?php
//generate categories on portfolio lists styles
$portfolio_category_list_font_styles = '';
if(isset($qode_options['portfolio_category_list_color']) && $qode_options['portfolio_category_list_color'] !== '') {
    $portfolio_category_list_font_styles .= 'color: '.$qode_options['portfolio_category_list_color'].';';
}

if(isset($qode_options['portfolio_category_list_font_family']) && $qode_options['portfolio_category_list_font_family'] !== '-1') {
    $portfolio_category_list_font_styles .= 'font-family: "'.str_replace('+', ' ', $qode_options['portfolio_category_list_font_family']).'";';
}

if(isset($qode_options['portfolio_category_list_font_size']) && $qode_options['portfolio_category_list_font_size'] !== '') {
    $portfolio_category_list_font_styles .= 'font-size: '.$qode_options['portfolio_category_list_font_size'].'px;';
}

if(isset($qode_options['portfolio_category_list_line_height']) && $qode_options['portfolio_category_list_line_height'] !== '') {
    $portfolio_category_list_font_styles .= 'line-height: '.$qode_options['portfolio_category_list_line_height'].'px;';
}

if(isset($qode_options['portfolio_category_list_font_style']) && $qode_options['portfolio_category_list_font_style'] !== '') {
    $portfolio_category_list_font_styles .= 'font-style: '.$qode_options['portfolio_category_list_font_style'].';';
}

if(isset($qode_options['portfolio_category_list_font_weight']) && $qode_options['portfolio_category_list_font_weight'] !== '') {
    $portfolio_category_list_font_styles .= 'font-weight: '.$qode_options['portfolio_category_list_font_weight'].';';
}

if(isset($qode_options['portfolio_category_list_letter_spacing']) && $qode_options['portfolio_category_list_letter_spacing'] !== '') {
    $portfolio_category_list_font_styles .= 'letter-spacing: '.$qode_options['portfolio_category_list_letter_spacing'].'px;';
}

if(isset($qode_options['portfolio_category_list_text_transform']) && $qode_options['portfolio_category_list_text_transform'] !== '') {
    $portfolio_category_list_font_styles .= 'text-transform: '.$qode_options['portfolio_category_list_text_transform'].';';
}

if($portfolio_category_list_font_styles !== '') {
    ?>
    .projects_holder article .portfolio_description .project_category, .projects_holder.hover_text article .project_category, .portfolio_slider li.item .project_category {
    <?php echo $portfolio_category_list_font_styles; ?>
    }
    <?php
}
?>

<?php if($qode_options['contact_hide_separator_after_title'] == 'yes') { ?>
	.contact_section .separator.small { display: none; }
<?php } ?>

<?php

$expandable_section_style		= array();
$expandable_label_style 		= array();
$expandable_label_hover_style 	= array();

if(isset($qode_options['expandable_background_color']) && $qode_options['expandable_background_color'] !== '') {
	$expandable_section_style[] = 'background-color: '.$qode_options['expandable_background_color'];
}

if(isset($qode_options['expandable_label_color']) && $qode_options['expandable_label_color'] !== '') {
	$expandable_label_style[] = 'color: '.$qode_options['expandable_label_color'];
}

if(isset($qode_options['expandable_label_font_family']) && $qode_options['expandable_label_font_family'] !== '-1') {
	$expandable_label_style[] = 'font-family: "'.str_replace('+', ' ', $qode_options['expandable_label_font_family']).'", sans-serif';
}

if(isset($qode_options['expandable_label_font_size']) && $qode_options['expandable_label_font_size'] !== '') {
	$expandable_label_style[] = 'font-size: '.$qode_options['expandable_label_font_size'].'px';
}

if(isset($qode_options['expandable_label_letter_spacing']) && $qode_options['expandable_label_letter_spacing'] !== '') {
	$expandable_label_style[] = 'letter-spacing: '.$qode_options['expandable_label_letter_spacing'].'px';
}

if(isset($qode_options['expandable_label_font_weight']) && $qode_options['expandable_label_font_weight'] !== '') {
	$expandable_label_style[] = 'font-weight: '.$qode_options['expandable_label_font_weight'];
}

if(isset($qode_options['expandable_label_text_transform']) && $qode_options['expandable_label_text_transform'] !== '') {
	$expandable_label_style[] = 'text-transform: '.$qode_options['expandable_label_text_transform'];
}

if(isset($qode_options['expandable_label_hover_color']) && $qode_options['expandable_label_hover_color'] !== '') {
	$expandable_label_hover_style[] = 'color: '.$qode_options['expandable_label_hover_color'];
}

if(is_array($expandable_section_style) && count($expandable_section_style)) { ?>
	.more_facts_holder {
		<?php echo implode(';', $expandable_section_style); ?>
	}

<?php }

if(is_array($expandable_label_style) && count($expandable_label_style)) { ?>
	.more_facts_button, .more_facts_button .more_facts_button_text {
		<?php echo implode(';', $expandable_label_style); ?>
	}
<?php }

if(is_array($expandable_label_hover_style) && count($expandable_label_hover_style)) { ?>
	more_facts_button:hover, .more_facts_button .more_facts_button_text:hover {
		<?php echo implode(';', $expandable_label_hover_style); ?>
	}
<?php } ?>

<?php
$image_hover_effect_styles = array();
if(isset($qode_options['image_hover_background_color']) && $qode_options['image_hover_background_color'] !== '') {
	$image_hover_effect_bg_color = $qode_options['image_hover_background_color'];
	if(isset($qode_options['image_hover_background_color_transparency']) && $qode_options['image_hover_background_color_transparency']) {
		$image_hover_effect_bg_color = qode_rgba_color($qode_options['image_hover_background_color'], $qode_options['image_hover_background_color_transparency']);
	}

	$image_hover_effect_styles[] = 'background-color: '.$image_hover_effect_bg_color;
}

if(isset($qode_options['image_hover_transition_speed']) && $qode_options['image_hover_transition_speed'] !== '') {
	$image_hover_effect_styles[] = '-webkit-transition: opacity '.$qode_options['image_hover_transition_speed'].'s ease-in-out;
-moz-transition: opacity '.$qode_options['image_hover_transition_speed'].'s ease-in-out;
-o-transition: opacity '.$qode_options['image_hover_transition_speed'].'s ease-in-out;
-ms-transition: opacity '.$qode_options['image_hover_transition_speed'].'s ease-in-out;';
}

if(is_array($image_hover_effect_styles) && count($image_hover_effect_styles)) { ?>
	.projects_holder article span.text_holder,
	.projects_masonry_holder .text_holder,
	.portfolio_gallery a .gallery_text_holder,
	.gallery_holder ul li .gallery_hover,
	.portfolio_slides li:hover .image_pixel_hover {
		<?php echo implode(';', $image_hover_effect_styles); ?>
	}
<?php } ?>

<?php

if(isset($qode_options['pricing_table_title_bg_color']) && $qode_options['pricing_table_title_bg_color'] !== '') { ?>
	.price_table_inner ul li.table_title { background-color: <?php echo $qode_options['pricing_table_title_bg_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_title_text_color']) && $qode_options['pricing_table_title_text_color'] !== '') { ?>
	.price_table_inner ul li.table_title .title_content { color: <?php echo $qode_options['pricing_table_title_text_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_period_color']) && $qode_options['pricing_table_period_color'] !== '') { ?>
	.price_in_table .mark { color: <?php echo $qode_options['pricing_table_period_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_price_color']) && $qode_options['pricing_table_price_color'] !== '') { ?>
	.price_in_table .price, .price_in_table .value { color: <?php echo $qode_options['pricing_table_price_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_active_text_color']) && $qode_options['pricing_table_active_text_color'] !== '') { ?>
	.q_price_table.active .active_text span { color: <?php echo $qode_options['pricing_table_active_text_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_active_bg_color']) && $qode_options['pricing_table_active_bg_color'] !== '') { ?>
	.q_price_table.active .active_text, .active .price_table_inner .price_button a  { background-color: <?php echo $qode_options['pricing_table_active_bg_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_item_first_bg_color']) && $qode_options['pricing_table_item_first_bg_color'] !== '') { ?>
	.price_table_inner ul li.pricing_table_content li:nth-child(2n+1)  { background-color: <?php echo $qode_options['pricing_table_item_first_bg_color']; ?> }
<?php }

if(isset($qode_options['pricing_table_item_second_bg_color']) && $qode_options['pricing_table_item_second_bg_color'] !== '') { ?>
	.q_price_table .price_table_inner  { background-color: <?php echo $qode_options['pricing_table_item_second_bg_color']; ?> }
<?php }

$pricing_table_button_styles = array();

if(isset($qode_options['pricing_table_button_font_family']) && $qode_options['pricing_table_button_font_family'] !== '-1') {
	$pricing_table_button_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['pricing_table_button_font_family']).'", sans-serif';
}

if(isset($qode_options['pricing_table_button_font_size']) && $qode_options['pricing_table_button_font_size'] !== '') {
	$pricing_table_button_styles[] = 'font-size: '.$qode_options['pricing_table_button_font_size'].'px';
}

if(isset($qode_options['pricing_table_button_letter_spacing']) && $qode_options['pricing_table_button_letter_spacing'] !== '') {
	$pricing_table_button_styles[] = 'letter-spacing: '.$qode_options['pricing_table_button_letter_spacing'].'px';
}

if(isset($qode_options['pricing_table_button_font_weight']) && $qode_options['pricing_table_button_font_weight'] !== '') {
	$pricing_table_button_styles[] = 'font-weight: '.$qode_options['pricing_table_button_font_weight'];
}

if(isset($qode_options['pricing_table_button_text_transform']) && $qode_options['pricing_table_button_text_transform'] !== '') {
	$pricing_table_button_styles[] = 'text-transform: '.$qode_options['pricing_table_button_text_transform'];
}

if(isset($qode_options['pricing_table_button_color']) && $qode_options['pricing_table_button_color'] !== '') {
	$pricing_table_button_styles[] = 'color: '.$qode_options['pricing_table_button_color'];
}

if(isset($qode_options['pricing_table_button_bg_color']) && $qode_options['pricing_table_button_bg_color'] !== '') {
	$pricing_table_button_styles[] = 'background-color: '.$qode_options['pricing_table_button_bg_color'];
}

if(isset($qode_options['pricing_table_button_font_style']) && $qode_options['pricing_table_button_font_style'] !== '') {
	$pricing_table_button_styles[] = 'font-style: '.$qode_options['pricing_table_button_font_style'];
}

if(is_array($pricing_table_button_styles) && count($pricing_table_button_styles)) { ?>
	.price_table_inner .price_button a {
	<?php echo implode(';', $pricing_table_button_styles); ?>
	}
<?php } ?>

<?php
$sidebar_title_styles = array();

$sidebar_title_styles = array();

if(isset($qode_options['sidebar_title_font_family']) && $qode_options['sidebar_title_font_family'] !== '-1') {
	$sidebar_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['sidebar_title_font_family']).'", sans-serif';
}

if(isset($qode_options['sidebar_title_font_size']) && $qode_options['sidebar_title_font_size'] !== '') {
	$sidebar_title_styles[] = 'font-size: '.$qode_options['sidebar_title_font_size'].'px';
}

if(isset($qode_options['sidebar_title_letter_spacing']) && $qode_options['sidebar_title_letter_spacing'] !== '') {
	$sidebar_title_styles[] = 'letter-spacing: '.$qode_options['sidebar_title_letter_spacing'].'px';
}

if(isset($qode_options['sidebar_title_font_weight']) && $qode_options['sidebar_title_font_weight'] !== '') {
	$sidebar_title_styles[] = 'font-weight: '.$qode_options['sidebar_title_font_weight'];
}

if(isset($qode_options['sidebar_title_text_transform']) && $qode_options['sidebar_title_text_transform'] !== '') {
	$sidebar_title_styles[] = 'text-transform: '.$qode_options['sidebar_title_text_transform'];
}

if(isset($qode_options['sidebar_title_color']) && $qode_options['sidebar_title_color'] !== '') {
	$sidebar_title_styles[] = 'color: '.$qode_options['sidebar_title_color'];
}

if(isset($qode_options['sidebar_title_background_color']) && $qode_options['sidebar_title_background_color'] !== '') {
	$sidebar_title_styles[] = 'background-color: '.$qode_options['sidebar_title_background_color'];
}

if(isset($qode_options['sidebar_title_border_color']) && $qode_options['sidebar_title_border_color'] !== '') {
	$sidebar_title_styles[] = 'border: 1px solid '.$qode_options['sidebar_title_border_color'];
}

if(isset($qode_options['sidebar_title_font_style']) && $qode_options['sidebar_title_font_style'] !== '') {
	$sidebar_title_styles[] = 'font-style: '.$qode_options['sidebar_title_font_style'];
}

if(is_array($sidebar_title_styles) && count($sidebar_title_styles)) { ?>
	aside.sidebar .widget h5 {
	<?php echo implode(';', $sidebar_title_styles); ?>
	}
<?php } ?>

<?php

$comment_box_styles = array();
if(isset($qode_options['comment_box_background_color']) && $qode_options['comment_box_background_color'] !== '') {
	$comment_box_styles[] = 'background-color: '.$qode_options['comment_box_background_color'];
}

if(isset($qode_options['comment_box_border_color']) && $qode_options['comment_box_border_color'] !== '') {
	$comment_box_styles[] = 'border: 1px solid '.$qode_options['comment_box_border_color'];
}

if(is_array($comment_box_styles) && count($comment_box_styles)) { ?>
	.comment_holder .comment { <?php echo implode(';', $comment_box_styles); ?> }
<?php }

?>

<?php

$portfolio_box_styles = array();
if(isset($qode_options['portfolio_standard_box_color']) && $qode_options['portfolio_standard_box_color']) {
	$portfolio_box_styles[] = 'background-color: '.$qode_options['portfolio_standard_box_color'];
}

if(isset($qode_options['portfolio_standard_border_color']) && $qode_options['portfolio_standard_border_color']) {
	$portfolio_box_styles[] = 'border: 1px solid '.$qode_options['portfolio_standard_border_color'];
}

if(is_array($portfolio_box_styles) && count($portfolio_box_styles)) { ?>
	.projects_holder.standard article .portfolio_description,
	.projects_holder.standard_no_space article .portfolio_description {
		<?php echo implode(';', $portfolio_box_styles); ?>
	}
<?php }

if(isset($qode_options['portfolio_category_color']) && $qode_options['portfolio_category_color'] !== '') {?>
	.projects_holder article .portfolio_description .project_category,
	.projects_holder.hover_text article .project_category,
	.portfolio_slider li.item .project_category,
	.projects_masonry_holder .project_category {
		color: <?php echo $qode_options['portfolio_category_color']; ?>
	}
<?php }

if(isset($qode_options['portfolio_title_color']) && $qode_options['portfolio_title_color'] !== '') {?>
	.projects_holder article .portfolio_description .portfolio_title a,
	.projects_holder.hover_text article .hover_feature_holder_title .portfolio_title a,
	.portfolio_slider .portfolio_title a,
	.projects_masonry_holder .portfolio_title a {
	color: <?php echo $qode_options['portfolio_title_color']; ?>
	}
<?php }

if(isset($qode_options['portfolio_title_hover_color']) && $qode_options['portfolio_title_hover_color'] !== '') {?>
	.projects_holder article .portfolio_description .portfolio_title a:hover,
	.projects_holder.hover_text article .hover_feature_holder_title .portfolio_title a:hover,
	.portfolio_slider .portfolio_title a:hover,
	.projects_masonry_holder .portfolio_title a:hover {
	color: <?php echo $qode_options['portfolio_title_hover_color']; ?>
	}
<?php }

if(isset($qode_options['portfolio_separator_after_title']) && $qode_options['portfolio_separator_after_title'] == 'yes') {
	$portfolio_separator_styles = array();
	if(isset($qode_options['portfolio_separator_color']) && $qode_options['portfolio_separator_color'] !== '') {
		$portfolio_separator_styles[] = 'border-bottom-color: '.$qode_options['portfolio_separator_color'];
	}

	if(isset($qode_options['portfolio_separator_thickness']) && $qode_options['portfolio_separator_thickness'] !== '') {
		$portfolio_separator_styles[] = 'border-bottom-width: '.$qode_options['portfolio_separator_thickness'].'px';
	}

	if(isset($qode_options['portfolio_separator_width']) && $qode_options['portfolio_separator_width'] !== '') {
		$portfolio_separator_styles[] = 'width: '.$qode_options['portfolio_separator_width'].'px';
	}

	if(is_array($portfolio_separator_styles) && count($portfolio_separator_styles)) { ?>
		.projects_holder article .portfolio_description .separator,
		.projects_holder article .portfolio_description .separator,
		.projects_holder article .hover_feature_holder_title .separator,
		.portfolio_slider .image_holder .separator,
		.portfolio_slider .image_holder .separator.small,
		.wpb_column>.wpb_wrapper .projects_holder article .hover_feature_holder_title .separator.small,
		.wpb_column>.wpb_wrapper .projects_holder article .portfolio_description .separator.small,
		.wpb_column>.wpb_wrapper .image_holder .separator.small {
			<?php echo implode(';', $portfolio_separator_styles); ?>
		}
	<?php }

}

$portfolio_icons_styles = array();
if(isset($qode_options['portfolio_features_icons_bg_color']) && $qode_options['portfolio_features_icons_bg_color']) {
	$portfolio_icons_styles[] = 'background-color: '.$qode_options['portfolio_features_icons_bg_color'];
}

if(isset($qode_options['portfolio_features_icons_color']) && $qode_options['portfolio_features_icons_color']) {
	$portfolio_icons_styles[] = 'color: '.$qode_options['portfolio_features_icons_color'];
}

if(is_array($portfolio_icons_styles) && count($portfolio_icons_styles)) { ?>
	.projects_holder .hover_icon_holder .hover_icon,
	.portfolio_slider .hover_icon_holder .hover_icon,
	.projects_masonry_holder .hover_icon_holder .hover_icon,
	.projects_holder .hover_icon_holder.portfolio_like,
	.portfolio_slider .hover_icon_holder.portfolio_like,
	.projects_masonry_holder .hover_icon_holder.portfolio_like {
		<?php echo implode(';', $portfolio_icons_styles); ?>
	}
<?php }

$portfolio_icons_hover_styles = array();

if(isset($qode_options['portfolio_features_icons_bg_hover_color']) && $qode_options['portfolio_features_icons_bg_hover_color']) {
	$portfolio_icons_hover_styles[] = 'background-color: '.$qode_options['portfolio_features_icons_bg_hover_color'];
}

if(isset($qode_options['portfolio_features_icons_hover_color']) && $qode_options['portfolio_features_icons_hover_color']) {
	$portfolio_icons_hover_styles[] = 'color: '.$qode_options['portfolio_features_icons_hover_color'];
}

if(is_array($portfolio_icons_hover_styles) && count($portfolio_icons_hover_styles)) { ?>
	.projects_holder .hover_icon_holder .hover_icon:hover,
	.portfolio_slider .hover_icon_holder .hover_icon:hover,
	.projects_masonry_holder .hover_icon_holder .hover_icon:hover,
	.projects_holder .hover_icon_holder.portfolio_like:hover,
	.portfolio_slider .hover_icon_holder.portfolio_like:hover,
	.projects_masonry_holder .hover_icon_holder.portfolio_like:hover {
	<?php echo implode(';', $portfolio_icons_hover_styles); ?>
	}
<?php }

if(qode_is_woocommerce_installed()) {
	if(isset($qode_options) && $qode_options['woo_product_border'] !== '') { ?>
		.woocommerce ul.products li.product.product_with_borders { border-color: <?php echo $qode_options['woo_product_border']; ?> }
	<?php }

    if(isset($qode_options) && $qode_options['woo_product_background_color'] !== '') { ?>
        .woocommerce ul.products li.product, .woocommerce-page ul.products li.product { background-color: <?php echo $qode_options['woo_product_background_color']; ?> }
    <?php }

	$woo_product_item_title_styles = array();

	if(isset($qode_options['woo_product_title_font_family']) && $qode_options['woo_product_title_font_family'] !== '-1') {
		$woo_product_item_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_product_title_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_product_title_font_size']) && $qode_options['woo_product_title_font_size'] !== '') {
		$woo_product_item_title_styles[] = 'font-size: '.$qode_options['woo_product_title_font_size'].'px';
	}

	if(isset($qode_options['woo_product_title_letter_spacing']) && $qode_options['woo_product_title_letter_spacing'] !== '') {
		$woo_product_item_title_styles[] = 'letter-spacing: '.$qode_options['woo_product_title_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_product_title_font_weight']) && $qode_options['woo_product_title_font_weight'] !== '') {
		$woo_product_item_title_styles[] = 'font-weight: '.$qode_options['woo_product_title_font_weight'];
	}

	if(isset($qode_options['woo_product_title_text_transform']) && $qode_options['woo_product_title_text_transform'] !== '') {
		$woo_product_item_title_styles[] = 'text-transform: '.$qode_options['woo_product_title_text_transform'];
	}

	if(isset($qode_options['woo_product_title_color']) && $qode_options['woo_product_title_color'] !== '') {
		$woo_product_item_title_styles[] = 'color: '.$qode_options['woo_product_title_color'];
	}

	if(isset($qode_options['woo_product_title_font_style']) && $qode_options['woo_product_title_font_style'] !== '') {
		$woo_product_item_title_styles[] = 'font-style: '.$qode_options['woo_product_title_font_style'];
	}

	if(is_array($woo_product_item_title_styles) && count($woo_product_item_title_styles)) { ?>
		.woocommerce ul.products li.product h6 {
		<?php echo implode(';', $woo_product_item_title_styles); ?>
		}
	<?php }

	$woo_product_item_price_styles = array();

	if(isset($qode_options['woo_product_price_font_family']) && $qode_options['woo_product_price_font_family'] !== '-1') {
		$woo_product_item_price_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_product_price_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_product_price_font_size']) && $qode_options['woo_product_price_font_size'] !== '') {
		$woo_product_item_price_styles[] = 'font-size: '.$qode_options['woo_product_price_font_size'].'px';
	}

	if(isset($qode_options['woo_product_price_letter_spacing']) && $qode_options['woo_product_price_letter_spacing'] !== '') {
		$woo_product_item_price_styles[] = 'letter-spacing: '.$qode_options['woo_product_price_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_product_price_font_weight']) && $qode_options['woo_product_price_font_weight'] !== '') {
		$woo_product_item_price_styles[] = 'font-weight: '.$qode_options['woo_product_price_font_weight'];
	}

	if(isset($qode_options['woo_product_price_text_transform']) && $qode_options['woo_product_price_text_transform'] !== '') {
		$woo_product_item_price_styles[] = 'text-transform: '.$qode_options['woo_product_price_text_transform'];
	}

	if(isset($qode_options['woo_product_price_color']) && $qode_options['woo_product_price_color'] !== '') {
		$woo_product_item_price_styles[] = 'color: '.$qode_options['woo_product_price_color'];
	}

	if(isset($qode_options['woo_product_price_font_style']) && $qode_options['woo_product_price_font_style'] !== '') {
		$woo_product_item_price_styles[] = 'font-style: '.$qode_options['woo_product_price_font_style'];
	}

	if(is_array($woo_product_item_price_styles) && count($woo_product_item_price_styles)) { ?>
		.woocommerce ul.products li.product .price {
		<?php echo implode(';', $woo_product_item_price_styles); ?>
		}
	<?php }

	if(isset($qode_options['woo_separator_after_title']) && $qode_options['woo_separator_after_title'] == 'yes') {
		$woo_prod_list_separator_styles = array();

		if(isset($qode_options['woo_separator_after_title_color']) && $qode_options['woo_separator_after_title_color']) {
			$woo_prod_list_separator_styles[] = 'border-bottom-color: '.$qode_options['woo_separator_after_title_color'];
		}

		if(isset($qode_options['woo_separator_after_title_width']) && $qode_options['woo_separator_after_title_width']) {
			$woo_prod_list_separator_styles[] = 'width: '.$qode_options['woo_separator_after_title_width'].'px';
		}

		if(isset($qode_options['woo_separator_after_title_thickness']) && $qode_options['woo_separator_after_title_thickness']) {
			$woo_prod_list_separator_styles[] = 'border-bottom-width: '.$qode_options['woo_separator_after_title_thickness'].'px';
		}

		if(isset($qode_options['woo_separator_after_title_style']) && $qode_options['woo_separator_after_title_style']) {
			$woo_prod_list_separator_styles[] = 'border-bottom-style: '.$qode_options['woo_separator_after_title_style'];
		}

		if(is_array($woo_prod_list_separator_styles) && count($woo_prod_list_separator_styles)) { ?>
			.woocommerce ul.products li.product .separator.product_list_separator { <?php echo implode(';', $woo_prod_list_separator_styles); ?> }
		<?php }
	}

	if(isset($qode_options['woo_out_of_stock_bg_color']) && $qode_options['woo_out_of_stock_bg_color'] !== '') { ?>
		.woocommerce .product .onsale.out-of-stock-button { background-color: <?php echo $qode_options['woo_out_of_stock_bg_color']; ?> }
	<?php }

	if(isset($qode_options['woo_on_sale_bg_color']) && $qode_options['woo_on_sale_bg_color'] !== '') { ?>
		.woocommerce .product .onsale, .woocommerce .product .single-onsale { background-color: <?php echo $qode_options['woo_on_sale_bg_color']; ?> }
	<?php }

	$woo_out_of_stock_on_sale_styles = array();

	if(isset($qode_options['woo_out_of_stock_on_sale_font_family']) && $qode_options['woo_out_of_stock_on_sale_font_family'] !== '-1') {
		$woo_out_of_stock_on_sale_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_out_of_stock_on_sale_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_font_size']) && $qode_options['woo_out_of_stock_on_sale_font_size'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'font-size: '.$qode_options['woo_out_of_stock_on_sale_font_size'].'px';
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_letter_spacing']) && $qode_options['woo_out_of_stock_on_sale_letter_spacing'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'letter-spacing: '.$qode_options['woo_out_of_stock_on_sale_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_font_weight']) && $qode_options['woo_out_of_stock_on_sale_font_weight'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'font-weight: '.$qode_options['woo_out_of_stock_on_sale_font_weight'];
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_text_transform']) && $qode_options['woo_out_of_stock_on_sale_text_transform'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'text-transform: '.$qode_options['woo_out_of_stock_on_sale_text_transform'];
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_color']) && $qode_options['woo_out_of_stock_on_sale_color'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'color: '.$qode_options['woo_out_of_stock_on_sale_color'];
	}

	if(isset($qode_options['woo_out_of_stock_on_sale_font_style']) && $qode_options['woo_out_of_stock_on_sale_font_style'] !== '') {
		$woo_out_of_stock_on_sale_styles[] = 'font-style: '.$qode_options['woo_out_of_stock_on_sale_font_style'];
	}

	if(is_array($woo_out_of_stock_on_sale_styles) && count($woo_out_of_stock_on_sale_styles)) { ?>
		.woocommerce .product .onsale, .woocommerce .product .single-onsale {
		<?php echo implode(';', $woo_out_of_stock_on_sale_styles); ?>
		}
	<?php }

	$woo_product_results_text_styles = array();

	if(isset($qode_options['woo_product_results_text_font_family']) && $qode_options['woo_product_results_text_font_family'] !== '-1') {
		$woo_product_results_text_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_product_results_text_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_product_results_text_font_size']) && $qode_options['woo_product_results_text_font_size'] !== '') {
		$woo_product_results_text_styles[] = 'font-size: '.$qode_options['woo_product_results_text_font_size'].'px';
	}

	if(isset($qode_options['woo_product_results_text_letter_spacing']) && $qode_options['woo_product_results_text_letter_spacing'] !== '') {
		$woo_product_results_text_styles[] = 'letter-spacing: '.$qode_options['woo_product_results_text_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_product_results_text_font_weight']) && $qode_options['woo_product_results_text_font_weight'] !== '') {
		$woo_product_results_text_styles[] = 'font-weight: '.$qode_options['woo_product_results_text_font_weight'];
	}

	if(isset($qode_options['woo_product_results_text_text_transform']) && $qode_options['woo_product_results_text_text_transform'] !== '') {
		$woo_product_results_text_styles[] = 'text-transform: '.$qode_options['woo_product_results_text_text_transform'];
	}

	if(isset($qode_options['woo_product_results_text_color']) && $qode_options['woo_product_results_text_color'] !== '') {
		$woo_product_results_text_styles[] = 'color: '.$qode_options['woo_product_results_text_color'];
	}

	if(isset($qode_options['woo_product_results_text_font_style']) && $qode_options['woo_product_results_text_font_style'] !== '') {
		$woo_product_results_text_styles[] = 'font-style: '.$qode_options['woo_product_results_text_font_style'];
	}

	if(is_array($woo_product_results_text_styles) && count($woo_product_results_text_styles)) { ?>
		.woocommerce-result-count {
		<?php echo implode(';', $woo_product_results_text_styles); ?>
		}
	<?php }

	$woo_sidebar_title_styles = array();
	if(isset($qode_options['woo_sidebar_widget_title_bg_color']) && $qode_options['woo_sidebar_widget_title_bg_color'] !== '') {
		$woo_sidebar_title_styles[] = 'background-color: '.$qode_options['woo_sidebar_widget_title_bg_color'];
	}

	if(isset($qode_options['woo_sidebar_widget_title_border_color']) && $qode_options['woo_sidebar_widget_title_border_color'] !== '') {
		$woo_sidebar_title_styles[] = 'border: 1px solid '.$qode_options['woo_sidebar_widget_title_border_color'];
	}

	if(isset($qode_options['woo_sidebar_widget_title_color']) && $qode_options['woo_sidebar_widget_title_color'] !== '') {
		$woo_sidebar_title_styles[] = 'color: '.$qode_options['woo_sidebar_widget_title_color'];
	}

	if(is_array($woo_sidebar_title_styles) && count($woo_sidebar_title_styles)) { ?>
		.woocommerce aside.sidebar .woocommerce.widget h5 { <?php echo implode(';', $woo_sidebar_title_styles); ?> }
	<?php }

	if(isset($qode_options['woo_sidebar_widget_border_around_prod_item']) && $qode_options['woo_sidebar_widget_border_around_prod_item'] == 'no') { ?>
		.woocommerce aside.sidebar ul.product_list_widget li, aside ul.product_list_widget li { border: none; padding: 0; }
	<?php }

	$woo_prod_widget_title = array();

	if(isset($qode_options['woo_prod_widget_title_font_family']) && $qode_options['woo_prod_widget_title_font_family'] !== '-1') {
		$woo_prod_widget_title[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_prod_widget_title_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_prod_widget_title_font_size']) && $qode_options['woo_prod_widget_title_font_size'] !== '') {
		$woo_prod_widget_title[] = 'font-size: '.$qode_options['woo_prod_widget_title_font_size'].'px';
	}

	if(isset($qode_options['woo_prod_widget_title_letter_spacing']) && $qode_options['woo_prod_widget_title_letter_spacing'] !== '') {
		$woo_prod_widget_title[] = 'letter-spacing: '.$qode_options['woo_prod_widget_title_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_prod_widget_title_font_weight']) && $qode_options['woo_prod_widget_title_font_weight'] !== '') {
		$woo_prod_widget_title[] = 'font-weight: '.$qode_options['woo_prod_widget_title_font_weight'];
	}

	if(isset($qode_options['woo_prod_widget_title_text_transform']) && $qode_options['woo_prod_widget_title_text_transform'] !== '') {
		$woo_prod_widget_title[] = 'text-transform: '.$qode_options['woo_prod_widget_title_text_transform'];
	}

	if(isset($qode_options['woo_prod_widget_title_color']) && $qode_options['woo_prod_widget_title_color'] !== '') {
		$woo_prod_widget_title[] = 'color: '.$qode_options['woo_prod_widget_title_color'];
	}

	if(isset($qode_options['woo_prod_widget_title_font_style']) && $qode_options['woo_prod_widget_title_font_style'] !== '') {
		$woo_prod_widget_title[] = 'font-style: '.$qode_options['woo_prod_widget_title_font_style'];
	}

	if(is_array($woo_prod_widget_title) && count($woo_prod_widget_title)) { ?>
		.woocommerce aside ul.product_list_widget li > a {
		<?php echo implode(';', $woo_prod_widget_title); ?>
		}
	<?php }

	$woo_prod_widget_price = array();

	if(isset($qode_options['woo_prod_widget_price_font_family']) && $qode_options['woo_prod_widget_price_font_family'] !== '-1') {
		$woo_prod_widget_price[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_prod_widget_price_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_prod_widget_price_font_size']) && $qode_options['woo_prod_widget_price_font_size'] !== '') {
		$woo_prod_widget_price[] = 'font-size: '.$qode_options['woo_prod_widget_price_font_size'].'px';
	}

	if(isset($qode_options['woo_prod_widget_price_letter_spacing']) && $qode_options['woo_prod_widget_price_letter_spacing'] !== '') {
		$woo_prod_widget_price[] = 'letter-spacing: '.$qode_options['woo_prod_widget_price_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_prod_widget_price_font_weight']) && $qode_options['woo_prod_widget_price_font_weight'] !== '') {
		$woo_prod_widget_price[] = 'font-weight: '.$qode_options['woo_prod_widget_price_font_weight'];
	}

	if(isset($qode_options['woo_prod_widget_price_text_transform']) && $qode_options['woo_prod_widget_price_text_transform'] !== '') {
		$woo_prod_widget_price[] = 'text-transform: '.$qode_options['woo_prod_widget_price_text_transform'];
	}

	if(isset($qode_options['woo_prod_widget_price_color']) && $qode_options['woo_prod_widget_price_color'] !== '') {
		$woo_prod_widget_price[] = 'color: '.$qode_options['woo_prod_widget_price_color'];
	}

	if(isset($qode_options['woo_prod_widget_price_font_style']) && $qode_options['woo_prod_widget_price_font_style'] !== '') {
		$woo_prod_widget_price[] = 'font-style: '.$qode_options['woo_prod_widget_price_font_style'];
	}

	if(is_array($woo_prod_widget_price) && count($woo_prod_widget_price)) { ?>
		.woocommerce aside ul.product_list_widget li span.amount, aside ul.product_list_widget li span.amount {
		<?php echo implode(';', $woo_prod_widget_price); ?>
		}
	<?php }

	$woo_prod_single_title_styles = array();

	if(isset($qode_options['woo_prod_single_title_font_family']) && $qode_options['woo_prod_single_title_font_family'] !== '-1') {
		$woo_prod_single_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_prod_single_title_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_prod_single_title_font_size']) && $qode_options['woo_prod_single_title_font_size'] !== '') {
		$woo_prod_single_title_styles[] = 'font-size: '.$qode_options['woo_prod_single_title_font_size'].'px';
	}

	if(isset($qode_options['woo_prod_single_title_letter_spacing']) && $qode_options['woo_prod_single_title_letter_spacing'] !== '') {
		$woo_prod_single_title_styles[] = 'letter-spacing: '.$qode_options['woo_prod_single_title_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_prod_single_title_font_weight']) && $qode_options['woo_prod_single_title_font_weight'] !== '') {
		$woo_prod_single_title_styles[] = 'font-weight: '.$qode_options['woo_prod_single_title_font_weight'];
	}

	if(isset($qode_options['woo_prod_single_title_text_transform']) && $qode_options['woo_prod_single_title_text_transform'] !== '') {
		$woo_prod_single_title_styles[] = 'text-transform: '.$qode_options['woo_prod_single_title_text_transform'];
	}

	if(isset($qode_options['woo_prod_single_title_color']) && $qode_options['woo_prod_single_title_color'] !== '') {
		$woo_prod_single_title_styles[] = 'color: '.$qode_options['woo_prod_single_title_color'];
	}

	if(isset($qode_options['woo_prod_single_title_font_style']) && $qode_options['woo_prod_single_title_font_style'] !== '') {
		$woo_prod_single_title_styles[] = 'font-style: '.$qode_options['woo_prod_single_title_font_style'];
	}

	if(is_array($woo_prod_single_title_styles) && count($woo_prod_single_title_styles)) { ?>
		.woocommerce .product h1.product_title {
		<?php echo implode(';', $woo_prod_single_title_styles); ?>
		}
	<?php }

	$woo_prod_single_price_styles = array();

	if(isset($qode_options['woo_prod_single_price_font_family']) && $qode_options['woo_prod_single_price_font_family'] !== '-1') {
		$woo_prod_single_price_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_prod_single_price_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_prod_single_price_font_size']) && $qode_options['woo_prod_single_price_font_size'] !== '') {
		$woo_prod_single_price_styles[] = 'font-size: '.$qode_options['woo_prod_single_price_font_size'].'px';
	}

	if(isset($qode_options['woo_prod_single_price_letter_spacing']) && $qode_options['woo_prod_single_price_letter_spacing'] !== '') {
		$woo_prod_single_price_styles[] = 'letter-spacing: '.$qode_options['woo_prod_single_price_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_prod_single_price_font_weight']) && $qode_options['woo_prod_single_price_font_weight'] !== '') {
		$woo_prod_single_price_styles[] = 'font-weight: '.$qode_options['woo_prod_single_price_font_weight'];
	}

	if(isset($qode_options['woo_prod_single_price_text_transform']) && $qode_options['woo_prod_single_price_text_transform'] !== '') {
		$woo_prod_single_price_styles[] = 'text-transform: '.$qode_options['woo_prod_single_price_text_transform'];
	}

	if(isset($qode_options['woo_prod_single_price_color']) && $qode_options['woo_prod_single_price_color'] !== '') {
		$woo_prod_single_price_styles[] = 'color: '.$qode_options['woo_prod_single_price_color'];
	}

	if(isset($qode_options['woo_prod_single_price_font_style']) && $qode_options['woo_prod_single_price_font_style'] !== '') {
		$woo_prod_single_price_styles[] = 'font-style: '.$qode_options['woo_prod_single_price_font_style'];
	}

	if(is_array($woo_prod_single_price_styles) && count($woo_prod_single_price_styles)) { ?>
		.woocommerce div.product .summary p.price span.amount {
		<?php echo implode(';', $woo_prod_single_price_styles); ?>
		}
	<?php }

	$woo_prod_single_accordion_title_styles = array();

	if(isset($qode_options['woo_prod_single_accordion_title_font_family']) && $qode_options['woo_prod_single_accordion_title_font_family'] !== '-1') {
		$woo_prod_single_accordion_title_styles[] = 'font-family: "'.str_replace('+', ' ', $qode_options['woo_prod_single_accordion_title_font_family']).'", sans-serif';
	}

	if(isset($qode_options['woo_prod_single_accordion_title_font_size']) && $qode_options['woo_prod_single_accordion_title_font_size'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'font-size: '.$qode_options['woo_prod_single_accordion_title_font_size'].'px';
	}

	if(isset($qode_options['woo_prod_single_accordion_title_letter_spacing']) && $qode_options['woo_prod_single_accordion_title_letter_spacing'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'letter-spacing: '.$qode_options['woo_prod_single_accordion_title_letter_spacing'].'px';
	}

	if(isset($qode_options['woo_prod_single_accordion_title_font_weight']) && $qode_options['woo_prod_single_accordion_title_font_weight'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'font-weight: '.$qode_options['woo_prod_single_accordion_title_font_weight'];
	}

	if(isset($qode_options['woo_prod_single_accordion_title_text_transform']) && $qode_options['woo_prod_single_accordion_title_text_transform'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'text-transform: '.$qode_options['woo_prod_single_accordion_title_text_transform'];
	}

	if(isset($qode_options['woo_prod_single_accordion_title_color']) && $qode_options['woo_prod_single_accordion_title_color'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'color: '.$qode_options['woo_prod_single_accordion_title_color'];
	}

	if(isset($qode_options['woo_prod_single_accordion_title_font_style']) && $qode_options['woo_prod_single_accordion_title_font_style'] !== '') {
		$woo_prod_single_accordion_title_styles[] = 'font-style: '.$qode_options['woo_prod_single_accordion_title_font_style'];
	}

	if(is_array($woo_prod_single_accordion_title_styles) && count($woo_prod_single_accordion_title_styles)) { ?>
		.woocommerce .woocommerce-accordion .ui-accordion-header {
		<?php echo implode(';', $woo_prod_single_accordion_title_styles); ?>
		}
	<?php }

	if(isset($qode_options['woo_prod_single_top_section_borders']) && $qode_options['woo_prod_single_top_section_borders'] == 'no') { ?>
		.woocommerce .product .summary-inner { border-top: none; padding-top: 0; }
		.woocommerce div.product div[itemprop="description"] { border-bottom: none; }
	<?php }
}

if(is_admin_bar_showing()){
?>

@media only screen and (min-width: 1000px){
	header.sticky.sticky_animate,
	header.fixed{
		padding-top: 32px !important;
	}

	header.sticky .qode_search_form,
	header.fixed .qode_search_form{
		top: 32px;
	}

	.side_menu{
		top: 32px;
	}
}

<?php
}
?>