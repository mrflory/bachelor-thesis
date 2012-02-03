<?php

/**
 * Frontend form to activate a bot. Class is used for validation only.
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 */
class FrontendbotForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'init_numbids'   => new sfWidgetFormInputText(),
      'start'          => new sfWidgetFormInputText(),
      'end'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'init_numbids'   => new sfValidatorInteger(array('min' => 1, 'max' => 999)),
      'start'          => new sfValidatorNumber(array('min' => 0)),
      'end'            => new sfValidatorNumber(array('min' => 0)),
    ));

    $this->widgetSchema->setNameFormat('bot[%s]');
    $this->disableCSRFProtection();
  }
}