/**
 * This file is being used to control the auction in the backend. Needs helper
 * function of the base.js file.
 */

(function($){
    "use strict";
    
    var Monitor = {
        init: function( ) {
            var self = this;
            
            self.base       = window.BaseAuction;
            self.update_url = $( "input[name='url_update']").val();
            self.auction_id = $( "input[name='auction_id']").val();
            
            self.elements = {
                curprice:      $( "#curprice" ),
                lastbidder:    $( "#a_lastbidder" ),
                part_table:    $( "#participants table > tbody" ),
                hist_table:    $( "#history table > tbody" ),
                bots_table:    $( "#bots table > tbody" ),
                statusmessage: $( "#statusmessage" ),
                botform:       $( "#allbotsform" )
            }
            
            self.setupAjaxErrors();
            self.setupBotForm();
            self.updateAuction( 1 );
            
            return this;
        },
        
        /**
         * Show error message if Ajax Request failed
         */
        setupAjaxErrors: function() {
            $(document).ajaxError(function(event, request, settings) {
                alert('An error occurred: ' + request.statusText);
            });
        },
        
        /**
         * Update auction information, this function is called by ajax request
         * and gets all the data
         */
        updateAuction: function( length ) {
            var self = this;
            
            setTimeout(function() {
                self.fetch().done(function( results ) {
                    self.auction_id = results.a_id;
                    if(results.active) {
                        self.base.setCountdown(results.a_timeremain);
                        self.displayAuction(results);
                        self.updateAuction();
                    } else {
                        self.elements.statusmessage.slideDown();
                        self.updateAuction(2000);
                    }
                });
            }, length || 1000);
        },
        
        /**
         * Execute ajax request
         */
        fetch: function() {
            var self = this;
            return $.ajax({
                url: self.update_url,
                data: {auction_id: self.auction_id},
                dataType: 'json'
            });
        },
        
        /**
         * Update DOM Elements for auction by given data
         */
        displayAuction: function( data ) {
            var self = this;
            if(self.elements.curprice.text() != data.a_curprice) {
                self.elements.curprice.effect('highlight', {}, 1000);
            }
            self.elements.curprice.text(data.a_curprice);

            if(data.a_lastbidder) {
                self.elements.lastbidder.text(data.a_lastbidder);
            } else {
                self.elements.lastbidder.html("<i>No one yet!</i>");
            }

            self.elements.part_table.html(data.participants);
            self.elements.hist_table.html(data.history);
            self.elements.bots_table.html(data.bots);
        },
        
        /**
         * Setup form to send ajax request
         */
        setupBotForm: function() {
            var self = this;
            var submit_button = self.elements.botform.find( "input[type=submit]" );
            var submit_load   = self.elements.botform.find( ".submitload" );
            
            self.elements.botform.on('submit', function( e ) {
                submit_button.hide();
                submit_load.show();
                $.ajax({
                    url:  self.elements.botform.attr("action"),
                    data: self.elements.botform.serialize(),
                    type: 'POST'
                }).always(function() {
                    submit_button.show();
                    submit_load.hide();
                });
                e.preventDefault();
            });
        }
        
    };
    window.Monitor = Monitor.init();
})( jQuery );