(function($) {
	
  $(function () 
  {
  	   
  	 $('.field-group-div h3, #field-document-uploads-values thead').click(function(e){

      $(this).parent().find('.field-name-field-learner-progress, .field-type-entityreference, .field-type-multifield, .field-type-field-collection, .field-type-text-with-summary').slideToggle();

     })

     $('.milestone-head, .registration-head').click(function(e){

      $(this).next('.milestone-container, .registration-container').slideToggle();

     })

      $('#block-system-main-menu li.expanded').find(' a.dropdown ').click(function(e){
         
            e.preventDefault();
            $(this).parent().find('ul.menu').slideToggle();

      });

      $('#responsive-alpha-search a').click(function(e){
        e.preventDefault();
        $('.alphabet').slideToggle();
      });
    
  });
	
})(jQuery);

