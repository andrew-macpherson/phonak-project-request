<input type="hidden" name="section_ad_information" value="AD INFORMATION" />
<div>
	<h3>Ad Information</h3>
	<div>
		<label>Have you booked an AD space in your publication?</label>
		<label><input type="radio" name="booked_ad_space" value="yes" <?php if(isset($_POST['booked_ad_space']) && $_POST['booked_ad_space'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
		<label><input type="radio" name="booked_ad_space" value="no" <?php if(isset($_POST['booked_ad_space']) && $_POST['booked_ad_space'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
	</div>
	<div>
		<label>If yes, when is the deadline to submit the AD to your publication?</label>
		<input type="text" name="ad_space_deadline" value="<?php if(isset($_POST['ad_space_deadline'])){ echo $_POST['ad_space_deadline']; } ?>" />
	</div>
	<div>
		<label>Please provide your AD dimensions (in inches) as per your booking.</label>
		<input type="text" name="ad_dimensions_width" placeholder="Width" value="<?php if(isset($_POST['ad_dimensions_width'])){ echo $_POST['ad_dimensions_width']; } ?>" />
		<input type="text" name="ad_dimensions_height" placeholder="Height" value="<?php if(isset($_POST['ad_dimensions_height'])){ echo $_POST['ad_dimensions_height']; } ?>" />
	</div>
	<div>
		<label>Please provide the name of your publication?</label>
		<input type="text" name="publication_name" value="<?php if(isset($_POST['publication_name'])){ echo $_POST['publication_name']; } ?>" />
	</div>
</div>