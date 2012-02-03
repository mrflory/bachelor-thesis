<?php

/**
 * Bot filter form base class.
 *
 * @package    bachelor
 * @subpackage filter
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBotFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'auction_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => true)),
      'bidder_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'add_empty' => true)),
      'active'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'init_numbids' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'price'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'countdown'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'numbids'      => new sfWidgetFormFilterInput(),
      'last_use'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'auction_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Auction'), 'column' => 'id')),
      'bidder_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Bidder'), 'column' => 'id')),
      'active'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'init_numbids' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'end'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'countdown'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'numbids'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_use'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('bot_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bot';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'auction_id'   => 'ForeignKey',
      'bidder_id'    => 'ForeignKey',
      'active'       => 'Boolean',
      'init_numbids' => 'Number',
      'start'        => 'Number',
      'end'          => 'Number',
      'price'        => 'Number',
      'countdown'    => 'Number',
      'numbids'      => 'Number',
      'last_use'     => 'Date',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
