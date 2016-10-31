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
		$existingUser = $user->uid;
		$existing = user_load($user->uid);

		node_load();
		$flag = 0;
	
		if(in_array('learner',$user->roles)) {
			$target_id = array('target_id' => $values->nid);
		
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

			$input = '';
			$time_v = '';

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
						$time_v = 'Selected time: '.$value['gp']['time'];
						break;
					}
					else
					{
						$time_v = '';
					}

                  }
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
					if(count($values->_field_data['nid']['entity']->field_time['und']) > 1)
					{	
						$input = '<label>Select class time: </label>';
						foreach ($values->_field_data['nid']['entity']->field_time['und'] as $key => $value) {
							$user_tz = new DateTimeZone('America/Toronto');  
							$offset = $user_tz->getOffset(new DateTime($value['value']));
							$start_stamp = strtotime($value['value']) + $offset;
				 	  		$time = format_date($start_stamp, 'custom', 'd/m/y h:i a');
				
				 	  		if(time() < $start_stamp)
				 	  		{	
								$input .= t('<input type="radio" name ="radio_time" value="'.$time.'"> '.$time.'<br/>');
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
				$time_v = '';
			}

			if($flag>0)
			{
				$form = '<span class="selected-time">'.$time_v.'</span><form method="post" action="/course-selection">
				<input type="hidden" name ="buttonValue" value="'.$button_value.'">
				<input type="hidden" name ="nid" value="'.$values->nid.'">'.$input.'
				<br/><input type="submit" value ="'.$button_value.'">
				</form>';
			}
			else
			{
				$form = '';
			}
			
			
			return $form;
			
		} 
	}
};

?>