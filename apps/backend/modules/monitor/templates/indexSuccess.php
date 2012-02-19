<?php if($session !== false && $auction !== false): ?>

<section class="width24" id="statusmessage" style="display:none">
    <article class="box notice">
        <header>
            <h2>Notice</h2>
        </header>
        <p>Action finished!</p>
    </article>
</section>

<div class="width8">
    <article class="box">
        <header>
            <h2>Session information</h2>
        </header>
        <p><span class="label">Name:</span><span class="value"><?php echo $session->name ?></span></p>
        <p><span class="label">Comment:</span><span class="value"><?php echo $session->comment ?></span></p>
        <p><span class="label">Message:</span><span class="value"><?php echo $session->message ?></span></p>
    </article>
</div>
<div class="width8">
    <article class="box">
        <header>
            <h2>Auction information</h2>
        </header>
        <input type="hidden" name="url_update" value="<?php echo url_for('monitor/status') ?>" />
        <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
        <p><span class="label">Name:</span><span class="value"><?php echo $auction->name ?></span></p>
        <p><span class="label">Description:</span><span class="value"><?php echo $auction->description ?></span></p>
        <p><span class="label">History:</span><span class="value"><?php echo ($auction->show_bidders ? 'an' : 'aus') ?></span></p>
        <p><span class="label">Manual bidding:</span><span class="value"><?php echo ($auction->show_bidbutton ? 'an' : 'aus') ?></span></p>
        <p><span class="label">Buy it now:</span><span class="value"><?php echo ($auction->show_directbuy ? sprintf($session->currency, $auction->direct_price) : 'aus') ?></span></p>
        <p><span class="label">Bot behavior:</span><span class="value"><?php echo ($auction->bot_behaviour == 'off' ? '<i>aus</i>' : $auction->bot_behaviour) ?></span></p>
    </article>
</div>
<?php if($running): ?>
<div class="width8" id="auction">
    <article class="box">
        <header>
            <h2>Auction</h2>
        </header>
        <div id="curprice" title="Current auction price"><?php echo sprintf($session->currency, $auction->current_price) ?></div>
        <div id="timeremain" title="Countdown">0:00</div>
        <p>Current winner<br /><span id="a_lastbidder">None</span></p>
        <p>Each bid increases auction price by <span id="a_priceraise"><?php echo sprintf($session->currency, $auction->bid_priceraise) ?></span></p>
    </article>
</div>
<?php else: ?>
<div class="width8" id="auction">
    <article class="box">
        <header>
            <h2>Auction</h2>
        </header>
        <div id="curprice" title="Finale auction price"><?php echo sprintf($session->currency, $auction->current_price) ?></div>
        <p>Winner<br /><span id="a_lastbidder"><?php echo ($auction->relatedExists('LastBidder') ? $auction->LastBidder->Participant->getName() : '<i>Keiner</i>') ?></span></p>
        <p>Each bid increases auction price by <span id="a_priceraise"><?php echo sprintf($session->currency, $auction->bid_priceraise) ?></span></p>
    </article>
</div>
<?php endif; ?>
<div class="width24" id="participants">
    <article class="box">
        <header>
            <h2>Participants</h2>
        </header>
        <table>
          <thead>
            <tr><th>Name</th><th>IP</th><th>Money</th><th>Earned money</th><th>Valuation</th><th>Bid fee</th><th>Buy it now</th><th>Bot active?</th></tr>
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
            <h2>History</h2>
        </header>
        <table>
          <thead>
            <tr><th>Bid</th><th>Bidder</th><th>Type</th></tr>
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
            <tr><th>Active?</th><th>Bidder</th><th>Bid from</th><th>Bid until</th><th>No. of bids</th></tr>
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
            <input type="submit" value="Execute all bots" />
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
            <h2>Actions</h2>
        </header>

        <form method="post" action="<?php echo url_for('assistant/auctionStop') ?>">
          <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
          <input type="submit" name="submit" value="Abort auction prematurely" />
          <input type="submit" name="submit" value="Interrupt auction" />
        </form>
    </article>
</div>
<?php elseif(!$auction->getEndTime()): ?>
<div class="width24">
    <article class="box">
        <header>
            <h2>Actions</h2>
        </header>

        <form method="post" action="<?php echo url_for('assistant/auctionStart') ?>">
          <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
          <input type="submit" value="Start auction" />
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
