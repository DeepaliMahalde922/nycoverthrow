<?php
	$lastsynced = 	get_option('_lastsynced_calender',false); 
	$l_date 	= 	date('j-M-Y', strtotime($lastsynced));
?>
<h2>Sync Settings</h2>
<p> Last synced at <b><?php echo $l_date; ?></b></p>
<div>
	<form name="" method="post" style="display: table;">
		<label style="display: table-cell;">Sync Class: <input type="checkbox" name="fetch_sync" id="classes" value="classes"/></label>
		<input type="image" src="<?php echo get_stylesheet_directory_uri().'/images/Live-Sync.png' ?>" name="sync_btn" alt="Sync" style="margin-left: 50px;">
	</form>	
</div>

<?php

	if(isset($_POST['fetch_sync'])) {
		if( $_POST['fetch_sync'] == 'classes' ){

			$prevent_after   =  get_option('booked_prevent_appointments_after',false);
			$prevent_before  =  get_option( '_lastsynced_calender',false);

			$diff = abs(strtotime($prevent_before) - strtotime($prevent_after));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$syncdays = $days +1;

			$response_handle =  update_classes_calender($syncdays);
			if($response_handle == 'true'){
				update_option( '_synclass_genrate', 'Manual');
				echo 'Sync for classes has completed successfully for '. $syncdays.' days.';
			}else{
				echo 'Something went wrong. Please try again later!';
			}
		}
	}

?>