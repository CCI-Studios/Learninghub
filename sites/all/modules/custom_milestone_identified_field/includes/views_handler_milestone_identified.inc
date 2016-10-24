<?php
class views_handler_milestone_identified extends views_handler_field {

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

			$user_id = $values->uid;
			$profiles = profile2_load_by_user($user_id,'plan_content');
			
         	if(isset($profiles->field_learner_plan1['und']))
		    {
				$count = count($profiles->field_learner_plan1['und']);
				for($i=0; $i < $count; $i++)
				{
					$field[] = $profiles->field_learner_plan1['und'][$i]['target_id'];
				}

				$nodes = node_load_multiple($field);

				$activities_completed_fiscal = [];
				$milestones_identified = [];
				$activities_completed_array = [];
				$in_progress = [];

				foreach($nodes as $node)
				{	
					if(isset($node->field_milestone1['und']))
					{
						if($node->field_milestone1['und'][0]['value'] != 0)
						{
							array_push($milestones_identified, $node->field_milestone1['und'][0]['value']);
						}
					}
				}
			
			    $milestones_identified = count(array_unique($milestones_identified));
		    }
	    	else
		    {	
		    	$milestones_identified = 'N/A';
		    }

			return t($milestones_identified);
			
	}
};

?>