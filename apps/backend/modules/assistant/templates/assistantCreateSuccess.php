<form action="<?php echo url_for('assistant/assistantBidders') ?>" method="post" enctype="multipart/form-data">
<div class="width12">
    <article class="box">
        <header>
            <h2>Session</h2>
        </header>
        <p><label for="session_name">Name:</label><br /><input type="text" id="session_name" name="session[name]" value="<?php echo $data['name'] ?>" required /></p>
        <p><label for="currency">Currency:</label><br /><input type="text" id="currency" name="session[currency]" value="<?php echo $data['currency'] ?>" required /></p>
        <p><label for="session_comment">Internal comment:</label><br /><textarea name="session[comment]" id="session_comment"></textarea></p>
        <p><label for="session_message">Message for participants:</label><br /><textarea name="session[message]" id="session_message"></textarea></p>
    </article>
</div>
<div class="width12">
    <article class="box">
        <header>
            <h2>Participants</h2>
        </header>
        <table>
          <thead>
            <tr><th>Name</th><th>Money</th></tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$data['anz_part'];$i++): ?>
            <tr>
              <td><input type="text" name="participant[<?php echo $i ?>][name]" value="Teilnehmer #<?php echo $i+1 ?>" required /></td>
              <td><input type="number" name="participant[<?php echo $i ?>][init_money]" min="0" value="<?php echo $data['money'] ?>" required /></td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
    </article>
</div>
<div class="width24">
    <article class="box">
        <header>
            <h2>Auctions</h2>
        </header>
        <table class="assistant">
          <colgroup>
            <col width="30%" />
            <col width="30%" />
            <col width="20%" />
            <col width="20%" />
          </colgroup>
          <thead>
            <tr><th>Name / Interface</th><th>Bot / Auction type</th><th>Buy it now</th><th>Bidder default values</th></tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$data['anz_auct'];$i++): ?>
            <tr>
              <td>
                <input type="text" name="auction[<?php echo $i ?>][name]" value="Auktion #<?php echo $i+1 ?>" required />
                
                <div class="radio">
                  <input type="radio" id="auction_<?php echo $i ?>_show_bidders1" name="auction[<?php echo $i ?>][show_bidders]" value="1" checked /><label for="auction_<?php echo $i ?>_show_bidders1">an</label>
                  <input type="radio" id="auction_<?php echo $i ?>_show_bidders2" name="auction[<?php echo $i ?>][show_bidders]" value="0" /><label for="auction_<?php echo $i ?>_show_bidders2">aus</label>
                  <span class="label">History</span>
                </div>
                <div class="radio">
                  <input type="radio" id="auction_<?php echo $i ?>_show_bidbutton1" name="auction[<?php echo $i ?>][show_bidbutton]" value="1" checked /><label for="auction_<?php echo $i ?>_show_bidbutton1">an</label>
                  <input type="radio" id="auction_<?php echo $i ?>_show_bidbutton2" name="auction[<?php echo $i ?>][show_bidbutton]" value="0" /><label for="auction_<?php echo $i ?>_show_bidbutton2">aus</label>
                  <span class="label">Manual bidding</span>
                </div>
                <div class="radio">
                  <input type="radio" id="auction_<?php echo $i ?>_show_directbuy1" name="auction[<?php echo $i ?>][show_directbuy]" value="1" /><label for="auction_<?php echo $i ?>_show_directbuy1">an</label>
                  <input type="radio" id="auction_<?php echo $i ?>_show_directbuy2" name="auction[<?php echo $i ?>][show_directbuy]" value="0" checked /><label for="auction_<?php echo $i ?>_show_directbuy2">aus</label>
                  <span class="label">Buy it now</span>
                </div>
              </td>
              <td>
                <div class="radio">
                  <input type="radio" id="auction_<?php echo $i ?>_bot_behaviour1" name="auction[<?php echo $i ?>][bot_behaviour]" value="off" checked /><label for="auction_<?php echo $i ?>_bot_behaviour1">off</label>
                  <input type="radio" id="auction_<?php echo $i ?>_bot_behaviour2" name="auction[<?php echo $i ?>][bot_behaviour]" value="all" /><label for="auction_<?php echo $i ?>_bot_behaviour2">all</label>
                  <input type="radio" id="auction_<?php echo $i ?>_bot_behaviour3" name="auction[<?php echo $i ?>][bot_behaviour]" value="lru" /><label for="auction_<?php echo $i ?>_bot_behaviour3">lru</label>
                  <input type="radio" id="auction_<?php echo $i ?>_bot_behaviour4" name="auction[<?php echo $i ?>][bot_behaviour]" value="random" /><label for="auction_<?php echo $i ?>_bot_behaviour4">random</label>
                </div>
                <span class="label">Price increase per bid</span>
                <input type="number" name="auction[<?php echo $i ?>][bid_priceraise]" min="0" value="<?php echo $data['bid_priceraise'] ?>" step="0.01" required />
                <br />
                <span class="label">Start price</span>
                <input type="number" name="auction[<?php echo $i ?>][price]" min="0" value="0" required />
                <br />
                <span class="label">Countdown</span>
                <input type="number" name="auction[<?php echo $i ?>][countdown]" min="1" value="<?php echo $data['countdown'] ?>" required />
              </td>
              <td>
                <span class="label">Buy it now price</span>
                <input type="number" name="auction[<?php echo $i ?>][direct_price]" min="0" onchange="$( '#auction_<?php echo $i ?>_show_directbuy1' ).prop({checked: true});$( '#auction_<?php echo $i ?>_show_directbuy1' ).button( 'refresh' );" />
                <br />
                <span class="label">Description</span>
                <textarea name="auction[<?php echo $i ?>][description]"></textarea>
                <br />
                <span class="label">Image</span>
                <input type="file" name="auction[<?php echo $i ?>][image]" accept="image/*" />
              </td>
              <td>
                <span class="label">Product valuation</span>
                <input type="number" name="auction[<?php echo $i ?>][valuation]" min="0" required />
                <br />
                <span class="label">Fee per bid</span>
                <input type="number" name="auction[<?php echo $i ?>][bid_fee]" min="0" value="<?php echo $data['bid_fee'] ?>" step="0.01" required />
              </td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
    </article>
</div>
<div class="width24">
    <article class="box">
        <header>
            <h2>Assistant</h2>
        </header>

        <div style="text-align: right">
          <input type="submit" value="Next" />
        </div>
    </article>
</div>
</form>

<?php echo javascript_include_tag('assistant'); ?>
