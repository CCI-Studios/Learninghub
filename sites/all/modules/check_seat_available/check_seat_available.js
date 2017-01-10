(function ($) {
  Drupal.behaviors.check_seat_available = {
    attach:function (context) {
  
    $('.views-field-custom-submit-field input[type="radio"]').change(function(){

        $(this).attr('checked','checked');
        var time = $(this).val();
        var button = $(this).parent('form').find('input[type="submit"]');
        var postData = $(this).parent('form').serialize();
        postData += "&time="+time;
        button.hide();
        $(this).parent('form').append('<div class="ajax-progress ajax-progress-throbber"><div class="throbber"></div>Looking for the seat availability</div>');

        var request = $.ajax({
             type: "POST",
             url: '/check-seat-available',
             data: postData,
             dataType : 'json',
             success: function(msg) {
              button.show();
              button.val(msg);
              $('.ajax-progress').remove();
              console.log(msg);
            },
            error: function(err)
            {
              console.log(err);
            }
          }); 
        });
    }
  }
})(jQuery);