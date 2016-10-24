(function($) {
	
  $(function () 
  { 

      $('#block-views-slideshow-block .view-content').slick(
      { infinite: true,
        arrows: false,
        dots: true,
        autoplay: true,
        pauseOnHover: true
      }); 
  	   
  	  var img = $('.views-field-field-image-slideshow img').attr('src');
  	  $('.views-field-field-image-slideshow img').remove();
  	  $('#block-views-slideshow-block').css('background-image','url('+img+')');

  	  $('#edit-profile-learner-profile-field-where-will-you-be-working-und-contact-north-site-you-must-enter-the-site-name-below').click(function(){
  	    if ($('#edit-profile-learner-profile-field-where-will-you-be-working-und-contact-north-site-you-must-enter-the-site-name-below').attr('checked')) {
  	       console.log('click');
  	       $('#edit-profile-learner-profile-field-site-name-und-0-value').attr('required','required');
  	    }
      }) 

    var img = $('.page-node .field-name-field-banner img').not('.page-node-2 .field-name-field-banner img').attr('src');
    $('.node-type-page .field-name-field-banner img').not('.page-node-2 .field-name-field-banner img').remove();
    $('.node-type-page #header').not('.page-node-2 #header').css({"background-image":'url(' + img + ')'});

    $('.page-node-2 #page-title').insertBefore('#content');

     var img1 = $('.page-node-2 .field-name-field-banner img').attr('src');
    $('.page-node-2 .field-name-field-banner img').remove();
    $('.page-node-2 #page-title').css({"background-image":'url(' + img1 + ')'});

     var img1 = $('#block-block-6 img').attr('src');
    $('#block-block-6 img').remove();
    $('#block-block-6').css({"background-image":'url(' + img1 + ')'});
    
  });
	
})(jQuery);

