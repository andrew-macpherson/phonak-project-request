<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Quantity</label>
		<input type="text" name="quantity" value="<?php if(isset($_POST['quantity'])){ echo $_POST['quantity']; } ?>" />
	</div>
	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
	<div>
		<label>Other Notes / Direction</label>
		<textarea name="other_notes_and_direction"><?php if(isset($_POST['other_notes_and_direction'])){ echo $_POST['other_notes_and_direction']; } ?></textarea>
	</div>
</div>