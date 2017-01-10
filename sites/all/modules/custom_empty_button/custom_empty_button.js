(function ($) {
  Drupal.behaviors.custom_empty_button = {
    attach:function (context) {
  
    $('.view-live-courses-users .views-row').each(function(){

    var id = $(this).find('form').attr('id');

      $(this).find('input.button').click(function(e){
      var postData = $('#'+id).serialize();
      var  current_row = $(this).parents('.views-row');
      $(this).parents('.views-row').append('<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;</div></div>');

      var request = $.ajax({
           type: "POST",
           url: '/empty-course-users',
           data: postData,
           dataType : 'json',
           success: function(msg) {
             console.log(msg);
             var arr = msg.split(',');
             console.log($(this));  
             $.each( arr, function(index,value){

             $('input[value='+value+']').next().next('br').remove();
             $('input[value='+value+']').next('label').remove();
             $('input[value='+value+']').remove();
             $('.ajax-progress').remove();
             $(current_row).append('<div class="messages status">Selected users are removed</div>');

             })
           
          }
        }); 

      
       
        });
    });


      
    }
  }
})(jQuery);