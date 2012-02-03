<?php

/**
 * Nur für Debugging Zwecke
 */

?>

<div class="width8">
    <article class="box">
        <header>
            <h2>Status</h2>
        </header>

      <?php if($activeAuction == false): ?>
        Keine aktiven Auktionen
        <?php #echo url_for('content/update') ?>
        <?php #echo link_to('I never say my name', 'content/update?name=anonymous') ?>
      <?php else: ?>
        <?php echo $activeAuction->getId() ?><br />
        <?php echo $activeAuction->getName() ?><br />
        <?php echo $activeAuction->getCurrentCountdown() ?><br />
        <?php echo $participant->getMoney() ?><br />
        <?php echo $bidder->getValuation() ?><br />
        <?php echo $history ?><br />
      <?php endif; ?>
      
    </article>
</div>


