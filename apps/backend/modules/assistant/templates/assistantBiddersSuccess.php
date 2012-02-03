<form action="<?php echo url_for('assistant/assistantSave') ?>" method="post">
<div class="width24">
    <article class="box">
        <header>
            <h2>Bieter</h2>
        </header>
        <table>
          <thead>
            <tr><th>Teilnehmer</th><th>Bewertung</th><th>Bietgebühr</th></tr>
          </thead>
          <tbody>
            <?php $x = 0; foreach($auctions as $i => $auction): ?>
            <tr>
              <th colspan="3"><?php echo $auction->name ?></th>
            </tr>
              <?php foreach($participants as $j => $participant): ?>
              <tr>
                <td><input type="hidden" name="bidder[<?php echo $x ?>][id]" value="<?php echo $bidders[$i][$j]->getId() ?>" /><?php echo $participant->name ?></td>
                <td><input type="number" name="bidder[<?php echo $x ?>][valuation]" min="0" /></td>
                <td><input type="number" name="bidder[<?php echo $x ?>][bid_fee]" min="0" step="0.01" /></td>
              </tr>
              <?php $x++; endforeach; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
    </article>
</div>
<div class="width24">
    <article class="box">
        <header>
            <h2>Assistent</h2>
        </header>

        <div style="text-align: right">
          <input type="submit" value="Fertig" />
        </div>
    </article>
</div>
</form>

<?php echo javascript_include_tag('assistant'); ?>