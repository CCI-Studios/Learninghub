<?php
/**
 * Implements hook_views_data().
 */
function custom_milestone_identified_field_views_data() {
  $data['custom_milestone_identified_field']['table']['group'] = t('My custom milestone identified');
  $data['custom_milestone_identified_field']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_milestone_identified_field']['milestone_identified'] = array(
    'title' => t('Milestone Identified'),
    'help' => t('Milestone Identified'),
    'field' => array(
      'handler' => 'views_handler_milestone_identified',
    ),
  );
 
  return $data;
}


