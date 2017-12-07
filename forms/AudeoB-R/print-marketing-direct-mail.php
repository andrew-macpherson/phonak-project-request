<div>
<input type="hidden" name="section_direct_mail_information" value="DIRECT MAIL INFORMATION" />
	<h3>Direct Mail Information</h3>
	<div>
		<label>When would you like to have your mailer distributed? (Please plan 4-6 weeks in advance in order to allow time for design, print and postage of all direct mail pieces.) </label>
		<input type="date" name="direct_mail_date" value="<?php if(isset($_POST['direct_mail_date'])){ echo $_POST['direct_mail_date']; } ?>">
	</div>
	<div>
		<label>Will your mailer be promoting a special event? i.e. Open House, etc.</label>
		<label><input type="radio" name="promoting_a_special_event" value="yes" <?php if(isset($_POST['promoting_a_special_event']) && $_POST['promoting_a_special_event'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
		<label><input type="radio" name="promoting_a_special_event" value="no" <?php if(isset($_POST['promoting_a_special_event']) && $_POST['promoting_a_special_event'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
	</div>
	<div>
		<label>If yes, please provide the date and name of the event?</label>
		<input type="text" name="special_event_name" placeholder="Event Name" value="<?php if(isset($_POST['special_event_date'])){ echo $_POST['special_event_date']; } ?>" />
		<input type="text" name="special_event_date" placeholder="Event Date" value="<?php if(isset($_POST['special_event_date'])){ echo $_POST['special_event_date']; } ?>" />
	</div>
	<div>
		<label>Would you like Phonak to arrange printing and delivery of your mailer? (Alternatively, clinics can arrange printing with a preferred printer and mailing through Canada Post)</label>
		<label><input type="radio" name="phonak_to_arrange_printing_and_delivery_of_your_mailer" value="yes" <?php if(isset($_POST['phonak_to_arrange_printing_and_delivery_of_your_mailer']) && $_POST['phonak_to_arrange_printing_and_delivery_of_your_mailer'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
		<label><input type="radio" name="phonak_to_arrange_printing_and_delivery_of_your_mailer" value="no" <?php if(isset($_POST['phonak_to_arrange_printing_and_delivery_of_your_mailer']) && $_POST['phonak_to_arrange_printing_and_delivery_of_your_mailer'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
	</div>
</div>