<?php 
	$media = '';
	if( isset( $_GET['media'] ) ) $media = base64_decode($_GET['media']);
	
	$description = '';
	if( isset( $_GET['media'] ) ) $description = base64_decode($_GET['title']);

	$rand = rand(0,1000);
	$video = false;
	$browser = '';

	// Get user agent to fix firefox
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
    	$agent = $_SERVER['HTTP_USER_AGENT'];
	}

	if (strlen(strstr($agent, 'Firefox')) > 0) {
    	$browser = ' mgl_firefox';
	}

	if(substr(strrchr($media,'.'),1) == 'mp4') {
		$embbed_media = '<div class="mgl_instagram_video'.$browser.'">
							<video id="mgl_instagram_video_'.$rand.'" class="mfp-img video-js vjs-default-skin" controls preload="auto" width="auto" height="auto">
 								<source src="'.$media.'" type="video/mp4" />
							</video>
						</div>';
		$video = true;
	} else {
		$embbed_media = '<img class="mfp-img" src="'.$media.'" />';
	}
	 
?>
<div class="mfp-figure">
	<button class="mfp-close" type="button" title="Close (Esc)">×</button>
	<?php echo $embbed_media; ?>
<div class="mfp-bottom-bar">
  <div class="mfp-title"><?php echo $description; ?></div>
  <div class="mfp-counter"></div>
</div>
</div>
<?php if($video == true) { ?>
<script type="text/javascript">
	videojs("mgl_instagram_video_<?php echo $rand; ?>", {}, function(){
	});
</script>
<?php } ?>