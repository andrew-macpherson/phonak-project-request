<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Subject of Email</label>
		<input type="text" name="subject_of_email" value="<?php if(isset($_POST['subject_of_email'])){ echo $_POST['subject_of_email']; } ?>" />
	</div>
	<div>
		<label>Description of Content</label>
		<input type="text" name="description_of_content" value="<?php if(isset($_POST['description_of_content'])){ echo $_POST['description_of_content']; } ?>" />
	</div>
	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
	<div>
		<label>Imagery Notes</label>
		<input type="text" name="imagery_notes" value="<?php if(isset($_POST['imagery_notes'])){ echo $_POST['imagery_notes']; } ?>" />
	</div>
	<div>
		<label>Other Notes / Direction</label>
		<input type="text" name="other_notes_and_direction" value="<?php if(isset($_POST['other_notes_and_direction'])){ echo $_POST['other_notes_and_direction']; } ?>" />
	</div>
</div>