var url_update = "/bid/status";
var url_wait = "bid/wait";
var dialog_info;
var dialog_error;
var dialog_directbuy;
var interface_active = true;

/**
 * Show error message if Ajax Request failed
 */
function ajaxError(data, status, error) {
  if(status == 'timeout') {
    $( "#dialog_error .messagetext" ).html('An error occurred.<br />Server is currently not available.');
  }else {
    $( "#dialog_error .messagetext" ).html('An error occurred.<br />Please refresh the page.<br /><br />Error: ' + error);
  }
  dialog_error.dialog('open');
}

/**
 * Show status message on the bottom, e.g. used for "bidding successful"
 */
function showStatus(message, options) {
  var settings = {
    'error': false,
    'permanent': false,
    'delay': 5000
  }
  if(options) {
    $.extend( settings, options );
  }
  $( "#statusbar" ).clearQueue();
  $( "#statusbar .messagetext" ).html(message);
  $( "#statusbar" ).toggleClass('statuserror', settings.error);
  if(settings.permanent) {
    $( "#statusbar" ).slideDown();
  } else {
    $( "#statusbar" ).slideDown().delay(settings.delay).slideUp();
  }
}

/**
 * Deactives interface so nothing can be done, e.g. if direct buy has been done
 */
function deactivateInterface() {
  interface_active = false;
  $( "#bidform input[type=submit]" ).prop({disabled: true});
  $( "#buyform input[type=submit]" ).prop({disabled: true});
  $( "#botform input[type=submit]" ).prop({disabled: true});
}

/**
 * Activates interface, so enable all buttons and hide statusbar, e.g. if new
 * auction starts
 */
function activateInterface() {
  interface_active = true;
  $( "#bidform input[type=submit]" ).prop({disabled: false});
  $( "#buyform input[type=submit]" ).prop({disabled: false});
  $( "#botform input[type=submit]" ).prop({disabled: false});
  $( "#statusbar" ).slideUp();
}

/**
 * Wait for new auction to begin, so far maybe show direct buy option
 */
function waitForAuction(data) {
  if(dialog_error.dialog('isOpen')) {
    return;
  }

  if(data.active) {
    // Neue Auktion aktiv, initialisiere Interface
    dialog_info.dialog('close');
    dialog_directbuy.dialog('close')
    activateInterface();

    $( "input[name='auction_id']").val(data.a_id);
    $( "#a_priceraise" ).text(data.a_priceraise);
    $( "#p_earned" ).text(data.p_earned);
    $( "#b_valuation" ).text(data.b_valuation);
    $( "#b_bidfee" ).text(data.b_bidfee);
    $( "#a_description" ).text(data.a_description);
    if(data.a_image) {
      $( "#a_image" ).html('<img alt="Produktbild" src="/uploads/products/'+data.a_image+'" />');
    } else {
      $( "#a_image" ).html("");
    }

    if(data.showbidbutton) {
      $( "#bidform .submitbutton" ).show();
    } else {
      $( "#bidform .submitbutton" ).hide();
    }

    if(data.showbidders) {
      $( "#history" ).show();
    } else {
      $( "#history" ).hide();
    }

    if(data.showdirectbuy) {
      $( "#directbuy" ).show();
      $( "#directprice" ).text(data.directprice);
    } else {
      $( "#directbuy" ).hide();
    }

    if(data.autobid) {
      $( "#bidbot" ).show();
    } else {
      $( "#bidbot" ).hide();
    }

    updateAuction(data);
    //setTimeout(function() {$.getJSON(url_update, null, updateAuction).error(ajaxError)}, 1000);
  } else if(data.show_last) {

    if(data.directbuy_last && !(dialog_info.dialog('isOpen'))) {
      $( "#buy_p_money" ).text(data.p_money);
      $( "#buy_directprice" ).text(data.directprice);
      $( "#buy_discount" ).text(data.discount);
      $( "#buy_directtotal" ).text(data.directtotal);
      $( "#dialog_directbuy input[name='directtotal']").val(data.directtotal_dec);
      $( "#dialog_directbuy input[name='auction_id']").val(data.a_id);
      if( !(dialog_directbuy.dialog('isOpen')) ) {
        dialog_directbuy.dialog('open');
      }
    } else {
      if( !(dialog_info.dialog('isOpen')) || $("#infoload").is(":visible") ) {
        var message = '';
        if(data.thisiswinner) {
          message += 'Sie haben die letzte Auktion zu einem Preis von ' + data.a_finalprice + ' gewonnen. ';
        } else {
          if(data.bidder_bought) {
            message += 'Sie haben die letzte Auktion mit einem Sofortkauf abgeschlossen. ';
          }
          if(data.a_winner) {
            message += 'Die letzte Auktion hat ' + data.a_winner + ' zu einem Preis von ' + data.a_finalprice + ' gewonnen. ';
          } else {
            message += 'Die letzte Auktion hat niemand gewonnen. ';
          }
        }
        $( "#dialog_info .messagetext" ).html(message + '<br />Sie haben noch ein Guthaben von ' + data.p_money + '. Bitte warten Sie auf die nächste Auktion.');
        $( "#infoload" ).hide();
      }
      dialog_info.dialog('open');
    }
    setTimeout(function() {$.getJSON(url_wait, null, waitForAuction).error(ajaxError)}, 2000);
  } else {
    if( !(dialog_info.dialog('isOpen')) ) {
      $( "#dialog_info .messagetext" ).html('Derzeit ist keine Auktion aktiv.<br />Bitte warten.');
      dialog_info.dialog('open');
    }
    setTimeout(function() {$.getJSON(url_wait, null, waitForAuction).error(ajaxError)}, 2000);
  }
}

