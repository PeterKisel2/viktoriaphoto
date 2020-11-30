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

// hide/show header and footer script
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
