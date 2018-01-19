<input type="hidden" name="section_clinic_information" value="CONTACT_LIST" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Upload Logo and Supporting Documents</label>
		<input type="file" name="upload_list" value="<?php if(isset($_POST['upload_list'])){ echo $_POST['upload_list']; } ?>" />
	</div>
	<div>
		<label>Additional Notes</label>
		<input type="text" name="other_notes" value="<?php if(isset($_POST['other_notes'])){ echo $_POST['other_notes']; } ?>" />
	</div>
</div>