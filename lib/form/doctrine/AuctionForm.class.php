<?php

/**
 * Auction form.
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AuctionForm extends BaseAuctionForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);

    $this->widgetSchema['image'] = new sfWidgetFormInputFile(array(
          'label' => 'Product image',
        ));
    $this->validatorSchema['image'] = new sfValidatorFile(array(
          'required'   => false,
          'path'       => sfConfig::get('sf_upload_dir').'/products',
          'mime_types' => 'web_images',
        ));
  }
}
