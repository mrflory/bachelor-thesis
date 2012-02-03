<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('assistant/assistantCreate') ?>" method="post">
  <?php echo $form->renderHiddenFields() ?>
  <fieldset>
    <legend>Sessiondaten</legend>
    <p><label for="session_name">Name:</label><input type="text" id="session_name" name="session[name]" required /></p>
    <p><label for="currency">Währung:</label><input type="text" id="currency" name="session[currency]" required /></p>
  </fieldset>
  <fieldset>
    <legend>Einstellungen</legend>
    <p><label for="anz_part">Anzahl Teilnehmer:</label><input type="number" id="anz_part" name="session[anz_part]" min="1" value="1" required /></p>
    <p><label for="anz_auct">Anzahl Auktionen:</label><input type="number" id="anz_auct" name="session[anz_auct]" min="1" value="1" required /></p>
  </fieldset>
  <fieldset>
    <legend>Standardwerte</legend>
    <p><label for="money">Teilnehmer Guthaben:</label><input type="number" id="money" name="session[money]" min="0" value="0" required /></p>
    <p><label for="countdown">Auktion Countdown:</label><input type="number" id="countdown" name="session[countdown]" min="1" value="60" required /></p>
    <p><label for="bid_priceraise">Preissteigerung:</label><input type="number" id="bid_priceraise" name="session[bid_priceraise]" min="0" value="1" step="0.01" required /></p>
    <p><label for="bid_fee">Gebühr:</label><input type="number" id="bid_fee" name="session[bid_fee]" min="0" value="0.1" step="0.01" required /></p>
  </fieldset>
  <input type="submit" value="Anlegen" />
</form>

<?php /*
<form action="<?php echo url_for('bid/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?auction_id='.$form->getObject()->getAuctionId().'&bidder_id='.$form->getObject()->getBidderId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          &nbsp;<a href="<?php echo url_for('bid/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'bid/delete?auction_id='.$form->getObject()->getAuctionId().'&bidder_id='.$form->getObject()->getBidderId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
 */ ?>
