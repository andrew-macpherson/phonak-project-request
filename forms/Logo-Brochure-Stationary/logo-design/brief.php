<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Colors to Include</label>
		<input type="text" name="colors_to_include" value="<?php if(isset($_POST['colors_to_include'])){ echo $_POST['colors_to_include']; } ?>" />
	</div>
	<div>
		<label>Font Selection</label>
		<input type="text" name="font_selection" value="<?php if(isset($_POST['font_selection'])){ echo $_POST['font_selection']; } ?>" />
	</div>
	<div>
		<label>Imagery Ideas</label>
		<input type="text" name="imagery_ideas" value="<?php if(isset($_POST['imagery_ideas'])){ echo $_POST['imagery_ideas']; } ?>" />
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