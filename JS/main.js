//slick
$(document).ready(function(){
    $('.slider').slick({
      autoplay: false,
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

//lightbox

$(document).ready(function(){
  var overlay = $("#overlay")
      img_container = $(".img-container")

  $(".img-container").hide();
  $(".pictures-thumb").find("a").click(function (e) {

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