/**
 * Update auction information, this function is called by ajax request and gets
 * all the data, start initial ajax request
 */
function updateAuction(data) {
  if(dialog_error.dialog('isOpen')) {
    return;
  }

  if(data.active) {
    if(interface_active) {
      if( (data.showdirectbuy && data.bidder_bought) || (data.autobid && data.bot_active) ) {
        deactivateInterface();
      } else if(!data.thisiswinner) {
        $( "#bidform input[type=submit]" ).prop({disabled: false});
        $( "#buyform input[type=submit]" ).prop({disabled: false});
      }
    } else {
      var show_buy = !data.showdirectbuy || (data.showdirectbuy && !data.bidder_bought);
      var show_bot = !data.autobid || (data.autobid && !data.bot_active);
      if( show_buy && show_bot ) {
        activateInterface();
      }
    }

    setCountdown(data.a_timeremain);
    if($( "#curprice" ).text() != data.a_curprice) {
      $( "#curprice" ).effect('highlight', {}, 1000);
    }
    $( "#curprice" ).text(data.a_curprice);
    $( "input[name='curprice']").val(data.a_curprice_dec);
    $( "#p_money" ).text(data.p_money);

    if(data.thisiswinner) {
      $( "#a_lastbidder" ).html("<strong>Sie!</strong>");
      $( "#bidform input[type=submit]" ).prop({disabled: true});
      $( "#buyform input[type=submit]" ).prop({disabled: true});
    } else if(data.a_lastbidder) {
      $( "#a_lastbidder" ).text(data.a_lastbidder);
    } else {
      $( "#a_lastbidder" ).html("<i>Noch keiner!</i>");
    }

    if(data.showbidders) {
      $( "#history table > tbody" ).html(data.history);
    }

    if(data.showdirectbuy) {
      $( "#discount" ).text(data.discount);
      $( "#directtotal" ).text(data.directtotal);
      $( "input[name='directtotal']").val(data.directtotal_dec);
    }

    //if(data.autobid) {
      //if( data.bot_limitcount < 0 ) {
        //$( "#botform input[type=submit]" ).buttonstate('disable');
        //$( "#bot_limitcount" ).text("(0)");
      //} else {
        //$( "#botform input[type=submit]" ).buttonstate('enable');
       // $( "#bot_limitcount" ).text("(" + data.bot_limitcount + ")");
      //}
    //}

    setTimeout(function() {$.getJSON(url_update, 'auction_id=' + data.a_id, updateAuction).error(ajaxError)}, 1000);
  } else {
    waitForAuction(data);
  }
}

/**
 * Initialization, add toggle for instructions, setup dialogs, setup forms to
 * send ajax request
 */
