<input type="hidden" name="section_newspaper_insert_information" value="NEWSPAPER INSERT INFORMATION" />
<div>
	<h3>Newspaper Insert Information</h3>
	<div>
		<label>When would you like to have your insert distributed? (Please plan 6-8 weeks in advance in order to allow time for customization, print distribution of inserts) </label>
		<input type="date" name="distribution_date" value="<?php if(isset($_POST['distribution_date'])){ echo $_POST['distribution_date']; } ?>" />
	</div>
	<div>
		<label>Have you booked an insertion with your publication?</label>
		<label><input type="radio" name="have_you_booked_an_insertion_with_your_publication" value="yes" <?php if(isset($_POST['have_you_booked_an_insertion_with_your_publication']) && $_POST['have_you_booked_an_insertion_with_your_publication'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
		<label><input type="radio" name="have_you_booked_an_insertion_with_your_publication" value="no" <?php if(isset($_POST['have_you_booked_an_insertion_with_your_publication']) && $_POST['have_you_booked_an_insertion_with_your_publication'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
	</div>
	<div>
		<label>If yes, when is the deadline to submit the insert creative to your publication?</label>
		<input type="date" name="insert_creative_deadline" value="<?php if(isset($_POST['insert_creative_deadline'])){ echo $_POST['insert_creative_deadline']; } ?>" />
	</div>
	<div>
		<label>Would you like Phonak to arrange printing and delivery of your newspaper insert? (Alternatively clinics can arrange printing with a preferred printer and distribution through their local newspapers)</label>
		<label><input type="radio" name="phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert" value="yes" <?php if(isset($_POST['phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert']) && $_POST['phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert'] == 'yes'){ echo 'SELECTED'; } ?> />Yes</label>
		<label><input type="radio" name="phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert" value="no" <?php if(isset($_POST['phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert']) && $_POST['phonak_to_arrange_printing_and_delivery_of_your_newspaper_insert'] == 'no'){ echo 'SELECTED'; } ?> />No</label>
	</div>
</div>