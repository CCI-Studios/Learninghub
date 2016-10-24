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

			if(in_array_r($values->nid, $existing->field_courses_4) == true)
			{
				$button_value = 'Remove course';
			}
			else if(in_array_r($values->nid, $existing->field_courses_4) == false )
			{

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

			}

			
			return t('<form method="post" action="/course-selection">
			<input type="hidden" name ="buttonValue" value="'.$button_value.'">
			<input type="hidden" name ="nid" value="'.$values->nid.'">
			<input type="submit" value ="'.$button_value.'">
			</form>');
			
		} 
	}
};

?>