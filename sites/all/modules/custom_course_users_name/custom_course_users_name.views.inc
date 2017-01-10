<?php
/**
 * Implements hook_views_data().
 */
function custom_course_users_name_views_data() {
  $data['custom_course_users_name']['table']['group'] = t('custom users name');
  $data['custom_course_users_name']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_course_users_name']['custom_users_name_field'] = array(
    'title' => t('custom users name'),
    'help' => t('users name'),
    'field' => array(
      'handler' => 'views_handler_custom_users_name_field',
    ),
  );
 
  return $data;
}

