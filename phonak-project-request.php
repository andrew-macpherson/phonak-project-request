<?php
/*
Plugin Name: Phonak Project Request
*/


/*
*
*
*
*
*
*  SCRIPTS
*
*
*
*
*
*/
add_shortcode('phonak_marketing_request_app', 'phonak_marketing_request_app');
function phonak_marketing_request_app()
{
	return '<div id="phonak_app"></div>';
}



function phonak_marketing_request_enqueue_style()
{
	wp_enqueue_style('project-request', plugins_url() . '/phonak-project-request/css/project-request.css', false);

	wp_enqueue_style('jquery-ui');
}

function phonak_marketing_request_enqueue_script()
{
	wp_enqueue_script('phonak-project-request', plugins_url() . '/phonak-project-request/js/phonak-project-request.js', array('jquery'));

	//wp_register_style('jquery-ui', plugins_url().'/phonak-project-request/css/jqueryui.css');
	//wp_enqueue_script( 'jquery-ui-datepicker' );
}

add_action('wp_enqueue_scripts', 'phonak_marketing_request_enqueue_style', 20);
add_action('wp_enqueue_scripts', 'phonak_marketing_request_enqueue_script');


/**
 *
 *
 * post types
 *
 *
 */
// Press Release Post Type
function register_phonak_post_types()
{
	register_post_type(
		'sales_reps',
		array(
			'labels' => array(
				'name' => __('Sales Reps'),
				'singular_name' => __('Sales Rep')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'sales-reps'),
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
		)
	);


	register_post_type(
		'pd_team',
		array(
			'labels' => array(
				'name' => __('Practice Development Team'),
				'singular_name' => __('Practice Development Team')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'pd-team'),
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
		)
	);
}
add_action('init', 'register_phonak_post_types');

//Locations Meta Boxes
function pd_team_admin()
{
	add_meta_box(
		'pd_team_admin',
		'Data',
		'pd_team_meta_box',
		'pd_team',
		'advanced',
		'high'
	);
}
add_action('admin_init', 'pd_team_admin');

function pd_team_meta_box($post)
{
	wp_enqueue_script('multiple_image_select', get_stylesheet_directory_uri() . '/admin/locations/js/multiple-image-select.js', 'jquery', '1.0.0', true);

	global $post;


	$contact_id = esc_html(get_post_meta($post->ID, 'contact_id', true));

?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 100%"><strong>Pro Work Flow Contact ID</strong></td>
		</tr>
		<tr>
			<td><input type="text" style="width: 100%" name="contact_id" value="<?php echo $contact_id; ?>" /></td>
		</tr>
		<input type="hidden" name="pd_team_flag" value="true" />
	</table>
	<?php
}



function pd_team_meta_update($post_id, $post)
{
	if ($post->post_type == 'pd_team') {
		if (isset($_POST['pd_team_flag'])) {
			if (isset($_POST['contact_id']) && $_POST['contact_id'] != '') {
				update_post_meta($post_id, 'contact_id', $_POST['contact_id']);
			} else {
				update_post_meta($post_id, 'contact_id', '');
			}
		}
	}
}

add_action('save_post', 'pd_team_meta_update', 10, 2);



/*
*
*
*
*
*
*  SHORT CODES
*
*
*

**
*/


function convert_post_to_description()
{
	$description = '';

	// define string replace array
	$replace = ['_'];
	$replaceWith = [' '];

	foreach ($_POST as $key => $val) {

		if (strpos($key, 'section_') !== false) {
			$description .= '<br/><strong>' . $val . '</strong><br/>';
		} else {
			$description .= '<strong>' . ucwords(str_replace($replace, $replaceWith, $key)) . ': </strong>';
			if (is_array($val)) {
				$artworkArray = array();
				foreach ($val as $subVal) {
					$parts = explode('|', $subVal);
					$artworkArray[] = '<a href="' . $parts[1] . '">' . $parts[0] . '</a>';
				}
				$description .= implode(', ', $artworkArray) . '<br/>';;
			} else {
				$description .= nl2br($val) . '<br/>';
			}
		}
	}


	return stripslashes($description);
}


function phonak_project_request($atts)
{

	if (!is_admin()) {
		extract(shortcode_atts(array(
			'apikey' 			=> 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044',
			'username'			=> 'phonakmarketingwebsite',
			'password'			=> 'Phonak1176!'
		), $atts));


		$success = false;
		if (isset($_POST['submit'])) {

			// Filter out any post items we don't need. Example: submit
			$filterOut = ['submit'];
			foreach ($filterOut as $out) {
				unset($_POST[$out]);
			}

			// define string replace array
			$replace = ['_'];
			$replaceWith = [' '];

			$description = convert_post_to_description();

			// Hit API
			//$username='phonakmarketingwebsite';
			//$password='Phonak1176!';
			//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
			$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

			$projectTitle = $_POST['project_name'];

			$projectData = [
				'title' 		=> $projectTitle,
				'description' 	=> $description
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
			$result = curl_exec($ch);

			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
			//echo '$status_code: '.$status_code.'<br/>';

			if ($status_code == 201) {
				$decodedResponse = json_decode($result);
				$projectId = $decodedResponse->details[0]->id;
				curl_close($ch);
				$success = true;
			} else {
				print curl_error($ch);
			}
		}

	?>


		<?php
		if ($success) {
			//echo '<p style="color:#86bc24; text-align:center; font-size:26px; margin: 100px 0;">Project successfully submitted.<p>';
			echo '<script>window.location="' . get_bloginfo('url') . '/project-request-complete/";</script>';
		} else {
		?>
			<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">

				<div>
					<h3>Clinic Details</h3>
					<input type="hidden" name="section_clinic_details" value="CLINIC DETAILS" />
					<div>
						<label>Clinic Name</label>
						<input type="text" name="clinic_name" value="<?php if (isset($_POST['clinic_name'])) {
																			echo $_POST['clinic_name'];
																		} ?>" required />
					</div>
					<div>
						<label>Clinic Address</label>
						<input type="text" name="clinic_address" value="<?php if (isset($_POST['clinic_address'])) {
																			echo $_POST['clinic_address'];
																		} ?>" required />
					</div>
					<div>
						<label>Clinic Phone Number</label>
						<input type="text" name="clinic_phone" value="<?php if (isset($_POST['clinic_phone'])) {
																			echo $_POST['clinic_phone'];
																		} ?>" required />
					</div>
					<div>
						<label>Clinic Website Address</label>
						<input type="text" name="clinic_Website" value="<?php if (isset($_POST['clinic_Website'])) {
																			echo $_POST['clinic_Website'];
																		} ?>" required />
					</div>
					<div>
						<label>Clinic Contact Name and Email Address</label>
						<input type="text" name="clinic_contact_name_and_email_address" value="<?php if (isset($_POST['clinic_contact_name_and_email_address'])) {
																									echo $_POST['clinic_contact_name_and_email_address'];
																								} ?>" required />
					</div>
					<div>
						<label>Regional Sales Manager</label>
						<select name="regional_sales_manager" style="width: 100%" required>
							<option>Select your RSM</option>
							<?php
							$sales_reps = get_posts(array(
								'orderby'    		=> 'title',
								'order' 		=> 'ASC',
								'post_type'		=> 'sales_reps',
								'posts_per_page' => -1,
							));


							foreach ($sales_reps as $post) {
								echo '<option>' . $post->post_title . '</option>';
							}

							?>

						</select>
					</div>
				</div>


				<div>
					<h3>Project Setup</h3>
					<input type="hidden" name="section_project_setup" value="PROJECT SET UP" />
					<div>
						<label>Project Name</label>
						<input type="text" name="project_name" value="<?php if (isset($_POST['project_name'])) {
																			echo $_POST['project_name'];
																		} ?>" required />
					</div>
					<div>
						<label>Project Type</label>
						<select name="project_type" style="width: 100%" required>
							<option>Advertisements (1-2 weeks)</option>
							<option>Branding Package (2-3 weeks)</option>
							<option>Database Marketing (2-3 weeks)</option>
							<option>Direct Mail Marketing (2-3 weeks)</option>
							<option>Physician Marketing Pieces (1-2 weeks)</option>
							<option>Promotional Materials (1-3 weeks)</option>
							<option>Refer-a-Friend Program (1-2 weeks)</option>
							<option>Signage (1-3 Weeks)</option>
							<option>Video marketing (1-2 Months)</option>
							<option>Website Design (1-3 Months)</option>
							<option>Other (1-3 Weeks)</option>
						</select>
					</div>
					<div>
						<label>Due Date</label>
						<input type="date" name="due_date" value="<?php if (isset($_POST['due_date'])) {
																		echo $_POST['due_date'];
																	} ?>" required />
					</div>
				</div>


				<div>
					<h3>Project Design Brief</h3>
					<input type="hidden" name="section_project_design_brief" value="PROJECT DESIGN BRIEF" />
					<div>
						<label>General Description of Project</label>
						<input type="text" name="general_description_of_project" value="<?php if (isset($_POST['general_description_of_project'])) {
																							echo $_POST['general_description_of_project'];
																						} ?>" required />
					</div>
					<div>
						<label>Headline</label>
						<span>i.e. Life, Uninterrupted. (Or ask us to come up with this)</span>
						<input type="text" name="headline" value="<?php if (isset($_POST['headline'])) {
																		echo $_POST['headline'];
																	} ?>" required />
					</div>
					<div>
						<label>Hook Line</label>
						<span>i.e. Test Drive the World's First 24 Hour Lithium-Ion Rechargeable Hearing Aid (Or ask us to come up with this)</span>
						<input type="text" name="hook_line" value="<?php if (isset($_POST['hook_line'])) {
																		echo $_POST['hook_line'];
																	} ?>" required />
					</div>
					<div>
						<label>Call to Action</label>
						<input type="text" name="call_to_action" value="<?php if (isset($_POST['call_to_action'])) {
																			echo $_POST['call_to_action'];
																		} ?>" required />
					</div>
					<div>
						<label>Imagery Notes</label>
						<textarea name="imagery_notes" required><?php if (isset($_POST['imagery_notes'])) {
																	echo $_POST['imagery_notes'];
																} ?></textarea>
					</div>
					<div>
						<label>Dimensions (Width & Height)</label>
						<input type="text" name="dimensions" value="<?php if (isset($_POST['dimensions'])) {
																		echo $_POST['dimensions'];
																	} ?>" required />
					</div>
					<div>
						<label>Description / Must-haves / Do-Not</label>
						<textarea name="description"><?php if (isset($_POST['description'])) {
															echo $_POST['description'];
														} ?></textarea>
					</div>

				</div>

				<input type="submit" name="submit" value="Submit Project Request" />
			</form>



			<style>
				textarea {
					width: 100%;
					height: 200px;
				}
			</style>

		<?php
		}
		?>
	<?php
	}
}
add_shortcode('phonak_project_request', 'phonak_project_request');


