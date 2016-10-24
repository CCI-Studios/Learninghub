(function($) {
	
  $(function () 
  {
  	   
  	 $('#mobile-menu a').click(function(e){

      e.preventDefault();
      $('#block-system-main-menu').slideToggle();

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

