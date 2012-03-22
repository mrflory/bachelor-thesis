/**
 * This file is being used to control the auction in the frontend. Needs helper
 * function of the base.js file.
 */

(function($){
    "use strict";
    
    var Auction = {
        init: function( ) {
            var self = this;
            
            self.base              = window.BaseAuction;
            self.update_url        = "/bid/status";
            self.waiting_url       = "/bid/wait";
            self.auction_id        = undefined;
            self.buying_already_decided = false;
            self.error             = false;
            self.interface_enabled = true;
            
            self.lang_texts = window.AuctionTranslations;
            
            //Compile handlebars.js template for final dialog
            self.lang_texts.auction_dialog = Handlebars.compile($(self.lang_texts.auction_dialog).html());
            
            self.elements = {
                curprice:          $( "#curprice" ),
                input_curprice:    $( "input[name='curprice']" ),
                p_money:           $( "#p_money" ),
                discount:          $( "#discount" ),
                directtotal:       $( "#directtotal" ),
                input_directtotal: $( "input[name='directtotal']" ),
                lastbidder:        $( "#a_lastbidder" ),
                hist_table:        $( "#history table > tbody" ),
                infoload:          $( "#infoload" ),
                statusbar:         $( "#statusbar" ),
                bidform:           $( "#bidform" ),
                buyform:           $( "#buyform" ),
                botform:           $( "#botform" ),
                dialog_error:      $( "#dialog_error" ),
                dialog_info:       $( "#dialog_info" ),
                dialog_directbuy:  $( "#dialog_directbuy" )
            };
            
            self.setupAjaxErrors();
            self.setupInstructions();
            
            if($( ".error" ).length == 0) {
                self.setupDialogs();
                self.setupForms();
                self.updateAuction( 1 );
            }
            
            return this;
        },
        
        /**
         * Show error message if Ajax Request failed
         */
        setupAjaxErrors: function() {
            var self = this;
            $(document).ajaxError(function(event, request, settings) {
                if(request.statusText == 'timeout') {
                    self.elements.dialog_error.find(".messagetext").html('An error occurred.<br />Server is currently not available.');
                }else {
                    self.elements.dialog_error.find(".messagetext").html('An error occurred.<br />Please refresh the page.<br /><br />Error: ' + request.statusText);
                }
                self.elements.dialog_error.dialog('open');
                self.error = true;
            });
        },
        
        /**
         * Make instructions header clickable
         */
        setupInstructions: function() {
            var dom_instr = $( "#instructions" );
            dom_instr.find( "header" ).on('click', function() {
                dom_instr.find( "p" ).toggle("blind", null, 500);
                dom_instr.find( "header" ).toggleClass('hidden visible', 500);
            });
        },
        
        /**
         * Setup dialogs
         */
        setupDialogs: function() {
            var self = this;
            
            self.elements.dialog_info.dialog({
                autoOpen: false,
                closeOnEscape: false,
                modal: true,
                resizable: false,
                width: 400,
                open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();}
                //close: function(event, ui) { dialog_directbuy.dialog("close"); }
            });
            
            self.elements.dialog_error.dialog({
                autoOpen: false,
                closeOnEscape: false,
                modal: true,
                resizable: false,
                width: 400,
                open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();}
            });
            
            self.elements.dialog_directbuy.dialog({
                autoOpen: false,
                closeOnEscape: false,
                modal: true,
                resizable: false,
                width: 400,
                open: function(event, ui) {$(".ui-dialog-titlebar-close", ui.dialog).hide();},
                buttons: {
                    "Kaufen": function() {
                        self.performBuy( $( "#dialog_buyform") ).done(function( results ) {
                            if(results.successful) {
                                self.showStatus(self.lang_texts.bought);
                            } else {
                                self.showStatus(self.lang_texts.buyerror, {'error': true});
                            }
                        });
                        self.buying_already_decided = true;
                        $( this ).dialog( "close" );
                        self.elements.infoload.show();
                        self.elements.dialog_info.find( ".messagetext" ).html('');
                        self.elements.dialog_info.dialog('open');
                    },
                    "Nicht kaufen": function() {
                        self.buying_already_decided = true;
                        $( this ).dialog( "close" );
                        self.elements.infoload.show();
                        self.elements.dialog_info.find( ".messagetext" ).html('');
                        self.elements.dialog_info.dialog('open');
                    }
                }
            });
        },
        
        /**
         * Setup forms
         */
        setupForms: function() {
            var self = this;
            
            self.elements.bidform.on('submit', function(e) {
                self.elements.bidform.find( "input[type=submit]" ).hide();
                self.elements.bidform.find( ".submitload" ).show();
                self.performBid().then(function() {
                    self.elements.bidform.find( "input[type=submit]" ).show();
                    self.elements.bidform.find( ".submitload" ).hide();
                });
                e.preventDefault();
            });
            
            self.elements.buyform.on('submit', function(e) {
                self.elements.buyform.find( "input[type=submit]" ).hide();
                self.elements.buyform.find( ".submitload" ).show();
                self.performBuy( self.elements.buyform ).done(function( results ) {
                    if(results.successful) {
                        self.buying_already_decided = true;
                        self.showStatus(self.lang_texts.bought, {'permanent': true});
                        self.disableInterface();
                    } else {
                        self.showStatus(self.lang_texts.buyerror, {'error': true});
                    }
                }).then(function() {
                    self.elements.buyform.find( "input[type=submit]" ).show();
                    self.elements.buyform.find( ".submitload" ).hide();
                });
                e.preventDefault();
            });
            
            self.elements.botform.on('submit', function(e) {
                self.elements.botform.find( "input[type=submit]" ).hide();
                self.elements.botform.find( ".submitload" ).show();
                self.performBot().then(function() {
                    self.elements.botform.find( "input[type=submit]" ).show();
                    self.elements.botform.find( ".submitload" ).hide();
                });
                e.preventDefault();
            });
        },
        
        /**
         * Send ajax request for direct buy
         */
        performBuy: function( form ) {
            return $.ajax({
                url:      form.attr("action"),
                data:     form.serialize(),
                dataType: 'json',
                type:     'POST'
            });
        },
        
        /**
         * Send ajax request for bidding
         */
        performBid: function() {
            var self = this;
            return $.ajax({
                url:      self.elements.bidform.attr("action"),
                data:     self.elements.bidform.serialize(),
                dataType: 'json',
                type:     'POST'
            }).done(function( results ) {
                if(results.successful) {
                    self.showStatus(self.lang_texts.bid);
                } else {
                    self.showStatus(self.lang_texts.biderror, {'error': true});
                }
            });
        },
        
        /**
         * Send ajax request to activate bot
         */
        performBot: function() {
            var self = this;
            return $.ajax({
                url:      self.elements.botform.attr("action"),
                data:     self.elements.botform.serialize(),
                dataType: 'json',
                type:     'POST'
            }).done(function( results ) {
                if(results.successful) {
                    self.showStatus(self.lang_texts.bot, {'permanent': true});
                    self.disableInterface();
                } else {
                    self.showStatus(self.lang_texts.boterror, {'error': true});
                }
            });
        },
        
        /**
         * Activates interface, so enable all buttons and hide statusbar, e.g. if new
         * auction starts
         */
        enableInterface: function() {
            var self = this;
            self.interface_enabled = true;
            self.elements.bidform.find("input[type=submit]").prop({disabled: false});
            self.elements.buyform.find("input[type=submit]").prop({disabled: false});
            self.elements.botform.find("input[type=submit]").prop({disabled: false});
            self.elements.statusbar.slideUp();
        },
        
        /**
         * Deactives interface so nothing can be done, e.g. if direct buy has been done
         */
        disableInterface: function() {
            var self = this;
            self.interface_enabled = false;
            self.elements.bidform.find("input[type=submit]").prop({disabled: true});
            self.elements.buyform.find("input[type=submit]").prop({disabled: true});
            self.elements.botform.find("input[type=submit]").prop({disabled: true});
        },
        
        /**
         * Show status message on the bottom, e.g. used for "bidding successful"
         */
        showStatus: function(message, options) {
            var self = this;
            var settings = {
                error:     false,
                permanent: false,
                delay:     5000
            }
            $.extend( settings, options );
            self.elements.statusbar.clearQueue();
            self.elements.statusbar.find( ".messagetext" ).html(message);
            self.elements.statusbar.toggleClass('statuserror', settings.error);
            if(settings.permanent) {
                self.elements.statusbar.slideDown();
            } else {
                self.elements.statusbar.slideDown().delay(settings.delay).slideUp();
            }
        },
        
        /**
         * Show dialog while waiting and final result after auction
         */
        showWaitingDialog: function( data ) {
            var self = this;
            
            if( self.elements.dialog_info.dialog('isOpen')
                && self.elements.infoload.is(":hidden") ) {
                return;
            }
            
            if(data.show_last) {
                if(data.directbuy_last && !self.buying_already_decided) {
                    $( "#buy_p_money" ).text(data.p_money);
                    $( "#buy_directprice" ).text(data.directprice);
                    $( "#buy_discount" ).text(data.discount);
                    $( "#buy_directtotal" ).text(data.directtotal);
                    self.elements.dialog_directbuy.find( "input[name='directtotal']" ).val(data.directtotal_dec);
                    self.elements.dialog_directbuy.find( "input[name='auction_id']" ).val(data.a_id);
                    if( !(self.elements.dialog_directbuy.dialog('isOpen')) ) {
                        self.elements.dialog_directbuy.dialog('open');
                    }
                } else {
                    var context = {
                        thisiswinner: data.thisiswinner,
                        bidder_bought: data.bidder_bought,
                        a_winner: data.a_winner,
                        a_finalprice: data.a_finalprice,
                        p_money: data.p_money
                    };
                    //Parse Handlebars.js template with variables
                    self.elements.dialog_info.find( ".messagetext" ).html(self.lang_texts.auction_dialog(context));
                    self.elements.infoload.hide();
                    self.elements.dialog_info.dialog('open');
                }
            } else {
                self.elements.dialog_info.find( ".messagetext" ).html(self.lang_texts.wait);
                self.elements.infoload.hide();
                self.elements.dialog_info.dialog('open');
            }
        },
        
        /**
         * Initialize interface when new auction begins
         */
        initAuctionInterface: function( data ) {
            var self = this;
            self.elements.dialog_info.dialog('close');
            self.elements.dialog_directbuy.dialog('close')
            self.enableInterface();
            self.buying_already_decided = false;

            $( "input[name='auction_id']").val(data.a_id);
            $( "#a_priceraise" ).text(data.a_priceraise);
            $( "#p_earned" ).text(data.p_earned);
            $( "#b_valuation" ).text(data.b_valuation);
            $( "#b_bidfee" ).text(data.b_bidfee);
            $( "#a_description" ).text(data.a_description);
            if(data.a_image) {
                $( "#a_image" ).html('<img alt="Product image" src="/uploads/products/'+data.a_image+'" />');
            } else {
                $( "#a_image" ).html("");
            }

            if(data.showbidbutton) {
                self.elements.bidform.find( ".submitbutton" ).show();
            } else {
                self.elements.bidform.find( ".submitbutton" ).hide();
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
        },
        
        displayAuction: function( data ) {
            var self = this;
            if(self.interface_enabled) {
                if( (data.showdirectbuy && data.bidder_bought)
                    || (data.autobid && data.bot_active) ) {
                    self.disableInterface();
                } else if(!data.thisiswinner) {
                    self.elements.bidform.find( "input[type=submit]" ).prop({disabled: false});
                    self.elements.buyform.find( "input[type=submit]" ).prop({disabled: false});
                }
            } else {
                var show_buy = !data.showdirectbuy || (data.showdirectbuy && !data.bidder_bought);
                var show_bot = !data.autobid || (data.autobid && !data.bot_active);
                if( show_buy && show_bot ) {
                    self.enableInterface();
                }
            }

            if(self.elements.curprice.text() != data.a_curprice) {
                self.elements.curprice.effect('highlight', {}, 1000);
            }
            self.elements.curprice.text(data.a_curprice);
            self.elements.input_curprice.val(data.a_curprice_dec);
            self.elements.p_money.text(data.p_money);

            if(data.thisiswinner) {
                self.elements.lastbidder.html(self.lang_texts.thisiswinner);
                self.elements.bidform.find( "input[type=submit]" ).prop({disabled: true});
                self.elements.buyform.find( "input[type=submit]" ).prop({disabled: true});
            } else if(data.a_lastbidder) {
                self.elements.lastbidder.text(data.a_lastbidder);
            } else {
                self.elements.lastbidder.html(self.lang_texts.nowinneryet);
            }

            if(data.showbidders) {
                self.elements.hist_table.html(data.history);
            }

            if(data.showdirectbuy) {
                self.elements.discount.text(data.discount);
                self.elements.directtotal.text(data.directtotal);
                self.elements.input_directtotal.val(data.directtotal_dec);
            }

            //if(data.autobid) {
                //if( data.bot_limitcount < 0 ) {
                    //$( "#botform input[type=submit]" ).buttonstate('disable');
                    //$( "#bot_limitcount" ).text("(0)");
                //} else {
                    //$( "#botform input[type=submit]" ).buttonstate('enable');
                    //$( "#bot_limitcount" ).text("(" + data.bot_limitcount + ")");
                //}
            //}
        },
        
        /**
         * Update auction information, this function is called by ajax request and gets
         * all the data, start initial ajax request
         */
        updateAuction: function( length ) {
            var self = this;
            
            if(self.error) {
                return;
            }
            
            setTimeout(function() {
                self.fetch().done(function( results ) {
                    if(results.active) {
                        if(self.auction_id != results.a_id) {
                            self.auction_id = results.a_id;
                            self.initAuctionInterface(results);
                        }
                        self.base.setCountdown(results.a_timeremain);
                        self.displayAuction(results);
                        self.updateAuction();
                    } else {
                        self.auction_id = undefined;
                        self.showWaitingDialog(results);
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
            if(self.auction_id) {
                return $.ajax({
                    url: this.update_url,
                    data: {auction_id: this.auction_id},
                    dataType: 'json'
                });
            }
            return $.ajax({
                url: this.waiting_url,
                dataType: 'json'
            });
        }
    };
    window.Auction = Auction.init();
})( jQuery );