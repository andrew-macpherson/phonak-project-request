<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Focus of Letter</label>
		<textarea name="focus_of_letter"><?php if(isset($_POST['focus_of_letter'])){ echo $_POST['focus_of_letter']; } ?></textarea>
	</div>
	<div>
		<label>Call to Action</label>
		<textarea name="call_to_action"><?php if(isset($_POST['call_to_action'])){ echo $_POST['call_to_action']; } ?></textarea>
	</div>
	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
	<div>
		<label>Other Notes / Directions</label>
		<textarea name="other_notes_direction"><?php if(isset($_POST['other_notes_direction'])){ echo $_POST['other_notes_direction']; } ?></textarea>
	</div>
</div>