$(document).ready(function() {
  $( "#instructions header" ).click(function() {
    $( "#instructions p" ).toggle("blind", null, 500);
    $( "#instructions header" ).toggleClass('hidden visible', 500);
  });

  if($( ".error" ).length == 0) {

    dialog_info = $( "#dialog_info" );
    dialog_info.dialog({
      autoOpen: false,
      closeOnEscape: false,
      modal: true,
      resizable: false,
      width: 400,
      open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();}
      //close: function(event, ui) {dialog_directbuy.dialog("close");}
    });

    dialog_error = $( "#dialog_error" );
    dialog_error.dialog({
      autoOpen: false,
      closeOnEscape: false,
      modal: true,
      resizable: false,
      width: 400,
      open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();}
    });

    dialog_directbuy = $( "#dialog_directbuy" );
    dialog_directbuy.dialog({
      autoOpen: false,
      closeOnEscape: false,
      modal: true,
      resizable: false,
      width: 400,
      open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();},
			buttons: {
				"Kaufen": function() {
          $.post( $( "#dialog_buyform").attr("action"), $( "#dialog_buyform").serialize(), function(data) {
            if(data.successful) {
              showStatus('Produkt gekauft! - Bitte warten Sie auf die nächste Auktion.');
              //$( "#dialog_info .messagetext" ).prepend('Sie haben die letzte Auktion mit einem Sofortkauf abgeschlossen. ');
            } else {
              showStatus('Kauf fehlgeschlagen! - Bitte wenden Sie sich an den Leiter.', {'error': true});
            }
          } ).error(ajaxError);
					$( this ).dialog( "close" );
          $( "#dialog_info .messagetext" ).html('');
          $( "#infoload" ).show();
          dialog_info.dialog('open');
				},
				"Nicht kaufen": function() {
					$( this ).dialog( "close" );
          $( "#dialog_info .messagetext" ).html('');
          $( "#infoload" ).show();
          dialog_info.dialog('open');
				}
			}
    });

    $( "#bidform" ).submit(function() {
      $( "#bidform input[type=submit]" ).hide();
      $( "#bidform .submitload" ).show();
      $.post( $( "#bidform").attr("action"), $( "#bidform").serialize(), function(data) {
        $( "#bidform input[type=submit]" ).show();
        $( "#bidform .submitload" ).hide();
        if(data.successful) {
          showStatus('Gebot abgegeben!');
        } else {
          showStatus('Bieten fehlgeschlagen! - Eventuell wurden Sie zwischenzeitlich überboten.', {'error': true});
        }
      } ).error(ajaxError);
      return false;
    });

    $( "#buyform" ).submit(function() {
      $( "#buyform input[type=submit]" ).hide();
      $( "#buyform .submitload" ).show();
      $.post( $( "#buyform").attr("action"), $( "#buyform").serialize(), function(data) {
        $( "#buyform input[type=submit]" ).show();
        $( "#buyform .submitload" ).hide();
        if(data.successful) {
          showStatus('Produkt gekauft! - Bitte warten Sie auf die nächste Auktion.', {'permanent': true});
          deactivateInterface();
        } else {
          showStatus('Kauf fehlgeschlagen! - Bitte wenden Sie sich an den Leiter.', {'error': true});
        }
      } ).error(ajaxError);
      return false;
    });

    $( "#botform" ).submit(function() {
      $( "#botform input[type=submit]" ).hide();
      $( "#botform .submitload" ).show();
      $.post( $( "#botform").attr("action"), $( "#botform").serialize(), function(data) {
        $( "#botform input[type=submit]" ).show();
        $( "#botform .submitload" ).hide();
        if(data.successful) {
          showStatus('Biet-Automat aktiviert! - Sie können nun nicht mehr manuell bieten.', {'permanent': true});
          deactivateInterface();
        } else {
          showStatus('Aktivierung fehlgeschlagen! - Bitte überprüfen Sie Ihre Eingaben.', {'error': true});
        }
      } ).error(ajaxError);
      return false;
    });

    updateCountdown();
    $.getJSON(url_wait, null, waitForAuction).error(ajaxError);
    
  }
});
