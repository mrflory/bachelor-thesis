var countdown_end = 0;
var colorred = false;
var ajax_resp_time;
var ajax_latency = 0;

$(document).ready(function() {
  $(document).ajaxSend(function(){
    ajax_resp_time = new Date();
  }).ajaxComplete(function(){
    ajax_latency = (new Date()) - ajax_resp_time;
    //$('#bidbot h2').text(ajax_latency);
  });
});

/*
 * Set new countdown and highlight if higher than previous countdown
 */
function setCountdown(countdown) {
  var now = new Date();
  new_end = now.getTime() + (1000 * countdown) + (ajax_latency / 2);
  //if(countdown_end > 0 && (new_end-1000) > countdown_end) {
  //  $( "#timeremain" ).effect('highlight', {}, 1000);
  //  $( "#curprice" ).effect('highlight', {}, 1000);
  //}
  countdown_end = new_end;
}

/**
 * Update and format countdown and color in red during last 10 seconds
 * Calls itself every 200 ms
 */
function updateCountdown() {
  var now = new Date();
  var countdown = Math.floor((countdown_end-now.getTime())/1000);
  if(countdown > 0) {
    if(!colorred && countdown <= 10) {
      $( "#timeremain" ).animate({color: '#ff0000'}, 200);
      colorred = true;
    } else if(colorred && countdown > 10) {
      $( "#timeremain" ).css('color', '#111111');
      colorred = false;
    }
    var seconds = countdown % 60;
    $( "#timeremain" ).text( Math.floor(countdown / 60) + ':' + (seconds < 10 ? '0' + seconds : seconds) );
  } else {
    $( "#timeremain" ).text( "0:00" );
  }
  setTimeout(function() {updateCountdown()}, 200);
}
