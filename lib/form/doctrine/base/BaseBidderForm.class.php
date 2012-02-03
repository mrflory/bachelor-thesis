<?php

/**
 * Bidder form base class.
 *
 * @method Bidder getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBidderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'auction_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => false)),
      'participant_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Participant'), 'add_empty' => false)),
      'valuation'      => new sfWidgetFormInputText(),
      'bid_fee'        => new sfWidgetFormInputText(),
      'money_spent'    => new sfWidgetFormInputText(),
      'bot_active'     => new sfWidgetFormInputCheckbox(),
      'direct_buy'     => new sfWidgetFormInputText(),
      'direct_count'   => new sfWidgetFormInputText(),
      'direct_price'   => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'auction_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'))),
      'participant_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Participant'))),
      'valuation'      => new sfValidatorNumber(array('required' => false)),
      'bid_fee'        => new sfValidatorNumber(array('required' => false)),
      'money_spent'    => new sfValidatorNumber(array('required' => false)),
      'bot_active'     => new sfValidatorBoolean(array('required' => false)),
      'direct_buy'     => new sfValidatorNumber(array('required' => false)),
      'direct_count'   => new sfValidatorInteger(array('required' => false)),
      'direct_price'   => new sfValidatorNumber(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('bidder[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bidder';
  }

}
