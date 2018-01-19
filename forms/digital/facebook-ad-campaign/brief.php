<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
	<div>
		<label>Monthly Budget</label>
		<input type="text" name="monthly_budget" value="<?php if(isset($_POST['monthly_budget'])){ echo $_POST['monthly_budget']; } ?>" />
	</div>
	<div>
		<label>Focus of Campaign</label>
		<input type="text" name="focus_of_campaign" value="<?php if(isset($_POST['focus_of_campaign'])){ echo $_POST['focus_of_campaign']; } ?>" />
	</div>
	<div>
		<label>Other Notes</label>
		<input type="text" name="other_notes" value="<?php if(isset($_POST['other_notes'])){ echo $_POST['other_notes']; } ?>" />
	</div>
</div>