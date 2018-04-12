<?php 
global $qode_options;
$blog_hide_comments = "";
if (isset($qode_options['blog_hide_comments'])) {
	$blog_hide_comments = $qode_options['blog_hide_comments'];
}

$blog_hide_author = "";
if (isset($qode_options['blog_hide_author'])) {
    $blog_hide_author = $qode_options['blog_hide_author'];
}

$qode_like = "on";
if (isset($qode_options['qode_like'])) {
	$qode_like = $qode_options['qode_like'];
}
?>
<article id="post-<?php the_ID(); ?>">
	<div class="post_content_holder">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="post_image">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail('full'); ?>
				</a>
			</div>
		<?php } ?>
		<div class="post_text">
			<div class="post_text_inner">
				<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<div class="post_info">
							<?php if($blog_hide_author == "no") { ?>
                                <span class="post_author">
									<a class="post_author_link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<i class="icon_pencil-edit" aria-hidden="true"></i>
									<span><?php the_author_meta('display_name'); ?></span></a>
								</span>
                            <?php } ?>
							<span class="time">
								<i class="icon_clock_alt" aria-hidden="true"></i>
								<span><?php the_time('d.m.Y '); ?></span>
							</span>
							<span class="post_category">
								<i class="icon_tag_alt" aria-hidden="true"></i>
								<span><?php the_category(', '); ?></span>
							</span>
							
							<?php if($blog_hide_comments != "yes"){ ?>
								<a class="post_comments" href="<?php comments_link(); ?>" target="_self">
								<i class="icon_comment_alt" aria-hidden="true"></i>
								<span><?php comments_number('0', '1', '%'); ?></span></a>
							<?php } ?>
							
							<?php if( $qode_like == "on" ) { ?>
									<div class="blog_like">
									<?php if( function_exists('qode_like') ) qode_like(); ?>
								</div>
							<?php } ?>
							
							<?php if(isset($qode_options['enable_social_share'])  && $qode_options['enable_social_share'] == "yes") { ?>
								<?php echo do_shortcode('[social_share]'); ?>	
							<?php } ?>
						</div>
				<?php
					$my_excerpt = get_the_excerpt();
					if ($my_excerpt != '') {
						echo $my_excerpt;
					}
				?>
				<div class="post_more">
					<a href="<?php the_permalink(); ?>" class="qbutton small"><?php _e('Read More','qode'); ?></a>
				</div>
			</div>
		</div>
	</div>
</article>