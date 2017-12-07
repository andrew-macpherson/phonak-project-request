<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Front Content</label>
		<textarea name="front_content"><?php if(isset($_POST['front_content'])){ echo $_POST['front_content']; } ?></textarea>
	</div>
	<div>
		<label>Inside Content</label>
		<textarea name="inside_content"><?php if(isset($_POST['inside_content'])){ echo $_POST['inside_content']; } ?></textarea>
	</div>
	<div>
		<label>Backside Content</label>
		<textarea name="backside_content"><?php if(isset($_POST['backside_content'])){ echo $_POST['backside_content']; } ?></textarea>
	</div>
	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
</div>