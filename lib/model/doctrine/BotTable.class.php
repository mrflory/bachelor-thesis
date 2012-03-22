<?php

/**
 * BotTable
 * 
 * This class provides functions to manage and execute bots.
 *
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 */
class BotTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class
     * @return BotTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Bot');
    }

    /**
     * Deactives all bots which don't have any bid or current price already
     * exceeded bot's end price
     * @param Auction $auction
     */
    public function deactivateOutbidBots(Auction $auction)
    {
      $q = Doctrine_Query::create()
        ->from('Bot b')
        ->andWhere('b.auction_id = ?', $auction->getId())
        ->andWhere('b.active = true')
        ->andWhere('(b.numbids = 0 OR b.end < ?)', ($auction->getCurrentPrice() + $auction->getBidPriceraise()) );

      $expired = $q->execute();
      foreach($expired as $bot) {
        $bot->deactivate();
      }
    }

    /**
     * Get next available bots depending on strategy
     * @param Auction $auction
     * @return boolean|Doctrine_Collection
     */
    public function getNextBots(Auction $auction)
    {
      $auction->refresh(true);
      $q = Doctrine_Query::create()
        ->from('Bot b');

      if($auction->relatedExists('LastBidder')) {
        #$q->andWhere('b.bidder_id <> ?', $auction->getBidderId());
        $q->andWhere('b.bidder_id <> ?', $auction->LastBidder->getId());
      }
      
      $q->andWhere('b.active = true')
        ->andWhere('b.auction_id = ?', $auction->getId())
        ->andWhere('b.numbids > 0')
        ->andWhere('b.end >= ?', ($auction->getCurrentPrice() + $auction->getBidPriceraise()) )
        ->andWhere('b.start <= ?', $auction->getCurrentPrice());
        
      
      if($auction->getBotBehaviour() == 'random') {
        $q->select('b.*, RANDOM() AS rand')
          ->orderBy('rand')
          ->limit(1);
      } elseif($auction->getBotBehaviour() == 'lru') {
        $q->orderBy('b.last_use ASC, b.id ASC')
          ->limit(1);
      } elseif($auction->getBotBehaviour() == 'all') {
        $q->orderBy('b.end ASC, b.id ASC');
      } else {
        return false;
      }

      return $q->execute();
    }

    /**
     * Get bext bot(s) and place automated bid
     * @param Auction $auction
     * @return integer
     */
    public function placeAutomatedBid(Auction $auction)
    {
      $bots = $this->getNextBots($auction);
      $bidsplaced = 0;

      foreach($bots as $bot) {
        if($bot->end > $auction->getCurrentPrice()) {
          $bidsplaced = ($auction->placeBid($bot, $auction->getCurrentPrice()) ? $bidsplaced+1 : $bidsplaced);
        }
      }
      return $bidsplaced;
    }

    /**
     * Place all bot bids
     * @param Auction $auction
     */
    public function placeAllAutomatedBids(Auction $auction)
    {
      $c = 0;

      do {
        $c = $this->placeAutomatedBid($auction);
        $this->deactivateOutbidBots($auction);
      } while($c > 0);

      #$auction->stopAuction();
    }
}