function checkCaptcha()
{
	if (isset($_POST['recaptcha_response'])) {

		// Build POST request:
		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha_secret = '6LejP90UAAAAAL_dgKipGUYLfVZg8DdoheOTms0_';
		$recaptcha_response = $_POST['recaptcha_response'];

		// Make and decode POST request:
		//$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
		//$recaptcha = json_decode($recaptcha);

		$data = array(
			'secret' => $recaptcha_secret,
			'response' => $recaptcha_response
		);

		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, $recaptcha_url);
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		$recaptcha = json_decode($response);
		// Take action based on the score returned:
		if ($recaptcha->score <= 0.3) {
			// Not verified - show form error
			echo 'ERROR: Recaptcha failed...';
			exit;
		}
	}
}

function phonak_event_request($atts)
{
	if (!is_admin()) {

		$siteId = get_current_blog_id();

		extract(shortcode_atts(array(
			'apikey' 			=> 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044',
			'username'			=> 'phonakmarketingwebsite',
			'password'			=> 'Phonak1176!'
		), $atts));

		wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?render=6LejP90UAAAAAEz9T38b5pRK69-qDCdr6GYw8y-k');
		wp_enqueue_script('phonak-project-request-recaptcha', plugins_url() . '/phonak-project-request/js/phonak-project-request-recaptcha.js', array('recaptcha'));

		$success = false;
		if (isset($_POST['submit'])) {

			checkCaptcha();

			// Filter out any post items we don't need. Example: submit
			//set practive development manager
			$practice_development_team_member = $_POST['practice_development_team_member'];

			// Filter out any post items we don't need. Example: submit
			$filterOut = ['submit', 'practice_development_team_member', 'recaptcha_response', 'site_id'];
			foreach ($filterOut as $out) {
				unset($_POST[$out]);
			}

			// define string replace array
			$replace = ['_'];
			$replaceWith = [' '];

			$description = convert_post_to_description();

			// Hit API
			//$username='phonakmarketingwebsite';
			//$password='Phonak1176!';
			//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
			$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

			$projectTitle = $_POST['event_name'];


			$projectData = [
				'title' 		=> $projectTitle,
				'description' 	=> $description
			];


			//echo 'username: '.$username.'<br/>';
			//echo 'password: '.$password.'<br/>';
			//echo 'apikey: '.$apikey.'<br/><br/>';
			//print_r($projectData);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
			$result = curl_exec($ch);

			//echo '<br/> result = '.$result.'<br/>';

			//print_r($result);

			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
			//echo '<br/> status_code = '.$status_code.'<br/>';

			if ($status_code == 201) {
				$decodedResponse = json_decode($result);
				$projectId = $decodedResponse->details[0]->id;
				curl_close($ch);
				//$success = true;


				// deal with any attached files after we have the project ID.
				if (!empty($_FILES)) {
					$description .= '<strong>File Attachments</strong>';


					foreach ($_FILES as $key => $val) {

						if ($_FILES[$key]['tmp_name'] != '') {
							$fileData =  [
								'content' => base64_encode(file_get_contents($_FILES[$key]['tmp_name'])),
								'name' => $_FILES[$key]['name'],
								'projectid' => $projectId
							];


							$URL = 'https://api.proworkflow.net/files?apikey=' . $apikey;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $URL);
							curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

							$result = curl_exec($ch);
							$decodedResponse = json_decode($result);
							exit;
						}
					}

					$success = true;
				} else {

					$success = true;
				}
			} else {
				print curl_error($ch);
			}
		}

	?>


		<?php
		if ($success) {
			echo '<p style="text-align:center; font-size:26px; margin: 100px 0;">Event successfully submitted.<p>';
			//echo '<script>window.location="'.get_bloginfo('url').'/project-request-complete/";</script>';
		} else {
		?>
			<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">


				<div>
					<h3>Event Details</h3>
					<input type="hidden" name="section_clinic_details" value="EVENT DETAILS" />
					<div>
						<label>Event Name</label>
						<input type="text" name="event_name" value="<?php if (isset($_POST['event_name'])) {
																		echo $_POST['event_name'];
																	} ?>" required />
					</div>
					<div>
						<label>Unitron Point of Contact</label>
						<input type="text" name="unitron_point_of_contact" value="<?php if (isset($_POST['unitron_point_of_contact'])) {
																						echo $_POST['unitron_point_of_contact'];
																					} ?>" required />
					</div>
					<div>
						<label>Date of Event</label>
						<input type="date" name="event_date" value="<?php if (isset($_POST['event_date'])) {
																		echo $_POST['event_date'];
																	} ?>" class=" required" required />
					</div>


					<div>
						<label>Event Start Time</label>
						<input type="text" name="event_start_time" value="<?php if (isset($_POST['event_start_time'])) {
																				echo $_POST['event_start_time'];
																			} ?>" required />
					</div>
					<div>
						<label>Event End Time</label>
						<input type="text" name="event_end_time" value="<?php if (isset($_POST['event_end_time'])) {
																			echo $_POST['event_end_time'];
																		} ?>" required />
					</div>
					<div>
						<label>Type of Event</label>
						<select name="type_of_event" style="width: 100%" required>
							<option>Launch training</option>
							<option>Off launch training</option>
							<option>Student</option>
							<option>Value Added Services</option>
							<option>Key Accounts</option>
							<option>Others</option>
						</select>
					</div>
					<div>
						<label>Purpose of The Event</label>
						<input type="text" name="purpose_of_event" value="<?php if (isset($_POST['purpose_of_event'])) {
																				echo $_POST['purpose_of_event'];
																			} ?>" required />
					</div>
					<div>
						<label>Estimated Number of Persons Attending</label>
						<input type="text" name="estimated_number_of_persons_attending" value="<?php if (isset($_POST['estimated_number_of_persons_attending'])) {
																									echo $_POST['estimated_number_of_persons_attending'];
																								} ?>" required />
					</div>
					<div>
						<label>Budget</label>
						<input type="text" name="budget" value="<?php if (isset($_POST['budget'])) {
																	echo $_POST['budget'];
																} ?>" required />
					</div>
					<div>
						<label>AV needs (include Soundfield if needed)</label>
						<input type="text" name="av_needs" value="<?php if (isset($_POST['av_needs'])) {
																		echo $_POST['av_needs'];
																	} ?>" required />
					</div>
					<div>
						<label>Room set up</label>
						<input type="text" name="room_set_up" value="<?php if (isset($_POST['room_set_up'])) {
																			echo $_POST['room_set_up'];
																		} ?>" required />
					</div>
					<div>
						<label>Catering Required?</label>
						<input type="text" name="catering_required" value="<?php if (isset($_POST['catering_required'])) {
																				echo $_POST['catering_required'];
																			} ?>" />
					</div>
					<div>
						<label>Hotel Room Block Required?</label>
						<input type="text" name="hotel_room_block_required" value="<?php if (isset($_POST['hotel_room_block_required'])) {
																						echo $_POST['hotel_room_block_required'];
																					} ?>" required />
					</div>
					<div>
						<label>RSVP Needed?</label>
						<select name="rsvp_needed" style="width: 100%" required>
							<option>Yes</option>
							<option>No</option>
						</select>
					</div>
				</div>




				<input type="submit" name="submit" value="Submit Event Request" />
			</form>



			<style>
				textarea {
					width: 100%;
					height: 200px;
				}
			</style>

	<?php
		}
	}
	?>
<?php
}
add_shortcode('phonak_event_request', 'phonak_event_request');


