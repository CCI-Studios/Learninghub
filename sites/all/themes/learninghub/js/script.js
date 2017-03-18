(function($) {


    $(function(){ 

 
    	
	 	$('.view-faq .views-field-title,.view-what-you-need .views-field-title, .view-using-learning-hub .views-field-title, .view-learner-stories .views-field-title').click(function(){
	        $(this).next().slideToggle();
	        $('.view-faq .views-field-body, .view-what-you-need .views-field-body, .view-using-learning-hub .views-field-body, .view-learner-stories .views-field-body').not($(this).next()).slideUp();
	        $('.view-faq .views-field-title, .view-what-you-need .views-field-title, .view-using-learning-hub .views-field-title,  .view-learner-stories .views-field-body').not($(this)).removeClass('open');
	        $(this).toggleClass('open');
	    });

	 	$('.view-learner-plan .views-row').each(function(){

	 		var div = $(this).find('.field-name-field-activity');
	    	$(this).find('.views-field-description').insertBefore(div);

	 	})

	    $('.view-learner-plan .views-row').click(function(){
	        $(this).find('.field-name-field-activity').slideToggle();
	        $('.view-learner-plan .field-name-field-activity').not($(this).find('.field-name-field-activity')).slideUp();
	        $('.view-learner-plan .views-row').not($(this)).removeClass('open');
	        $(this).toggleClass('open');
	    });

	    /*move learner plan classes link after export button*/

	    $('.view-footer').insertAfter('.feed-icon');

	    $('.view-learner-plan .feed-icon a').click(function(e){

	    	e.preventDefault();
	    	window.print();

	    });

	    /* move export buttons in course catalogue*/


	     $('.view-courses-list-content-view .view-header').insertAfter('.view-courses-list-content-view .view-filters');

	    /* move footer inside view content course category*/

	    $('.view-course-category .view-header').appendTo('.view-course-category .view-content').addClass('views-row');

	    /*mailchimp*/

	    $('.mailchimp-signup-subscribe-form .form-type-radio').append('<label class="option unsubscribe">(You may unsubscribe at anytime)</label>');

        $('#edit-mergevars-email').attr('required','required');

        if (!$('.mailchimp-signup-subscribe-form').find("input").is(':checked')){
          $('.mailchimp-signup-subscribe-form span.error-msg').show();
        }
		var flag = true;
		$('.mailchimp-signup-subscribe-form .form-submit').click(function(e){

   
      		var email = $("#edit-mergevars-email").val();
	  
			var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			var valid = emailReg.test(email);
	
	
           if(!valid)
           {	
           	 $('.email-error').remove();
   		  	 $('.form-item-mergevars-EMAIL').append('<span class="email-error">PLease fill the email correctly</span>');
             return false;
            
           }
           else
           {
           	return true;
           }
          
      	});
        
	    /*Placeholder learner portal list*/
	    $('#edit-combine').attr('placeholder','search');
	    $('#views-exposed-form-learners-list-page .form-type-textfield input').attr('placeholder','Search');

	    var pathname = window.location.pathname;
	    path = pathname.split("/");
	    path = path[2];

	    //$('.feed-icon a').text('export');
	    $('.view-learner-plan .feed-icon a').text('print');

	    $('.wizard').eq(3).addClass('goals');

	    $('.form-type-markup').appendTo('.wizard.goals');

	    $('.view-learner-plan .view-header a.expand-all').click(function(e){

	    	 e.preventDefault();
	    	 $('.field-name-field-activity').slideDown();
	    	 $(this).addClass('hide');
	    	 $(this).parent().find('.hide-all').removeClass('hide');

	    });

	    $('.view-learner-plan .view-header a.hide-all').click(function(e){

	    	 e.preventDefault();
	    	 $('.field-name-field-activity').slideUp();
	    	 $(this).addClass('hide');
	    	 $(this).parent().find('.expand-all').removeClass('hide');

	    })

	   	var exportButton = '<div class="exportButton"><a href="/learner-plan-view-export/'+path+'/learner_plan.csv" class="form-submit-export">Export</a></div>';
	 	var divHeader = '<div class="header-label"><span>Approval</span><span>Start Date</span><span>End Date</span><span>Outcome</span><span>Learning Activity</span></div>'

        var search = '<div class="view-filters"><input id="notes-search" type="text" placeholder="Search progress notes" value=""></div>';

        $('#edit-profile-plan-content-field-learner-plan1').append(exportButton);
        $('.group-pg-notes').prepend(search);
	    $('.field-name-field-activity').prepend(divHeader);


		var note_search = $('.inline-entity-form-node-title,.inline-entity-form-node-field_description .field-item');

		/* Progress notes search*/

		$('#notes-search').keyup(function(){

			var takeLetter = $(this).val().toLowerCase();
		  	note_search.each(function(i) {

		        var text = $(this).text().toLowerCase();
		        console.log(text.indexOf(takeLetter));
		        (text.indexOf(takeLetter) > -1) ? $(this).parents('tr.ief-row-entity').fadeIn(222) : $(this).parents('tr.ief-row-entity').hide(); 
		    
		    });

		});

   	 /* Alphabetical Filter*/


        var alphabetFilter = '<ul class="alphabet">'+
 	 				'<li><a>A</a></li>'+
                    '<li><a>B</a></li>'+
                    '<li><a>C</a></li>'+
                    '<li><a>D</a></li>'+
                    '<li><a>E</a></li>'+
                    '<li><a>All</a></li>'+
                '</ul>';

        if($(window).width() < 767)
	    {
	    	 var alphabetFilterDrop = '<div id="responsive-alpha-search"><a href="#">Alphabetical Search</a></div>'
	    }

     	$(alphabetFilter).insertBefore('#edit-profile-plan-content-field-learner-plan1');

     	//$(alphabetFilterDrop).insertBefore('.view-learners-list .alphabet');
     	//$('.view-learners-list .alphabet').appendTo('#responsive-alpha-search');
 		var triggers = $('ul.alphabet li a');
		var filters = $('.field-name-field-task-group-level-indicator .field-item');

		triggers.click(function() {

			$('ul.alphabet li').removeClass('active');
			$(this).parent().addClass('active');
		    var takeLetter = $(this).text().toLowerCase();
		    filters.parents('tr.ief-row-entity').hide();
		    
		    filters.each(function(i) {
		        var compareFirstLetter = $(this).text().substr(0,1).toLowerCase();
		        if ( compareFirstLetter ==  takeLetter ) {

		        	$('.no-result').hide();
		            $(this).parents('tr.ief-row-entity').fadeIn(222);
		        }
		        else if(takeLetter === 'all')
		        {	
		        	$('.no-result').hide();
		        	$('tr.ief-row-entity').fadeIn(222);
		        }
		  
		    });
		});
    /* Progress notes sort alphabetically */


	 var notes = '.group-pg-notes tr.ief-row-entity';
	$(".group-pg-notes tr.ief-row-entity").sort(function(a,b){
		console.log(new Date($(notes+' .field field-name-field-date span').text()) < new Date($(notes+' .field field-name-field-date span').text()));
	    return new Date($(notes+' .field field-name-field-date span').text()) < new Date($(notes+' .field field-name-field-date span').text());
	}).each(function(){
		console.log(this);
	    $(".group-pg-notes tbody").append(this);
	})

	var plan = '.field-name-field-learner-plan1 tr.ief-row-entity';
	$(plan).sort(asc_sort).appendTo('.field-name-field-learner-plan1 tbody');

	function asc_sort(a, b){
	    return ($(b).find('.field-name-field-task-group-level-indicator .field-item').text().toLowerCase()) < ($(a).find('.field-name-field-task-group-level-indicator .field-item').text().toLowerCase()) ? 1 : -1;    
	}

	$(document).ajaxComplete(function(event, xhr, settings) {

		$('.ief-row-form').each(function(i){
			$(this).addClass('row-form-'+i);
			$(this).prev('.ief-row-entity').addClass('row-form-'+i);
		})

	 	var plan = '.field-name-field-learner-plan1 #ief-entity-table-edit-profile-plan-content-field-learner-plan1-und-entities > tbody > tr.ief-row-entity';
		$(plan).sort(asc_sort).appendTo('.field-name-field-learner-plan1 #ief-entity-table-edit-profile-plan-content-field-learner-plan1-und-entities > tbody');

		function asc_sort(a, b){
		    return ($(b).find('.field-name-field-task-group-level-indicator .field-item').text().toLowerCase()) < ($(a).find('.field-name-field-task-group-level-indicator .field-item').text().toLowerCase()) ? 1 : -1;    
		}

		$('.ief-row-form').each(function(i){
			
			if($(this).hasClass('row-form-'+i))
			{
				$(this).insertAfter('.ief-row-entity.row-form-'+i);
			}
		})

	});

	
	 /* Add dropdown arrow*/
	 $('#block-system-main-menu li.expanded').append('<a href="#" class="dropdown">Drop</a>');

	 /*registration page information*/
	 $('.username-thankyou').insertAfter('.info-block');
	
	   //course selection block active
	   if(window.location.pathname.split('/')[1] == 'course-list-content' || window.location.pathname.split('/')[1] == 'current-courses')
	   {
   		   var id =  window.location.pathname.split('/')[2];
	   }
	   else
	   {
		   var id =  window.location.pathname.split('/')[3];
	   }

	   $('.view-course-category .search-all').addClass('selected');

	   $('.view-course-category .view-content').each(function(){

	   if($(this).find("a[href*="+id+"]").length > 0)
	   {  
	       $('.view-course-category .search-all').removeClass('selected');
	   	   $(this).find("a[href*="+id+"]").addClass('selected');
	   }

   });
   		
	});


	Drupal.behaviors.module = {
  attach: function() {

    var events = $('#profile-plan-content-field-referral-add-more-wrapper .form-submit').data('events'); // Get the jQuery events.
    $('.confirm').unbind('click'); // Remove the click events.
    $('.confirm').click(function () {
     console.log('hello');
        $.each(events.click, function() {
          this.handler(); // Invoke the click handlers that was removed.
        });
     
      // Prevent default action.
      return false;
    });
  }
}

	Drupal.behaviors.yourBehaviorName = {
	    attach: function (context, settings) {
    	var count = 0;
    	var triggers = $('ul.alphabet li a');
		var filters = $('.field-name-field-task-group-level-indicator .field-item');

	      triggers.click(function() {

			$('ul.alphabet li').removeClass('active');
			$(this).parent().addClass('active');
		    var takeLetter = $(this).text().toLowerCase();
		    filters.parents('tr.ief-row-entity').hide();
		    
		    filters.each(function(i) {
		        var compareFirstLetter = $(this).text().substr(0,1).toLowerCase();
		        if ( compareFirstLetter ==  takeLetter ) {

		        	$('.no-result').hide();
		            $(this).parents('tr.ief-row-entity').fadeIn(222);
		        }
		        else if(takeLetter === 'all')
		        {	
		        	$('.no-result').hide();
		        	 $('tr.ief-row-entity').fadeIn(222);
		        }
		  
		    });
		});

	    }
	  }

	
})(jQuery)
