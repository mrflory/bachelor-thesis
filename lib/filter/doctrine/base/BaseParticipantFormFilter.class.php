<?php

/**
 * Participant filter form base class.
 *
 * @package    bachelor
 * @subpackage filter
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseParticipantFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'session_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Session'), 'add_empty' => true)),
      'ip'           => new sfWidgetFormFilterInput(),
      'name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'init_money'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'money'        => new sfWidgetFormFilterInput(),
      'earned_money' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'session_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Session'), 'column' => 'id')),
      'ip'           => new sfValidatorPass(array('required' => false)),
      'name'         => new sfValidatorPass(array('required' => false)),
      'init_money'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'money'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'earned_money' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('participant_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Participant';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'session_id'   => 'ForeignKey',
      'ip'           => 'Text',
      'name'         => 'Text',
      'init_money'   => 'Number',
      'money'        => 'Number',
      'earned_money' => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
