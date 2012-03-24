<!DOCTYPE html>
<html lang="de">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
  </head>
  <body>
    <header>
        <h1><?php echo __(sfConfig::get('app_title')) ?></h1>
    </header>
    <div id="wrapper">
        <section class="width24" id="instructions">
            <article class="box">
                <header class="visible">
                    <h2><?php echo __('Anleitung') ?></h2>
                </header>
                <p>
                    <?php echo __('Anleitung.Text') ?>

                </p>
            </article>
        </section>

        <?php if ($sf_user->hasFlash('error')): ?>
        <section class="width24">
            <article class="box error">
                <header>
                    <h2><?php echo __('Fehler') ?></h2>
                </header>
                <?php echo __($sf_user->getFlash('error')) ?>
            </article>
        </section>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('notice')): ?>
        <section class="width24">
            <article class="box notice">
                <header>
                    <h2><?php echo __('Hinweis') ?></h2>
                </header>
                <?php echo __($sf_user->getFlash('notice')) ?>
            </article>
        </section>
        <?php endif ?>

        <section>
            <?php echo $sf_content ?>

          <?php if($sf_user->hasAttribute('participant_id')): ?>

            <div class="width8" id="auction">
                <article class="box">
                    <header>
                        <h2><?php echo __('Auktion') ?></h2>
                    </header>
                    <div id="curprice" title="<?php echo __('Aktueller Auktionspreis') ?>">0.00</div>
                    <div id="timeremain" title="<?php echo __('Countdown') ?>">0:00</div>
                    <p><?php echo __('Aktueller Gewinner') ?><br /><span id="a_lastbidder">Teilnehmer #1</span></p>
                    <form method="post" action="<?php echo url_for('bid/placebid') ?>" id="bidform">
                      <div class="submitbutton">
                        <input type="hidden" name="auction_id" value="" />
                        <input type="hidden" name="curprice" value="" />
                        <input type="submit" value="<?php echo __('Bieten') ?>" />
                        <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="..." />
                      </div>
                    </form>
                    <p><?php echo __('Gebot erhöht den Auktionspreis um <span id="a_priceraise">1 Euro</span>') ?></p>
                </article>
            </div>
            <div class="width8">
                <article class="box">
                    <header>
                        <h2><?php echo __('Auktions-Informationen') ?></h2>
                    </header>
                    <p id="a_image"></p>
                    <p id="a_description"></p>
                    <p><span class="label"><?php echo __('Wert des Produkts:') ?></span><span class="value" id="b_valuation"></span></p>
                    <p><span class="label"><?php echo __('Gebühr pro Gebot:') ?></span><span class="value" id="b_bidfee"></span></p>
                </article>
            </div>
          </section>
          <section>
            <div class="width8" id="directbuy">
                <article class="box">
                    <header>
                        <h2><?php echo __('Sofortkauf') ?></h2>
                    </header>
                    <p><span class="label"><?php echo __('Produktpreis:') ?></span><span class="value" id="directprice"></span></p>
                    <p><span class="label" title="<?php echo __('Summe bisher ausgegebener Bietgebühren') ?>"><?php echo __('Rabatt:') ?></span><span class="value" id="discount"></span></p>
                    <p><span class="label" title="<?php echo __('Produktpreis abzüglich Rabatt') ?>"><?php echo __('Kaufpreis:') ?></span><span class="value" id="directtotal"></span></p>
                    <form method="post" action="<?php echo url_for('bid/directbuy') ?>" id="buyform">
                      <div class="submitbutton">
                        <input type="hidden" name="auction_id" value="" />
                        <input type="hidden" name="directtotal" value="" />
                        <input type="submit" value="<?php echo __('Kaufen') ?>" />
                        <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="..." />
                      </div>
                    </form>
                </article>
            </div>
            <div class="width8" id="bidbot">
                <article class="box">
                    <header>
                        <h2><?php echo __('Biet-Automat') ?></h2>
                    </header>
                  <form method="post" action="<?php echo url_for('bid/activatebot') ?>" id="botform">
                    <input type="hidden" name="auction_id" value="" />
                    <p><label for="autostart" title="<?php echo __('Mindest-Auktionspreis ab dem der Biet-Automat bieten soll') ?>"><?php echo __('Bieten ab:') ?></label><input type="number" id="autostart" name="bot[start]" min="0" step="0.1" value="0" required /></p>
                    <p><label for="autoend" title="<?php echo __('Maximal-Auktionspreis bis zu dem der Biet-Automat bieten soll') ?>"><?php echo __('Bieten bis:') ?></label><input type="number" id="autoend" name="bot[end]" min="0" step="0.1" value="0" required /></p>
                    <p><label for="autonumbids" title="<?php echo __('Maximale Anzahl an Geboten, die der Biet-Automat machen soll') ?>"><?php echo __('Anz. Gebote:') ?></label><input type="number" id="autonumbids" name="bot[init_numbids]" min="1" max="999" value="1" required /></p>
                    <div class="submitbutton">
                      <input type="submit" value="<?php echo __('Aktivieren') ?>" />
                      <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="..." />
                    </div>
                  </form>
                </article>
            </div>
            <div class="width8" id="history">
                <article class="box">
                    <header>
                        <h2><?php echo __('Verlauf') ?></h2>
                    </header>
                    <table>
                      <thead>
                        <tr><th><?php echo __('Gebot') ?></th><th><?php echo __('Bieter') ?></th><th><?php echo __('Typ') ?></th></tr>
                      </thead>
                      <tbody>
                        <tr><td>1</td><td>Bieter</td><td>Manuell</td></tr>
                      </tbody>
                    </table>
                </article>
            </div>

          <?php endif; ?>

        </section>
        <footer>
            Bachelor-Arbeit, Florian Stallmann, Universität Paderborn, Information Management &amp; E-Finance
        </footer>
    </div>

  <div id="dialog_info" style="display:none" title="<?php echo __('Hinweis') ?>">
    <p>
      <span class="ui-icon ui-icon-info"></span>
      <span class="messagetext">TEXT</span>
      <img style="display:none;" id="infoload" src="/images/ajax-loader.gif" alt="..." />
    </p>
  </div>
  <div id="dialog_error" style="display:none" title="Error">
    <p>
      <span class="ui-icon ui-icon-alert"></span>
      <span class="messagetext">TEXT</span>
    </p>
  </div>
  <div id="dialog_directbuy" style="display:none" title="<?php echo __('Sofortkauf') ?>">
    <p>
      <span class="ui-icon ui-icon-help"></span>
      <span class="messagetext"><?php echo __('Sie haben die letzte Auktion nicht gewonnen. Wollen Sie das Produkt nun kaufen?') ?></span>
    </p>
    <form method="post" action="<?php echo url_for('bid/directbuy') ?>" id="dialog_buyform">
      <input type="hidden" name="auction_id" value="" />
      <input type="hidden" name="directtotal" value="" />
      <p><span class="label"><?php echo __('Ihr Guthaben:') ?></span><span class="value" id="buy_p_money"></span></p>
      <p><span class="label"><?php echo __('Produktpreis:') ?></span><span class="value" id="buy_directprice"></span></p>
      <p><span class="label"><?php echo __('Rabatt:') ?></span><span class="value" id="buy_discount"></span></p>
      <p><span class="label"><?php echo __('Kaufpreis:') ?></span><span class="value" id="buy_directtotal"></span></p>
    </form>
  </div>
  <div id="statusbar" style="display:none">
    <p>
      <span class="ui-icon ui-icon-carat-1-e"></span>
      <span class="messagetext">TEXT</span>
    </p>
  </div>
      
    <script id="auction-dialog-templ" type="text/x-handlebars-template">
        {{#if thisiswinner}}
          <?php echo __('Sie haben die letzte Auktion zu einem Preis von {{a_finalprice}} gewonnen.') ?>
        {{else}}
            {{#if bidder_bought}}
                <?php echo __('Sie haben die letzte Auktion mit einem Sofortkauf abgeschlossen.') ?>
            {{/if}}
            {{#if a_winner}}
                <?php echo __('Die letzte Auktion hat {{a_winner}} zu einem Preis von {{a_finalprice}} gewonnen.') ?>
            {{else}}
                <?php echo __('Die letzte Auktion hat niemand gewonnen.') ?>
            {{/if}}
        {{/if}}
        <br />
        <?php echo __('Sie haben noch ein Guthaben von {{p_money}}. Bitte warten Sie auf die nächste Auktion.') ?>
    </script>
    <script type="text/javascript">
        window.AuctionTranslations = {
            thisiswinner: '<?php echo __('<strong>Sie!</strong>') ?>',
            nowinneryet:  '<?php echo __('<i>Noch keiner!</i>') ?>',
            wait:         '<?php echo __('Derzeit ist keine Auktion aktiv.<br />Bitte warten.') ?>',
            bought:       '<?php echo __('Produkt gekauft! - Bitte warten Sie auf die nächste Auktion.') ?>',
            buyerror:     '<?php echo __('Kauf fehlgeschlagen! - Bitte wenden Sie sich an den Leiter.') ?>',
            bid:          '<?php echo __('Gebot abgegeben!') ?>',
            biderror:     '<?php echo __('Bieten fehlgeschlagen! - Eventuell wurden Sie zwischenzeitlich überboten.') ?>',
            bot:          '<?php echo __('Biet-Automat aktiviert! - Sie können nun nicht mehr manuell bieten.') ?>',
            boterror:     '<?php echo __('Aktivierung fehlgeschlagen! - Bitte überprüfen Sie Ihre Eingaben.') ?>',
            buttonbuy:    '<?php echo __('Kaufen') ?>',
            buttonnobuy:  '<?php echo __('Nicht kaufen') ?>',
            auction_dialog: '#auction-dialog-templ'
        };
    </script>
    <?php include_javascripts() ?>
  </body>
</html>
