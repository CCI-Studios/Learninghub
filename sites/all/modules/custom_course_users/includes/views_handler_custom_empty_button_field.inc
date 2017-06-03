<?php
class views_handler_custom_empty_button_field extends views_handler_field {

	function query() {
		$this->ensure_my_table();
        $this->add_additional_fields();
	}

/**
* {@inheritdoc}
*
* Modify any end user views settings here. Debug $options to view the field
* settings you can change.
*/
function option_definition() {
$options = parent::option_definition();
return $options;
}

/**
* {@inheritdoc}
*
* Make changes to the field settings form seen by the end user when adding
* your field.
*/
function options_form(&$form, &$form_state) {
parent::options_form($form, $form_state);
}

/**
* Render callback handler.
*
* Return the markup that will appear in the rendered field.
*/
function render($values) {

			$node = node_load($values->nid);
			$users_attending_name = [];
			$users_waiting_name = [];
			$check_attending = '';
			$check_waiting = '';
			$time_start_winter = strtotime('01 January');
			$time_end_winter = strtotime('31 March');

			$time_sec_quarter = strtotime('30 June');
			$time_third_quarter = strtotime('30 september');
			$time_fourth_quarter = strtotime('31 december');

			$user_arr = [];

			$quarter = array($time_end_winter,$time_sec_quarter,$time_third_quarter,$time_fourth_quarter);

			if(isset($node->field_users_attending['und']))
			{	
				 foreach ($node->field_users_attending['und'] as $k => $v) {
    	
			    	$user = user_load($v['target_id']);
			    

			    	if(!empty($user->field_courses_live['und'])) {
					   //	print_r($user->field_courses_live['und']);

		    		foreach ($user->field_courses_live['und'] as $key => $value) {

		    		$data = explode(',', $value['value']);
					$id1 = $data[0];
				  	$time1 = $data[1];
				  	//echo $time1;
				 
		    		if($values->nid == $id1) {
					  	$current = date('Y-m-d',time());
					  	$current_time =  new DateTime($current);

					    $time1 = str_replace('/', '-', $time1);
						$arr = explode(' ',$time1);
						//print_r($arr);
					  	$arr1 = array_reverse(explode('-',$arr[0]));
					  	$time1 = implode('-',$arr1);
					  	//echo $time1;
					    $date1 = date("l dS F Y h:i:s A",strtotime($time1.' '.$arr[1].$arr[2]));
					  	$date1 = new DateTime($date1);
					  	//print_r($date1);
					  	
					  	$win_month = new DateTime(date('l dS F Y h:i:s A', $time_end_winter));
					  	$sec_qua_month = new DateTime(date('l dS F Y h:i:s A', $time_sec_quarter));
					  	$third_qua_month = new DateTime(date('l dS F Y h:i:s A', $time_third_quarter));
		  				$fourth_qua_month = new DateTime(date('l dS F Y h:i:s A', $time_fourth_quarter));

					  	if($current_time > $win_month && $current_time < $sec_qua_month) 
				    	{
				    		$start_time = $win_month;
				    		$end_time = $sec_qua_month;
				    	}

			    		if($current_time > $sec_qua_month && $current_time < $third_qua_month) {
			    			$start_time = $sec_qua_month;
				    		$end_time = $third_qua_month;
			    		}

			    		if($current_time > $third_qua_month && $current_time < $fourth_qua_month) {
			    			$start_time = $third_qua_month;
				    		$end_time = $fourth_qua_month;
			    		}

			    		if($current_time < $win_month) {
			    			$start_time = new DateTime(date('Y-m-d',$time_start_winter));
				    		$end_time = $win_month;
			    		}
			    			
			    		if($date1 > $start_time && $date1 < $end_time)
		    			{	
		    				array_push($user_arr,array(
		    					'id' => $v['target_id'],
		    					'time' => $date1,
		    					)
    							);
			    			}
			    		}

			    	}  
			    	}
			    	 
			    }
				
				foreach ($user_arr as $key => $value) {
					
					if(isset($value['id'])){
						$user_id = $value['id'];
					}
					else {
						$user_id = '';
					}

					$profile = profile2_load_by_user($user_id,'learner_profile');
					if(isset($profile->field_first_name['und'][0]['value']))
					{
						$first_name = $profile->field_first_name['und'][0]['value'];
					}
					else
					{
						$first_name = '';
					}

					if(isset($profile->field_last_name['und'][0]['value']))
					{
						$last_name = $profile->field_last_name['und'][0]['value'];
					}
					else
					{
						$last_name = '';
					}

					//print_r($profile);
					if(isset($value['time']->date)){
						$user_time = $value['time']->date;
					}
					else {
						$user_time = '';
					}
					$user_tz = 'Canada/Eastern';
				    $time = new DateTime($user_time,new DateTimeZone($user_tz));
				    $time = $time->format('d/m/y h:i');

					$full_name = $first_name.' '.$last_name.' - Time:'.$time;
					$check_attending .= t('<input type="checkbox" name ="chk_attending_'.$values->nid.'[]" value="'.$user_id.'"> <label>'.$full_name.'</label><br/>');
					if($check_attending == '')
					{
						$check_attending = 'Empty';
					}
				
					array_push($users_attending_name, $full_name);
				}
			}

			if(isset($node->field_wait_list['und']))
			{	$arr = $node->field_wait_list['und'];
				foreach ($arr as $key => $value) {
					$user_id = $value['target_id'];
					$profile = profile2_load_by_user($user_id,'learner_profile');
					if(isset($profile->field_first_name['und'][0]['value']))
					{
						$first_name = $profile->field_first_name['und'][0]['value'];
					}
					else
					{
						$first_name = '';
					}

					if(isset($profile->field_last_name['und'][0]['value']))
					{
						$last_name = $profile->field_last_name['und'][0]['value'];
					}
					else
					{
						$last_name = '';
					}
				
					$full_name = $first_name.' '.$last_name;
					$check_waiting .= t('<input type="checkbox"  name ="chk_waiting_'.$values->nid.'[]" value="'.$user_id.'"> <label>'.$full_name.'</label><br/>');
				
					array_push($users_waiting_name, $full_name);
				}
			}

			if($check_waiting == '')
			{
				$check_waiting = 'Empty';
			}

			if($check_attending == '') {
				$check_attending = 'Empty';
			}

			$users_attending_name = implode('<br>', $users_attending_name);
			$users_waiting_name = implode('<br>', $users_waiting_name);

			$form = '<form method="post" name="remove-user-'.$values->nid.'""  id="remove-user-'.$values->nid.'"" action="">
			<div class="users_name_attending"><h4>Users attending</h4>'.$check_attending.'</div><div class="users_name_waiting"><h4>Users Waiting</h4>'.$check_waiting.'</div>
			<input type="hidden" name ="buttonValue" value="emptylist">
			<input type="hidden" name ="nid" value="'.$values->nid.'">
			<br/><input type="button"  class="button remove-user" value ="Remove User">
			</form>';
			
			return $form;
			
		} 
}

?>