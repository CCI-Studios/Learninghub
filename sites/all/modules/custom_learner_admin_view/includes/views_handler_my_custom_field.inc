<?php
class views_handler_my_custom_field extends views_handler_field {

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

			
			$user_id = $values->profile_users_uid;
			$profiles = profile2_load_by_user($user_id,'plan_content');
			
	
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
					if(isset($node->field_milestone_outcome['und'][0]['value']) && $node->field_milestone_outcome['und'][0]['value'] == 'Activities Completed')
					{	
						$activities_completed++;
					}

					if(isset($node->field_milestone_outcome['und'][0]['value']) && $node->field_milestone_outcome['und'][0]['value'] == 'In Progress')
					{
						$in_progress++;
					}
				
					if(isset($node->field_milestone_outcome['und']))
					{	
						if(($node->field_milestone_outcome['und'][0]['value'] == 'Attained' || $node->field_milestone_outcome['und'][0]['value'] == 'Attained in Classroom') )
						{	
							if(isset($node->field_milestone1['und']))
							{	
								if($node->field_milestone1['und'][0]['value'] != 0)
								{
									$date1 = strtotime($node->field_milestone_attempt_date['und'][0]['value']);

									$date2 = strtotime('+3 month January'.date('Y'));
									$prevYear = date("Y",strtotime("-1 year"));
									$date3 = strtotime('+3 month January'.$prevYear );
							
									if($date1 < $date2)
									{
										array_push($activities_completed_fiscal, $node->field_milestone1['und'][0]['value']);
									}
								}
								
							}
							
							
						}
					}
				}

		     $activities_completed_fiscal = count(array_unique($activities_completed_fiscal));

		    }
	    	else
		    {	
		    	$activities_completed_fiscal = 'N/A';
		    	$activities_completed = 'N/A';
		    	$in_progress = 'N/A';
		    	$milestones_identified = 'N/A';
		    }

			return t($activities_completed_fiscal);
			
	}
};

?>