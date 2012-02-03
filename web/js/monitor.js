var url_update = "/backend/monitor/status";

/**
 * Show error message if Ajax Request failed
 */
function ajaxError(data, status, error) {
  alert('Ein Fehler ist aufgetreten: ' + error);
}

/**
 * Update auction information, this function is called by ajax request and gets
 * all the data
 */
function updateAuction(data) {
  if(data.active) {
    setCountdown(data.a_timeremain);
    if($( "#curprice" ).text() != data.a_curprice) {
      $( "#curprice" ).effect('highlight', {}, 1000);
    }
    $( "#curprice" ).text(data.a_curprice);

    if(data.a_lastbidder) {
      $( "#a_lastbidder" ).text(data.a_lastbidder);
    } else {
      $( "#a_lastbidder" ).html("<i>Noch keiner!</i>");
    }

    $( "#participants table > tbody" ).html(data.participants);
    $( "#history table > tbody" ).html(data.history);
    $( "#bots table > tbody" ).html(data.bots);

    setTimeout(function() {$.getJSON(url_update, 'auction_id=' + data.a_id, updateAuction).error(ajaxError)}, 1000);
  } else {
    $( "#statusmessage" ).slideDown();
    setTimeout(function() {$.getJSON(url_update, 'auction_id=' + data.a_id, updateAuction).error(ajaxError)}, 2000);
  }
}

/**
 * Initialization, setup form to send ajax request, start initial ajax request
 */
$(document).ready(function() {
  url_update = $( "input[name='url_update']").val();

    $( "#allbotsform" ).submit(function() {
      $( "#allbotsform input[type=submit]" ).hide();
      $( "#allbotsform .submitload" ).show();
      $.post( $( "#allbotsform").attr("action"), $( "#allbotsform").serialize(), function(data) {
        $( "#allbotsform input[type=submit]" ).show();
        $( "#allbotsform .submitload" ).hide();
      } ).error(ajaxError);
      return false;
    });

  updateCountdown();
  $.getJSON(url_update, 'auction_id=' + $( "input[name='auction_id']").val(), updateAuction).error(ajaxError);
});