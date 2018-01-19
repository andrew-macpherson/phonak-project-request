<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Headline</label>
		<input type="text" name="headline" value="<?php if(isset($_POST['headline'])){ echo $_POST['headline']; } ?>" />
	</div>
	<div>
		<label>Hookline</label>
		<input type="text" name="hookline" value="<?php if(isset($_POST['hookline'])){ echo $_POST['hookline']; } ?>" />
	</div>
	<div>
		<label>Supporting Content</label>
		<textarea name="supporting_content"><?php if(isset($_POST['supporting_content'])){ echo $_POST['supporting_content']; } ?></textarea>
	</div>
	<div>
		<label>Call to action</label>
		<input type="text" name="call_to_action" value="<?php if(isset($_POST['call_to_action'])){ echo $_POST['call_to_action']; } ?>" />
	</div>
	<div>
		<label>Imagery Notes</label>
		<input type="text" name="imagery_notes" value="<?php if(isset($_POST['imagery_notes'])){ echo $_POST['imagery_notes']; } ?>" />
	</div>
	<div>
		<label>Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
	<div>
		<label>Dimensions</label>
		<input type="text" name="dimensions" value="<?php if(isset($_POST['dimensions'])){ echo $_POST['dimensions']; } ?>" />
	</div>
	<div>
		<label>Other Notes / Direction</label>
		<textarea name="other_notes_and_direction"><?php if(isset($_POST['other_notes_and_direction'])){ echo $_POST['other_notes_and_direction']; } ?></textarea>
	</div>

</div>