function phonak_quick_project_request($atts)
{

	if (!is_admin()) {

		extract(shortcode_atts(array(
			'apikey' 			=> 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044',
			'username'			=> 'phonakmarketingwebsite',
			'password'			=> 'Phonak1176!'
		), $atts));

		wp_enqueue_script('momentjs', plugins_url() . '/phonak-project-request/js/moment.js');

		//wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?render=6LejP90UAAAAAEz9T38b5pRK69-qDCdr6GYw8y-k');
		//wp_enqueue_script( 'phonak-project-request-recaptcha', plugins_url().'/phonak-project-request/js/phonak-project-request-recaptcha.js', array('momentjs','recaptcha') );
		wp_enqueue_script('phonak-project-request-recaptcha', plugins_url() . '/phonak-project-request/js/phonak-project-request-recaptcha.js', array('momentjs'));

		$siteId = get_current_blog_id();

		$success = false;
		if (isset($_POST['submit']) && $_POST['your_email'] == '') {

			//checkCaptcha();


			//set practive development manager
			$practice_development_team_member = $_POST['practice_development_team_member'];

			// Filter out any post items we don't need. Example: submit
			$filterOut = ['submit', 'practice_development_team_member', 'recaptcha_response', 'site_id', 'your_email'];
			foreach ($filterOut as $out) {
				unset($_POST[$out]);
			}

			// define string replace array
			$replace = ['_'];
			$replaceWith = [' '];

			$description = convert_post_to_description();

			// Hit API
			//$username='phonakmarketingwebsite';
			//$password='Phonak1176!';
			//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
			$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

			//echo $apikey.'<br />';
			//echo $username.'<br />';
			//echo $password.'<br />';

			$projectTitle = $_POST['project_name'];

			if ($siteId == 2 ||  $siteId == 3) {
				$projectData = [
					'title' 		=> $projectTitle,
					'description' 	=> $description
				];
			} else {
				$projectData = [
					'title' 		=> $projectTitle,
					'description' 	=> $description,
					'contactId'		=> $practice_development_team_member
				];
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120); //timeout after 120 seconds
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
			$result = curl_exec($ch);
			//print_r($result);

			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
			//echo '$status_code: '.$status_code.'<br/>';

			if ($status_code == 201) {
				$decodedResponse = json_decode($result);
				$projectId = $decodedResponse->details[0]->id;
				curl_close($ch);
				//$success = true;


				// deal with any attached files after we have the project ID.
				if (!empty($_FILES)) {
					$description .= '<strong>File Attachments</strong>';


					foreach ($_FILES as $key => $val) {

						if ($_FILES[$key]['tmp_name'] != '') {
							$fileData =  [
								'content' => base64_encode(file_get_contents($_FILES[$key]['tmp_name'])),
								'name' => $_FILES[$key]['name'],
								'projectid' => $projectId
							];


							$URL = 'https://api.proworkflow.net/files?apikey=' . $apikey;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $URL);
							curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
							curl_setopt($ch, CURLOPT_TIMEOUT, 120);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

							$result = curl_exec($ch);
							$decodedResponse = json_decode($result);
							//print_r($decodedResponse);
							//exit;
						}
					}

					$success = true;
				} else {

					$success = true;
				}
			} else {
				print curl_error($ch);
			}
		}

		$return = '';

		if ($success) {
			//echo '<p style="color:#86bc24; text-align:center; font-size:26px; margin: 100px 0;">Project successfully submitted.<p>';
			$return = '<script>window.location="' . get_bloginfo('url') . '/project-request-complete/";</script>';
		} else {

			$siteId = get_current_blog_id();
			//echo $siteId;



			$return =  '<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">';

			$return .= '<div>';
			$return .= '<label>';

			if ($siteId == 4) {
				$return .=  'Client Marketing Team Member';
			} elseif ($siteId == 2) {
				$return .=  'Marketing Executive';
			} elseif ($siteId == 3) {
				$return .=  'Marketing Associate';
			} else {
				$return .=  'Practice Development Team Member';
			}

			$return .= '</label>';
			$return .= '<select name="practice_development_team_member" style="width: 100%" >';

			$sales_reps = get_posts(array(
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_type'	=> 'pd_team',
				'posts_per_page' => -1
			));


			foreach ($sales_reps as $post) {
				$contact_id = esc_html(get_post_meta($post->ID, 'contact_id', true));
				$return .= '<option value="' . $contact_id . '">' . $post->post_title . '</option>';
			}

			$return .= '</select>';
			$return .= '</div>';

			$return .= '<div>';
			$clinicWording = 'Clinic';
			if ($siteId == '2') {
				$clinicWording = 'Account';
			}
			$return .= '<h3>' . $clinicWording . ' Details</h3>';
			$return .= '<input type="hidden" name="section_clinic_details" value="CLINIC DETAILS" />';
			$return .= '<div>';
			$return .= '<label>' . $clinicWording . ' Name</label>';
			$return .= '<input type="text" name="clinic_name" value="' . (isset($_POST['clinic_name']) ? $_POST['clinic_name'] : '') . '" class="required" required />';
			$return .= '</div>';
			$return .= '<div style="display: none">';
			$return .= '<label>' . $clinicWording . ' Address</label>';
			$return .= '<input type="text" name="clinic_address" value="' . (isset($_POST['clinic_address']) ? $_POST['clinic_address'] : '') . '"  />';
			$return .= '</div>';
			$return .= '<div style="display: none">';
			$return .= '<label>' . $clinicWording . ' Phone Number</label>';
			$return .= '<input type="text" name="clinic_phone" value="' . (isset($_POST['clinic_phone']) ? $_POST['clinic_phone'] : '') . '"  />';
			$return .= '</div>';
			$return .= '<div style="display: none">';
			$return .= '<label>' . $clinicWording . ' Website Address</label>';
			$return .= '<input type="text" name="clinic_Website" value="' . (isset($_POST['clinic_Website']) ? $_POST['clinic_Website'] : '') . '"  />';
			$return .= '</div>';
			$return .= '<div>';
			$return .= '<label>' . $clinicWording . ' Contact Name and Email Address</label>';
			$return .= '<input type="text" name="clinic_contact_name_and_email_address" value="' . (isset($_POST['clinic_contact_name_and_email_address']) ? $_POST['clinic_contact_name_and_email_address'] : '') . '" class="required" required />';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>';
			if ($siteId == 4) {
				$return .= 'Business Solutions Manager';
			} elseif ($siteId == 3) {
				$return .= 'Account Manager';
			} else {
				$return .= 'Regional Sales Manager';
			}
			$return .= '</label>';
			$return .= '<select name="sales_manager_or_business_solutions_manager" style="width: 100%" class="required" required>';
			$sales_reps = get_posts(array(
				'orderby'    		=> 'title',
				'order' 		=> 'ASC',
				'post_type'		=> 'sales_reps',
				'posts_per_page' => -1,
			));

			foreach ($sales_reps as $post) {
				$return .= '<option>' . $post->post_title . '</option>';
			}

			$return .= '</select>';
			$return .= '</div>';

			$return .= '</div>';


			$return .= '<div>';
			$return .= '<h3>Project Setup</h3>';
			$return .= '<input type="hidden" name="section_project_setup" value="PROJECT SET UP" />';
			$return .= '<div>';
			$return .= '<label>Project Name</label>';
			$return .= '<input type="text" name="project_name" value="' . (isset($_POST['project_name']) ? $_POST['project_name'] : '') . '" class="required" required />';
			$return .= '</div>';
			$return .= '<div style="display: none">';
			$return .= '<label>Project Type</label>';
			$return .= '<select name="project_type" style="width: 100%">';
			$return .= '<option></option>';
			$return .= '<option>Advertisements</option>';
			$return .= '<option>Branding Package (2-3 weeks)</option>';
			$return .= '<option>Database Marketing (2-3 weeks)</option>';
			$return .= '<option>Direct Mail Marketing (2-3 weeks)</option>';
			$return .= '<option>Physician Marketing Pieces (1-2 weeks)</option>';
			$return .= '<option>Promotional Materials (1-3 weeks)</option>';
			$return .= '<option>Refer-a-Friend Program (1-2 weeks)</option>';
			$return .= '<option>Signage (1-3 Weeks)</option>';
			$return .= '<option>Video marketing (1-2 Months)</option>';
			$return .= '<option>Website Design (1-3 Months)</option>';
			$return .= '<option>Other (1-3 Weeks)</option>';
			$return .= '</select>';
			$return .= '</div>';
			$return .= '<div>';
			$return .= '<label>Due Date</label>';
			$return .= '<input type="date" name="due_date" value="' . (isset($_POST['due_date']) ? $_POST['due_date'] : '') . '" class="required" required />';
			$return .= '</div>';
			$return .= '</div>';

			/*
				<div style="display: none">
					<h3>Project Design Brief</h3>
					<input type="hidden" name="section_project_design_brief" value="PROJECT DESIGN BRIEF" />
					<div>
						<label>General Description of Project</label>
						<input type="text" name="general_description_of_project" value="<?php if(isset($_POST['general_description_of_project'])){ echo $_POST['general_description_of_project']; } ?>"  />
					</div>
					<div>
						<label>Headline</label>
						<span>i.e. Life, Uninterrupted. (Or ask us to come up with this)</span>
						<input type="text" name="headline" value="<?php if(isset($_POST['headline'])){ echo $_POST['headline']; } ?>"  />
					</div>
					<div>
						<label>Hook Line</label>
						<span>i.e. Test Drive the World's First 24 Hour Lithium-Ion Rechargeable Hearing Aid (Or ask us to come up with this)</span>
						<input type="text" name="hook_line" value="<?php if(isset($_POST['hook_line'])){ echo $_POST['hook_line']; } ?>"  />
					</div>
					<div>
						<label>Call to Action</label>
						<input type="text" name="call_to_action" value="<?php if(isset($_POST['call_to_action'])){ echo $_POST['call_to_action']; } ?>"  />
					</div>
					<div>
						<label>Imagery Notes</label>
						<textarea name="imagery_notes" ><?php if(isset($_POST['imagery_notes'])){ echo $_POST['imagery_notes']; } ?></textarea>
					</div>
					<div>
						<label>Dimensions (Width & Height)</label>
						<input type="text" name="dimensions" value="<?php if(isset($_POST['dimensions'])){ echo $_POST['dimensions']; } ?>"  />
					</div>
				</div>
				*/


			$return .= '<div>';
			$return .= '<h3>Project Description</h3>';
			$return .= '<input type="hidden" name="section_project_description" value="PROJECT DESCRIPTION" />';
			$return .= '<div>';
			$return .= '<textarea name="description" class="required" required>' . (isset($_POST['project_description']) ? $_POST['project_description'] : '') . '</textarea>';
			$return .= '</div>';
			$return .= '</div>';

			$return .= '<div class="projectFileContainer">';
			$return .= '<h3>Project Files</h3>';
			$return .= '<div>';
			$return .= '<input type="file" name="attachments" class="attachmentsFileBtn"  />';
			$return .= '</div>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<a href="#" class="addProjectFileContainer">Need another file?</a>';
			$return .= '</div>';

			$return .= '<input type="email" name="your_email" id="your_email">';
			//$return .= '<input type="hidden" name="recaptcha_response" id="recaptchaResponse">';
			$return .= '<input type="hidden" name="site_id" id="siteID" value="' . $siteId . '" />';

			$return .= '<input class="projectSubmitBtn" type="submit" name="submit" value="Submit Project Request" />';
			$return .= '<p class="loadingText" style="display:none">Loading... This could take some time. Thank you for your patience</p>';
			$return .= '</form>';



			$return .= '<style>';
			$return .= 'textarea{';
			$return .= 'width: 100%;';
			$return .= 'height: 200px;';
			$return .= '}';
			$return .= 'input[type="email"]#your_email {';
			$return .= 'display: none;';
			$return .= '}';
			$return .= '</style>';
		}
		return $return;
	}
}
add_shortcode('phonak_quick_project_request', 'phonak_quick_project_request');








