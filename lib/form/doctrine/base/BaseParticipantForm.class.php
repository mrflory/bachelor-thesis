<?php

/**
 * Participant form base class.
 *
 * @method Participant getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseParticipantForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'session_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Session'), 'add_empty' => false)),
      'ip'           => new sfWidgetFormInputText(),
      'name'         => new sfWidgetFormInputText(),
      'init_money'   => new sfWidgetFormInputText(),
      'money'        => new sfWidgetFormInputText(),
      'earned_money' => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'session_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Session'))),
      'ip'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 255)),
      'init_money'   => new sfValidatorNumber(),
      'money'        => new sfValidatorNumber(array('required' => false)),
      'earned_money' => new sfValidatorNumber(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Participant', 'column' => array('ip')))
    );

    $this->widgetSchema->setNameFormat('participant[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Participant';
  }

}
