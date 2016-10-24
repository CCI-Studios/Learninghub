<?php
/**
 * Implements hook_views_data().
 */
function custom_milestone_inprogress_field_views_data() {
  $data['custom_milestone_inprogress_field']['table']['group'] = t('My custom milestone in progress');
  $data['custom_milestone_inprogress_field']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_milestone_inprogress_field']['milestone_inprogress'] = array(
    'title' => t('Milestone In Progress'),
    'help' => t('Milestone In Progress'),
    'field' => array(
      'handler' => 'views_handler_milestone_inprogress',
    ),
  );
 
  return $data;
}


