<?php

/**
 * Bidder filter form base class.
 *
 * @package    bachelor
 * @subpackage filter
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBidderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'auction_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Auction'), 'add_empty' => true)),
      'participant_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Participant'), 'add_empty' => true)),
      'valuation'      => new sfWidgetFormFilterInput(),
      'bid_fee'        => new sfWidgetFormFilterInput(),
      'money_spent'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bot_active'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'direct_buy'     => new sfWidgetFormFilterInput(),
      'direct_count'   => new sfWidgetFormFilterInput(),
      'direct_price'   => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'auction_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Auction'), 'column' => 'id')),
      'participant_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Participant'), 'column' => 'id')),
      'valuation'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'bid_fee'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'money_spent'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'bot_active'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'direct_buy'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'direct_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'direct_price'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('bidder_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Bidder';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'auction_id'     => 'ForeignKey',
      'participant_id' => 'ForeignKey',
      'valuation'      => 'Number',
      'bid_fee'        => 'Number',
      'money_spent'    => 'Number',
      'bot_active'     => 'Boolean',
      'direct_buy'     => 'Number',
      'direct_count'   => 'Number',
      'direct_price'   => 'Number',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
