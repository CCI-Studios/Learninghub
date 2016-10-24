(function($) {
    var timer = null;
    var maxHeight = 0;

    $(function(){
        createIndicators();
        setActive(1);
        timer = setInterval(next, 7000);
        $(window).resize(function(){
            maxHeight = 0;
            fixHeight();
        });
        fixHeight();
        setTimeout(fixHeight, 200);
    });
    $(window).load(fixHeight);

    function container()
    {
        return $(".view-slideshow");
    }
    function rows()
    {
        return container().find(".views-row");
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
        var current = getActive()
        
      
        if (i-1 == current.index())
        {   
            i++;
        }

        current.addClass("outbound").removeClass("active");
        rows().eq(i-1).addClass("active").hide().fadeIn(function(){
        current.removeClass("outbound").hide();

        });
        fixHeight();

        indicators().removeClass("active").eq(i).addClass("active");
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
    function next()
    {
        var i = getActiveIndex();
        i++;
        console.log(getActiveIndex());
        if (i > max())
        {
            i = 1;
        }


        setActive(i);
    }
    function stop()
    {
        clearInterval(timer);
    }

    function createIndicators()
    {
        container().append("<div class='ind-container'><ul class='indicators' />");
        var indicatorsContainer = container().find(".indicators");
        
        indicatorsContainer.append("<li><a href='#' class='btn-pause'><span class='sr-only'>Pause</span></a></li>");
        indicatorsContainer.find(".btn-pause").click(function(){
            stop();
            $(this).addClass("paused");
            return false;
        });
        
        rows().each(function(i){
            indicatorsContainer.append("<li><a href='#' aria-label='Slide "+(i+1)+"' data-indicator='"+(i+1)+"'><span class='sr-only'>Activate slide "+(i+1)+"</span></a></li>");
        });
        indicatorsContainer.find("a[data-indicator]").click(function(){
            indicatorClick($(this).data("indicator"));
            return false;
        });
    }
    function indicatorClick(i)
    {
        setActive(i);
        stop();
    }
}(jQuery));