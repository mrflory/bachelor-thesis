<?php foreach($bots as $row): ?>
  <tr>
    <td><?php if($row->active) { echo link_to('ja', 'monitor/botdeactivate?bot_id=' . $row->id, array('title' => 'Bot deaktivieren') ); } ?></td>
    <td><?php echo $row->Bidder->Participant->name ?></td>
    <td><?php echo $row->start ?></td>
    <td><?php echo $row->end ?></td>
    <td><?php echo $row->numbids ?></td>
  </tr>
<?php endforeach; ?>