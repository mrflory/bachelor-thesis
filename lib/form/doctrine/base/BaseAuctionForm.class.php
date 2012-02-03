<?php

/**
 * Auction form base class.
 *
 * @method Auction getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAuctionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'session_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Session'), 'add_empty' => false)),
      'name'            => new sfWidgetFormInputText(),
      'start_time'      => new sfWidgetFormDateTime(),
      'show_bidders'    => new sfWidgetFormInputCheckbox(),
      'show_bidbutton'  => new sfWidgetFormInputCheckbox(),
      'bot_behaviour'   => new sfWidgetFormChoice(array('choices' => array('off' => 'off', 'random' => 'random', 'lru' => 'lru', 'all' => 'all'))),
      'bid_countraise'  => new sfWidgetFormInputText(),
      'bid_raise_start' => new sfWidgetFormInputText(),
      'bid_raise_limit' => new sfWidgetFormInputText(),
      'bid_priceraise'  => new sfWidgetFormInputText(),
      'price'           => new sfWidgetFormInputText(),
      'countdown'       => new sfWidgetFormInputText(),
      'show_directbuy'  => new sfWidgetFormInputCheckbox(),
      'direct_price'    => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormTextarea(),
      'image'           => new sfWidgetFormInputText(),
      'valuation'       => new sfWidgetFormInputText(),
      'bid_fee'         => new sfWidgetFormInputText(),
      'current_price'   => new sfWidgetFormInputText(),
      'time_remaining'  => new sfWidgetFormInputText(),
      'money_spent'     => new sfWidgetFormInputText(),
      'last_bid'        => new sfWidgetFormDateTime(),
      'bidder_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('LastBidder'), 'add_empty' => true)),
      'end_time'        => new sfWidgetFormDateTime(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'session_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Session'))),
      'name'            => new sfValidatorString(array('max_length' => 255)),
      'start_time'      => new sfValidatorDateTime(array('required' => false)),
      'show_bidders'    => new sfValidatorBoolean(array('required' => false)),
      'show_bidbutton'  => new sfValidatorBoolean(array('required' => false)),
      'bot_behaviour'   => new sfValidatorChoice(array('choices' => array(0 => 'off', 1 => 'random', 2 => 'lru', 3 => 'all'), 'required' => false)),
      'bid_countraise'  => new sfValidatorInteger(array('required' => false)),
      'bid_raise_start' => new sfValidatorInteger(array('required' => false)),
      'bid_raise_limit' => new sfValidatorInteger(array('required' => false)),
      'bid_priceraise'  => new sfValidatorNumber(array('required' => false)),
      'price'           => new sfValidatorNumber(array('required' => false)),
      'countdown'       => new sfValidatorInteger(array('required' => false)),
      'show_directbuy'  => new sfValidatorBoolean(array('required' => false)),
      'direct_price'    => new sfValidatorNumber(array('required' => false)),
      'description'     => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'image'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'valuation'       => new sfValidatorNumber(array('required' => false)),
      'bid_fee'         => new sfValidatorNumber(array('required' => false)),
      'current_price'   => new sfValidatorNumber(array('required' => false)),
      'time_remaining'  => new sfValidatorInteger(array('required' => false)),
      'money_spent'     => new sfValidatorNumber(array('required' => false)),
      'last_bid'        => new sfValidatorDateTime(array('required' => false)),
      'bidder_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('LastBidder'), 'required' => false)),
      'end_time'        => new sfValidatorDateTime(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('auction[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Auction';
  }

}
