<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Number Of Pages</label>
		<input type="text" name="number_of_pages" value="<?php if(isset($_POST['number_of_pages'])){ echo $_POST['number_of_pages']; } ?>" />
	</div>
	<div>
		<label>Cover Page</label>
		<textarea name="cover_page"><?php if(isset($_POST['cover_page'])){ echo $_POST['cover_page']; } ?></textarea>
	</div>
	<div>
		<label>About Us</label>
		<textarea name="about_us"><?php if(isset($_POST['about_us'])){ echo $_POST['about_us']; } ?></textarea>
	</div>
	<div>
		<label>Body</label>
		<textarea name="body"><?php if(isset($_POST['body'])){ echo $_POST['body']; } ?></textarea>
	</div>
	<div>
		<label>Imagery Notes</label>
		<textarea name="imagery_notes"><?php if(isset($_POST['imagery_notes'])){ echo $_POST['imagery_notes']; } ?></textarea>
	</div>
	<div>
		<label>Upload Supporting Documents</label>
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