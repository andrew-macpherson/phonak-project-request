<input type="hidden" name="section_clinic_information" value="CONTACT_LIST" />
<div>
	<h3>Contact List</h3>
	<div>
		<label>Contact Client for List</label>
		<label>
			<input type="radio" name="contact_client_for_list[]" value="Yes" />Yes
		</label>
		<label>
			<input type="radio" name="contact_client_for_list[]" value="No" />No
		</label>
	</div>
	<div>
		<label>Upload List</label>
		<input type="file" name="upload_list" value="<?php if(isset($_POST['upload_list'])){ echo $_POST['upload_list']; } ?>" />
	</div>
</div>