/*
function phonak_project_request_2() {
	echo '<script src="'.plugins_url('js/project-request.js',__FILE__).'"></script>';
	?>
	<div>
		<div>
			<select name="destination">
				<option value="http://phonakmarketing.ca/request/advertisements-direct-mail/">Advertisements & Direct Mail</option>
				<option value="http://phonakmarketing.ca/request/database-marketing-2/">Database Marketing</option>
				<option value="http://phonakmarketing.ca/request/digital/">Digital</option>
				<option value="http://phonakmarketing.ca/request/logo-brochure-stationary/">Logo, Brochure & Stationery</option>
			</select>
		</div>
		<div>
			<a href="#" class="redirect">GET STARTED</a>
		</div>
	</div>
	<?php
}
add_shortcode('phonak_project_request_2','phonak_project_request_2');
*/







function phonak_project_request_2($atts)
{
	extract(shortcode_atts(array(
		'apikey' 			=> 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044',
		'username'			=> 'phonakmarketingwebsite',
		'password'			=> 'Phonak1176!'
	), $atts));
	/*
	*
	*
	* Handle submit stuff.
	*
	*
	*/
	$success = false;
	if (isset($_POST['submit'])) {

		// Filter out any post items we don't need. Example: submit
		$filterOut = ['submit'];
		foreach ($filterOut as $out) {
			unset($_POST[$out]);
		}

		// define string replace array
		$replace = ['_'];
		$replaceWith = [' '];

		$description = convert_post_to_description();

		// Hit API
		//$username='phonakmarketingwebsite';
		//$password='Phonak1176!';
		//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
		$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

		$projectTitle = $_POST['project_name'];

		$projectData = [
			'title' 		=> $projectTitle,
			'description' 	=> $description
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
		$result = curl_exec($ch);

		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

		if ($status_code == 201) {
			$decodedResponse = json_decode($result);
			$projectId = $decodedResponse->details[0]->id;
			curl_close($ch);
			$success = true;
		} else {
			print curl_error($ch);
		}
	}



	echo '<script src="' . plugins_url('js/project-request.js', __FILE__) . '"></script>';
	echo '<script src="' . plugins_url('js/tabs.js', __FILE__) . '"></script>';

?>



	<?php if ($success) {
		echo '<p style="color:#86bc24; text-align:center; font-size:26px; margin: 100px 0;">Project successfully submitted.<p>';
	} else { ?>
		<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">
			<div class="tabs-wrapper">
				<div class="tab tab-1 active">
					<input type="hidden" name="section_clinic_information" value="CLINIC INFORMATION" />
					<div>
						<h3>Clinic Content for Advertisement</h3>
						<div>
							<label>Clinic Name</label>
							<input type="text" name="clinic_name" value="<?php if (isset($_POST['clinic_name'])) {
																				echo $_POST['clinic_name'];
																			} ?>" />
						</div>
						<div>
							<label>Address</label>
							<input type="text" name="clinic_address" value="<?php if (isset($_POST['clinic_address'])) {
																				echo $_POST['clinic_address'];
																			} ?>" />
						</div>
						<div>
							<label>Phone Number</label>
							<input type="text" name="clinic_phone" value="<?php if (isset($_POST['clinic_phone'])) {
																				echo $_POST['clinic_phone'];
																			} ?>" />
						</div>
						<div>
							<label>Website</label>
							<input type="text" name="clinic_Website" value="<?php if (isset($_POST['clinic_Website'])) {
																				echo $_POST['clinic_Website'];
																			} ?>" />
						</div>

						<div>
							<label>Contact Name</label>
							<input type="text" name="clinic_contact_name" value="<?php if (isset($_POST['clinic_contact_name'])) {
																						echo $_POST['clinic_contact_name'];
																					} ?>" />
						</div>
						<div>
							<label>Contact Email</label>
							<input type="text" name="clinic_contact_email" value="<?php if (isset($_POST['clinic_contact_email'])) {
																						echo $_POST['clinic_contact_email'];
																					} ?>" />
						</div>
						<div>
							<label>Regional Sales Manager</label>
							<select name="regional_sales_manager" style="width: 100%" required>
								<?php
								$sales_reps = get_posts(array(
									'orderby'    		=> 'title',
									'order' 		=> 'ASC',
									'post_type'		=> 'sales_reps',
									'posts_per_page' => -1,
								));


								foreach ($sales_reps as $post) {
									echo '<option>' . $post->post_title . '</option>';
								}

								?>
							</select>
						</div>

					</div>
				</div>
				<div class="tab tab-2">
					<div>
						<input type="hidden" name="section_clinic_information" value="PROJECT SETUP" />
						<h3>Project Set Up</h3>
						<div>
							<label>Name of Project</label>
							<input type="text" name="project_name" value="<?php if (isset($_POST['project_name'])) {
																				echo $_POST['project_name'];
																			} ?>" />
						</div>
						<div>
							<label>Project Submission Date</label>
							<input type="text" name="project_submission_date" value="<?php if (isset($_POST['project_submission_date'])) {
																							echo $_POST['project_submission_date'];
																						} ?>" />
						</div>

						<div>
							<select name="destination" class="projectTypeSelector">
								<option>Select a Project Type</option>
								<optgroup label="Print Marketing">
									<option>Print Advertisement</option>
									<option>Direct Mail</option>
								</optgroup>
								<optgroup label="Logo, Brochure & Stationary">
									<option>Logo</option>
									<option>Brochure</option>
									<option>Stationary</option>
								</optgroup>
								<optgroup label="Database Marketing">
									<option>Newsletter</option>
									<option>Appointment Anniversary</option>
									<option>Greeting Cards</option>
									<option>Letter</option>
								</optgroup>
								<optgroup label="Digital Marketing">
									<option>Facebook Ad Campaign</option>
									<option>Email Marketing</option>
									<option>Google Ad Words</option>
									<option>Lead Nurturing Campaign</option>
								</optgroup>
								<optgroup label="Medical Professional Partnerships">
									<option>GP One Pagers</option>
								</optgroup>
								<optgroup label="Other">
									<option>Describe</option>
								</optgroup>
							</select>
						</div>
					</div>
				</div>

				<div class="additionalTabContent">

				</div>


				<div class="controls">
					<a href="#" class="prev inactive">Previous</a>
					<a href="#" class="next">Next</a>

					<input type="submit" name="submit" value="Submit Project Request" id="submitProjectForm" />
				</div>

				<div class="step-pagination">
					<span class="step step-1 active"></span>
					<span class="step step-2"></span>
					<div class="additionalTabs">

					</div>
				</div>

			</div>
		</form>
	<?php } ?>

	<?php
}
add_shortcode('phonak_project_request_2', 'phonak_project_request_2');










