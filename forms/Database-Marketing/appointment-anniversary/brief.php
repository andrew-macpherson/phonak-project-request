<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Headline/Subject</label>
		<input type="text" name="headline_subject" value="<?php if(isset($_POST['headline_subject'])){ echo $_POST['headline_subject']; } ?>" />
	</div>
	<div>
		<label>Focus of Message (Example: Reminder for a clean and check, reminder for annual appointment)</label>
		<input type="text" name="focus_of_message" value="<?php if(isset($_POST['focus_of_message'])){ echo $_POST['focus_of_message']; } ?>" />
	</div>
	<div>
		<label>Call to Action</label>
		<input type="text" name="call_to_action" value="<?php if(isset($_POST['call_to_action'])){ echo $_POST['call_to_action']; } ?>" />
	</div>

	<div>
		<label>Other notes/Direction</label>
		<textarea name="other_notes_and_direction"><?php if(isset($_POST['other_notes_and_direction'])){ echo $_POST['other_notes_and_direction']; } ?></textarea>
	</div>

	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
</div>