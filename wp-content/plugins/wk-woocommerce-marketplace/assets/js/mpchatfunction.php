<?php 
if ( ! defined( 'ABSPATH' ) ) { echo json_encode(array('sorry')); exit; };
// add_action( 'wp_ajax_my_action', 'my_action_mpcallback' );
	add_action( 'wp_ajax_add_foobar', 'prefix_ajax_add_foobar' );
	add_action( 'wp_ajax_nopriv_add_foobar', 'prefix_ajax_add_foobar' );

	function prefix_ajax_add_foobar() {
	    // Handle request then generate response using WP_Ajax_Response
	    global $wpdb; // this is how you get access to the database
		$a=array();
		$a=$_POST;
		$a['timestamp']=time();
		$timestamp=$a['timestamp'];
		$sender_id=$a['sender_id'];
		$receiver_id=$a['receiver_id'];
		$message=$a['message'];
		$insert="INSERT INTO ".$wpdb->prefix."mpchat SET sender_id=".$sender_id.", receiver_id=".$receiver_id.",timestamp=".$timestamp.", message='".$message."', status=1 ";
		$wpdb->query($insert);
		echo json_encode($a);
	}

// function my_action_mpcallback() {
// 	global $wpdb; // this is how you get access to the database

// 	return "hello";
// 	wp_die(); // this is required to terminate immediately and return a proper response
// }
/*	add_action('wp_ajax_my_action','new_ajax_mp');
	function new_ajac_mp(){
		global $wpdb; // this is how you get access to the database
		$a=array();
		$a=$_POST;
		$a['timestamp']=time();
		$timestamp=$a['timestamp'];
		$sender_id=$a['sender_id'];
		$receiver_id=$a['receiver_id'];
		$message=$a['message'];
		$insert="INSERT INTO ".$wpdb->prefix."mpchat SET sender_id=".$sender_id.", receiver_id=".$receiver_id.",timestamp=".$timestamp.", message='".$message."', status=1 ";
		$wpdb->query($insert);
		echo json_encode($a);
		wp_die();
	}
	do_action('wp_ajax_my_action');*/
		// global $wpdb; // this is how you get access to the database
		// $a=array();
		// $a=$_POST;
		// $a['timestamp']=time();
		// $timestamp=$a['timestamp'];
		// $sender_id=$a['sender_id'];
		// $receiver_id=$a['receiver_id'];
		// $message=$a['message'];
		// $insert="INSERT INTO ".$wpdb->prefix."mpchat SET sender_id=".$sender_id.", receiver_id=".$receiver_id.",timestamp=".$timestamp.", message='".$message."', status=1 ";
		// $wpdb->query($insert);
		// echo json_encode($a);
		// wp_die();

?>