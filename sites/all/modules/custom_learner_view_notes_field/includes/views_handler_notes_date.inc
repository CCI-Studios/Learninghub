<?php
class views_handler_notes_date extends views_handler_field {

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
			

  		  	if(isset($profiles->field_progress_notes1['und']))
		    {
				$count = count($profiles->field_progress_notes1['und']);
				for($i=0; $i < $count; $i++)
				{
					$field[] = $profiles->field_progress_notes1['und'][$i]['target_id'];
				}

				$nodes = node_load_multiple($field);

				$mostRecent = 0;
				$flag = 0;
				
				foreach($nodes as $node)
				{	
					if(isset($node->field_date['und'][0]['value']))
					{
						 $curDate = $node->field_date['und'][0]['value'];
						  if ($curDate > $mostRecent) {
						    $mostRecent = $curDate;
						  }

						  $flag++;
					}
					else
					{	
						$mostRecent = 'N/A';
					}
				}

				if($flag)
				{
					$user_tz = 'Canada/Eastern';
			 	  	$mostRecent =  new DateTime($mostRecent, new DateTimeZone($user_tz));
		    		$mostRecent = $mostRecent->format('d/m/y');
				}
		    }
		    else
		    {
		    	$mostRecent = 'N/A';
		    }
	    	
			return t($mostRecent);
			
			
		
	}
};

?>