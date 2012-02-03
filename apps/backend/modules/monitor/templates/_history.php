<?php foreach($history as $row): ?>
  <tr>
    <td><?php echo $row->price ?></td>
    <td><?php echo $row->Bidder->Participant->name ?></td>
    <td><?php if(!is_null($row->bot_id)) { echo "Biet-Automat"; } else { echo "Manuell"; } ?></td>
  </tr>
<?php endforeach; ?>