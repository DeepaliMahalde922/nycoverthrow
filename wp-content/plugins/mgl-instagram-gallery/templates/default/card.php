<div class="mgl-instagram-card mgl_instagram_template_<?php echo $template; ?>">
	<div class="mgl-instagram-card__header">
		<span class="mgl-instagram-card__header__title">
			<?php echo $user->username; ?>
		</span>
	</div>
	<div class="mgl-instagram-card__container">
		<div class="mgl-instagram-card__row">
			<div class="mgl-instagram-card__avatar">
				<img src="<?php echo $user->profile_picture; ?>">
			</div>
			<div class="mgl-instagram-card__bio">

				<div class="mgl-instagram-card__bio__name"><?php echo $user->full_name; ?></div>
				<div class="mgl-instagram-card__bio__description"><?php echo $user->bio; ?></div>
				<?php if( isset($user->website) ) { ?>
					<a href="<?php echo $user->website; ?>" target="_blank"><?php echo mgl_instagram_format_link( $user->website ); ?></a>
				<?php } ?>
			</div>
		</div>
		<div class="mgl-instagram-card__row">
			<ul class="mgl-instagram-card__counts">
				<li><span><?php echo mgl_instagram_instagram_format_number( $user->counts->media ); ?></span><small><?php _e('posts', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></small></li>
				<li><span><?php echo mgl_instagram_instagram_format_number( $user->counts->followed_by ); ?></span><small><?php _e('followers', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></small></li>
				<li><span><?php echo mgl_instagram_instagram_format_number( $user->counts->follows ); ?></span><small><?php _e('following', MGL_INSTAGRAM_GALLERY_DOMAIN); ?></small></li>
			</ul>
		</div>
		<div class="mgl-instagram-card__row">
			<?php echo $gallery; ?>
		</div>
	</div>
</div>