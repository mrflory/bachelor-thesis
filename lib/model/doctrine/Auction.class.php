<?php

/**
 * Auction
 * 
 * This class provides all functions to vew and manage an auction.
 * 
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Auction extends BaseAuction
{
  /**
   * Get current countdown, if auction not started return -1
   * @return integer
   */
  public function getCurrentCountdown()
  {
    if( !$this->getTimeRemaining() || !$this->getStartTime() ) {
      return -1;
    }
    #$last = $this->getLastBid() ? $this->getDateTimeObject('last_bid')->format('U') : $this->getDateTimeObject('start_time')->format('U');
    $begin = $this->getDateTimeObject('start_time')->format('U');
    return round( ($begin + $this->getTimeRemaining()) - microtime(true) , 2);
  }

  /**
   * Get current countdown for bot activation
   * @todo Umsetzung solala
   * @return integer
   */
  /*public function getBotlimitCountdown()
  {
    if( !$this->getBotCountlimit() || !$this->getStartTime() ) {
      return -1;
      #return $this->getCurrentCountdown() - sfConfig::get('app_daemon_upperlimit', 15) - 1;
    }
    $begin = $this->getDateTimeObject('start_time')->format('U');
    return ($begin + $this->getBotCountlimit()) - time();
  }*/

  /**
   * Start current auction
   */
  public function startAuction()
  {
    $this->setDateTimeObject('start_time', new DateTime());
    $this->setCurrentPrice($this->getPrice());
    $this->setTimeRemaining($this->getCountdown());
    $this->setMoneySpent(0);
    $this->setEndTime(null);

    $this->save();
  }

  /**
   * Stop current auction and save winner
   */
  public function stopAuction()
  {
    $this->setDateTimeObject('end_time', new DateTime());
    $this->save();

    if($this->relatedExists('LastBidder')) {
      $participant = $this->LastBidder->getParticipant();
      $participant->money -= $this->getCurrentPrice();
      $participant->earned_money += $this->LastBidder->getInheritedValuation();
      $participant->save();
    }
  }

  /**
   * Interrupts auction by resetting start time
   */
  public function interruptAuction()
  {
    $this->start_time = null;
    $this->save();
  }

  /**
   * Place a new bit for this auction, takes care of race conditions through
   * DBMS, accepts Participant, Bidder or Bot
   * @param Participant|Bidder|Bot $participant
   * @param decimal $bid
   * @return boolean
   */
  public function placeBid($bidder, $bid)
  {
    if($bidder instanceof Participant) {
      $participant = $bidder;
      $bidder = Doctrine_Core::getTable('Bidder')->getBidder($this, $participant);
      $bot = false;
    } elseif($bidder instanceof Bidder) {
      $participant = $bidder->getParticipant();
      $bot = false;
    } elseif($bidder instanceof Bot) {
      $bot = $bidder;
      $bidder = $bot->getBidder();
      $participant = $bidder->getParticipant();
      if($bot->numbids <= 0 || $bot->end <= $bid) {
        #throw new Expcetion('Bot is not allowed to bid at this time!');
        $bot->deactivate();
        return false;
      }
      if($bot->start > $bid) {
        return false;
      }
    } else {
      throw new Expcetion('Unknown bidder instance!');
    }

    $countdown = $this->getCurrentCountdown();
    
    $bid_fee = $bidder->getInheritedBidFee();

    $q = Doctrine_Query::create()
      ->update('Auction a')
      ->andWhere('a.end_time IS NULL')
      ->andWhere('a.id = ?', $this->getId())
      ->andWhere('a.current_price = ?', $bid)
      ->set('a.current_price', 'a.current_price + a.bid_priceraise')
      ->set('a.money_spent', 'a.money_spent + ?', $bid_fee)
      ->set('a.last_bid', 'NOW()')
      ->set('a.bidder_id', '?', $bidder->getId())
      ->limit(1);

    if(!$this->getBidRaiseStart() || ($countdown <= $this->getBidRaiseStart())) {
      if($this->getBidRaiseLimit() && $countdown < $this->getBidRaiseLimit()) {
        $q->set('a.time_remaining', 'a.time_remaining + ?', min($this->bid_countraise, $this->bid_raise_limit - $countdown));
      } elseif(!$this->getBidRaiseLimit()) {
        $q->set('a.time_remaining', 'a.time_remaining + a.bid_countraise');
      }
    }

    $rows = $q->execute();
    if( $rows == 0 ) {
      return false;
    }

    // Refresh model and relationships
    $this->refresh();

    #$connection = sfContext::getInstance()->getDatabaseManager()->getDatabase('doctrine')->getDoctrineConnection();
    #$connection->beginTransaction();
    #try {

    if($bot !== false) {
      $bot->numbids -= 1;
      $bot->setDateTimeObject('last_use', new DateTime());
      $bot->save();
    }

    $bid = new Bid();
    $bid->setAuction($this);
    $bid->setBidder($bidder);
    if($bot !== false) {
      $bid->setBot($bot);
    }
    $bid->price = $this->getCurrentPrice();
    $bid->countdown = $countdown;
    $bid->save();

    $bidder->money_spent += $bid_fee;
    $bidder->save();

    $participant->money -= $bid_fee;
    $participant->save();

    #  $connection->commit();
    #} catch (Exception $e) {
    #  $connection->rollback();
    #  throw $e;
    #}

    return true;
  }

  /**
   * Returns history of the auction
   * @param integer $limit
   * @return Doctrine_Collection
   */
  public function getHistory($limit = 5)
  {
    #return $this->getBids();

    $q = Doctrine_Query::create()
      ->from('Bid b')
      ->select('b.id, d.id, p.id, b.price, b.countdown, b.bot_id, p.name')
      ->leftJoin('b.Bidder d')
      ->leftJoin('d.Participant p')
      ->andWhere('b.auction_id = ?', $this->getId())
      ->orderBy('b.id DESC');

    if($limit > 0) {
      $q->limit($limit);
    }

    return $q->execute();
  }

  /**
   * Get all auction information as array for ajax request
   * @param Participant $participant
   * @param boolean $verbose
   * @return array
   */
  public function getAjaxArray(Participant $participant = null, $verbose = false)
  {
    $currency = $this->Session->getCurrency();
    $lastbidder = ($this->relatedExists('LastBidder') ? $this->LastBidder : false);

    $result = array(
        'a_id'          => $this->getId(),
        'showdirectbuy' => ($this->getShowDirectbuy() && $this->getDirectPrice() > 0),
        'showbidbutton' => $this->getShowBidbutton(),
    );

    if($this->getEndTime()) {
      $result['a_finalprice']   = sprintf($currency, $this->getCurrentPrice());
      $result['a_winner']       = ($lastbidder ? $lastbidder->Participant->getName() : false);
    } else {
      $result['a_timeremain']   = $this->getCurrentCountdown();
      $result['a_curprice']     = sprintf($currency, $this->getCurrentPrice());
      $result['a_curprice_dec'] = $this->getCurrentPrice();
      $result['a_lastbidder']   = ($lastbidder ? $lastbidder->Participant->getName() : false);
      $result['showbidders']    = $this->getShowBidders();
      $result['autobid']        = ($this->getBotBehaviour() != 'off');
    }

    if($participant !== null) {
      $bidder = Doctrine_Core::getTable('Bidder')->getBidder($this, $participant);
      $result['thisiswinner']  = ($lastbidder == $bidder ? true : false);
      $result['p_money']       = sprintf($currency, $participant->getMoney());
      $result['bidder_bought'] = ($bidder->getDirectBuy() != null ? true : false);
      $result['bot_active']    = $bidder->getBotActive();
      if($verbose || ($this->getEndTime() && $result['showdirectbuy'])) {
        $result['p_earned']    = sprintf($currency, $participant->getEarnedMoney());
        $result['b_valuation'] = sprintf($currency, $bidder->getInheritedValuation());
        $result['b_bidfee']    = sprintf($currency, $bidder->getInheritedBidFee());
      }
    }

    if($verbose) {
      $result['a_priceraise']   = sprintf($currency, $this->getBidPriceraise());
      $result['a_description']  = $this->getDescription();
      $result['a_image']        = $this->getImage();
    }

    if( $result['showdirectbuy'] ) {
      if(isset($bidder)) {
        $result['discount'] = sprintf($currency, $bidder->getMoneySpent());
        $result['directtotal_dec'] = max($this->getDirectPrice() - $bidder->getMoneySpent(), 0);
        $result['directtotal'] = sprintf($currency, $result['directtotal_dec']);
      }
      if($verbose || $this->getEndTime()) {
        $result['directprice']    = sprintf($currency, $this->getDirectPrice());
      }
      
    }

    return $result;
  }
}
