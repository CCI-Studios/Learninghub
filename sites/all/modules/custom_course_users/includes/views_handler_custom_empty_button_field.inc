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
			if(isset($node->field_users_attending['und']))
			{	$arr = $node->field_users_attending['und'];
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