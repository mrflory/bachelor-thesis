<?php

/**
 * Bot
 * 
 * This class provides functions for a bot.
 * 
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Bot extends BaseBot
{
  /**
   * Deactivate current bot
   */
  public function deactivate()
  {
    $this->active = false;
    if($this->relatedExists('Bidder')) {
      $this->Bidder->bot_active = false;
    }
    $this->save();
  }
}
