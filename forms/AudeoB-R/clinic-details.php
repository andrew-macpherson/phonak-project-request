<input type="hidden" name="section_clinic_information" value="CLINIC DETAILS" />
<div>
	<h3>Clinic Content for Advertisement</h3>
	<div>
		<label>Clinic Name</label>
		<input type="text" name="clinic_name" value="<?php if(isset($_POST['clinic_name'])){ echo $_POST['clinic_name']; } ?>" />
	</div>
	<div>
		<label>Address</label>
		<input type="text" name="clinic_address" value="<?php if(isset($_POST['clinic_address'])){ echo $_POST['clinic_address']; } ?>" />
	</div>
	<div>
		<label>Phone Number</label>
		<input type="text" name="clinic_phone" value="<?php if(isset($_POST['clinic_phone'])){ echo $_POST['clinic_phone']; } ?>" />
	</div>
	<div>
		<label>Website</label>
		<input type="text" name="clinic_Website" value="<?php if(isset($_POST['clinic_Website'])){ echo $_POST['clinic_Website']; } ?>" />
	</div>
	<div>
		<label>Please upload your logo if you would like it included in the ad:</label>
		<input type="file" name="clinic_logo" value="<?php if(isset($_POST['clinic_logo'])){ echo $_POST['clinic_logo']; } ?>" />
	</div>
	<div>
		<label>Would you like to include a map?</label>
		<label><input type="radio" name="clinic_include_map" value="yes" <?php if(isset($_POST['clinic_include_map']) && $_POST['clinic_include_map'] == 'yes'){ echo 'SELECTED'; } ?> /> Yes</label>
		<label><input type="radio" name="clinic_include_map" value="no" <?php if(isset($_POST['clinic_include_map']) && $_POST['clinic_include_map'] == 'no'){ echo 'SELECTED'; } ?> /> No</label>
	</div>
	<div>
	<label>Other Details</label>
	<textarea name="other_details"><?php if(isset($_POST['other_details'])){ echo $_POST['other_details']; } ?></textarea>
	</div>
</div>