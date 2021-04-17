/**
slick slider (with media query)
**/

$(document).ready(function(){
  const matchResult = window.matchMedia('(max-width: 850px)');
    
    
    if (matchResult.matches == true) {

      $('.slider-portrait').slick({
        autoplay: false,
        autoplaySpeed: 3000,
        dots: false,
        infinite: true,
        fade: true,
        speed: 1200,
        adaptiveHeight: false,
        focusOnSelect: true,
        arrows: false
      });

    } else {
      
      $('.slider-landscape').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        infinite: true,
        fade: true,
        speed: 1200,
        adaptiveHeight: false,
        focusOnSelect: true,
        arrows: false
      });
    }
});


/** 
MY SLIDER
**/
$(".gallery > a").hide()
$(".active").show()

function active_next() {
  var current = $('.active'); // get the current active
  var next = current.next();

  // if no next then we're at the end
  if( !next.length )
      next = current.parent().find('a:first'); // get the first a element in the parent

      current.fadeOut(function(){
        next.fadeIn("slow").addClass('active');
      }).removeClass('active');
}
var myinterval = setInterval( active_next, 5000 ); // repeat that javascrispt every 3 seconds




/** 
media query - show menu on #bars click
**/
$(document).ready(function(){
    $("#bars").click(function (e){
      var hiddenmenu = $(".primary > .list-item").not("#bars");
      e.preventDefault();
      if (hiddenmenu.css("transform") === "matrix(0, 0, 0, 0, 0, 0)") {
        hiddenmenu.css("transform", "scale(1)");
      }
      else { 
        hiddenmenu.css("transform", "scale(0)");
      }
    });
});

/**
lightbox
 **/
$(document).ready(function(){
  var overlay = $("#overlay")
      img_container = $(".img-container")

  $(".img-container").hide();
  $(".gallery").find("a").click(function (e) {

      var href = $(this).attr("href");
          image = $("<img>", { src: href});
      
      $(".img-container").html( image )

      overlay.slideToggle({
        duration: 250,
        complete: function(){$(".img-container").fadeToggle({
          duration: 250
        });}
      });

      e.preventDefault();
  });

  //hide overlay on click
  overlay.click(function (e) {
    $(".img-container").fadeToggle({
        duration: 250,
        complete: function(){overlay.slideToggle({
          duration: 250
        });}
    }); 
  });

  //hide overlay on esc key
  $(document).keyup(function (e) { 
    if (e.which === 27 && overlay.css("display") === "block")  {
      $(".img-container").fadeToggle({
        duration: 250,
        complete: function(){overlay.slideToggle({
          duration: 250
        });}
      });
    };
  });
});

//bottom-left page info
$(document).ready(function(){
  $(".fa-info").click(function (e) { 
    e.stopPropagation()
    $(".speech-bubble").fadeToggle(300);
  });
});

if ($('body').hasClass('services')) {

      document.addEventListener('DOMContentLoaded', () => {

        // Unix timestamp (in seconds) to count down to
        var twoDaysFromNow = (new Date().getTime() / 1000) + (86400 * 2) + 1;

        // Set up FlipDown
        var flipdown = new FlipDown(twoDaysFromNow)

          // Start the countdown
          .start()

          // Do something when the countdown ends
          .ifEnded(() => {
            console.log('The countdown has ended!');
          });

        // Toggle theme
        /*
        var interval = setInterval(() => {
          let body = document.body;
          body.classList.toggle('light-theme');
          body.querySelector('#flipdown').classList.toggle('flipdown__theme-dark');
          body.querySelector('#flipdown').classList.toggle('flipdown__theme-light');
        }, 5000);
        */

      });
}

        
var subMenu = $(".sub"),
    menuLinks = subMenu.find("a");     
    
menuLinks.click(function(e){
  e.stopPropagation;
  
  var hash = $(this.hash)
      jshash = this.hash.replace("#","");

  sessionStorage.setItem("idcko", jshash)

  if (document.location.pathname.match(/[^\/]+$/)[0] === "sluzby.html") {
    $("html, body").animate({ 
      scrollTop: hash.offset().top }, 1000);
    } else {
      
    }
});


// hide/show header and footer script
/*
var header = $(".head-container"),
    footer = $(".footer-container"),
    i = null,
    j = null;


    $("body").mousemove(function() {
      clearTimeout(i);
      clearTimeout(j);
      header.slideDown();
      footer.show("slide", {direction: "down"});
      i = setTimeout('header.slideUp();', 1500);
      j = setTimeout('footer.hide("slide", {direction: "down"});', 1500);
    });
*/

const media2 = window.matchMedia('(max-width: 500px)');

function checkMediaQuery(e) {
  
  if (e.matches) {
    $(".wrapper-left").hide();
    $(".wrapper-right").css("width","100%")
  } else {
    $(".wrapper-left").show();
    $(".wrapper-right").css("width","50%")
  }

};

media2.addEventListener("change", checkMediaQuery)