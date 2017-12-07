<input type="hidden" name="section_clinic_information" value="PROJECT BRIEF" />
<div>
	<h3>Brief</h3>
	<div>
		<label>Title of Newsletter</label>
		<input type="text" name="title_of_newsletter" value="<?php if(isset($_POST['title_of_newsletter'])){ echo $_POST['title_of_newsletter']; } ?>" />
	</div>
	<div>
		<label>News about the Clinic Related Topic</label>
		<input type="text" name="news_about_the_clinic_related_topic" value="<?php if(isset($_POST['news_about_the_clinic_related_topic'])){ echo $_POST['news_about_the_clinic_related_topic']; } ?>" />
	</div>
	<div>
		<label>Hearing Health Update Related Topic</label>
		<input type="text" name="hearing_health_update_related_topic" value="<?php if(isset($_POST['hearing_health_update_related_topic'])){ echo $_POST['hearing_health_update_related_topic']; } ?>" />
	</div>
	<div>
		<label>Technology Update Related Topic</label>
		<input type="text" name="technology_update_related_topic" value="<?php if(isset($_POST['technology_update_related_topic'])){ echo $_POST['technology_update_related_topic']; } ?>" />
	</div>
	<div>
		<label>Hearing Health Success Story</label>
		<input type="text" name="hearing_health_success_story" value="<?php if(isset($_POST['hearing_health_success_story'])){ echo $_POST['hearing_health_success_story']; } ?>" />
	</div>
	<div>
		<label>Fun Game/Recipe</label>
		<input type="text" name="fun_game_recipe" value="<?php if(isset($_POST['fun_game_recipe'])){ echo $_POST['fun_game_recipe']; } ?>" />
	</div>
	<div>
		<label>Promotion</label>
		<input type="text" name="promotion" value="<?php if(isset($_POST['promotion'])){ echo $_POST['promotion']; } ?>" />
	</div>

	<div>
		<label>Upload Supporting Documents</label>
		<input type="file" name="supporting_documents" value="<?php if(isset($_POST['supporting_documents'])){ echo $_POST['supporting_documents']; } ?>" />
	</div>
</div>