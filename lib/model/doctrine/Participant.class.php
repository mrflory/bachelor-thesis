<?php

/**
 * Participant
 * 
 * This class provides functions for a participant.
 * 
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Participant extends BaseParticipant
{
  /**
   * Reset unique IP of participant
   */
  public function resetParticipant()
  {
    $this->setIp(null);
    $this->setMoney($this->getInitMoney());
    $this->save();
  }
}
