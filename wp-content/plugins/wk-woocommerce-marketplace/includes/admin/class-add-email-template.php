<?php

if ( ! defined( 'ABSPATH' ) )
		exit;

global $wpdb;
$table_name = $wpdb->prefix.'emailTemplate';
$results = '';

if ( isset( $_POST[ 'submit_template' ] ) ) {

		$title 			= $_POST['txtbx'];
		$logo 			= $_POST['upld_logo'];
		$base 			= $_POST['frstclr'];
		$body 			= $_POST['scndclr'];
		$background = $_POST['thrdclr'];
		$txt 				= $_POST['frthclr'];
		$width 			= $_POST['page_width'];

		if( empty($title) || empty($logo) || empty($base) || empty($body) || empty($background) || empty($txt) || empty($width) ) {
				echo "<div class='notice notice-error'><h4>Please fill all details.</h4></div>";
		}
		else {
				if ( isset( $_GET['user'] ) ) {
						$id = $_GET['user'];
						$sql = $wpdb->update(
								$table_name,
								array(
										'title'			=> $title,
										'logoPath'	=> $logo,
										'basecolor'	=> $base,
										'bodycolor'	=> $body,
										'backgroundcolor'	=> $background,
										'textcolor'	=> $txt,
										'pagewidth'	=> $width
								),
								array(
										'id'	=> $id
								),
								array(
										'%s',
										'%s',
										'%s',
										'%s',
										'%s',
										'%s',
										'%d'
								),
								array( '%d' )
						);
				}
				else {
						$sql = $wpdb->insert(
								$table_name,
								array(
										'title'			=> $title,
										'logoPath'	=> $logo,
										'basecolor'	=> $base,
										'bodycolor'	=> $body,
										'backgroundcolor'	=> $background,
										'textcolor'	=> $txt,
										'pagewidth'	=> $width,
										'status'		=> 'publish'
								),
								array(
										'%s',
										'%s',
										'%s',
										'%s',
										'%s',
										'%s',
										'%d',
										'%s'
								)
						);

				}
				if ( $sql ) {
						if ( isset( $_GET['user'] ) ) {
								echo "<div class='notice notice-success'><h4>Template updated successfully.</h4></div>";
						}
						else {
								echo "<div class='notice notice-success'><h4>Template added successfully.</h4></div>";
						}
				}
  	}
}


if( isset( $_GET[ 'user' ] ) ) {

		global $wpdb;
		$id = $_GET['user'];
		$sql = "SELECT * FROM $table_name WHERE id = $id";
		$results = $wpdb->get_results($sql);
		$results = $results[0];

}

?>

<div class="wrap">

		<?php
		if( isset( $_GET[ 'user' ] ) ) :
				echo '<h1 class="wp-heading-inline">Edit Template</h1>';
		else :
				echo '<h1 class="wp-heading-inline">Add New Template</h1>';
		endif;
		echo '<a href="?page=class-email-templates" class="page-title-action">Back</a>';
		?>

		<form method="POST" id="emailtemplate">

				<div id="titlediv" style="margin-bottom:30px;">
						<div id="titlewrap">
								<input type="text" name="txtbx" size="30" value="<?php if( $results ) echo $results->title; ?>" id="title" class="tmplt_name" spellcheck="true" autocomplete="off" placeholder="Enter title here">
						</div>
				</div>

				<table class="form-table">
						<tbody>

								<tr valign="top">
		  							<th scope="row" class="titledesc">Header Logo</th>
										<td class="forminp"><div class="btns">
										   <input type="button" name="btn" class="button button-primary upload-button" id="uploadButton" value="Upload">
										   <p><input type="text" name="upld_logo" class="regular-text img_txtbx" id="img_url" value='<?php if( $results ) echo $results->logoPath; ?>'></p>
								    </div></td>
								</tr>

  							<tr valign="top"><th>Style Options</th><td><hr></td></tr>

								<tr valign="top">
			  						<th scope="row" class="titledesc">Base Color</th>
		        				<td class="forminp"><input type="text" name="frstclr" id="clr1" class="frstclrchooser" value="<?php if( $results ) echo $results->basecolor; ?>"></td>
    						</tr>

								<tr valign="top">
					          <th scope="row" class="titledesc">Body Color</th>
						      	<td class="forminp"><input type="text" name="scndclr" id="clr2" class="frstclrchooser" value="<?php if( $results ) echo $results->bodycolor; ?>" ></td>
						    <tr>

								<tr valign="top">
		  							<th scope="row" class="titledesc">Background Color</th>
		    						<td class="forminp"><input type="text" name="thrdclr" id="clr3"  class="thirdclrchooser" value="<?php if( $results ) echo $results->backgroundcolor; ?>" ></td>
								</tr>

							  <tr valign="top">
							   		<th scope="row" class="titledesc">Text Color</th>
						       	<td class="forminp"><input type="text" name="frthclr" id="clr4" class="frstclrchooser" value="<?php if( $results ) echo $results->textcolor; ?>" ></td>
							   </tr>

							 	<tr valign="top">
		  							<th scope="row" class="titledesc">Page Width</th>
		    						<td class="forminp"><input ype="text" name="page_width" id="page-width" class="regular-text" placeholder="Enter Page Width" value="<?php if( $results ) echo $results->pagewidth; ?>" >
										<p class="width_err description">Enter width in px.</p></td>
										<input type="hidden" name="user" value="<?php echo get_current_user_id(); ?>">
								</tr>

						</tbody>

				</table>

 				<p class="submit"><input type="submit" name="submit_template" value="Save" class="button button-primary saveBtn"></p>

		</form>

</div>
