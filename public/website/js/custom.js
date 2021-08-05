
/** search ***/
  
        jQuery(".search-btn").click(function() {
            jQuery(".search-btn").removeClass("active");
            jQuery(".search-field").addClass("active");
            jQuery(".search-close-btn").addClass("active");
        });
        jQuery(".search-close-btn").click(function() {
            jQuery(".search-btn").addClass("active");
            jQuery(".search-close-btn").removeClass("active");
            jQuery(".search-field").removeClass("active");
        });

// tab-accordian js

 $('button').on('click', function(){
        
    });
    $('#myTab').tabCollapse();

    // slider
        
$('.shop-carousel').owlCarousel({
    loop:true,
    margin:30,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:4
        }
    }
	
})    
$('.tabs-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
	
})      
$('.tabs2-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
	
})   
$('.tabs3-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
	
}) 
$('.tabs4-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
	
})
$('.tabs5-carousel').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>","<i class='fa fa-angle-right' aria-hidden='true'></i>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        768:{
            items:3
        },
        1000:{
            items:5
        }
    }
	
})   
$('.review-carousel').owlCarousel({
    loop:true,
    margin:30,
    nav:true,
    responsiveClass:true,
	navText: ["<img src='images/review_arrow-l.png' class='images'>","<img src='images/review_arrow.png' class='images'>"],
    responsive:{
        0:{
            items:1
        },
        568:{
            items:2
        },
        1000:{
            items:4
        }
    }
	
})
$('.test-slider').owlCarousel({
    loop:true,
    margin:30,
    responsiveClass:true,
	navText: ["<i class='fa fa-angle-left' aria-hidden='true'>","<i class='fa fa-angle-right' aria-hidden='true'>"],
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:2,
            nav:false
        },
        1000:{
            items:4,
            nav:true
        }
    }
	
})

 
    $(window).load(function(){
      $('#carousel').flexslider({
        animation: "slide",
        controlNav: true,
        animationLoop: true,
        slideshow: true,
        itemWidth: 88,
        itemMargin: 3,
        asNavFor: '#slider'
      });

      $('#slider').flexslider({
        animation: "slide",
        controlNav: true,
        animationLoop: true,
        slideshow: true,
        sync: "#carousel",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
// custom scrollbar js starts here
//
//(function($){
//  $(window).on("load",function(){
//    
//				$("#content-7").mCustomScrollbar({
//					scrollButtons:{enable:true},
//					theme:"3d-thick"
//				});
//  });
//})(jQuery);
//
//$(".faq-accordion-container.content").mCustomScrollbar({
//					scrollButtons:{enable:true}
//				});

/* accordian js */
$(document).ready(function(){
    $(".collapse.in").each(function(){
        $(this).siblings(".panel-heading").find(".fa").addClass("fa fa-angle-up").removeClass("fa fa-angle-down");
    });
    
    $(".collapse").on('show.bs.collapse', function(){
        $(this).parent().find(".fa").removeClass("fa fa-angle-down").addClass("fa fa-angle-up");
    }).on('hide.bs.collapse', function(){
        $(this).parent().find(".fa").removeClass("fa fa-angle-up").addClass("fa fa-angle-down");
    });
    
    $(".panel-title a").click(function(){
        $(".panel-title a").removeClass("active");
        $(".panel-title a").addClass("active");
    });
    
});


 jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });



/* conter down js */
//
//        function getTimeRemaining(endtime) {
//  var t = Date.parse(endtime) - Date.parse(new Date());
//  var seconds = Math.floor((t / 1000) % 60);
//  var minutes = Math.floor((t / 1000 / 60) % 60);
//  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
//  var days = Math.floor(t / (1000 * 60 * 60 * 24));
//  return {
//    'total': t,
//    'days': days,
//    'hours': hours,
//    'minutes': minutes,
//    'seconds': seconds
//  };
//}
//
//function initializeClock(id, endtime) {
//  var clock = document.getElementById(id);
//  var daysSpan = clock.querySelector('.days');
//  var hoursSpan = clock.querySelector('.hours');
//  var minutesSpan = clock.querySelector('.minutes');
//  var secondsSpan = clock.querySelector('.seconds');
//
//  function updateClock() {
//    var t = getTimeRemaining(endtime);
//
//    daysSpan.innerHTML = t.days;
//    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
//    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
//    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
//
//    if (t.total <= 0) {
//      clearInterval(timeinterval);
//    }
//  }
//
//  updateClock();
//  var timeinterval = setInterval(updateClock, 1000);
//}
//
//var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
//initializeClock('clockdiv', deadline);
