<?php

/**
 * Bid form base class.
 *
 * @method Bid getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBidForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'auction_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => false)),
      'bidder_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'add_empty' => true)),
      'bot_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bot'), 'add_empty' => true)),
      'price'      => new sfWidgetFormInputText(),
      'countdown'  => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'auction_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'))),
      'bidder_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'required' => false)),
      'bot_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Bot'), 'required' => false)),
      'price'      => new sfValidatorNumber(),
      'countdown'  => new sfValidatorInteger(),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('bid[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bid';
  }

}
