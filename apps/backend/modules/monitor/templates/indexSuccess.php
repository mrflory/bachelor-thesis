<?php if($session !== false && $auction !== false): ?>

<section class="width24" id="statusmessage" style="display:none">
    <article class="box notice">
        <header>
            <h2>Notice</h2>
        </header>
        <p>Auktion beendet!</p>
    </article>
</section>

<div class="width8">
    <article class="box">
        <header>
            <h2>Session-Informationen</h2>
        </header>
        <p><span class="label">Name:</span><span class="value"><?php echo $session->name ?></span></p>
        <p><span class="label">Kommentar:</span><span class="value"><?php echo $session->comment ?></span></p>
        <p><span class="label">Nachricht:</span><span class="value"><?php echo $session->message ?></span></p>
    </article>
</div>
<div class="width8">
    <article class="box">
        <header>
            <h2>Auktions-Informationen</h2>
        </header>
        <input type="hidden" name="url_update" value="<?php echo url_for('monitor/status') ?>" />
        <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
        <p><span class="label">Name:</span><span class="value"><?php echo $auction->name ?></span></p>
        <p><span class="label">Beschreibung:</span><span class="value"><?php echo $auction->description ?></span></p>
        <p><span class="label">Verlauf:</span><span class="value"><?php echo ($auction->show_bidders ? 'an' : 'aus') ?></span></p>
        <p><span class="label">Manuelles Bieten:</span><span class="value"><?php echo ($auction->show_bidbutton ? 'an' : 'aus') ?></span></p>
        <p><span class="label">Sofortkauf:</span><span class="value"><?php echo ($auction->show_directbuy ? sprintf($session->currency, $auction->direct_price) : 'aus') ?></span></p>
        <p><span class="label">Botverhalten:</span><span class="value"><?php echo ($auction->bot_behaviour == 'off' ? '<i>aus</i>' : $auction->bot_behaviour) ?></span></p>
    </article>
</div>
<?php if($running): ?>
<div class="width8" id="auction">
    <article class="box">
        <header>
            <h2>Auktion</h2>
        </header>
        <div id="curprice" title="Aktueller Auktionspreis"><?php echo sprintf($session->currency, $auction->current_price) ?></div>
        <div id="timeremain" title="Countdown">0:00</div>
        <p>Aktueller Gewinner<br /><span id="a_lastbidder">Keiner</span></p>
        <p>Gebot erhöht den Auktionspreis um <span id="a_priceraise"><?php echo sprintf($session->currency, $auction->bid_priceraise) ?></span></p>
    </article>
</div>
<?php else: ?>
<div class="width8" id="auction">
    <article class="box">
        <header>
            <h2>Auktion</h2>
        </header>
        <div id="curprice" title="Finaler Auktionspreis"><?php echo sprintf($session->currency, $auction->current_price) ?></div>
        <p>Gewinner<br /><span id="a_lastbidder"><?php echo ($auction->relatedExists('LastBidder') ? $auction->LastBidder->Participant->getName() : '<i>Keiner</i>') ?></span></p>
        <p>Gebot erhöht den Auktionspreis um <span id="a_priceraise"><?php echo sprintf($session->currency, $auction->bid_priceraise) ?></span></p>
    </article>
</div>
<?php endif; ?>
<div class="width24" id="participants">
    <article class="box">
        <header>
            <h2>Teilnehmer</h2>
        </header>
        <table>
          <thead>
            <tr><th>Name</th><th>IP</th><th>Guthaben</th><th>Einnahmen</th><th>Bewertung</th><th>Bietgebühr</th><th>Sofortkauf</th><th>Bot aktiv?</th></tr>
          </thead>
          <tbody>
            <!--<tr><td>Bieter</td><td>1234</td><td>100</td><td>100</td><td>0.1</td><td>-</td><td>ja</td></tr>-->
            <?php include_partial('monitor/participants', array('participants' => $auction->getBidder())) ?>
          </tbody>
        </table>
    </article>
</div>
<div class="width12" id="history">
    <article class="box">
        <header>
            <h2>Verlauf</h2>
        </header>
        <table>
          <thead>
            <tr><th>Gebot</th><th>Bieter</th><th>Typ</th></tr>
          </thead>
          <tbody>
            <!--<tr><td>1</td><td>Bieter</td><td>Manuell</td></tr>-->
            <?php include_partial('monitor/history', array('history' => $auction->getHistory(10))) ?>
          </tbody>
        </table>
    </article>
</div>
<div class="width12" id="bots">
    <article class="box">
        <header>
            <h2>Bots</h2>
        </header>
        <table>
          <thead>
            <tr><th>Aktiv?</th><th>Bieter</th><th>Bieten ab</th><th>Bieten bis</th><th>Anz. Gebote</th></tr>
          </thead>
          <tbody>
            <!--<tr><td>x</td><td>Bieter</td><td>2</td><td>5</td><td>10</td></tr>-->
            <?php include_partial('monitor/bots', array('bots' => $auction->getBots())) ?>
          </tbody>
        </table>
        <?php if($running): ?>
        <form method="post" action="<?php echo url_for('monitor/bidAllBots') ?>" id="allbotsform">
          <div class="submitbutton">
            <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
            <input type="submit" value="Alle Bots ausführen" />
            <img style="display:none;" class="submitload" src="/images/ajax-loader.gif" alt="Lade..." />
          </div>
        </form>
        <?php endif; ?>
    </article>
</div>

<?php if($running): ?>
<div class="width24">
    <article class="box">
        <header>
            <h2>Aktionen</h2>
        </header>

        <form method="post" action="<?php echo url_for('assistant/auctionStop') ?>">
          <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
          <input type="submit" name="submit" value="Auktion vorzeitig beenden" />
          <input type="submit" name="submit" value="Auktion unterbrechen" />
        </form>
    </article>
</div>
<?php elseif(!$auction->getEndTime()): ?>
<div class="width24">
    <article class="box">
        <header>
            <h2>Aktionen</h2>
        </header>

        <form method="post" action="<?php echo url_for('assistant/auctionStart') ?>">
          <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
          <input type="submit" value="Auktion starten" />
        </form>
    </article>
</div>
<?php endif; ?>

<?php
if($running) {
  echo javascript_include_tag('base');
  echo javascript_include_tag('monitor');
}
?>

<?php endif; ?>