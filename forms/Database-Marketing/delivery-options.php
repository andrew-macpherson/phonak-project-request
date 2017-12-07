<input type="hidden" name="section_clinic_information" value="DELIVERY_OPTIONS" />
<div>
	<h3>Delivery Options</h3>
	<div>
		<label>If Email: New or Existing MailChimp Account</label>
		<input type="text" name="mailchimp_account" value="<?php if(isset($_POST['mailchimp_account'])){ echo $_POST['mailchimp_account']; } ?>" />
	</div>
	<div>
		<label>IF Print: Client to Mail or Phonak to Mail</label>
		<input type="text" name="client_to_main_or_phonak_to_mail" value="<?php if(isset($_POST['client_to_main_or_phonak_to_mail'])){ echo $_POST['client_to_main_or_phonak_to_mail']; } ?>" />
	</div>
</div>