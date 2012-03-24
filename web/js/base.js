/**
 * This file contains some helper methods for the auction.js and monitor.js
 * files.
 */

(function($){
    "use strict";
    
    var BaseAuction = {
        
        /**
         * Initialize variables and run setup methods
         */
        init: function( ) {
            var self = this;
            
            self.countdown_end = 0;
            self.ajax_latency  = 0;
            self.colorred      = false;
            
            self.elements = {
                timeremain: $( "#timeremain" )
            }
            
            self.setupLatencyMeasurement();
            self.updateCountdown( 1 );
            
            return this;
        },
        
        /**
         * Setup event handlers to measure latency on every ajax request
         */
        setupLatencyMeasurement: function() {
            var self = this;
            $(document).ajaxSend(function() {
                self.ajax_resp_time = new Date();
            }).ajaxComplete(function() {
                self.ajax_latency = (new Date()) - self.ajax_resp_time;
                //console.log(self.ajax_latency);
            });
        },
        
        /**
         * Set new countdown (usually called by ajax request to sync countdown)
         */
        setCountdown: function( countdown ) {
            var self = this;
            var now = new Date();
            self.countdown_end = now.getTime() + (1000 * countdown) + (self.ajax_latency / 2);
        },
        
        /**
         * Update and format countdown and color in red during last 10 seconds
         * Calls itself every 200 ms
         */
        updateCountdown: function( length ) {
            var self = this;
            
            setTimeout(function() {
                var now = new Date();
                var countdown = Math.floor((self.countdown_end-now.getTime())/1000);
                if(countdown > 0) {
                    if(!self.colorred && countdown <= 10) {
                        self.elements.timeremain.animate({color: '#ff0000'}, 200);
                        self.colorred = true;
                    } else if(self.colorred && countdown > 10) {
                        self.elements.timeremain.css('color', '#111111');
                        self.colorred = false;
                    }
                    var seconds = countdown % 60;
                    self.elements.timeremain.text( Math.floor(countdown / 60) + ':' + (seconds < 10 ? '0' + seconds : seconds) );
                } else {
                    self.elements.timeremain.text( "0:00" );
                }
                self.updateCountdown()
            }, length || 200);
        }
    };
    //Invoke init method and save to global name space
    window.BaseAuction = BaseAuction.init();
})( jQuery );