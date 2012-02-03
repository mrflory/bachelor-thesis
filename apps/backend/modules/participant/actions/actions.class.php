<?php

require_once dirname(__FILE__).'/../lib/participantGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/participantGeneratorHelper.class.php';

/**
 * participant actions.
 *
 * @package    bachelor
 * @subpackage participant
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class participantActions extends autoParticipantActions
{
  /**
   * Reset participant so it can be assigned to a new user
   * @param sfWebRequest $request
   */
  public function executeResetParticipant(sfWebRequest $request)
  {
    $this->forward404Unless($participant = Doctrine_Core::getTable('Participant')->find($request->getParameter('id')),
                                              sprintf('Object participant does not exist (%s).', $request->getParameter('id')));
    $participant->resetParticipant();

    $this->redirect('participant/index');
  }
}
