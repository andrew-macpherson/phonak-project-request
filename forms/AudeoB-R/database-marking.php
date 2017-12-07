<input type="hidden" name="section_database_marketing_information" value="DATABASE MARKETING INFORMATION" />
	<div>
		<h3>Database marketing Information</h3>
		<div>
			<label>Date you would like to send? </label>
			<input type="date" name="date_to_send" value="<?php if(isset($_POST['date_to_send'])){ echo $_POST['date_to_send']; } ?>" />
		</div>
		<div>
			<label>Do you have a Database client list?</label>
			<label><input type="radio" name="database_client_list" value="yes" <?php if(isset($_POST['database_client_list']) && $_POST['database_client_list'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
			<label><input type="radio" name="database_client_list" value="no" <?php if(isset($_POST['database_client_list']) && $_POST['database_client_list'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
		</div>
	</div>