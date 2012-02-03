<?php foreach($participants as $row): ?>
  <tr>
    <td><?php echo $row->Participant->name ?></td>
    <td><?php echo $row->Participant->ip ?></td>
    <td><?php echo $row->Participant->money ?></td>
    <td><?php echo $row->Participant->earned_money ?></td>
    <td><?php echo $row->getInheritedValuation() ?></td>
    <td><?php echo $row->getInheritedBidFee() ?></td>
    <td><?php echo ($row->getDirectBuy() ? $row->getDirectBuy() : '-') ?></td>
    <td><?php echo ($row->getBotActive() ? 'ja' : 'nein') ?></td>
  </tr>
<?php endforeach; ?>