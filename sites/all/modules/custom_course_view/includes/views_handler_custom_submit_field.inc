<?php
class views_handler_custom_submit_field extends views_handler_field {

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

		global $user;

		if(arg(1) && arg(0) == 'learners-course-selection')
		{
			$existingUser = arg(1);
		}
		else
		{
			$existingUser = $user->uid;
		}
		
		$existing = user_load($existingUser);

		$flag = 0;
		$class_button = '';
		
		if (!function_exists('in_array_r'))
		{
			function in_array_r($needle, $haystack, $strict = false) {
			    foreach ($haystack as $item) {
			        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			            return true;
			        }
			    }

			    return false;
			}
		}

		$lhubAdmin = in_array('LHUB admin',$user->roles);
		$prac = in_array('practitioner', $user->roles);
		$admin = in_array('admin', $user->roles);
		$learner = in_array('learner', $user->roles);


		if(($prac == 0 || $lhubAdmin == 0 || $admin == 0 ) && arg(0) != 'course-list-content' || $learner == 1) {

			$target_id = array('target_id' => $values->nid);	
		
			$input = '';
			$time_v = '';
			$time_n = '';

			if(in_array_r($values->nid, $existing->field_courses_4) == true)
			{	
				$flag++;
				$button_value = 'Remove course';

				foreach ($existing->field_courses_live as $key => $value) {
				
					$id = [];
					$time = [];
					$group = [];

					foreach ($value as $key => $value) {
						
						  $data = explode(',', $value['value']);
						  $id1 = $data[0];
						  $time1 = $data[1];
						  $group1 = array('gp'=> array('id'=>$id1,'time'=>$time1));
						  array_push($group,$group1);

					}
				}
				if(isset($group))
				{
					foreach ($group as $key => $value) {
                  	
                  	if(in_array_r($values->nid, $value))
					{   
						$time = explode(' ',$value['gp']['time']);
						$datePart = explode('/',$time[0]);
						$datereverse = array_reverse($datePart);
						$dt = DateTime::createFromFormat('y', $datereverse[0]);
						$datereverse[0] = $dt->format('Y');
						$date = implode('-', $datereverse );					
						$date = new DateTime($date);
						$time_v = 'Selected time: '.$date->format('l, F d, Y').' '.$time[1].' '.$time[2];
						break;
					}
					else
					{
						$time_v = '';
					}

                  }
				}

				if(isset($values->_field_data['nid']['entity']->field_time['und']))
		  		{	
		  			$field_time = $values->_field_data['nid']['entity']->field_time['und'];
		  			$count = count($field_time);
		  		}
		  		else
		  		{
		  			$count = '';
		  		}

				if($values->_field_data['nid']['entity']->type == 'courses_live')
			  	{	
			  		
				  	$timestamp = strtotime($field_time[0]['value']); 
				  	$user_tz = new DateTimeZone('America/Toronto');  
					$offset = $user_tz->getOffset(new DateTime($field_time[0]['value']));
					$start_stamp = $timestamp + $offset;
		 	  		$time_n = format_date($start_stamp, 'custom', 'd/m/y h:i a');
		 	  		$time_display = format_date($start_stamp, 'custom', 'l, F d, Y h:i a');
		 	  		$time_n = 'Selected time: '.$time_display;
			  	}
			  	else
			  	{
				  	$time_n = '';
			  	}
			}
			else if(in_array_r($values->nid, $existing->field_courses_4) == false )
			{
				if($values->_field_data['nid']['entity']->type == 'courses')
				{
					$flag++;
				}
				if($values->_field_data['nid']['entity']->type == 'courses_live')
				{
					if(isset($values->_field_data['nid']['entity']->field_class_size['und'][0]['value']))
					{
						$class_size = $values->_field_data['nid']['entity']->field_class_size['und'][0]['value'];
					}

					if(!empty($values->_field_data['nid']['entity']->field_users_attending))
					{	
						$users_attending = count($values->_field_data['nid']['entity']->field_users_attending['und']);

						foreach ($values->_field_data['nid']['entity']->field_users_attending['und'] as $key => $value) {
							//echo $values->nid;
							//print_r($value);
						}

						if($class_size == $users_attending)
						{
							$button_value = 'Add to wait list';
						}
						else
						{
							$button_value = 'Add course';
						}
					}
					else
					{
						$button_value = 'Add course';
					}
				}
				else
				{
					$button_value = 'Add course';
				}

				if(isset($values->_field_data['nid']['entity']->field_time['und']))
				{	
					if(count($values->_field_data['nid']['entity']->field_time['und']) >= 1)
					{	
						$input = '<label>Select class time: </label>';
						foreach ($values->_field_data['nid']['entity']->field_time['und'] as $key => $value) {
							$user_tz = new DateTimeZone('America/Toronto');  
							$offset = $user_tz->getOffset(new DateTime($value['value']));
							$start_stamp = strtotime($value['value']) + $offset;
				 	  		$time = format_date($start_stamp, 'custom', 'd/m/y h:i a');
				 	  		$time_display = format_date($start_stamp, 'custom', 'l, F d, Y h:i a');
				
				 	  		if(time() < $start_stamp)
				 	  		{	
								$input .= t('<input type="radio" class="select-time" required name="radio_time" value="'.$time.'"> '.$time_display.'<br/>');
				 	  		}
						}
					}
					else
					{
						$input = '';
					}

					if(count($values->_field_data['nid']['entity']->field_time['und']) >= 1 )
					{	
						foreach ($values->_field_data['nid']['entity']->field_time['und'] as $key => $value) {
							$user_tz = new DateTimeZone('America/Toronto');  
							$offset = $user_tz->getOffset(new DateTime($value['value']));
							$start_stamp = strtotime($value['value']) + $offset;
				 	  		$time = format_date($start_stamp, 'custom', 'd/m/y h:i a');
				 	  		$time_display = format_date($start_stamp, 'custom', 'l, F d, Y h:i a');
				
				 	  		if(time() < $start_stamp)
				 	  		{			
								$flag++;
				 	  		}
						}	
					}
				}
			}

			if(!$input)
			{
				$input = '';
			}

			if(!$time_v)
			{
				$time_v = $time_n;
			}
			else if(!$time_v)
			{
				$time_v = '';
			}

			if(in_array('learner', $user->roles) && $button_value == 'Remove course')
			{
				$flag = 0;
			}

			if($button_value == 'Remove course')
				{ $class_button = 'remove-course-icon'; 
				} else 
				{ $class_button = "add-course-icon"; }

			if($flag>0)
			{
				$form = '<span class="selected-time">'.$time_v.'</span><form method="post" id="course-select" action="/course-selection/'.$existingUser.'">
				<input type="hidden" name ="nid" value="'.$values->nid.'">'.$input.'
				<br/><span class='.$class_button.'></span><input type="submit" name ="buttonValue" value ="'.$button_value.'">
				</form>';
			}
			else
			{
				$form = '<span class="selected-time">'.$time_v.'</span>';
			}
			
			return $form;
			
		} 
	}
};

?>