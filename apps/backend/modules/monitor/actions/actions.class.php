<?php

/**
 * monitor actions.
 *
 * @package    bachelor
 * @subpackage monitor
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class monitorActions extends sfActions
{
 /**
  * Executes index action
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    #$this->sessions = Doctrine_Core::getTable('Session')
    #  ->createQuery('a')
    #  ->execute();

    $fp = fopen(sfConfig::get('sf_lib_dir') . '/daemon.lock', 'r');
    if($fp && flock($fp, LOCK_EX | LOCK_NB)) {
      $this->getUser()->setFlash('error', 'Dämon wurde nicht gestartet! Auktionen werden nicht beendet und Bots werden nicht bieten. Sie können den Dämon mit folgendem Kommando starten: "php '.sfConfig::get('sf_lib_dir').'/daemon.php"', false);
    }

    $this->running = true;
    if($request->hasParameter('auction_id')) {
      $this->auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id'));
      $this->session = $this->auction->getSession();
      if(!$this->auction->getStartTime()) {
        $this->running = false;
        $this->getUser()->setFlash('notice', 'Diese Auktion wurde noch nicht gestartet!', false);
      } elseif($this->auction->getEndTime()) {
        $this->running = false;
        $this->getUser()->setFlash('notice', 'Diese Auktion wurde bereits beendet!', false);
      }
    } else {
      $this->auction = Doctrine_Core::getTable('Auction')->getActiveAuction();
      #$this->session = Doctrine_Core::getTable('Session')->getActiveSession();

      if($this->auction == false) {
        $this->session = false;
        $this->getUser()->setFlash('notice', 'Keine Auktion aktiv!', false);
      } else {
        $this->session = $this->auction->getSession();
      }
    }

  }

  /**
   * Deactivate bot
   * @param sfWebRequest $request
   */
  public function executeBotdeactivate(sfWebRequest $request)
  {
    $this->forward404Unless($bot = Doctrine_Core::getTable('Bot')->find($request->getParameter('bot_id')),
                                              sprintf('Object bot does not exist (%s).', $request->getParameter('bot_id')));
    $bot->deactivate();

    $this->redirect('monitor/index');
  }

  /**
   * Returns current auction status as JSON data, only available via ajax
   * request
   * @param sfWebRequest $request
   */
  public function executeStatus(sfWebRequest $request)
  {
    $this->forward404Unless($this->auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));
    $this->forward404Unless($request->isXmlHttpRequest(), 'This is not an Ajax Request');

    $result = $this->auction->getAjaxArray(null);
    $result['active'] = ($this->auction->getEndTime() ? false : true);

    $result['participants'] = $this->getPartial('monitor/participants', array('participants' => $this->auction->getBidder()) );
    $result['history'] = $this->getPartial('monitor/history', array('history' => $this->auction->getHistory(10)) );
    $result['bots'] = $this->getPartial('monitor/bots', array('bots' => $this->auction->getBots()) );

    $this->getResponse()->setHttpHeader('Content-type','application/json');
    return $this->renderText(json_encode($result));
  }

  /**
   * Calculate all bots
   * @param sfWebRequest $request
   */
  public function executeBidAllBots(sfWebRequest $request)
  {
    $this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));

    Doctrine_Core::getTable('Bot')->placeAllAutomatedBids($auction);

    if($request->isXmlHttpRequest()) {
      return $this->renderText('');
    }

    $this->redirect('monitor/index');
  }
}