function phonak_project($atts)
{
	extract(shortcode_atts(array(
		'apikey' 			=> 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044',
		'username'			=> 'phonakmarketingwebsite',
		'password'			=> 'Phonak1176!'
	), $atts));

	$success = false;
	//PROCESS FORM DATA IF SUBBMITED
	if (isset($_POST['submit'])) {

		echo 'submit';

		// Filter out any post items we don't need. Example: submit
		$filterOut = ['submit'];
		foreach ($filterOut as $out) {
			unset($_POST[$out]);
		}

		// define string replace array
		$replace = ['_'];
		$replaceWith = [' '];

		$description = '';

		foreach ($_POST as $key => $val) {

			if (strpos($key, 'section_') !== false) {
				$description .= '<br/><strong>' . $val . '</strong><br/>';
			} else {
				$description .= '<strong>' . ucwords(str_replace($replace, $replaceWith, $key)) . ': </strong>';
				if (is_array($val)) {
					$artworkArray = array();
					foreach ($val as $subVal) {
						$parts = explode('|', $subVal);
						$artworkArray[] = '<a href="' . $parts[1] . '">' . $parts[0] . '</a>';
					}
					$description .= implode(', ', $artworkArray) . '<br/>';;
				} else {
					$description .= $val . '<br/>';
				}
			}
		}


		// Hit API
		//$username='phonakmarketingwebsite';
		//$password='Phonak1176!';
		//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
		$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

		$projectTitle = $_POST['clinic_name'] . ' | ' . $_POST['project_type'];

		$projectData = [
			'title' 		=> $projectTitle,
			'description' 	=> $description
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
		$result = curl_exec($ch);

		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		//echo '$status_code: '.$status_code.'<br/>';

		if ($status_code == 201) {
			$decodedResponse = json_decode($result);
			$projectId = $decodedResponse->details[0]->id;
			curl_close($ch);

			// deal with any attached files after we have the project ID.
			if (!empty($_FILES)) {
				$description .= '<strong>File Attachments</strong>';
				foreach ($_FILES as $key => $val) {
					//$description .= '<p>'.$val['name'].': '.$val['tmp_name'].'</p>';
					//print_r($_FILES[$key]);
					//echo base64_encode($_FILES[$key]['tmp_name']);
					if ($_FILES[$key]['tmp_name'] != '') {
						$fileData =  [
							'content' => base64_encode(file_get_contents($_FILES[$key]['tmp_name'])),
							'name' => $_FILES[$key]['name'],
							'projectid' => $projectId
						];

						//print_r($fileData);

						$URL = 'https://api.proworkflow.net/files?apikey=' . $apikey;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $URL);
						curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

						$result = curl_exec($ch);
						$decodedResponse = json_decode($result);
					}
				}

				$success = true;
			} else {
				print curl_error($ch);
				curl_close($ch);

				$success = false;
			}
		} else {
			print curl_error($ch);
		}
	}



	if ($success) {
		echo '<p style="color:#86bc24; text-align:center; font-size:26px; margin: 100px 0;">Project successfully submitted.<p>';
	} else {
		/*
		if ( get_cat_name( $category_id = 19 )): ?>
		<img src="http://phonakmarketing.ca/site/wp-content/uploads/WebBanner_Audeo_B-Direct_1200pxX519px.jpg" width="100%" class="directbanner" alt="Phonak Banner"/>
	<?php endif;
	*/
	?>

		<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">

			<input type="hidden" name="project_type" value="<?php echo get_queried_object()->name; ?>">

			<?php
			// CHECK FOR CHILDREN
			$taxonomy_name = 'phonak-projects';

			$term_id = get_queried_object()->term_id;
			$parent_term = get_term_by('term_id', $term_id, $taxonomy_name);
			$term_meta = get_option('taxonomy_' . $term_id);
			$sku_code = get_post_meta(get_the_ID(), 'sku_code', true);

			//print_r($term_id);
			//print_r($parent_term);

			/*$term_new = get_queried_object();
			$f = true;
			$breadcrumbData = array();
			$a = array();
			$breadcrumbData[0]['name'] = $term_new->name;
			$breadcrumbData[0]['slug'] = $term_new->slug;
			echo 'Loop start<br/>';

			do {
				$parent = get_term_by( 'id', $term_new->parent, $taxonomy_name );
				echo 'parent:<br/>';
				print_r($parent);
				if(is_numeric($parent->parent) && $parent->parent != 0) {
					print_r('step 1');
					$a['name'] = $parent->name;
					$a['slug'] = $parent->slug;
					$term_new = get_term_by( 'id', $parent->parent, $taxonomy_name );
					array_push($breadcrumbData,$a);
				}
				else if(is_numeric($parent->parent) && $parent->parent == 0) {
					print_r('step 2');
					$a['name'] = $parent->name;
					$a['slug'] = $parent->slug;
					array_push($breadcrumbData,$a);
					$f = false;
				}
				else {
					$f = false;
				}
			}while($f);


			echo 'Loop End<br/>';
			print_r($breadcrumbData);
			*/
			if (function_exists('yoast_breadcrumb')) {
				yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
			}
			echo '<h2>' . $parent_term->name . '</h2>';

			$term_children = get_term_children($term_id, $taxonomy_name);

			$term_children = get_terms(array(
				'taxonomy' => $taxonomy_name,
				'parent'   => $term_id,
				'hide_empty'    => false,
				'hierarchical'    => true
			));


			/**
			 **
			 **
			 ** HEADER
			 **
			 **/
			if (count($term_children) != 0) {
				echo '<div class="project_list">';
				foreach ($term_children as $child) {
					$term = get_term_by('id', $child->term_id, $taxonomy_name);
					$child_term_meta = get_option('taxonomy_' . $term->term_id);
					echo '<div>';
					if (!empty($child_term_meta['custom_url']) && $child_term_meta['custom_url'] != '') {
						echo '<a href="' . $child_term_meta['custom_url'] . '" target="_blank">';
					} else {
						echo '<a href="' . get_term_link($child->term_id, $taxonomy_name) . '">';
					}
					echo '<img src="' . $child_term_meta['project_thumbnail'] . '" />';
					echo '</a>';

					echo '<a class="button" href="' . get_term_link($child, $taxonomy_name) . '">';
					echo $term->name;
					echo '</a>';

					echo '</div>';
				}
				echo '</div>';
			}


			/**
			 **
			 **
			 ** IF NO CHILDREN RENDER PROJECT ELEMENTS AND FORM
			 **
			 **/
			if (count($term_children) == 0) {
				query_posts(array(
					'post_type' => 'project-elements',
					'showposts' => -1,
					'paged' => $paged,
					'tax_query' => array(
						array(
							'taxonomy' => 'phonak-projects',
							'field' => 'id',
							'terms' => array($term_id)
						)
					)
				));


				/**
				 **
				 **
				 ** PROJECT ELEMENTS - Downloadable
				 **
				 **/
				if (have_posts() && $term_meta['project_type'] == 'Downloadable') { ?>
					<?php
					echo '<h3>Campaign Elements</h3>';
					echo '<p>Please select the campaign <strong>format</strong> you wish to order (you can choose more than one).</p>';
					echo '<div class="project_elements">';
					while (have_posts()) {
						the_post();
					?>
						<input type="hidden" name="section_project_elements" value="PROJECT ELEMENTS" />
						<div>
							<label>
								<?php
								if (!empty($sku_code)) {
									echo '<p class="skualign">';
									echo get_post_meta(get_the_ID(), 'sku_code', true);
									echo '</p>';
								}

								echo the_post_thumbnail('thumbnail');
								?>

								<span class="button"><?php the_title(); ?></span>

							</label>
							<a class="view_example" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'example_url', true); ?>">Download</a>
						</div>
					<?php
					}
					echo '</div>';
				}



				/**
				 **
				 **
				 ** IF NO CHILDREN RENDER PROJECT ELEMENTS NON DOWNLOADABLE
				 **
				 **/
				if (have_posts() && $term_meta['project_type'] != 'Downloadable') {
					echo '<h3>Campaign Elements</h3>';
					echo '<p>Please select the campaign <strong>format</strong> you wish to order (you can choose more than one).</p>';

					echo '<div class="project_elements">';
					while (have_posts()) {
						the_post();
					?>
						<input type="hidden" name="section_project_elements" value="PROJECT ELEMENTS" />
						<div>
							<label>
								<?php if (!empty($sku_code)) {
									echo '<p class="skualign">';
									echo get_post_meta(get_the_ID(), 'sku_code', true);
									echo '</p>';
								} ?>
								<?php echo the_post_thumbnail('thumbnail'); ?>

								<span class="button"><?php the_title(); ?></span>
							</label>
							<label class="tgl">
								<input type="checkbox" class="checkbox_class" name="project_element[]" value="<?php the_title(); ?>|<?php echo get_post_meta(get_the_ID(), 'artwork_url', true); ?>">
								<span data-on="Selected" data-off="Deselected"></span>
							</label>
							<a class="view_example" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'example_url', true); ?>">View Example</a>
						</div>
			<?php
					}
					echo '</div>';
				}


				/*
				*
				*
				* Define Audeo B-R Categories and forms for these projects
				* In else statement show regular project types
				*
				*
				*/

				$audeoBR = array(
					'Audéo B-R - Print marketing - Newspaper Advertisement',
					'Audéo B-R - Print marketing - Direct Mail',
					'Audéo B-R - Print marketing - Newspaper Insert',
					'Audéo B-R - Database marking',
					'Audéo B-R - Digital marketing',
					'Audéo B-R - Events marketing',
					'Audéo B-R - Downloadable'
				);

				if (in_array($term_meta['project_type'], $audeoBR)) {
					if ($term_meta['project_type'] != 'Downloadable') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/downloadable.php');
					}
					/**
					 ** Newspaper Advertisement
					 **/
					if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Newspaper Advertisement') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/print-marketing-newspaper-advertisement.php');
					}

					/**
					 ** Direct Mail
					 **/
					if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Direct Mail') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/print-marketing-direct-mail.php');
					}

					/**
					 ** Newspaper Insert
					 **/
					if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Newspaper Insert') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/printmarketing-newspaper-insert.php');
					}

					/**
					 ** Database marking
					 **/
					if ($term_meta['project_type'] == 'Audéo B-R - Database marking') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/database-marking.php');
					}

					/**
					 ** Digital marketing
					 **/

					if ($term_meta['project_type'] == 'Audéo B-R - Digital marketing') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/digital-marketing.php');
					}


					/**
					 ** clinic details
					 **/
					if ($term_meta['project_type'] != 'Audéo B-R - Downloadable') {
						require_once(dirname(__FILE__) . '/forms/AudeoB-R/clinic-details.php');
					}
				} else {


					/**
					 *
					 * Advertisements and direct mail
					 *
					 */
					echo '<script src="' . plugins_url('js/tabs.js', __FILE__) . '"></script>';
					if ($term_meta['project_type'] == 'Advertisements & Direct Mail - Print Advertisements') {
						//echo '<input type="hidden" name="section_clinic_information" value="Advertisements & Direct Mail - Print Advertisements" />';

						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/type-of-ad.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Advertisements & Direct Mail - Direct Mail') {
						//echo '<input type="hidden" name="section_clinic_information" value="Advertisements & Direct Mail - Direct Mail" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/type-of-project.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/postage-type.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/postage-type-demographics.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/Advertisements-&-Direct-Mail/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '</div>';
						echo '</div>';
					}

					/**
					 *
					 * Logo Brochure & Stationary
					 *
					 */

					if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Logo Design') {
						//echo '<input type="hidden" name="section_clinic_information" value="Logo, Brochure & Stationary - Logo Design" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Logo-Brochure-Stationary/logo-design/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Brochures') {
						//echo '<input type="hidden" name="section_clinic_information" value="Logo, Brochure & Stationary - Brochures" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Logo-Brochure-Stationary/template.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Logo-Brochure-Stationary/brochures/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Stationary') {
						//echo '<input type="hidden" name="section_clinic_information" value="Logo, Brochure & Stationary - Stationary" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Logo-Brochure-Stationary/type.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Logo-Brochure-Stationary/stationary/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '</div>';
						echo '</div>';
					}



					/**
					 *
					 * Database Marketing
					 *
					 */

					if ($term_meta['project_type'] == 'Database Marketing - Newsletter') {
						//echo '<input type="hidden" name="section_clinic_information" value="Database Marketing - Newsletter" />';

						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/type.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/template.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/newsletter/brief.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/delivery-options.php');
						echo '</div>';

						echo '<div class="tab tab-7">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/contact-list.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '<span class="step step-7"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Database Marketing - Appointment Anniversary') {
						//echo '<input type="hidden" name="section_clinic_information" value="Database Marketing - Appointment Anniversary" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/type.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/template.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/appointment-anniversary/brief.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/delivery-options.php');
						echo '</div>';

						echo '<div class="tab tab-7">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/contact-list.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '<span class="step step-7"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Database Marketing - Greeting Cards') {
						//echo '<input type="hidden" name="section_clinic_information" value="Database Marketing - Greeting Cards" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/type.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/occasion.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/template.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/greeting-cards/brief.php');
						echo '</div>';

						echo '<div class="tab tab-7">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/contact-list.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '<span class="step step-7"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Database Marketing - Letter') {
						//echo '<input type="hidden" name="section_clinic_information" value="Database Marketing - Letter" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/type.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/letter/brief.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/delivery-options.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/Database-Marketing/contact-list.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '</div>';
						echo '</div>';
					}




					/**
					 *
					 * Digital Marketing
					 *
					 */

					if ($term_meta['project_type'] == 'Digital - Facebook Ad Campaign') {
						//echo '<input type="hidden" name="section_clinic_information" value="Digital - Facebook Ad Campaign" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/digital/objective.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/digital/facebook-ad-campaign/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Digital - Email Marketing') {
						//echo '<input type="hidden" name="section_clinic_information" value="Digital - Email Marketing" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/digital/email-marketing/type-of-email.php');
						echo '</div>';

						echo '<div class="tab tab-4">';
						require_once(dirname(__FILE__) . '/forms/digital/email-marketing/brief.php');
						echo '</div>';

						echo '<div class="tab tab-5">';
						require_once(dirname(__FILE__) . '/forms/digital/email-marketing/contacts.php');
						echo '</div>';

						echo '<div class="tab tab-6">';
						require_once(dirname(__FILE__) . '/forms/digital/email-marketing/email-software.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '<span class="step step-4"></span>';
						echo '<span class="step step-5"></span>';
						echo '<span class="step step-6"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Digital - Google Ad Words') {
						//echo '<input type="hidden" name="section_clinic_information" value="Digital - Google Ad Words" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/digital/google-ad-words/brief.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '<div class="step-pagination">';
						echo '<span class="step step-1 active"></span>';
						echo '<span class="step step-2"></span>';
						echo '<span class="step step-3"></span>';
						echo '</div>';
						echo '</div>';
					}

					if ($term_meta['project_type'] == 'Digital - Lead Nurturing Campaign') {
						//echo '<input type="hidden" name="section_clinic_information" value="Digital - Lead Nurturing Campaign" />';
						echo '<div class="tabs-wrapper">';
						echo '<div class="tab tab-1 active">';
						require_once(dirname(__FILE__) . '/forms/clinic-information.php');
						echo '</div>';

						echo '<div class="tab tab-2">';
						require_once(dirname(__FILE__) . '/forms/project-set-up.php');
						echo '</div>';

						echo '<div class="tab tab-3">';
						require_once(dirname(__FILE__) . '/forms/digital/lead-nurturing-campaign/contacts.php');
						echo '</div>';

						echo '<div class="controls">';
						echo '<a href="#" class="prev inactive">Previous</a>';
						echo '<a href="#" class="next">Next</a>';
						echo '</div>';

						echo '</div>';
					}
				}



				/*** marvel ***/
				if ($term_meta['project_type'] == 'Marvel') {
					//echo '<input type="hidden" name="section_clinic_information" value="Advertisements & Direct Mail - Print Advertisements" />';

					echo '<div class="tabs-wrapper">';
					echo '<div class="tab tab-1 active">';
					require_once(dirname(__FILE__) . '/forms/clinic-information.php');
					echo '</div>';

					echo '<div class="step-pagination">';
					echo '<span class="step step-1 active"></span>';
					echo '</div>';
					echo '</div>';
				}

				echo '<input type="submit" name="submit" value="Submit Project Request">';
			}
			?>
		</form>
	<?php
	}
}
add_shortcode('phonak_project', 'phonak_project');
/*
*
*
*
*
*
*  POST TYPE / TAXONOMIES
*
*
*
*
*
*/

