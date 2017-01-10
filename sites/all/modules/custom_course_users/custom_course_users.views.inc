<?php
/**
 * Implements hook_views_data().
 */
function custom_course_users_views_data() {
  $data['custom_course_users']['table']['group'] = t('custom empty button');
  $data['custom_course_users']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_course_users']['custom_empty_button_field'] = array(
    'title' => t('custom empty button'),
    'help' => t('empty button'),
    'field' => array(
      'handler' => 'views_handler_custom_empty_button_field',
    ),
  );
 
  return $data;
}

