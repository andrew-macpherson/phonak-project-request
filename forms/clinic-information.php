<input type="hidden" name="section_clinic_information" value="CLINIC INFORMATION" />
<div>
	<h3>Clinic Content for Advertisement</h3>
	<div>
		<label>Clinic Name</label>
		<input type="text" name="clinic_name" value="<?php if(isset($_POST['clinic_name'])){ echo $_POST['clinic_name']; } ?>" />
	</div>
	<div>
		<label>Address</label>
		<input type="text" name="clinic_address" value="<?php if(isset($_POST['clinic_address'])){ echo $_POST['clinic_address']; } ?>" />
	</div>
	<div>
		<label>Phone Number</label>
		<input type="text" name="clinic_phone" value="<?php if(isset($_POST['clinic_phone'])){ echo $_POST['clinic_phone']; } ?>" />
	</div>
	<div>
		<label>Website</label>
		<input type="text" name="clinic_Website" value="<?php if(isset($_POST['clinic_Website'])){ echo $_POST['clinic_Website']; } ?>" />
	</div>

	<div>
		<label>Contact Name</label>
		<input type="text" name="clinic_contact_name" value="<?php if(isset($_POST['clinic_contact_name'])){ echo $_POST['clinic_contact_name']; } ?>" />
	</div>
	<div>
		<label>Contact Email</label>
		<input type="text" name="clinic_contact_email" value="<?php if(isset($_POST['clinic_contact_email'])){ echo $_POST['clinic_contact_email']; } ?>" />
	</div>
	<div>
		<label>Regional Sales Manager?</label>
		<select name="regional_sales_manager" style="width: 100%" required>
			<option>Select your RSM</option>
			<option>Aaron Lee</option>
			<option>Brent Wildeman</option>
			<option>Daryl Houghton</option>
			<option>Jacques Erpelding</option>
			<option>Janace Daley</option>
			<option>Lara Livingston</option>
			<option>Nadine Anis</option>
			<option>Nicky Saldhana</option>
			<option>Samantha McKendrick</option>
			<option>Sarah Young</option>
		</select>
	</div>

</div>