<?php

/**
 * BidderTable
 * 
 * This class has provides functions to get a specific bidder.
 *
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 */
class BidderTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     * @return BidderTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Bidder');
    }

    /**
     * Get bidder with given auction and participant
     * @param integer|Auction $auction
     * @param integer|Participant $participant
     * @return Bidder
     */
    public function getBidder($auction, $participant)
    {
      if($auction instanceof Auction) {
        $auction = $auction->getId();
      }
      if($participant instanceof Participant) {
        $participant = $participant->getId();
      }
      $q = Doctrine_Query::create()
        ->from('Bidder b')
        ->andWhere('b.auction_id = ?', $auction)
        ->andWhere('b.participant_id = ?', $participant)
        ->limit(1);

      if($q->count() == 0) {
        throw new Exception('No bidder for given auction and participant!');
      }

      return $q->fetchOne();
    }
}