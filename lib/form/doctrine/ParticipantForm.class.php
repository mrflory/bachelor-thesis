<?php

/**
 * Participant form.
 *
 * @package    bachelor
 * @subpackage form
 * @author     Florian Stallmann
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ParticipantForm extends BaseParticipantForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['ip']);
  }
}