//BUILD Project
add_action('init', 'phonak_project_post_type');
function phonak_project_post_type()
{
	register_post_type(
		'project-elements',
		array(
			'labels' => array(
				'name' => 'Phonak Project Elements',
				'singular_name' => 'Phonak Project Element',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Phonak Project Element',
				'edit' => 'Edit',
				'edit_item' => 'Edit Phonak Project Element',
				'new_item' => 'New Phonak Project Element',
				'view' => 'View',
				'view_item' => 'View Phonak Project Element',
				'search_items' => 'Search Phonak Project Elements',
				'not_found' => 'No Phonak Project Elements found',
				'not_found_in_trash' => 'No Phonak Project Elements found in Trash'
			),

			'public' => true,
			'supports' => array('title', 'thumbnail', 'page-attributes'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'project-elements', 'with_front' => false),
		)
	);

	register_taxonomy(
		'phonak-projects',
		'project-elements',
		array(
			'labels' => array(
				'name' => 'Projects',
				'add_new_item' => 'Add New Phonak Project',
				'new_item_name' => "New Phonak Project"
			),
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array('slug' => 'request', 'with_front' => false)
		)
	);
}


//CAROUSEL META BOX
function phonak_project_meta()
{
	add_meta_box(
		'phonak_project_meta_box',
		'Project Details',
		'display_phonak_project_meta_box',
		'project-elements',
		'advanced',
		'high'
	);
}

add_action('admin_init', 'phonak_project_meta');

function display_phonak_project_meta_box($project)
{
	global $post;
	$artwork_url = esc_html(get_post_meta($project->ID, 'artwork_url', true));
	$example_url = esc_html(get_post_meta($project->ID, 'example_url', true));
	$sku_code = esc_html(get_post_meta($project->ID, 'sku_code', true));
	?>
	<table style="width: 100%;">
		<tr>
			<td style="width: 100%">EXAMPLE URL</td>
		</tr>
		<tr>
			<td><input type="text" style="width: 100%" name="example_url" value="<?php echo $example_url; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 100%">Artwork URL</td>
		</tr>
		<tr>
			<td><input type="text" style="width: 100%" name="artwork_url" value="<?php echo $artwork_url; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 100%">SKU Code</td>
		</tr>
		<tr>
			<td><input type="text" style="width: 100%" name="sku_code" value="<?php echo $sku_code; ?>" /></td>
		</tr>
		<input type="hidden" name="phonak_project_flag" value="true" />
	</table>
<?php
}


add_action('save_post', 'custom_fields_phonak_project_update', 10, 2);

function custom_fields_phonak_project_update($post_id, $post)
{
	if ($post->post_type == 'project-elements') {
		if (isset($_POST['phonak_project_flag'])) {
			if (isset($_POST['artwork_url']) && $_POST['artwork_url'] != '') {
				update_post_meta($post_id, 'artwork_url', $_POST['artwork_url']);
			} else {
				update_post_meta($post_id, 'artwork_url', '');
			}
			if (isset($_POST['example_url']) && $_POST['example_url'] != '') {
				update_post_meta($post_id, 'example_url', $_POST['example_url']);
			} else {
				update_post_meta($post_id, 'example_url', '');
			}
			if (isset($_POST['sku_code']) && $_POST['sku_code'] != '') {
				update_post_meta($post_id, 'sku_code', $_POST['sku_code']);
			} else {
				update_post_meta($post_id, 'sku_code', '');
			}
		}
	}
}



