<?php

/**
 * Session form base class.
 *
 * @method Session getObject() Returns the current form's model object
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSessionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'currency'   => new sfWidgetFormInputText(),
      'active'     => new sfWidgetFormInputCheckbox(),
      'comment'    => new sfWidgetFormTextarea(),
      'message'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'currency'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'active'     => new sfValidatorBoolean(array('required' => false)),
      'comment'    => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'message'    => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('session[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Session';
  }

}
