<?php 
if (!function_exists('learninghub_form_alter'))   {
function learninghub_form_alter(&$form, &$form_state, $form_id) {
	
	if ( $form_id == 'search_block_form' )
	{
		$form['search_block_form']['#attributes']['placeholder'] = t( 'Search' );
	}

	switch ($form_id) {
    case 'user_login':
   
      $user_login_final_validate_index = array_search('user_login_final_validate', $form['#validate']);
      if ($user_login_final_validate_index >= 0) {
        $form['#validate'][$user_login_final_validate_index] = 'learninghub_final_validate';
      }
 	}

	 if ($form_id == 'mailchimp_signup_subscribe_block_subscription_form') {
		    //dpm($form);
		  $form['mergevars']['EMAIL']['#attributes'] = array(
			 'placeholder'=> t('What is your email address?')
			);
		   $form['mergevars']['FNAME']['#attributes'] = array(
			 'placeholder'=> t('What is your first name?')
			);
		    $form['mergevars']['LNAME']['#attributes'] = array(
			 'placeholder'=> t('What is your last name?')
			);
		    $form['#submit'][] = 'thank_you_message';
			
    }
	if (!function_exists('thank_you_message'))   {
		function thank_you_message($form, &$form_state) {

			drupal_set_message('You have successfully subscribed to the HUBCaps  Newsletter. Thank you for joining our mailing list!  .') ;
		}
	}

	if ($form_id == 'user_profile_form')	
	{	
	   $form['profile_plan_content']['field_learner_plan1']['und']['actions']['ief_add']['#value'] = t('Add New Competency');
	   $form['profile_plan_content']['field_progress_notes1']['und']['actions']['ief_add']['#value'] = t('Add Progress Notes');
    	

	  /*  for ($i=0; $i<count($form['profile_plan_content']['field_learner_plan1']['und']['entities']); $i++) {
	    	if (!isset($form['profile_plan_content']['field_learner_plan1']['und']['entities'][$i])) break;


	    	$uid = $form['profile_plan_content']['field_learner_plan1']['und']['entities'][$i]['#entity']->vid;
	    	$form['profile_plan_content']['field_learner_plan1']['und']['entities'][$i]['activity_view'] = array(
			'#type' => 'markup',
			'#markup' => views_embed_view('learning_activity','block',$uid),
			'#weight' => 2,
			'#access' => true,
			);

	        //echo '<pre>';
			// print htmlspecialchars(print_r($rows[$i], true));
			// echo '</pre>';
			

	    
		}
		echo '<pre>';
		print htmlspecialchars(print_r($form['profile_plan_content']['field_learner_plan1'], true));
		echo '</pre>';
		*/

	   unset($form['profile_plan_content']['field_learner_plan1']['und']['form']['field_user_id']['und']);
       unset($form['profile_plan_content']['field_learner_plan1']['und']['entities'][0]['form']['field_user_id']['und']);
       unset($form['profile_plan_content']['field_learner_plan1']['und']['entities'][0]['form']['status']);
    
	}
   	
	if ($form_id == 'user_login') {
		$form['heading'] = array(
		'#type' => 'markup',
		'#markup' => '<h1>Learner Portal</h1>',
		'#weight' => -100
		);
	}

	if ($form_id == 'user_register_form' ) 
	{ 	
		unset($form['account']['name']);
		$form['profile_learner_profile']['field_first_name']['#value'] = "";
		$form['profile_learner_profile']['field_last_name']['#value'] = "";
		$firstname = $form['profile_learner_profile']['field_first_name']['#value'];
	    $firstname_initial = substr($firstname, 0, 2);
	    $lastname = $form['profile_learner_profile']['field_last_name']['#value'];
	    // Also consider trimming the length and to lowercase your username.
	   
	    $name1 =  'st'.$firstname_initial.$lastname;
	    $name1 = str_replace(array("?","!",",","'",";"," "), "", $name1);
	    $final_username = "";
	  	if(!db_query("SELECT COUNT(*) FROM {users} WHERE name = :name;", array(':name' =>  $name1))->fetchField())
	  	{	
	  		 $final_username = $name1;
		     //drupal_set_message("CHANGING {$edit['name']} TO {$name}");
	  	}
	  	else
	  	{	
	  		$i=0;
	  		$name = $name1;
	  		do {
			  //Check in the database here
			  $exists = db_query("SELECT COUNT(*) FROM {users} WHERE name = :name;", array(':name' =>  $name))->fetchField();
			  if($exists) {
			    $i++;
			    $name = $name1 . $i;
			  }

			}while($exists);
	  		
	    
	  		$final_username = $name;
	  		
	  		$_SESSION['username'] = $final_username;
  		}

	 	$form['#validate'][] = 'learninghub_validate_register_form';
		
		$form['account']['mail']['#description'] = t('Each learner registering with the LearningHUB must have a personal email address included on their registration form. The LearningHUB staff relies on this form of communication to get in touch with you on a regular basis. Be sure to check your junk folder and mark messages from the LearningHUB staff as safe.');
	
}
	if ($form_id == 'user_profile_form') 
	{ 	
		$form['subtitle'] = array(
		'#type' => 'markup',
		'#markup' => '<h3 class="subtitle">User: '.$form['#user']->name.'</h3>',
		'#weight' => -1000
		);

		if(isset($form['profile_plan_content']['field_learner_plan1']['und']['entities']))
		{
			$arr = $form['profile_plan_content']['field_learner_plan1']['und']['entities'];
			$form['profile_plan_content']['field_learner_plan1']['und']['entities'] = &$arr;

			function bubbleSort($array)
			{
				$count = 0;
				foreach ($array as $key => $value) {
					if(is_int($key))
					{
						$count++;
					}
				}
			 if (!$length = $count) {
			  return $array;
			 }      

		 		 for ($outer = 0; $outer < $length; $outer++) {
				  for ($inner = 0; $inner < $length; $inner++) {

				   if (strtolower($array[$outer]['#entity']->title) < strtolower($array[$inner]['#entity']->title) ) {
				    $tmp = $array[$outer];
				    $array[$outer] = $array[$inner];
				    $array[$inner] = $tmp;
				   }
				  }
				 }
			return $array;
			}

			$arr = bubbleSort($arr);

			$length = 0;
			foreach ($arr as $key => $value) {
				
				if(is_int($key))
				{	
					$length++;
					$value['#weight'] = $key;
				}
			}

			for($i=0; $i<$length; $i++)
			{
				$arr[$i]['#weight']=$i;
			}
		}
		

		$profiles = profile2_load_by_user($form['#user']->uid,'learner_profile');
		
		$firstName = $profiles->field_first_name['und'][0]['value'];
		$lastName = $profiles->field_last_name['und'][0]['value'];
		$long_term = isset($profiles->field_what_is_your_long_term_goa['und'][0]['value'])?$profiles->field_what_is_your_long_term_goa['und'][0]['value']:'not available';
		$path_changed = isset($profiles->field_goal_path_changed_1['und'][0]['value'])?$profiles->field_goal_path_changed_1['und'][0]['value']:'not available';
		$how_learn = isset($profiles->field_how_would_you_like_to_lea['und'][0]['value'])?$profiles->field_how_would_you_like_to_lea['und'][0]['value']:'not available';
		$training_type = isset($profiles->field_training_type['und'][0]['value'])?$profiles->field_training_type['und'][0]['value']:'not available';
		$learner_type = isset($profiles->field_learner_type1['und'][0]['value'])?$profiles->field_learner_type1['und'][0]['value']:'not available';
		$where_work = isset($profiles->field_where_will_you_be_working_['und'][0]['value'])?$profiles->field_where_will_you_be_working_['und'][0]['value']:'not available';
		$site_name = isset($profiles->field_site_name['und'][0]['value'])?$profiles->field_site_name['und'][0]['value']:'not available';
		$teacher_name = isset($profiles->field_teacher_name['und'][0]['value'])?$profiles->field_teacher_name['und'][0]['value']:'not available';
		$teacher_phone = isset($profiles->field_teacher_phone['und'][0]['value'])?$profiles->field_teacher_phone['und'][0]['value']:'not available';
		$teacher_email = isset($profiles->field_teacher_email['und'][0]['value'])?$profiles->field_teacher_email['und'][0]['value']:'no available';
		$learner_weekly = isset($profiles->field_estimated_learner_weekly['und'][0]['value'])?$profiles->field_estimated_learner_weekly['und'][0]['value']:'not available';
		$culminating_task = isset($profiles->field_culminating_tasks1['und'][0]['value'])?$profiles->field_culminating_tasks1['und'][0]['value']:'not available';
    
       /* Mapping of the fields  */
       /*learner type*/
		if($learner_type == '10')
		{
			$learner_type = 'Supported';

		}else if($learner_type == '20')
		{
			$learner_type = 'Unsupported';
		}
		else if($learner_type == '30')
		{
			$learner_type = 'Blended';
		}

		/* training type*/
		if($training_type == '10')
		{
			$training_type = 'Synchronous';

		}else if($training_type == '20')
		{
			$training_type = 'Asynchronous';
		}
		else if($training_type == '30')
		{
			$training_type = 'Blended';
		}

		$long_term_option1 = 'Goal Path to Employment';
		$long_term_option2 = 'Goal Path to Apprenticeship';
		$long_term_option3 = 'Goal Path to Secondary School Credit (Gr 12/OAC)';
		$long_term_option4 = 'Goal Path to Postsecondary (ACE, College or University)';
		$long_term_option5 = 'Goal Path to Independence';

		/* Long term goal */
		if($long_term == '10')
		{
			$long_term = $long_term_option1;

		}else if($long_term == '20')
		{
			$long_term = $long_term_option2;
		}
		else if($long_term == '30')
		{
			$long_term = $long_term_option3;
		}
		else if($long_term == '40')
		{
			$long_term = $long_term_option4;
		}
		else if($long_term == '50')
		{
			$long_term = $long_term_option5;
		}

		/* Path changed */

		if($path_changed == '10')
		{
			$path_changed = $long_term_option1;

		}else if($long_term == '20')
		{
			$path_changed = $long_term_option2;
		}
		else if($path_changed == '30')
		{
			$path_changed = $long_term_option3;
		}
		else if($path_changed == '40')
		{
			$path_changed = $long_term_option4;
		}
		else if($path_changed == '50')
		{
			$path_changed = $long_term_option5;
		}

		/* Where you will be working */
		
		if($where_work == '10')
		{
			$where_work = 'Home';

		}else if($where_work == '20')
		{
			$where_work = 'Adult Learning Centre';
		}
		else if($where_work == '30')
		{
			$where_work = 'Public Access Centre';
		}
		else if($where_work == '40')
		{
			$where_work = 'Contact North Site (You must enter the Site Name below';
		}

		/*culminating task*/


		if($culminating_task == '10')
		{
			$culminating_task = 'Yes';

		}else if($culminating_task == '20')
		{
			$culminating_task = 'No';
		}
		else if($culminating_task == '30')
		{
			$culminating_task = 'No Response';
		}

		$profiles_plan_content = profile2_load_by_user($form['#user']->uid,'plan_content');

		
		$referral_out = isset($profiles_plan_content->field__community_resources_refer['und'][0]['value'])?$profiles_plan_content->field__community_resources_refer['und'][0]['value']:'not available';
		$referral_out_time = isset($profiles_plan_content->field_community_resources_referr['und'][0]['value'])?$profiles_plan_content->field_community_resources_referr['und'][0]['value']:'not available';
		$other_referral_out_time = isset($profiles_plan_content->field_other_programs_services_re['und'][0]['value'])?$profiles_plan_content->field_other_programs_services_re['und'][0]['value']:'not available';

		if($referral_out == '10')
		{
			$referral_out = 'Childcare';
		}
		else if($referral_out == '20')
		{
			$referral_out = 'Educational/Academic Services';
		}
		else if($referral_out == '30')
		{
			$referral_out = 'Financial Planning';
		}
		else if($referral_out == '40')
		{
			$referral_out = 'Health/Counselling Services';
		}
		else if($referral_out == '50')
		{
			$referral_out = 'Housing Services';
		}
		else if($referral_out == '60')
		{
			$referral_out = 'Language Services Assessment';
		}
		else if($referral_out == '70')
		{
			$referral_out = 'Legal Services';
		}
		else if($referral_out == '80')
		{
			$referral_out = 'Newcomer Services';
		}
		else if($referral_out == '90')
		{
			$referral_out = 'Regualtory Bodies';
		}
		else if($referral_out == '100')
		{
			$referral_out = 'Other';
		}
		else if($referral_out == '110')
		{
			$referral_out = 'Not Applicable';
		}
		else if($referral_out == '0')
		{
			$referral_out = 'Not found';
		}
  /* Referral out time */
		if($referral_out_time == '10')
		{
			$referral_out_time = 'Referred at Entrance';
		}
		else if($referral_out_time == '20')
		{
			$referral_out_time = 'Referred at Exit';
		}
		else if($referral_out_time == '30')
		{
			$referral_out_time = 'Referred During Service';
		}
		else
		{
			$referral_out_time = 'not found';
		}

		/* Other resources referral*/

		if($other_referral_out_time == '10')
		{
			$other_referral_out_time = 'Credential Assessment';
		}
		else if($other_referral_out_time == '20')
		{
			$other_referral_out_time = 'EO - Action Center';
		}
		else if($other_referral_out_time == '30')
		{
			$other_referral_out_time = 'EO - Apprenticeship Program - Other';
		}
		else if($other_referral_out_time == '40')
		{
			$other_referral_out_time = 'EO - Apprenticeship Program - Co-op Diploma Apprenticeship Program';
		}	
		else if($other_referral_out_time == '50')
		{
			$other_referral_out_time = 'EO - Apprenticeship Program - Pre-Apprenticeship Program';
		}
		else if($other_referral_out_time == '60')
		{
			$other_referral_out_time = 'EO - Service Provider';
		}
		else if($other_referral_out_time == '70')
		{
			$other_referral_out_time = 'EO-Literacy and Basic Skills Service Provider';
		}
		else if($other_referral_out_time == '80')
		{
			$other_referral_out_time = 'Referred During Service';
		}
		else if($other_referral_out_time == '90')
		{
			$other_referral_out_time = 'EO - Local Boards';
		}
		else if($other_referral_out_time == '100')
		{
			$other_referral_out_time = 'EO - Ontario Job Bank';
		}
		else if($other_referral_out_time == '110')
		{
			$other_referral_out_time = 'EO - RRTS';
		}
		else if($other_referral_out_time == '112')
		{
			$other_referral_out_time = 'EO - Targeted Initiative for Older Workers Provider';
		}
		else if($other_referral_out_time == '114')
		{
			$other_referral_out_time = 'EO - Youth Job Connection Provider';
		}
		else if($other_referral_out_time == '116')
		{
			$other_referral_out_time = 'EO - Youth Job Connection - Summer Provider';
		}
		else if($other_referral_out_time == '118')
		{
			$other_referral_out_time = 'EO - Youth Job Link Provider';
		}
		else if($other_referral_out_time == '120')
		{
			$other_referral_out_time = 'General Education Development';
		}
		else if($other_referral_out_time == '130')
		{
			$other_referral_out_time = 'Govt Services Municipal';
		}
		else if($other_referral_out_time == '140')
		{
			$other_referral_out_time = 'Govt Training Federal - Other';
		}
		else if($other_referral_out_time == '150')
		{
			$other_referral_out_time = 'Govt Training Federal - Youth Employment Strategy';
		}
		else if($other_referral_out_time == '160')
		{
			$other_referral_out_time = 'Govt Training Provincial - Other';
		}
		else if($other_referral_out_time == '210')
		{
			$other_referral_out_time = 'Referred During Service';
		}
		else if($other_referral_out_time == '170')
		{
			$other_referral_out_time = 'High School';
		}
		else if($other_referral_out_time == '180')
		{
			$other_referral_out_time = 'Independent Learning Centre';
		}
		else if($other_referral_out_time == '190')
		{
			$other_referral_out_time = 'Language Services - Training';
		}
		else if($other_referral_out_time == '200')
		{
			$other_referral_out_time = 'Ministry of Citizenship and Immigration - Bridge Training for Immigrants';
		}
		else if($other_referral_out_time == '210')
		{
			$other_referral_out_time = 'Ministry of Citizenship and Immigration - Other';
		}
		else if($other_referral_out_time == '220')
		{
			$other_referral_out_time = 'Ontario Disability Support Program';
		}
		else if($other_referral_out_time == '230')
		{
			$other_referral_out_time = 'Ontario Internship Program';
		}
		else if($other_referral_out_time == '240')
		{
			$other_referral_out_time = 'Ontario Women\'s Directorate';
		}
		else if($other_referral_out_time == '250')
		{
			$other_referral_out_time = 'Ontario Works';
		}
		else if($other_referral_out_time == '260')
		{
			$other_referral_out_time = 'Other - Strutured/Formal Referral';
		}
		else if($other_referral_out_time == '265')
		{
			$other_referral_out_time = 'Post-Secondary Education';
		}
		else if($other_referral_out_time == '270')
		{
			$other_referral_out_time = 'Service Canada';
		}
		else if($other_referral_out_time == '280')
		{
			$other_referral_out_time = 'Services for Aboriginal People';
		}
		else if($other_referral_out_time == '290')
		{
			$other_referral_out_time = 'Summer Job Service';
		}
		else if($other_referral_out_time == '0')
		{
			$other_referral_out_time = 'Not found';
		}

	
		$form['profile_plan_content']['field__community_resources_refer']['und'][0]['value']['#value'] = $referral_out;
		$form['profile_plan_content']['field_community_resources_referr']['und'][0]['value']['#value'] = $referral_out_time;
		$form['profile_plan_content']['field_other_programs_services_re']['und'][0]['value']['#value'] = $other_referral_out_time;


		$form['subtitle_fname'] = array(
		'#type' => 'markup',
		'#markup' => '<h3 class="subtitle">'.$firstName.' '.$lastName.'</h3>',
		'#weight' => -1200
		);

		$clientID = isset($form['#user']->field_client_id['und']['0']['value'])?$form['#user']->field_client_id['und']['0']['value']:'Not Available';
		$EO_num = isset($form['#user']->field_eo_1['und']['0']['value'])?$form['#user']->field_eo_1['und']['0']['value']:'Not Available';
		$hub_lbs_1 = isset($form['#user']->field_hub_lbs_1['und']['0']['value'])?$form['#user']->field_hub_lbs_1['und']['0']['value']:'Not Available';
		

		//shows the total amount of days (not divided into years, months and days like above)
		$date_start = isset($form['#user']->field_program_start_date['und']['0']['value'])?$form['#user']->field_program_start_date['und']['0']['value']:'Not Available';

		if(isset($form['#user']->field_program_start_date['und']['0']['value']))
		{
			$date_start = $form['#user']->field_program_start_date['und']['0']['value'];
		    $user_tz = 'Canada/Eastern';
		    $date1 = new DateTime($date_start,new DateTimeZone($user_tz));
		}

		if(isset($form['profile_exit_information']['#entity']->field_program_end_date['und']['0']['value']))
		{
		    $date_end = $form['profile_exit_information']['#entity']->field_program_end_date['und']['0']['value']; 
		    $user_tz = 'Canada/Eastern';
		   	$date2 =  new DateTime($date_end, new DateTimeZone($user_tz));
		}

	    if(isset($form['profile_exit_information']))
	    {	

			if(isset($date1) && isset($date2))
			{   
			    $interval =  $date1->diff($date2);
				if($date1 < $date2)
				{
					$weeks = floor(($interval->days)/7);
				}
				else
				{
					$weeks = "Program start date is greater that End date";
				}

					$form['profile_exit_information']['program_duration'] = array(
						'#type' => 'markup',
						'#markup' => '<div class="form-type-markup"><label>PROGRAM DURATION IN WEEKS</label><div>'. $weeks .'</div></div>',
						'#weight' => 5
					);

			}
			
			$form['profile_exit_information']['clientID'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>client ID</label><div>'. $clientID .'</div></div>',
			'#weight' => 10
			);

			$form['profile_exit_information']['EO_num'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>EO #</label><div>'. $EO_num .'</div></div>',
			'#weight' => 11
			);

			$form['profile_exit_information']['hub_lbs_1'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>HUB LBS #</label><div>'. $hub_lbs_1 .'</div></div>',
			'#weight' => 11
			);

			unset($form['milestones_completed']);
			
	    }

	    if(isset($form['profile_follow_ups']) && arg(3)=='follow_ups')
	    {	
	    	$profiles = profile2_load_by_user($form['#user']->uid,'exit_information');

	    	if(isset($profiles->field_program_end_date['und']['0']['value']))
	    	{	

	    		$date_end = $profiles->field_program_end_date['und']['0']['value'];
	    		$user_tz = 'Canada/Eastern';
		 	  	$date2 =  new DateTime($date_end, new DateTimeZone($user_tz));
	    		$date_end = $date2->format('d/m/y');
	    	}
	    	else
	    	{
	    		$date_end = 'Not Available';
	    	}

	    	if(isset($form['#user']->field_program_start_date['und']['0']['value']))
			{	
				$date_start = $form['#user']->field_program_start_date['und']['0']['value'];
			    $user_tz = 'Canada/Eastern';
			    $date_start = new DateTime($date_start,new DateTimeZone($user_tz));
			    $date_start = $date_start->format('d/m/y');
			}
	    
	    	$form['profile_follow_ups']['Start Date'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>PROGRAM Start date</label><div>'. $date_start .'</div></div>',
			'#weight' => -100
			);

			$form['profile_follow_ups']['End Date'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>PROGRAM End date</label><div>'.$date_end.'</div></div>',
			'#weight' => -99
			);
	    }

		if(isset($form['profile_plan_content']) && arg(3)=='plan_content')
	    {	
	    	$profiles = profile2_load_by_user($form['#user']->uid,'plan_content');

		    if(isset($profiles->field_learner_plan1['und']))
		    {
				$count = count($profiles->field_learner_plan1['und']);
				for($i=0; $i < $count; $i++)
				{
					$field[] = $profiles->field_learner_plan1['und'][$i]['target_id'];
				}

				$nodes = node_load_multiple($field);

				$activities_completed = 0;
				$activities_completed_fiscal = [];
				$milestones_identified = [];
				$activities_completed_array = [];
				$in_progress = [];

			
				foreach($nodes as $node)
				{	
					if(isset($node->field_milestone_outcome['und'][0]['value']) && ($node->field_milestone_outcome['und'][0]['value'] == 'Attained' || $node->field_milestone_outcome['und'][0]['value'] == 'Attained in Classroom' ))
					{	
						if(isset($node->field_milestone1['und'][0]['value']))
						{	
							if($node->field_milestone1['und'][0]['value'] !=0)
							{
								array_push($activities_completed_array, $node->field_milestone1['und'][0]['value']);
							}
						}
					}
						
					if(isset($node->field_milestone1['und']))
					{
						if($node->field_milestone1['und'][0]['value'] != 0)
						{
							array_push($milestones_identified, $node->field_milestone1['und'][0]['value']);
						}
					}
					

					if(isset($node->field_milestone_outcome['und'][0]['value']) && $node->field_milestone_outcome['und'][0]['value'] == 'In Progress')
					{	
						if(isset($node->field_milestone1['und']))
							if($node->field_milestone1['und'][0]['value'] != 0)
								array_push($in_progress,  $node->field_milestone1['und'][0]['value']);
					}

					if(isset($node->field_milestone_outcome['und'][0]['value']) && ($node->field_milestone_outcome['und'][0]['value'] == 'Attained' || $node->field_milestone_outcome['und'][0]['value'] == 'Attained in Classroom'))
					{	

						if(isset($node->field_milestone1['und'][0]['value']))
						{	
							if($node->field_milestone1['und'][0]['value'] !=0 )
							{	
								$date1 = date("d m Y", strtotime($node->field_milestone_attempt_date['und'][0]['value']));
								$date2 = date("d m Y", strtotime('+3 month January' ));
								
								if($date1 < $date2)
								{	
									array_push($activities_completed_fiscal, $node->field_milestone1['und'][0]['value']);
								}
							}
						}
					}

				}
				
			    $activities_completed = count(array_unique($activities_completed_array));
			    $in_progress = count(array_unique($in_progress));
			    $milestones_identified = count(array_unique($milestones_identified));
			    $activities_completed_fiscal = count(array_unique($activities_completed_fiscal));
		    }
		    else
		    {	
		    	$activities_completed_fiscal = 'Not Available';
		    	$activities_completed = 'Not Available';
		    	$in_progress = 'Not Available';
		    	$milestones_identified = 'Not Available';
		    }

			$form['profile_plan_content']['milestones_completed'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Current Number of Completed Milestones </label>'.$activities_completed.'</div>',
			'#weight' => -28
			);

			$form['profile_plan_content']['milestones_in_progress'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Current Number of In-Progress Milestones</label>'.$in_progress.'</div>',
			'#weight' => -29
			);

			$form['profile_plan_content']['milestones_identified'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Number of Milestones Identified for learner</label>'.$milestones_identified.'</div>',
			'#weight' => -30
			);

			$form['profile_plan_content']['milestones_identified_fiscal'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Number of Milestones completed at start of fiscal</label>'.$activities_completed_fiscal.'</div>',
			'#weight' => -30
			);


			$form['profile_plan_content']['goal_path_heading'] = array(
			'#type' => 'markup',
			'#markup' => '<h2>Goal Path</h2>',
			'#weight' => -27
			);

			$form['profile_plan_content']['long_term2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>What is your long term goal? </label>'.$long_term.'</div>',
			'#weight' => -15
			);

			$form['profile_plan_content']['path_changed2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Goal path changed </label>'.$path_changed.'</div>',
			'#weight' => -16
			);

			$form['profile_plan_content']['how_learn2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>How would you like to learn? </label>'.$how_learn.'</div>',
			'#weight' => -17,
			);

			$form['profile_plan_content']['training_type2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Training Type  </label>'.$training_type.'</div>',
			'#weight' => -18,
			);

			$form['profile_plan_content']['learner_type2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Learner Type</label>'.$learner_type.'</div>',
			'#weight' => -19
			);

			$form['profile_plan_content']['where_work2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Where will you be working on your upgrading?</label>'.$where_work.'</div>',
			'#weight' => -20
			);

			$form['profile_plan_content']['site_name2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Site name </label>'.$site_name.'</div>',
			'#weight' => -21
			);
			$form['profile_plan_content']['teacher_name2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Teacher Name</label>'.$teacher_name.'</div>',
			'#weight' => -22
			);

			$form['profile_plan_content']['teacher_phone2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Teacher Phone </label>'.$teacher_phone.'</div>',
			'#weight' => -23
			);

			$form['profile_plan_content']['teacher_email2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Teacher Email </label>'.$teacher_email.'</div>',
			'#weight' => -24
			);

			$form['profile_plan_content']['learner_weekly2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Estimated Learner Weekly Time Commitment at Registration </label>'.$learner_weekly.'</div>',
			'#weight' => -25
			);

			$form['profile_plan_content']['culminating_task2'] = array(
			'#type' => 'markup',
			'#markup' => '<div class="form-type-markup"><label>Culminating Tasks Completed </label>'.$culminating_task.'</div>',
			'#weight' => -26
			);
		}
	}

  	if($form_id == 'views_exposed_form')
	{	
		$form['created']['min']['#title'] = t('From');
		$form['created']['max']['#title'] = t('To');
		
	}

	if($form_id == 'views_exposed_form')
	{	
		if ( $form_state['view']->name == 'courses_list_content_view')
		{	
			foreach ($form_state['view']->filter as $field => $filter) {
		    if ($filter->options['exposed'] /* && is my filter */) {
		      $field_id = $form['#info']["filter-$field"]['value'];
		      $form['#info']["filter-$field"]['value'];
		    }
		  }
		}
	}
}
}
if (!function_exists('learninghub_validate_register_form'))   {
	function learninghub_validate_register_form(&$form, &$form_state) {
		$how_learn = isset($form_state['values']['profile_learner_profile']['field__how_would_you_like_to_lea']['und'][0]['value'])?$form_state['values']['profile_learner_profile']['field__how_would_you_like_to_lea']['und'][0]['value']:'';
		$learn_type = isset($form_state['values']['profile_learner_profile']['field_teacher_name']['und'][0]['value'])?$form_state['values']['profile_learner_profile']['field_teacher_name']['und'][0]['value']:'';
		
		if($how_learn == 'Live Classes (scheduled for a specific date and time)')
		{	
			$form_state['values']['profile_learner_profile']['field_training_type']['und'][0]['value'] = 10;
		}
		else if($how_learn == 'Anytime Learning')
		{
			$form_state['values']['profile_learner_profile']['field_training_type']['und'][0]['value'] = 20;
		}
		else if($how_learn == 'Blended Learning (Live Classes and Anytime Learning)')
		{
			$form_state['values']['profile_learner_profile']['field_training_type']['und'][0]['value'] = 30 ;
		}
		else
		{
			$form_state['values']['profile_learner_profile']['field_training_type']['und'][0]['value'] = '_none';
		}

		if($learn_type == '')
		{
			$form_state['values']['profile_learner_profile']['field_learner_type1']['und'][0]['value'] = 20;
		}
		else
		{
			$form_state['values']['profile_learner_profile']['field_learner_type1']['und'][0]['value'] = 10;
		}
		
		$mail = $form['account']['mail']['#value'];
  
		$emails = db_query("SELECT status FROM {users} WHERE mail = :mail;", array(':mail' =>  $mail))->fetchAll();

		$count = 0;
		foreach ($emails as $key => $value) {
			if($value->status == 1)
			{
				$count++;
			}
		}

    	if($count > 0)
		{
    		form_set_error('mail', t('It appears that you already have an active account with the LearningHUB. If you want to add more courses or view your account, please log in with your Username and Password
				Do you wish to login? <a href="/user">Yes</a> | <a href="/">No</a>
			'));
    	}


	 }
}

if (!function_exists('learninghub_inline_entity_form_entity_form_alter'))   {
function learninghub_inline_entity_form_entity_form_alter(&$entity_form, &$form_state)
{	
	
	 if($entity_form['#entity_type'] == 'node' && $entity_form['#bundle'] == 'competency')
	 { 
	    unset($entity_form['title']); 
	    $entity_form['field_activity']['und']['actions']['ief_add']['#value'] = t('Add New Activity');
     	$entity_form['actions']['ief_add_save']['#value'] = t('Create Competency');
     	$entity_form['actions']['ief_edit_save']['#value'] = t('Update Competency');
		$entity_form['actions']['ief_add']['#value'] = t('Add new Activity');
		unset($entity_form['status']);
		unset($entity_form['field_user_id']);
  	 }

  	 if($entity_form['#entity_type'] == 'node' && $entity_form['#bundle'] == 'progress_notes')
	 {
		unset($entity_form['status']);
  	 }

  	if($entity_form['#entity_type'] == 'node' && $entity_form['#bundle'] == 'learning_activity')
	{	
	    $entity_form['actions']['ief_add_save']['#value'] = t('Create Activity');
	    $entity_form['actions']['ief_edit_save']['#value'] = t('Update Activity');
	    $entity_form['markup_field'] = array(

	    	'#type' => 'markup',
	    	'#markup' => '<h2>activity</h2>',
	    	'#weight' => -10

	    	);
	    unset($entity_form['status']);
		unset($entity_form['field_competency_id']);
  	}

	if($entity_form['#entity_type'] == 'node' && $entity_form['#bundle'] == 'progress_notes')
	{	
	    $entity_form['actions']['ief_add_save']['#value'] = t('Add Note');
	    $entity_form['actions']['ief_edit_save']['#value'] = t('Update Note');
  	}
}

}

if (!function_exists('learninghub_final_validate')) {
function learninghub_final_validate($form, &$form_state) {
  if (empty($form_state['uid'])) {
    // Always register an IP-based failed login event.
    flood_register_event('failed_login_attempt_ip', variable_get('user_failed_login_ip_window', 3600));
    // Register a per-user failed login event.
    if (isset($form_state['flood_control_user_identifier'])) {
      flood_register_event('failed_login_attempt_user', variable_get('user_failed_login_user_window', 21600), $form_state['flood_control_user_identifier']);
    }

    if (isset($form_state['flood_control_triggered'])) {
      if ($form_state['flood_control_triggered'] == 'user') {
        form_set_error('name', format_plural(variable_get('user_failed_login_user_limit', 5), 'Sorry, there has been more than one failed login attempt for this account. It is temporarily blocked. Try again later or request a new password.', 'Sorry, there have been more than @count failed login attempts for this account. It is temporarily blocked. Try again later or request a new password.', array('@url' => url('user/password'))));
      }
      else {
        // We did not find a uid, so the limit is IP-based.
        form_set_error('name', t('Sorry, too many failed login attempts from your IP address. This IP address is temporarily blocked. Try again later or request a new password.', array('@url' => url('user/password'))));
      }
    }
    else {
      form_set_error('name', t('Username or Password is incorrect', array('@password' => url('user/password', array('query' => array('name' => $form_state['values']['name']))))));
      watchdog('user', 'Login attempt failed for %user.', array('%user' => $form_state['values']['name']));
    }
  }
  elseif (isset($form_state['flood_control_user_identifier'])) {
    // Clear past failures for this user so as not to block a user who might
    // log in and out more than once in an hour.
    flood_clear_event('failed_login_attempt_user', $form_state['flood_control_user_identifier']);
  }
}
}


if(!function_exists('learninghub_js_alter')){
function learninghub_js_alter(&$js) {
 $js['settings']['data'][] = array('better_exposed_filters'=> array('datepicker_options' => array('dateformat'=>'dd-mm-yy')));}
}