// cateogry "Type"
// Add term page
function phonak_projects_taxonomy_add_new_meta_field()
{
	// this will add the custom meta field to the add new term page
?>
	<div class="form-field">
		<label for="term_meta[project_type]">Project Type</label>
		<select name="term_meta[project_type]" id="term_meta[project_type]">
			<option>Audéo B-R - Print marketing</option>
			<option>Audéo B-R - Database marking</option>
			<option>Audéo B-R - Digital marketing</option>
			<option>Audéo B-R - Events marketing</option>
			<option>Audéo B-R - Downloadable</option>

			<option>Advertisements & Direct Mail - Print Advertisements</option>
			<option>Advertisements & Direct Mail - Direct Mail</option>
			<option>Logo, Brochure & Stationary - Logo Design</option>
			<option>Logo, Brochure & Stationary - Brochures</option>
			<option>Logo, Brochure & Stationary - Stationary</option>
			<option>Database Marketing - Newsletter</option>
			<option>Database Marketing - Appointment Anniversary</option>
			<option>Database Marketing - Greeting Cards</option>
			<option>Database Marketing - Letter</option>
			<option>Digital - Facebook Ad Campaign</option>
			<option>Digital - Email Marketing</option>
			<option>Digital - Google Ad Words</option>
			<option>Digital - Lead Nurturing Campaign</option>

			<option>Marvel</option>
		</select>
	</div>
	<div class="form-field">
		<label for="term_meta[project_thumbnail]">Project Thumbnail</label>
		<input type="text" name="term_meta[project_thumbnail]" style="width: 100%" value="">
	</div>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="custom_url">Custom URL:</label></th>
		<td>
			<input type="text" name="custom_url" style="width: 100%" value="<?php echo $custom_url; ?>">
		</td>
	</tr>
<?php
}

function phonak_projects_taxonomy_edit_new_meta_field($term)
{
	// this will add the custom meta field to the add new term page
	$t_id = $term->term_id;
	$term_meta = get_option('taxonomy_' . $t_id);
?>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[custom_term_meta]">Project Type</label></th>
		<td>
			<select name="term_meta[project_type]" id="term_meta[project_type]">
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Print marketing') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Print marketing</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Newspaper Advertisement') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Print marketing - Newspaper Advertisement</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Direct Mail') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Print marketing - Direct Mail</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Print marketing - Newspaper Insert') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Print marketing - Newspaper Insert</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Database marking') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Database marking</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Digital marketing') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Digital marketing</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Events marketing') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Events marketing</option>
				<option <?php if ($term_meta['project_type'] == 'Audéo B-R - Downloadable') {
							echo 'SELECTED';
						} ?>>Audéo B-R - Downloadable</option>

				<option <?php if ($term_meta['project_type'] == 'Advertisements & Direct Mail - Print Advertisements') {
							echo 'SELECTED';
						} ?>>Advertisements & Direct Mail - Print Advertisements</option>
				<option <?php if ($term_meta['project_type'] == 'Advertisements & Direct Mail - Direct Mail') {
							echo 'SELECTED';
						} ?>>Advertisements & Direct Mail - Direct Mail</option>
				<option <?php if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Logo Design') {
							echo 'SELECTED';
						} ?>>Logo, Brochure & Stationary - Logo Design</option>
				<option <?php if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Brochures') {
							echo 'SELECTED';
						} ?>>Logo, Brochure & Stationary - Brochures</option>
				<option <?php if ($term_meta['project_type'] == 'Logo, Brochure & Stationary - Stationary') {
							echo 'SELECTED';
						} ?>>Logo, Brochure & Stationary - Stationary</option>
				<option <?php if ($term_meta['project_type'] == 'Database Marketing - Newsletter') {
							echo 'SELECTED';
						} ?>>Database Marketing - Newsletter</option>
				<option <?php if ($term_meta['project_type'] == 'Database Marketing - Appointment Anniversary') {
							echo 'SELECTED';
						} ?>>Database Marketing - Appointment Anniversary</option>
				<option <?php if ($term_meta['project_type'] == 'Database Marketing - Greeting Cards') {
							echo 'SELECTED';
						} ?>>Database Marketing - Greeting Cards</option>
				<option <?php if ($term_meta['project_type'] == 'Database Marketing - Letter') {
							echo 'SELECTED';
						} ?>>Database Marketing - Letter</option>
				<option <?php if ($term_meta['project_type'] == 'Digital - Facebook Ad Campaign') {
							echo 'SELECTED';
						} ?>>Digital - Facebook Ad Campaign</option>
				<option <?php if ($term_meta['project_type'] == 'Digital - Email Marketing') {
							echo 'SELECTED';
						} ?>>Digital - Email Marketing</option>
				<option <?php if ($term_meta['project_type'] == 'Digital - Google Ad Words') {
							echo 'SELECTED';
						} ?>>Digital - Google Ad Words</option>
				<option <?php if ($term_meta['project_type'] == 'Digital - Lead Nurturing Campaign') {
							echo 'SELECTED';
						} ?>>Digital - Lead Nurturing Campaign</option>

				<option <?php if ($term_meta['project_type'] == 'Marvel') {
							echo 'SELECTED';
						} ?>>Marvel</option>
			</select>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[custom_term_meta]">Project Thumbnail</label></th>
		<td>
			<input type="text" name="term_meta[project_thumbnail]" style="width: 100%" value="<?php echo $term_meta['project_thumbnail']; ?>">
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[custom_url]">Custom URL:</label></th>
		<td>
			<input type="text" name="term_meta[custom_url]" style="width: 100%" value="<?php echo $term_meta['custom_url']; ?>">
		</td>
	</tr>

<?php
}
add_action('phonak-projects_add_form_fields', 'phonak_projects_taxonomy_edit_new_meta_field', 10, 2);
add_action('phonak-projects_edit_form_fields', 'phonak_projects_taxonomy_edit_new_meta_field', 10, 2);



function save_phonak_projects_custom_meta($term_id)
{
	if (isset($_POST['term_meta'])) {
		$t_id = $term_id;


		$term_meta = get_option('taxonomy_' . $t_id);
		$term_meta['project_type'] = $_POST['term_meta']['project_type'];
		$term_meta['project_thumbnail'] = $_POST['term_meta']['project_thumbnail'];
		$term_meta['custom_url'] = $_POST['term_meta']['custom_url'];

		// Save the option array.
		update_option('taxonomy_' . $t_id, $term_meta);
	}
}
add_action('edited_phonak-projects', 'save_phonak_projects_custom_meta', 10, 2);
add_action('create_phonak-projects', 'save_phonak_projects_custom_meta', 10, 2);



