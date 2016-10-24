<?php
/**
 * Implements hook_views_data().
 */
function custom_milestone_completed_field_views_data() {
  $data['custom_milestone_completed_field']['table']['group'] = t('My custom milestone completed');
  $data['custom_milestone_completed_field']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_milestone_completed_field']['milestone_completed'] = array(
    'title' => t('Milestone Completed'),
    'help' => t('Milestone Completed'),
    'field' => array(
      'handler' => 'views_handler_milestone_completed',
    ),
  );
 
  return $data;
}


