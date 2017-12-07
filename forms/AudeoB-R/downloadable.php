<input type="hidden" name="section_client_information" value="CLIENT INFORMATION" />
<div>
	<h3>Clinic Information</h3>
	<div>
		<label>Who is your Regional Sales Manager?</label>
		<select name="regional_sales_manager" style="width: 100%">
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
	<div>
		<label>Contact Name</label>
		<input type="text" name="contact_name" value="<?php if(isset($_POST['contact_name'])){ echo $_POST['contact_name']; } ?>" />
	</div>
	<div>
		<label>Email Address</label>
		<input type="email" name="email_address" value="<?php if(isset($_POST['email_address'])){ echo $_POST['email_address']; } ?>" />
	</div>
	<div>
		<label>Account Name</label>
		<input type="text" name="account_name" value="<?php if(isset($_POST['account_name'])){ echo $_POST['account_name']; } ?>" />
	</div>
	<div>
		<label>Phone</label>
		<input type="text" name="phone" value="<?php if(isset($_POST['phone'])){ echo $_POST['phone']; } ?>" />
	</div>
</div>