function phonak_project_request_product_marketing($atts)
{

	if (!is_admin()) {

		extract(shortcode_atts(array(
			'apikey' 			=> 'CJ7E-OOBI-5FCD-K5VJ-PWFR5ZS-US10527',
			'username'			=> 'sonovaeventsuser',
			'password'			=> 'Phonak21'
		), $atts));

		wp_enqueue_script('momentjs', plugins_url() . '/phonak-project-request/js/moment.js');

		//wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?render=6LejP90UAAAAAEz9T38b5pRK69-qDCdr6GYw8y-k');
		//wp_enqueue_script( 'phonak-project-request-recaptcha', plugins_url().'/phonak-project-request/js/phonak-project-request-recaptcha.js', array('momentjs','recaptcha') );
		//wp_enqueue_script('phonak-project-request-recaptcha', plugins_url() . '/phonak-project-request/js/phonak-project-request-recaptcha.js', array('momentjs'));

		$siteId = get_current_blog_id();

		$success = false;
		if (isset($_POST['submit']) && $_POST['your_email'] == '') {

			//checkCaptcha();


			//set practive development manager
			//$practice_development_team_member = $_POST['practice_development_team_member'];

			// Filter out any post items we don't need. Example: submit
			$filterOut = ['submit', 'recaptcha_response', 'site_id', 'your_email'];
			foreach ($filterOut as $out) {
				unset($_POST[$out]);
			}

			// define string replace array
			$replace = ['_'];
			$replaceWith = [' '];

			$description = convert_post_to_description();

			// Hit API
			//$username='phonakmarketingwebsite';
			//$password='Phonak1176!';
			//$apikey = 'V81L-YXDN-U7M5-QOJ9-PWFM3UN-US7044';
			$URL = 'https://api.proworkflow.net/projectrequests?apikey=' . $apikey;

			//echo $apikey.'<br />';
			//echo $username.'<br />';
			//echo $password.'<br />';

			$projectTitle = $_POST['project_title'];

			if ($siteId == 2 ||  $siteId == 3) {
				$projectData = [
					'title' 		=> $projectTitle,
					'description' 	=> $description
				];
			} else {
				$projectData = [
					'title' 		=> $projectTitle,
					'description' 	=> $description//,
					//'contactId'		=> $practice_development_team_member
				];
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120); //timeout after 120 seconds
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $projectData);
			$result = curl_exec($ch);
			//print_r($result);

			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
			//echo '$status_code: '.$status_code.'<br/>';

			if ($status_code == 201) {
				$decodedResponse = json_decode($result);
				$projectId = $decodedResponse->details[0]->id;
				curl_close($ch);
				//$success = true;


				// deal with any attached files after we have the project ID.
				if (!empty($_FILES)) {
					$description .= '<strong>File Attachments</strong>';


					foreach ($_FILES as $key => $val) {

						if ($_FILES[$key]['tmp_name'] != '') {
							$fileData =  [
								'content' => base64_encode(file_get_contents($_FILES[$key]['tmp_name'])),
								'name' => $_FILES[$key]['name'],
								'projectid' => $projectId
							];


							$URL = 'https://api.proworkflow.net/files?apikey=' . $apikey;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $URL);
							curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
							curl_setopt($ch, CURLOPT_TIMEOUT, 120);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

							$result = curl_exec($ch);
							$decodedResponse = json_decode($result);
							//print_r($decodedResponse);
							//exit;
						}
					}

					$success = true;
				} else {

					$success = true;
				}
			} else {
				print curl_error($ch);
			}
		}

		$return = '';

		if ($success) {
			//echo '<p style="color:#86bc24; text-align:center; font-size:26px; margin: 100px 0;">Project successfully submitted.<p>';
			$return = '<script>window.location="' . get_bloginfo('url') . '/project-request-complete-internal/";</script>';
		} else {

			$siteId = get_current_blog_id();
			//echo $siteId;



			$return =  '<form class="project_request_form" method="post" action="#" enctype="multipart/form-data">';

			$return .= '<div>';

			$return .= '<div>';
			$return .= '<label>Project Title</label>';
			$return .= '<input type="text" name="project_title" value="' . (isset($_POST['project_title']) ? $_POST['project_title'] : '') . '" class="required" required />';
			$return .= '</div>';

			$category_select = (isset($_POST['project_category']) ? $_POST['project_category'] : '');

			$return .= '<div>';
			$return .= '<label>Project Category</label>';
			$return .= '<select name="project_category" style="width: 100%">';
			$return .= '<option'.(($category_select == "Launch Material" || $category_select == '') ? ' selected ':'').'>Launch Material</option>';
			$return .= '<option'.(($category_select == "Price and Third Party") ? ' selected ':'').'>Price and Third Party</option>';
			$return .= '<option'.(($category_select == "Costco") ? ' selected ':'').'>Costco</option>';
			$return .= '<option'.(($category_select == "Roger") ? ' selected ':'').'>Roger</option>';
			$return .= '<option'.(($category_select == "Corporate Branding") ? ' selected ':'').'>Corporate Branding</option>';
			$return .= '<option'.(($category_select == "Sales Support") ? ' selected ':'').'>Sales Support</option>';
			$return .= '<option'.(($category_select == "LMS Elearn") ? ' selected ':'').'>LMS Elearn</option>';
			$return .= '<option'.(($category_select == "Digital Communication") ? ' selected ':'').'>Digital Communication</option>';
			$return .= '<option'.(($category_select == "Marketing Materials") ? ' selected ':'').'>Marketing Materials</option>';
			$return .= '<option'.(($category_select == "Social Media") ? ' selected ':'').'>Social Media</option>';
			$return .= '</select>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>Objective of this project and anticipated impact on business activites (will this project have a national impact? Can you quantify in $ what the impact will be?)</label>';
			$return .= '<textarea name="project_objective" class="required" required>' . (isset($_POST['project_objective']) ? $_POST['project_objective'] : '') . '</textarea>';
			$return .= '</div>';

			$approved_radio = (isset($_POST['project_approved']) ? $_POST['project_approved'] : '');

			$return .= '<div>';
			$return .= '<label>Has this project already been approved:</label>';
			$return .= '<input type="radio" name="project_approved" '.(($approved_radio == "Yes") ? ' checked ':'').' value="Yes" id="project_approved_yes"/><label for="project_approved_yes">Yes</label>'; //Dynamic Field
			$return .= '<input type="radio" checked name="project_approved" '.(($approved_radio == "No") ? ' checked ':'').' value="No" id="project_approved_no"/><label for="project_approved_no">No</label>';
			$return .= '</div>';

			$return .= '<div id="approved-option" style="display: none;">';
			$return .= '<label>Approved Option</label>';
			$return .= '<input type="text" name="project_approved_option" disabled value="' . (isset($_POST['project_approved_option']) ? $_POST['project_approved_option'] : '') . '"/>';
			$return .= '</div>';

			$budget_radio = (isset($_POST['project_budget']) ? $_POST['project_budget'] : '');

			$return .= '<div>';
			$return .= '<label>Is there a budget allocated to this project:</label>';
			$return .= '<input type="radio" name="project_budget" '.(($budget_radio == "Yes") ? ' checked ':'').' value="Yes" id="project_budget_yes"/><label for="project_budget_yes">Yes</label>';
			$return .= '<input type="radio" checked name="project_budget" '.(($budget_radio == "No") ? ' checked ':'').' value="No" id="project_budget_no"/><label for="project_budget_no">No</label>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>Latest date to complete this project by marketing (including final approval):</label>';
			$return .= '<input type="date" name="project_latest_date" value="' . (isset($_POST['project_latest_date']) ? $_POST['project_latest_date'] : '') . '" class="required" required />';
			$return .= '</div>';

			$deliverables_select = (isset($_POST['project_deliverables']) ? $_POST['project_deliverables'] : '');

			$return .= '<div>';
			$return .= '<label>Deliverables:</label>';
			$return .= '<select name="project_deliverables" style="width: 100%">';
			$return .= '<option'.(($deliverables_select == "Printed" || $deliverables_select == '') ? ' selected ':'').'>Printed</option>';
			$return .= '<option'.(($deliverables_select == "Digital") ? ' selected ':'').'>Digital</option>';
			$return .= '<option'.(($deliverables_select == "Both") ? ' selected ':'').'>Both</option>';
			$return .= '</select>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>Format (For ex. quadfold, pamphlet, booklet, one pager etc...)</label>';
			$return .= '<input type="text" name="project_format" value="' . (isset($_POST['project_format']) ? $_POST['project_format'] : '') . '" class="required" required />';
			$return .= '</div>';

			$target_select = (isset($_POST['project_target']) ? $_POST['project_target'] : '');

			$return .= '<div>';
			$return .= '<label>Target</label>';
			$return .= '<select name="project_target" style="width: 100%">';
			$return .= '<option'.(($target_select == "B2B" || $distribution_select == '') ? ' selected ':'').'>B2B</option>';
			$return .= '<option'.(($target_select == "B2C") ? ' selected ':'').'>B2C</option>';
			$return .= '<option'.(($target_select == "B2B2C") ? ' selected ':'').'>B2B2C</option>';
			$return .= '</select>';
			$return .= '</div>';

			$french_radio = (isset($_POST['project_french_no']) ? $_POST['project_french_no'] : '');

			$return .= '<div>';
			$return .= '<label>French</label>';
			$return .= '<input type="radio" name="project_french" '.(($budget_radio == "Yes") ? ' checked ':'').'value="Yes" id="project_french_yes"/><label for="project_french_yes">Yes</label>';
			$return .= '<input type="radio" checked name="project_french" '.(($budget_radio == "No") ? ' checked ':'').'value="No" id="project_french_no"/><label for="project_french_no">No</label>';
			$return .= '</div>';

			$distribution_select = (isset($_POST['project_distribution']) ? $_POST['project_distribution'] : '');

			$return .= '<div>';
			$return .= '<label>Distribution</label>';
			$return .= '<select name="project_distribution" id="project_distribution" style="width: 100%">';
			$return .= '<option'.(($distribution_select == "Eblast" || $distribution_select == '') ? ' selected ':'').'>Eblast</option>';//Dynamic input
			$return .= '<option'.(($distribution_select == "PDF") ? ' selected ':'').'>PDF</option>';
			$return .= '<option'.(($distribution_select == "Printer mailer") ? ' selected ':'').'>Printer mailer</option>';//Dynamic input
			$return .= '<option'.(($distribution_select == "INSERT Card") ? ' selected ':'').'>INSERT Card</option>';
			$return .= '<option'.(($distribution_select == "OTHER") ? ' selected ':'').'>OTHER</option>';//Dynamic input
			$return .= '</select>';
			$return .= '</div>';

			$return .= '<div id="distribution-option" style="display: none;">';
			$return .= '<label>Distribution Option</label>';
			$return .= '<input type="text" name="project_distribution_option" disabled value="' . (isset($_POST['project_distribution_option']) ? $_POST['project_distribution_option'] : '') . '"/>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>Staff members involved in the project and who will need to be sent a draft for review:</label>';
			$return .= '<textarea name="project_involved" class="required" required>' . (isset($_POST['project_involved']) ? $_POST['project_involved'] : '') . '</textarea>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<label>Brief (add anything we need to know to work on this project)</label>';
			$return .= '<textarea name="project_brief" class="required" required>' . (isset($_POST['project_brief']) ? $_POST['project_brief'] : '') . '</textarea>';
			$return .= '</div>';


			$return .= '<div class="projectFileContainer">';
			$return .= '<h3>Project Files</h3>';
			$return .= '<div>';
			$return .= '<input type="file" name="attachments" class="attachmentsFileBtn"  />';
			$return .= '</div>';
			$return .= '</div>';

			$return .= '<div>';
			$return .= '<a href="#" class="addProjectFileContainer">Need another file?</a>';
			$return .= '</div>';

			$return .= '<input type="email" name="your_email" id="your_email">';
			//$return .= '<input type="hidden" name="recaptcha_response" id="recaptchaResponse">';
			$return .= '<input type="hidden" name="site_id" id="siteID" value="' . $siteId . '" />';

			$return .= '<input class="projectSubmitBtn" type="submit" name="submit" value="Submit Project Request" />';
			$return .= '<p class="loadingText" style="display:none">Loading... This could take some time. Thank you for your patience</p>';
			$return .= '</form>';



			$return .= '<style>';
			$return .= 'textarea{';
			$return .= 'width: 100%;';
			$return .= 'height: 200px;';
			$return .= '}';
			$return .= 'input[type="email"]#your_email {';
			$return .= 'display: none;';
			$return .= '}';
			$return .= '</style>';
		}
		return $return;
	}
}
add_shortcode('phonak_project_request_product_marketing', 'phonak_project_request_product_marketing');


?>