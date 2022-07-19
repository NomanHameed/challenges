$('.menu-ico').append("<span></span>").on('click',function(){
  $(this ).toggleClass('active')
})
$('.v1').text('<div class="menu-ico"></div>')

$(".toggleMenu").click(function(){
$(".site-menu-mobile").toggleClass("OpenNav");
$("body").toggleClass("overflow-hidden");

});



// $(function(){
//   $('.hide-show').show();
//   $('.hide-show').show();
//   $('.hide-show span').addClass('show')
  
//   $('.hide-show span').click(function(){
//     if( $(this).hasClass('show') ) {
      
//       $('input[name="login[password]"]').attr('type','text');
//       $(this).removeClass('show');
//     } else {
     
//        $('input[name="login[password]"]').attr('type','password');
//        $(this).addClass('show');
//        $(this).removeClass("hide-pass")
//     }
//   });
	

// });

$(document).on('hidden.bs.modal', function (event) {
  if ($('.modal:visible').length) {
    $('body').addClass('modal-open');
  }
});



$(window).scroll(function() {
  if ($(this).scrollTop() > 30){  
      $('.site-header').addClass("sticky");
    }
    else{
      $('.site-header').removeClass("sticky");
    }
  });



$(document).ready(function(){

  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;
  
  $(".next").click(function(){
  
  current_fs = $(this).parent();
  next_fs = $(this).parent().next();
  
  //Add Class Active
  $("#progressbar .step").eq($("fieldset").index(next_fs)).addClass("active");
  $("#progressbar .step").eq($("fieldset").index(current_fs)).addClass("finish");

  
  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  next_fs.css({'opacity': opacity});
  },
  duration: 600
  });
  });
  
  $(".previous").click(function(){
  
  current_fs = $(this).parent();
  previous_fs = $(this).parent().prev();
  
  //Remove class active
  $("#progressbar .step").eq($("fieldset").index(current_fs)).removeClass("active");
  
  //show the previous fieldset
  previous_fs.show();
  
  //hide the current fieldset with style
  current_fs.animate({opacity: 0}, {
  step: function(now) {
  // for making fielset appear animation
  opacity = 1 - now;
  
  current_fs.css({
  'display': 'none',
  'position': 'relative'
  });
  previous_fs.css({'opacity': opacity});
  },
  duration: 600
  });
  });
  
  
  });








  $(function() {

    $(" .circle-progress").each(function() {
  
      var value = $(this).attr('data-value');
      var left = $(this).find('.progress-left .progress-bar');
      var right = $(this).find('.progress-right .progress-bar');
  
      if (value > 0) {
        if (value <= 50) {
          right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
        } else {
          right.css('transform', 'rotate(180deg)')
          left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
        }
      }
  
    })
  
    function percentageToDegrees(percentage) {
  
      return percentage / 100 * 360
  
    }
  
  });



  $(".OpenSideBar").click(function(){
    $("body").toggleClass("OpenSideBarMenu");
    });