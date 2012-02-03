<div class="width12">
    <article class="box">
        <header>
            <h2>Neue Session erstellen</h2>
        </header>
        <?php include_partial('form', array('form' => $form)) ?>
    </article>
</div>

<div class="width12">
    <article class="box">
        <header>
            <h2>Aktive Session verwalten</h2>
        </header>
        <?php if($session): ?>
          <form method="post" action="<?php echo url_for('assistant/sessionDeactivate') ?>" id="sessionform">
          <input type="hidden" name="session_id" value="<?php echo $session->id ?>" />
            <fieldset>
              <legend>Aktive Session</legend>
                <p><span class="label">Name:</span><span class="value"><?php echo $session->name ?></span></p>
                <p><span class="label">Kommentar:</span><span class="value"><?php echo $session->comment ?></span></p>
                <p><input type="submit" value="Session deaktivieren" /></p>
            </fieldset>
          </form>

          <?php if($auction): ?>
            <form method="post" action="<?php echo url_for('assistant/auctionStop') ?>" id="auctionform">
            <input type="hidden" name="auction_id" value="<?php echo $auction->id ?>" />
              <fieldset>
                <legend>Aktive Auktion</legend>
                  <p><span class="label">Name:</span><span class="value"><?php echo $auction->name ?></span></p>
                  <p><input type="submit" name="submit" value="Auktion vorzeitig beenden" /></p>
                  <p><input type="submit" name="submit" value="Auktion unterbrechen" /></p>
              </fieldset>
            </form>
          <?php else: ?>
            <form method="post" action="<?php echo url_for('assistant/auctionStart') ?>" id="auctionform">
              <fieldset>
                <legend>Keine Auktion aktiv!</legend>
                <p>
                  <label for="auction_id">Wählen Sie eine Auktion:</label>
                  <select name="auction_id" id="auction_id">
                    <?php foreach($auctions as $auction): ?>
                      <option value="<?php echo $auction->id ?>"<?php echo ($auction->getEndTime() ? ' disabled' : '') ?>><?php echo $auction->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </p>
                <input type="submit" value="Auktion starten" />
              </fieldset>
            </form>
          <?php endif; ?>
      
        <?php else: ?>
          <form method="post" action="<?php echo url_for('assistant/sessionActivate') ?>" id="sessionform">
            <fieldset>
              <legend>Keine Session aktiv!</legend>
              <p>
                <label for="session_id">Wählen Sie eine Session:</label>
                <select name="session_id" id="session_id">
                  <?php foreach($sessions as $session): ?>
                    <option value="<?php echo $session->id ?>"><?php echo $session->name ?></option>
                  <?php endforeach; ?>
                </select>
              </p>
              <input type="submit" value="Session aktivieren" />
            </fieldset>
          </form>
        <?php endif; ?>
    </article>
</div>


<?php echo javascript_include_tag('assistant'); ?>
