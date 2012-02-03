<?php if($participant): ?>
<div class="width8">
    <article class="box">
        <header>
            <h2>Teilnehmer-Informationen</h2>
        </header>
        <!--<p><span class="label">IP:</span><span class="value" id="p_ip"><?php echo $userip ?></span></p>-->
        <p><span class="label">Name:</span><span class="value" id="p_name"><?php echo $participant->getName() ?></span></p>
        <p><span class="label" title="Ihr noch verfügbares Guthaben">Ihr Guthaben:</span><span class="value" id="p_money"><?php echo $money ?></span></p>
        <p><span class="label" title="Summe der Produktwerte bisher gewonnener Auktionen oder gekaufter Produkte">Geldwerte Einnahmen:</span><span class="value" id="p_earned">0</span></p>
        <p><?php echo $message ?></p>
    </article>
</div>
<?php endif; ?>
