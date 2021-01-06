//slick
$(document).ready(function(){
    $('.slider').slick({
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
  });

//media query - show menu on #bars click
$(document).ready(function(){
  $("#bars").click(function (e) {
    e.preventDefault()
    if ($(".primary > .list-item").not("#bars").css("transform") === "matrix(0, 0, 0, 0, 0, 0)") {
    $(".primary > .list-item").not("#bars").css("transform", "scale(1)");
    }
    else { $(".primary > .list-item").not("#bars").css("transform", "scale(0)");
    }
  });
});


//bottom-left page info
$(document).ready(function(){
  $(".fa-info").click(function (e) { 
    e.stopPropagation()
    $(".speech-bubble").fadeToggle(300);
  });
});

// hide/show header and footer script
/**var header = $(".head-container"),
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
**/