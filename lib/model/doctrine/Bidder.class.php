<?php

/**
 * Bidder
 * 
 * This class provides functions which are used by an individual bidder, like
 * creating a new bot or buy a product directly.
 * 
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Bidder extends BaseBidder
{
  /**
   * Get meaningful name
   * @return string
   */
  public function __toString()
  {
    #return $this->Participant->getName() . ', ' . $this->getValuation();
    return $this->getId();
  }

  /**
   * Buy product directly
   * @param integer $total
   * @return boolean
   */
  public function buyDirect($total)
  {
    $countdown = $this->Auction->getCurrentCountdown();
    
    if($this->getDirectBuy() != null) {
      return false;
    }

    if( max($this->Auction->getDirectPrice() - $this->getMoneySpent(), 0) != $total) {
      return false;
    }

    $this->direct_buy = $total;
    $this->direct_price = $this->Auction->getCurrentPrice();
    $this->direct_count = $countdown;
    $this->save();

    $this->Participant->money -= $total;
    $this->Participant->earned_money += $this->getInheritedValuation();
    $this->Participant->save();

    return true;
  }

  /**
   * Get active Bot for current bidder if exists
   * @return boolean|Doctrine_Collection
   */
  public function getActiveBot()
  {
      $q = Doctrine_Query::create()
        ->from('Bot b')
        ->andWhere('b.bidder_id = ?', $this->getId())
        ->andWhere('b.active = ?', true)
        ->limit(1);

      if($q->count() == 0) {
        return false;
      }

      return $q->fetchOne();
  }

  /**
   * Activate bot for current bidder, gets array of initial values
   * @param array $values
   * @return boolean
   */
  public function activateBot($values)
  {
    if($this->getActiveBot() !== false) {
      return false;
    }
    if($this->Auction->getCurrentPrice() >= $values['end']) {
      return false;
    }

    #$connection = sfContext::getInstance()->getDatabaseManager()->getDatabase('doctrine')->getDoctrineConnection();
    $connection = Doctrine_Manager::connection();
    $connection->beginTransaction();
    try {
      $this->bot_active  = true;

      $bot = new Bot();
      $bot->setAuction($this->Auction);
      $bot->setBidder($this);
      $bot->price        = $this->Auction->getCurrentPrice();
      $bot->countdown    = $this->Auction->getCurrentCountdown();
      $bot->init_numbids = $values['init_numbids'];
      $bot->start        = $values['start'];
      $bot->end          = $values['end'];
      $bot->numbids      = $values['init_numbids'];
      //Last use set to start time, so with LRU strategy it is used at first
      #$bot->last_use     = $this->Auction->start_time;
      //Last use set to actual time, so with LRU strategy it is used at last
      $bot->setDateTimeObject('last_use', new DateTime());
      $bot->active       = true;
      
      $this->save();
      $bot->save();

      $connection->commit();
    } catch (Exception $e) {
      $connection->rollback();
      throw $e;
    }

    return true;
  }

  /**
   * Get valuation, may be inherited from auctions default value
   * @return decimal
   */
  public function getInheritedValuation() {
    if(parent::_get('valuation')) {
      return parent::_get('valuation');
    }
    return $this->Auction->getValuation();
  }

  /**
   * Get bid fee, may be inherited from auctions default value
   * @return decimal
   */
  public function getInheritedBidFee() {
    if(parent::_get('bid_fee')) {
      return parent::_get('bid_fee');
    }
    return $this->Auction->getBidFee();
  }
}
