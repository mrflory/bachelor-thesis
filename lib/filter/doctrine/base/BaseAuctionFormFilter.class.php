<?php

/**
 * Auction filter form base class.
 *
 * @package    bachelor
 * @subpackage filter
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAuctionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'session_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Session'), 'add_empty' => true)),
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'show_bidders'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'show_bidbutton'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'bot_behaviour'   => new sfWidgetFormChoice(array('choices' => array('' => '', 'off' => 'off', 'random' => 'random', 'lru' => 'lru', 'all' => 'all'))),
      'bid_countraise'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bid_raise_start' => new sfWidgetFormFilterInput(),
      'bid_raise_limit' => new sfWidgetFormFilterInput(),
      'bid_priceraise'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'countdown'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_directbuy'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'direct_price'    => new sfWidgetFormFilterInput(),
      'description'     => new sfWidgetFormFilterInput(),
      'image'           => new sfWidgetFormFilterInput(),
      'valuation'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bid_fee'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'current_price'   => new sfWidgetFormFilterInput(),
      'time_remaining'  => new sfWidgetFormFilterInput(),
      'money_spent'     => new sfWidgetFormFilterInput(),
      'last_bid'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'bidder_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LastBidder'), 'add_empty' => true)),
      'end_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'session_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Session'), 'column' => 'id')),
      'name'            => new sfValidatorPass(array('required' => false)),
      'start_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'show_bidders'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'show_bidbutton'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'bot_behaviour'   => new sfValidatorChoice(array('required' => false, 'choices' => array('off' => 'off', 'random' => 'random', 'lru' => 'lru', 'all' => 'all'))),
      'bid_countraise'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bid_raise_start' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bid_raise_limit' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bid_priceraise'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'countdown'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_directbuy'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'direct_price'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'description'     => new sfValidatorPass(array('required' => false)),
      'image'           => new sfValidatorPass(array('required' => false)),
      'valuation'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'bid_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'current_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'time_remaining'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'money_spent'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'last_bid'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'bidder_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('LastBidder'), 'column' => 'id')),
      'end_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('auction_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Auction';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'session_id'      => 'ForeignKey',
      'name'            => 'Text',
      'start_time'      => 'Date',
      'show_bidders'    => 'Boolean',
      'show_bidbutton'  => 'Boolean',
      'bot_behaviour'   => 'Enum',
      'bid_countraise'  => 'Number',
      'bid_raise_start' => 'Number',
      'bid_raise_limit' => 'Number',
      'bid_priceraise'  => 'Number',
      'price'           => 'Number',
      'countdown'       => 'Number',
      'show_directbuy'  => 'Boolean',
      'direct_price'    => 'Number',
      'description'     => 'Text',
      'image'           => 'Text',
      'valuation'       => 'Number',
      'bid_fee'         => 'Number',
      'current_price'   => 'Number',
      'time_remaining'  => 'Number',
      'money_spent'     => 'Number',
      'last_bid'        => 'Date',
      'bidder_id'       => 'ForeignKey',
      'end_time'        => 'Date',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
