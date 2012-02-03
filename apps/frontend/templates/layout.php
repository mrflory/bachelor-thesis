<!DOCTYPE html>
<html lang="de">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <header>
        <h1><?php echo sfConfig::get('app_title') ?></h1>
    </header>
    <div id="wrapper">
        <section class="width24" id="instructions">
            <article class="box">
                <header class="visible">
                    <h2>Anleitung</h2>
                </header>
                <p>
                  Sie sind Teilnehmer einer Pay-per-Bid Auktion, d.h. Sie zahlen eine Gebühr für jedes abgegebene Gebot und mit jedem Gebot erhöht sich der Auktionspreis
                  und der Countdown. Wenn der Countdown bei 0 ankommt, hat der letzte Bieter das Produkt gewonnen und muss den finalen Auktionspreis zahlen.<br />
                  Der Wert des Produkts und die Kosten für ein Gebot sind unten angegeben. Je nach Auktionseinstellung sehen Sie außerdem die letzten 5 Gebote.<br />
                  In einigen Auktionen haben Sie die Möglichkeit, das Produkt sofort zu kaufen, wobei Sie einen Rabatt in Höhe der bisher abgegebenen Gebot erhalten. Falls Sie
                  bis zum Auktionsende mit dem Sofortkauf warten möchten, können Sie den Kauf auch nach Ablauf des Countdown tätigen.<br />
                  Des weiteren können Sie in manchen Auktionen ein Biet-Automat aktivieren. Dieser bietet ab einem Startpreis automatisch die von Ihnen angegebene Anzahl von Geboten
                  bis zu einem Höchstpreis. Bitte beachten Sie, dass der Biet-Automat vor Ablauf der letzten 4-5 Sekunden aktiviert werden muss, da ansonsten nicht garantiert werden kann,
                  dass der Biet-Automat noch ein Gebot abgibt. Während der Biet-Automat aktiv ist, können Sie außerdem nicht manuell bieten und ein vorzeitiges Deaktivieren ist ebenfalls nicht möglich.
                </p>
            </article>
        </section>

        <?php if ($sf_user->hasFlash('error')): ?>
        <section class="width24">
            <article class="box error">
                <header>
                    <h2>Fehler</h2>
                </header>
                <?php echo $sf_user->getFlash('error') ?>
            </article>
        </section>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('notice')): ?>
        <section class="width24">
            <article class="box notice">
                <header>
                    <h2>Hinweis</h2>
                </header>
                <?php echo $sf_user->getFlash('notice') ?>
            </article>
        </section>
        <?php endif ?>

        <section>
            <?php echo $sf_content ?>

          <?php if($sf_user->hasAttribute('participant_id')): ?>

            <div class="width8" id="auction">
                <article class="box">
                    <header>
                        <h2>Auktion</h2>
                    </header>
                    <div id="curprice" title="Aktueller Auktionspreis">0.00</div>
                    <div id="timeremain" title="Countdown">0:00</div>
                    <p>Aktueller Gewinner<br /><span id="a_lastbidder">Teilnehmer #1</span></p>
                    <form method="post" action="<?php echo url_for('bid/placebid') ?>" id="bidform">
                      <div class="submitbutton">
                        <input type="hidden" name="auction_id" value="" />
                        <input type="hidden" name="curprice" value="" />
                        <input type="submit" value="Bieten" />
                        <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="Lade..." />
                      </div>
                    </form>
                    <p>Gebot erhöht den Auktionspreis um <span id="a_priceraise">1.00 Euro</span></p>
                </article>
            </div>
            <div class="width8">
                <article class="box">
                    <header>
                        <h2>Auktions-Informationen</h2>
                    </header>
                    <p id="a_image"></p>
                    <p id="a_description"></p>
                    <p><span class="label">Wert des Produkts:</span><span class="value" id="b_valuation"></span></p>
                    <p><span class="label">Gebühr pro Gebot:</span><span class="value" id="b_bidfee"></span></p>
                </article>
            </div>
          </section>
          <section>
            <div class="width8" id="directbuy">
                <article class="box">
                    <header>
                        <h2>Sofortkauf</h2>
                    </header>
                    <p><span class="label">Produktpreis:</span><span class="value" id="directprice"></span></p>
                    <p><span class="label" title="Summe bisher ausgegebener Bietgebühren">Rabatt:</span><span class="value" id="discount"></span></p>
                    <p><span class="label" title="Produktpreis abzüglich Rabatt">Kaufpreis:</span><span class="value" id="directtotal"></span></p>
                    <form method="post" action="<?php echo url_for('bid/directbuy') ?>" id="buyform">
                      <div class="submitbutton">
                        <input type="hidden" name="auction_id" value="" />
                        <input type="hidden" name="directtotal" value="" />
                        <input type="submit" value="Kaufen" />
                        <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="Lade..." />
                      </div>
                    </form>
                </article>
            </div>
            <div class="width8" id="bidbot">
                <article class="box">
                    <header>
                        <h2>Biet-Automat</h2>
                    </header>
                  <form method="post" action="<?php echo url_for('bid/activatebot') ?>" id="botform">
                    <input type="hidden" name="auction_id" value="" />
                    <p><label for="autostart" title="Mindest-Auktionspreis ab dem der Biet-Automat bieten soll">Bieten ab:</label><input type="number" id="autostart" name="bot[start]" min="0" step="0.1" value="0" required /></p>
                    <p><label for="autoend" title="Maximal-Auktionspreis bis zu dem der Biet-Automat bieten soll">Bieten bis:</label><input type="number" id="autoend" name="bot[end]" min="0" step="0.1" value="0" required /></p>
                    <p><label for="autonumbids" title="Maximale Anzahl an Geboten, die der Biet-Automat machen soll">Anz. Gebote:</label><input type="number" id="autonumbids" name="bot[init_numbids]" min="1" max="999" value="1" required /></p>
                    <div class="submitbutton">
                      <input type="submit" value="Aktivieren" />
                      <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="Lade..." />
                    </div>
                  </form>
                </article>
            </div>
            <div class="width8" id="history">
                <article class="box">
                    <header>
                        <h2>Verlauf</h2>
                    </header>
                    <table>
                      <thead>
                        <tr><th>Gebot</th><th>Bieter</th><th>Typ</th></tr>
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

  <div id="dialog_info" style="display:none" title="Hinweis">
    <p>
      <span class="ui-icon ui-icon-info"></span>
      <span class="messagetext">TEXT</span>
      <img style="display:none;" id="infoload" src="/images/ajax-loader.gif" alt="Lade..." />
    </p>
  </div>
  <div id="dialog_error" style="display:none" title="Fehler">
    <p>
      <span class="ui-icon ui-icon-alert"></span>
      <span class="messagetext">TEXT</span>
    </p>
  </div>
  <div id="dialog_directbuy" style="display:none" title="Sofortkauf">
    <p>
      <span class="ui-icon ui-icon-help"></span>
      <span class="messagetext">Sie haben die letzte Auktion nicht gewonnen. Wollen Sie das Produkt nun kaufen?</span>
    </p>
    <form method="post" action="<?php echo url_for('bid/directbuy') ?>" id="dialog_buyform">
      <input type="hidden" name="auction_id" value="" />
      <input type="hidden" name="directtotal" value="" />
      <p><span class="label">Ihr Guthaben:</span><span class="value" id="buy_p_money"></span></p>
      <p><span class="label">Produktpreis:</span><span class="value" id="buy_directprice"></span></p>
      <p><span class="label">Rabatt:</span><span class="value" id="buy_discount"></span></p>
      <p><span class="label">Kaufpreis:</span><span class="value" id="buy_directtotal"></span></p>
    </form>
  </div>
  <div id="statusbar" style="display:none">
    <p>
      <span class="ui-icon ui-icon-carat-1-e"></span>
      <span class="messagetext">TEXT</span>
    </p>
  </div>
  </body>
</html>
