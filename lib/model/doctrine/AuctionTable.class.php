<?php

/**
 * AuctionTable
 * 
 * This class provides functions to get a specific auction.
 *
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 */
class AuctionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     * @return AuctionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Auction');
    }

    /**
     * Get active auction
     * @param Doctrine_Query $q
     * @return boolean|Auction
     */
    public function getActiveAuction(Doctrine_Query $q = null)
    {
      $session = Doctrine_Core::getTable('Session')->getActiveSession();
      if($session == false) {
        return false;
      }

      if (is_null($q))
      {
        $q = Doctrine_Query::create()
          ->from('Auction a');
      }
   
      $alias = $q->getRootAlias();
   
      $q->andWhere($alias . '.session_id = ?', $session->getId())
        ->andWhere($alias . '.end_time IS NULL')
        ->andWhere($alias . '.start_time <= ?', date('Y-m-d H:i:s', time()));

      if($q->count() > 1) {
        throw new Exception('There is more than 1 active auction!');
      }
   
      return $q->fetchOne();
    }
    
    /**
     * Get last recently active auction
     * @param Doctrine_Query $q
     * @return boolean|Auction
     */
    public function getLastActiveAuction(Doctrine_Query $q = null)
    {
      $session = Doctrine_Core::getTable('Session')->getActiveSession();
      if($session == false) {
        return false;
      }

      if (is_null($q))
      {
        $q = Doctrine_Query::create()
          ->from('Auction a');
      }

      $alias = $q->getRootAlias();

      $q->andWhere($alias . '.session_id = ?', $session->getId())
        ->andWhere($alias . '.end_time IS NOT NULL')
        ->orderBy('end_time DESC')
        ->limit(1);

      return $q->fetchOne();
    }
}
