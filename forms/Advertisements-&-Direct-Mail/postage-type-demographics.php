<input type="hidden" name="section_clinic_information" value="DEMOGRAPHICS" />
<div>
	<h3>Demographics</h3>
	<div>
		<label>Age Range</label>
		<input type="text" name="age_range" value="<?php if(isset($_POST['age_range'])){ echo $_POST['age_range']; } ?>" />
	</div>
	<div>
		<label>Income Range</label>
		<input type="text" name="income_range" value="<?php if(isset($_POST['income_range'])){ echo $_POST['income_range']; } ?>" />
	</div>
	<div>
		<label>Target Geographic Area (Towns, Postal codes)</label>
		<input type="text" name="target_geographic_area" value="<?php if(isset($_POST['target_geographic_area'])){ echo $_POST['target_geographic_area']; } ?>" />
	</div>
	<div>
		<label>Education Range</label>
		<input type="text" name="education_range" value="<?php if(isset($_POST['education_range'])){ echo $_POST['education_range']; } ?>" />
	</div>
	<div>
		<label>Property Type (House, Apartment, Farm, All)</label>
		<input type="text" name="property_type" value="<?php if(isset($_POST['property_type'])){ echo $_POST['property_type']; } ?>" />
	</div>

</div>