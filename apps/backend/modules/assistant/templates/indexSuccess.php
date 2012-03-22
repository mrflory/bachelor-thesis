<div class="width12">
    <article class="box">
        <header>
            <h2>Create new session</h2>
        </header>
        <?php include_partial('form', array('form' => $form)) ?>
    </article>
</div>

<div class="width12">
    <article class="box">
        <header>
            <h2>Manage active session</h2>
        </header>
        <?php if($session): ?>
          <form method="post" action="<?php echo url_for('assistant/sessionDeactivate') ?>" id="sessionform">
          <input type="hidden" name="session_id" value="<?php echo $session->id ?>" />
            <fieldset>
              <legend>Active session</legend>
                <p><span class="label">Name:</span><span class="value"><?php echo $session->name ?></span></p>
                <p><span class="label">Comment:</span><span class="value"><?php echo $session->comment ?></span></p>
                <p><input type="submit" value="Deactivate session" /></p>
            </fieldset>
          </form>

          <?php if($auction): ?>
            <form method="post" action="<?php echo url_for('assistant/auctionStop') ?>" id="auctionform">
            <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
              <fieldset>
                <legend>Active auction</legend>
                  <p><span class="label">Name:</span><span class="value"><?php echo $auction->name ?></span></p>
                  <p><input type="submit" name="submit" value="Abort auction prematurely" /></p>
                  <p><input type="submit" name="submit" value="Interrupt auction" /></p>
              </fieldset>
            </form>
          <?php else: ?>
            <form method="post" action="<?php echo url_for('assistant/auctionStart') ?>" id="auctionform">
              <fieldset>
                <legend>No active auction</legend>
                <p>
                  <label for="auction_id">Choose an auction:</label>
                  <select name="auction_id" id="auction_id">
                    <?php foreach($auctions as $auction): ?>
                      <option value="<?php echo $auction->id ?>"<?php echo ($auction->getEndTime() ? ' disabled' : '') ?>><?php echo $auction->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </p>
                <input type="submit" value="Start auction" />
              </fieldset>
            </form>
          <?php endif; ?>
      
        <?php else: ?>
          <form method="post" action="<?php echo url_for('assistant/sessionActivate') ?>" id="sessionform">
            <fieldset>
              <legend>No active session!</legend>
              <p>
                <label for="session_id">Choose a session:</label>
                <select name="session_id" id="session_id">
                  <?php foreach($sessions as $session): ?>
                    <option value="<?php echo $session->id ?>"><?php echo $session->name ?></option>
                  <?php endforeach; ?>
                </select>
              </p>
              <input type="submit" value="Activate session" />
            </fieldset>
          </form>
        <?php endif; ?>
    </article>
</div>

<?php use_javascript('assistant.js') ?>
