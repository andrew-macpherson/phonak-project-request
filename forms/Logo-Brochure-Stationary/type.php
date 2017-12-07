<input type="hidden" name="section_clinic_information" value="TYPE" />
<div>
	<h3>Type</h3>
	<div>
		<label>Please Describe</label>
		<textarea name="type"><?php if(isset($_POST['type'])){ echo $_POST['type']; } ?></textarea>
	</div>
</div>