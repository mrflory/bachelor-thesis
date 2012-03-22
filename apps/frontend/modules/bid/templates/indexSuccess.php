<?php if($participant): ?>
<div class="width8">
    <article class="box">
        <header>
            <h2><?php echo __('Teilnehmer-Informationen') ?></h2>
        </header>
        <!--<p><span class="label">IP:</span><span class="value" id="p_ip"><?php echo $userip ?></span></p>-->
        <p><span class="label"><?php echo __('Name:') ?></span><span class="value" id="p_name"><?php echo $participant->getName() ?></span></p>
        <p><span class="label" title="<?php echo __('Ihr noch verfügbares Guthaben') ?>"><?php echo __('Ihr Guthaben:') ?></span><span class="value" id="p_money"><?php echo $money ?></span></p>
        <p><span class="label" title="<?php echo __('Summe der Produktwerte bisher gewonnener Auktionen oder gekaufter Produkte') ?>"><?php echo __('Geldwerte Einnahmen:') ?></span><span class="value" id="p_earned">0</span></p>
        <p><?php echo $message ?></p>
    </article>
</div>
<?php endif; ?>
