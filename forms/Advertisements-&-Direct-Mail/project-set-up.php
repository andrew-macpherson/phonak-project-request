<input type="hidden" name="section_clinic_information" value="PROJECT SETUP" />
<div>
	<h3>Project Set Up</h3>
	<div>
		<label>Name of Project</label>
		<input type="text" name="name_of_project" value="<?php if(isset($_POST['name_of_project'])){ echo $_POST['name_of_project']; } ?>" />
	</div>
	<div>
		<label>Project Submission Date</label>
		<input type="text" name="project_submission_date" value="<?php if(isset($_POST['project_submission_date'])){ echo $_POST['project_submission_date']; } ?>" />
	</div>
	

</div>