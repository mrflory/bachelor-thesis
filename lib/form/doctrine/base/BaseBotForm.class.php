<?php

/**
 * Bot form base class.
 *
 * @method Bot getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBotForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'auction_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => false)),
      'bidder_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'add_empty' => true)),
      'active'       => new sfWidgetFormInputCheckbox(),
      'init_numbids' => new sfWidgetFormInputText(),
      'start'        => new sfWidgetFormInputText(),
      'end'          => new sfWidgetFormInputText(),
      'price'        => new sfWidgetFormInputText(),
      'countdown'    => new sfWidgetFormInputText(),
      'numbids'      => new sfWidgetFormInputText(),
      'last_use'     => new sfWidgetFormDateTime(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'auction_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'))),
      'bidder_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'required' => false)),
      'active'       => new sfValidatorBoolean(array('required' => false)),
      'init_numbids' => new sfValidatorInteger(),
      'start'        => new sfValidatorNumber(),
      'end'          => new sfValidatorNumber(),
      'price'        => new sfValidatorNumber(),
      'countdown'    => new sfValidatorInteger(),
      'numbids'      => new sfValidatorInteger(array('required' => false)),
      'last_use'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('bot[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bot';
  }

}
