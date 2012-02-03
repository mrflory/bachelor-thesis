<?php

/**
 * BaseBidder
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $auction_id
 * @property integer $participant_id
 * @property decimal $valuation
 * @property decimal $bid_fee
 * @property decimal $money_spent
 * @property boolean $bot_active
 * @property decimal $direct_buy
 * @property integer $direct_count
 * @property decimal $direct_price
 * @property Auction $Auction
 * @property Participant $Participant
 * @property Auction $Auctionleader
 * @property Doctrine_Collection $Bots
 * @property Doctrine_Collection $Bids
 * 
 * @method integer             getAuctionId()      Returns the current record's "auction_id" value
 * @method integer             getParticipantId()  Returns the current record's "participant_id" value
 * @method decimal             getValuation()      Returns the current record's "valuation" value
 * @method decimal             getBidFee()         Returns the current record's "bid_fee" value
 * @method decimal             getMoneySpent()     Returns the current record's "money_spent" value
 * @method boolean             getBotActive()      Returns the current record's "bot_active" value
 * @method decimal             getDirectBuy()      Returns the current record's "direct_buy" value
 * @method integer             getDirectCount()    Returns the current record's "direct_count" value
 * @method decimal             getDirectPrice()    Returns the current record's "direct_price" value
 * @method Auction             getAuction()        Returns the current record's "Auction" value
 * @method Participant         getParticipant()    Returns the current record's "Participant" value
 * @method Auction             getAuctionleader()  Returns the current record's "Auctionleader" value
 * @method Doctrine_Collection getBots()           Returns the current record's "Bots" collection
 * @method Doctrine_Collection getBids()           Returns the current record's "Bids" collection
 * @method Bidder              setAuctionId()      Sets the current record's "auction_id" value
 * @method Bidder              setParticipantId()  Sets the current record's "participant_id" value
 * @method Bidder              setValuation()      Sets the current record's "valuation" value
 * @method Bidder              setBidFee()         Sets the current record's "bid_fee" value
 * @method Bidder              setMoneySpent()     Sets the current record's "money_spent" value
 * @method Bidder              setBotActive()      Sets the current record's "bot_active" value
 * @method Bidder              setDirectBuy()      Sets the current record's "direct_buy" value
 * @method Bidder              setDirectCount()    Sets the current record's "direct_count" value
 * @method Bidder              setDirectPrice()    Sets the current record's "direct_price" value
 * @method Bidder              setAuction()        Sets the current record's "Auction" value
 * @method Bidder              setParticipant()    Sets the current record's "Participant" value
 * @method Bidder              setAuctionleader()  Sets the current record's "Auctionleader" value
 * @method Bidder              setBots()           Sets the current record's "Bots" collection
 * @method Bidder              setBids()           Sets the current record's "Bids" collection
 * 
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBidder extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('bidder');
        $this->hasColumn('auction_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('participant_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('valuation', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             ));
        $this->hasColumn('bid_fee', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             ));
        $this->hasColumn('money_spent', 'decimal', 10, array(
             'type' => 'decimal',
             'notnull' => true,
             'default' => 0,
             'length' => 10,
             ));
        $this->hasColumn('bot_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('direct_buy', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             ));
        $this->hasColumn('direct_count', 'integer', 10, array(
             'type' => 'integer',
             'length' => 10,
             ));
        $this->hasColumn('direct_price', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             ));


        $this->index('auct_part', array(
             'fields' => 
             array(
              0 => 'auction_id',
              1 => 'participant_id',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Auction', array(
             'local' => 'auction_id',
             'foreign' => 'id'));

        $this->hasOne('Participant', array(
             'local' => 'participant_id',
             'foreign' => 'id'));

        $this->hasOne('Auction as Auctionleader', array(
             'local' => 'id',
             'foreign' => 'bidder_id'));

        $this->hasMany('Bot as Bots', array(
             'local' => 'id',
             'foreign' => 'bidder_id'));

        $this->hasMany('Bid as Bids', array(
             'local' => 'id',
             'foreign' => 'bidder_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             ));
        $this->actAs($timestampable0);
    }
}