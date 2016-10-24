<?php
/**
 * Implements hook_views_data().
 */
function custom_course_view_views_data() {
  $data['custom_course_view']['table']['group'] = t('My custom module');
  $data['custom_course_view']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['custom_course_view']['custom_submit_field'] = array(
    'title' => t('My custom field'),
    'help' => t('My custom field displays the word "Some custom markup".'),
    'field' => array(
      'handler' => 'views_handler_custom_submit_field',
    ),
  );
 
  return $data;
}

function custom_course_view_views_post_execute(&$view) {

   global $user;

   $existingUser = $user->uid;

   if ($view->name == 'courses_list_content_view') {
   
    $existing = user_load($user->uid);
     $count_remove = 0;
     $count_remove_taken = 0;
    foreach ($view->result as  $key => &$result) {
      // Replace all empty fields with the dash '-' character.
     $values = $view ->result[$key];
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

        if ($view->current_display == 'page' || $view->current_display == 'page_1')
        {

            if(in_array_r($values->nid, $existing->field_courses_4) == true)
            {
              unset($view ->result[$key]);
            }
        }
        
        if ($view->current_display == 'page_2' || $view->current_display == 'page_3') {

           if(in_array_r($values->nid, $existing->field_courses_4) == false)
           {  
              $count_remove++;
              unset($view ->result[$key]);
           }
        }
      } 
    }
    
  }

}
