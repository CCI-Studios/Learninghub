(function($) {
	
  $(function () 
  {
  	   
  	 $('.field-group-div h3, #field-document-uploads-values thead').click(function(e){

        $(this).toggleClass('polygon-up');
        $(this).parent().find('.field-name-field-learner-progress, .field-type-entityreference,'+
          ' .field-type-multifield, .field-type-field-collection, .field-type-text-with-summary, .referral-container').toggleClass('show');

     })

     $('#edit-expand-all').click(function(e){
        e.preventDefault();
        $('.milestone-container, .registration-container, .field-name-field-learner-progress,' +
         '.field-type-entityreference, .field-type-multifield, .field-type-field-collection,'+
         '.field-type-text-with-summary, .old-referral .referral-container').addClass('show');
     });

     $('#edit-collapse-all').click(function(e){
        e.preventDefault();
        $('.milestone-container, .registration-container, .field-name-field-learner-progress,'+
          '.field-type-entityreference, .field-type-multifield, .field-type-field-collection,'+
           '.field-type-text-with-summary, .old-referral .referral-container').removeClass('show');
     });

     $('.milestone-head, .registration-head').click(function(e){

        $(this).find('h2').toggleClass('polygon-up');
        $(this).next('.milestone-container, .registration-container').toggleClass('show');

     });

      $('#responsive-alpha-search a').click(function(e){

          e.preventDefault();
          $('.alphabet').slideToggle();
      });

      /* Fixed Bar portal*/

      $(window).scroll(function(){

           $action_bar = $('#block-system-main').offset().top;

           if ($(window).scrollTop() > $action_bar) {
                $('#action-bar').addClass('fixed');
            } else {
                $('#action-bar').removeClass('fixed');
            }
      });

      /* Wrapping old referral fields*/

      $('.old-referral .field-widget-text-textfield').wrapAll('<div class="referral-container">')
    
  });
	
})(jQuery);

