(function($) {

    var timer = null;
    var maxHeight = 0;
    min = 0;

    $(function(){

        var navigation = $('<div class="form-navigation">'+
        '<div class="prev btn btn-info pull-left">&lt; Previous</div>'+
        '<div class="next btn btn-info pull-right">Next &gt;</div>'+
        '<span class="clearfix"></span>'+
        '</div>');
        $('#edit-account').insertAfter('#edit-profile-learner-profile-field-other-phone');

        $('#user-register-form .wizard .form-wrapper').each(function(i,value){

            if($(this).find('.description').length > 0)
            {
                $(this).prepend('<span class="help-button">Help</span>');
            }

            var err = $(this).find('label').text();  
            $(this).find('input.required,select.required, .form-type-radios, .form-type-checkboxes,#edit-profile-learner-profile-field-date-of-birth-und-0-value-datepicker-popup-0').parent().append('<span class="error-msg">Please fill the '+err+'</span>');
            
        });

        $(navigation).insertAfter('#user-register-form');
        $('#user-register-form #edit-actions').appendTo('#user-register-form .group-consent');

        $('.form-navigation .next').click(function(){
            next();
        });
         $('.form-navigation .prev').click(function(){
            prev();
        });
         $('.wizard .form-submit').click(function(){
      
            if(validate() != 0)
            {
              return false;
            }
        });

        createIndicators();
        setActive(0);
       // timer = setInterval(next, 2000);
        $(window).resize(function(){
            maxHeight = 0;
            fixHeight();
        });
        //fixHeight();
       // setTimeout(fixHeight, 200);
        $('#user-register-form').validate();
    });

    $(window).load(fixHeight);

    function container()
    {
        return $("#user-register-form");
    }
    function rows()
    {
        return container().find(".wizard");
    }
    function indicators()
    {
        return container().find(".indicators li");
    }
    function getActive()
    {
        var activeRow = rows().filter(".active");
        return activeRow;

    }
    function getActiveIndex()
    {
        var index = getActive().index()+1;
     
        return index;
    }
    function setActive(i)
    {   
        var current = getActive();      
        current.addClass("outbound").removeClass("active");
        rows().eq(i).addClass("active").hide().fadeIn(function(){
        current.removeClass("outbound").hide();

        });
       // fixHeight();

        indicators().removeClass("active").eq(i).addClass("active");
        if(rows().eq(0).hasClass('active'))
        {
            $(".form-navigation .prev").hide();
        }
    }
    function fixHeight()
    {
        var h = getActive().find(".bg-image").height();
        if (h > maxHeight)
        {
            rows().height(h).parent().height(h);
            maxHeight = h;
        }
    }
    function max()
    {
        return rows().length;
    }

     var flag = 0;
     var confirm_email = '#edit-profile-learner-profile-field-confirm-email-address-und-0-value';
     function validate()
     {  

        flag =0;
       
        $('span.error-msg').hide();

        $('#user-register-form .wizard.active .form-wrapper').each(function(i,value){
            
           $(this).find('input.required').removeClass('error');
           var val = $(this).find('input.required').val();
           var regex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;

           var postal_regex = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/;


           if($(this).find('#edit-mail').length > 0)
           {
                if(!regex.test($(this).find('#edit-mail').val()))
                {
                    $(this).find('.form-item-mail span.error-msg').text('Please fill the correct email address');
                    $(this).find('.form-item-mail span.error-msg').show();
                    flag++;
                }   

               console.log($(this));
           }

           if($(this).find('input').length > 0)
           {  

            var postal = $('#edit-profile-learner-profile-field-postal-code-und-0-value').val();
            console.log(postal_regex.test($(this).find('#edit-profile-learner-profile-field-postal-code-und-0-value').val()));
            var match = postal_regex.exec(postal);
            if (!match){
                $('.field-name-field-postal-code span.error-msg').text('Please fill the correct postal code');
                $('.field-name-field-postal-code span.error-msg').show();
                flag++;
            }
           }

            if($(this).find(confirm_email).length > 0)
            {
                if($(this).find(confirm_email).val() != $('#edit-mail').val())
                {   
                    $(confirm_email).parent().find('span.error-msg').show();
                    $(confirm_email).addClass('error');
                    $(confirm_email).parent().find('span.error-msg').text('Email do not match');
                    flag++;
                }
                console.log('email confirm');
            }
          

           if(val=='')
           { 
             $(this).find('input.required').addClass('error');
             $(this).find('span.error-msg').show();
             flag++;

              console.log('value null');
           }

           var date = '#edit-profile-learner-profile-field-date-of-birth-und-0-value-datepicker-popup-0'
           if($(date).val() == '')
           {  
             $(date).addClass('error');
             flag++;
           }

           if($(this).find('select.required'))
           {   
              var sel = $(this).find('select.required').val();
              if(sel == '_none')
              {
                  $(this).find('select.required').addClass('error');
                  $(this).find('span.error-msg').show();
                   console.log('select required');
                  flag++;
              }
           }

           if($(this).find('.form-type-radios').length > 0)
           {
                if (!$(this).find("input").is(':checked')) {
              $(this).find('span.error-msg').show();
              console.log('radio erroor');
              flag++
            }
           }

           if($(this).find('.form-type-checkboxes').length > 0)
           {
              
            if (!$(this).find("input").is(':checked')) {
              $(this).find('span.error-msg').show();
              console.log('check erroor');
              flag++
            }

            if($(this).find('#edit-profile-learner-profile-field-where-will-you-be-working-und-contact-north-site-you-must-enter-the-site-name-below').is(':checked'))
            {   
                $('#edit-profile-learner-profile-field-site-name-und-0-value').attr('required','required');
                $('#edit-profile-learner-profile-field-site-name-und-0-value').addClass('required');
            }

           }

        });

        return flag;
     }
    function next()
    {     
        
       if(validate() === 0)
       {      
          var i = getActiveIndex();
          if(hasNext(i))
          {
            i++;
            setActive(i-1);
            hideArrows(i);
          }
          $("html, body").animate({ scrollTop:  $('.ind-container').offset().top }, "slow");
       }
       else
       {
        $("html, body").animate({ scrollTop:  $('.wizard.active span.error-msg').first().offset().top }, "slow");
       }
    }

    function prev()
    {  
        var i = getActiveIndex();
        if (hasPrevious(i))
        {
            i--;
            setActive(i-1);
            hideArrows(i+1);
        }
         
    }
    function stop()
    {
        clearInterval(timer);
    }

    function hasPrevious(i)
    {
        if (i - 1 == min)
        return false;
        return true;
    }
    function hasNext(i)
    {
        if (i + 1 > max())
        return false;
            
        return true;
    }

    function createIndicators()
    {
        container().prepend("<div class='ind-container'><ul class='indicators' />");
        var indicatorsContainer = container().find(".indicators");
    
        rows().each(function(i){
            indicatorsContainer.append("<li><a href='#' aria-label='Slide "+(i+1)+"'  data-indicator='"+(i+1)+"'><span class='sr-only'>"+(i+1)+"</span></a></li>");
        });
        indicatorsContainer.find("a[data-indicator]").click(function(){
            indicatorClick($(this).data("indicator"));
            return false;
        });
    }
    function indicatorClick(i)
    {   
       if(validate() === 0)
       { 
        console.log(i);
        setActive(i-1);
        hideArrows(i); 
      }
        
    }

     function hideArrows(i)
    {
        var $btnPrevious = $(".form-navigation .prev");
        var $btnNext = $(".form-navigation .next");

        if (hasPrevious(i))
        {
            $btnPrevious.show();
        }
        else
        {
            $btnPrevious.hide();
        }

        if (hasNext(i))
        {
            $btnNext.show();
        }
        else
        {
            $btnNext.hide();
        }
    }
})(jQuery);

