<?php

require_once dirname(__FILE__).'/../lib/botGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/botGeneratorHelper.class.php';

/**
 * bot actions.
 *
 * @package    bachelor
 * @subpackage bot
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class botActions extends autoBotActions
{
  /**
   * Deactivate bot
   * @param sfWebRequest $request
   */
  public function executeDeactivateBot(sfWebRequest $request)
  {
    $this->forward404Unless($bot = Doctrine_Core::getTable('Bot')->find($request->getParameter('id')),
                                              sprintf('Object bot does not exist (%s).', $request->getParameter('id')));
    $bot->deactivate();

    $this->redirect('bot/index');
  }
}
