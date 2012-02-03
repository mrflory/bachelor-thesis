<?php

/**
 * First step of assistant form to create a new session. Class is used for
 * validation only.
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 */
class AsSessionForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
          'name'       => new sfWidgetFormInputText(),
          'currency'   => new sfWidgetFormInputText(),
          'anz_part'   => new sfWidgetFormInputText(),
          'anz_auct'   => new sfWidgetFormInputText(),
          'money'      => new sfWidgetFormInputText(),
          'countdown'  => new sfWidgetFormInputText(),
          'bid_priceraise' => new sfWidgetFormInputText(),
          'bid_fee'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'currency'        => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'anz_part'        => new sfValidatorInteger(array('min' => 1, 'max' => 999)),
      'anz_auct'        => new sfValidatorInteger(array('min' => 1, 'max' => 999)),
      'money'           => new sfValidatorNumber(array('min' => 0)),
      'countdown'       => new sfValidatorInteger(array('min' => 1)),
      'bid_priceraise'  => new sfValidatorNumber(array('min' => 0)),
      'bid_fee'         => new sfValidatorNumber(array('min' => 0)),
    ));

    $this->widgetSchema->setNameFormat('session[%s]');
  }
}