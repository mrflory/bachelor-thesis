<?php

/**
 * Bid filter form base class.
 *
 * @package    bachelor
 * @subpackage filter
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBidFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'auction_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => true)),
      'bidder_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bidder'), 'add_empty' => true)),
      'bot_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Bot'), 'add_empty' => true)),
      'price'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'countdown'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'auction_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Auction'), 'column' => 'id')),
      'bidder_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Bidder'), 'column' => 'id')),
      'bot_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Bot'), 'column' => 'id')),
      'price'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'countdown'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('bid_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bid';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'auction_id' => 'ForeignKey',
      'bidder_id'  => 'ForeignKey',
      'bot_id'     => 'ForeignKey',
      'price'      => 'Number',
      'countdown'  => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
