<?php

/**
 * Bid actions, provides all important actions for the frontend.
 *
 * @package    bachelor
 * @subpackage bid
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bidActions extends sfActions
{

  /**
   * Homepage of an auction, assigns unique ip and participant
   * @param sfWebRequest $request
   */
  public function executeIndex(sfWebRequest $request)
  {
    $language = $request->getPreferredCulture(array('de', 'en'));
    $this->getUser()->setCulture($language);
    
    $this->userip  = $this->getUser()->getUniqueIp();
    $this->session = Doctrine_Core::getTable('Session')->getActiveSession();

    if($this->session == false) {
        $this->getUser()->setFlash('error', 'Keine Session aktiv!', false);
        $this->participant = false;
    } elseif( $this->getUser()->hasAttribute('participant_id') ) {
      $this->participant = Doctrine_Core::getTable('Participant')->find( $this->getUser()->getAttribute('participant_id') );
      if( $this->participant->getSession() != $this->session || $this->participant->ip != $this->getUser()->getUniqueIp()) {
        $this->getUser()->refreshUniqueIp();
        $this->getUser()->getAttributeHolder()->remove('participant_id');
        $this->getUser()->setFlash('notice', 'Aktive Session wurde geändert. Ein neuer Teilnehmer wurde zugewiesen.');
        $this->redirect('bid/index');
      }
    } else {
      $this->participant = Doctrine_Core::getTable('Participant')->getFreeParticipantAndLock();
      if( $this->participant == false ) {
        $this->getUser()->setFlash('error', 'Aktive Session ist bereits voll!', false);
      } else {
        $this->getUser()->setAttribute('participant_id', $this->participant->getId());
      }
    }

    if($this->participant !== false) {
      $currency = $this->session->getCurrency();
      $this->message = $this->session->getMessage();
      $this->money = sprintf($currency, $this->participant->getMoney());
    }
  }

  /**
   * Returns if auction is active and sends inital data as JSON data if
   * necessary, only available via ajax request.
   * @param sfWebRequest $request
   */
  public function executeWait(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasAttribute('participant_id'), 'Participant ID is not set!');
    $this->forward404Unless($request->isXmlHttpRequest(), 'This is not an Ajax Request');

    $this->participant = Doctrine_Core::getTable('Participant')->find($this->getUser()->getAttribute('participant_id'));
    
    $this->auction = Doctrine_Core::getTable('Auction')->getActiveAuction();
    if($this->auction == false) {
      $this->auction = Doctrine_Core::getTable('Auction')->getLastActiveAuction();
    }

    $result = array(
        'active' => false,
        'show_last' => false,
        'directbuy_last' => false,
        );

    if($this->auction !== false) {

      if($this->auction->getEndTime()) {
        $result['show_last'] = true;
        $result = array_merge($result, $this->auction->getAjaxArray($this->participant));

        // Direktkauf nach Auktion
        if($result['showdirectbuy'] && !$result['bidder_bought'] && !$result['thisiswinner']) {
          $result['directbuy_last']  = true;
        }
      } else {
        $result['active'] = true;
        $result = array_merge($result, $this->auction->getAjaxArray($this->participant, true));

        if($result['showbidders']) {
          $result['history'] = $this->getPartial('bid/history', array('history' => $this->auction->getHistory()) );
        }
      }
    }

    $this->getResponse()->setHttpHeader('Content-type','application/json');
    return $this->renderText(json_encode($result));
  }

  /**
   * Returns actual status of an auction as JSON data, only available via
   * ajax request.
   * @param sfWebRequest $request
   */
  public function executeStatus(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest(), 'This is not an Ajax Request');
    $this->forward404Unless($this->getUser()->hasAttribute('participant_id'), 'Participant ID is not set!');
    $this->forward404Unless($this->auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));
    $this->forwardIf($this->auction->getEndTime(), 'bid', 'wait');
    $this->forwardUnless($this->auction->getStartTime(), 'bid', 'wait');

    $this->participant = Doctrine_Core::getTable('Participant')->find($this->getUser()->getAttribute('participant_id'));

    $result = $this->auction->getAjaxArray($this->participant);
    $result['active'] = true;

    if($result['showbidders']) {
      $result['history'] = $this->getPartial('bid/history', array('history' => $this->auction->getHistory()) );
    }

    /*if($this->auction->getBotBehaviour() == 'off') {
    } else {
      $result['bot_limitcount'] = $this->auction->getBotlimitCountdown();
    }*/
    
    $this->getResponse()->setHttpHeader('Content-type','application/json');
    return $this->renderText(json_encode($result));
  }

  /**
   * Request to place a bid, gets auction id and current auction price to
   * prevent being overbid during this process
   * @param sfWebRequest $request
   */
  public function executePlacebid(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasAttribute('participant_id'), 'Participant ID is not set!');
    $this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));

    $participant = Doctrine_Core::getTable('Participant')->find($this->getUser()->getAttribute('participant_id'));
    $success = $auction->placeBid($participant, $request->getParameter('curprice'));

    $this->logMessage('Request to make a bid ' . ($success ? 'successfull' : 'failed'));

    if ($request->isXmlHttpRequest()) {
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      return $this->renderText(json_encode(array('successful' => $success)));
    }
    
    if($success) {
      $this->getUser()->setFlash('notice', 'Gebot abgegeben');
    } else {
      $this->getUser()->setFlash('error', 'Gebot fehlgeschlagen');
    }
    $this->redirect('bid/index');
  }

  /**
   * Request to activate a new bot, gets auction id
   * @param sfWebRequest $request
   */
  public function executeActivatebot(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasAttribute('participant_id'), 'Participant ID is not set!');
    #$this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
    #                                          sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));

    $bidder = Doctrine_Core::getTable('Bidder')->getBidder($request->getParameter('auction_id'), $this->getUser()->getAttribute('participant_id'));

    $form = new FrontendbotForm();
    $form->bind($request->getParameter('bot'));
    if ($form->isValid()) {
      $success = $bidder->activateBot($form->getValues());
    } else {
      $success = false;
    }

    $this->logMessage('Request to activate a bid bot ' . ($success ? 'successfull' : 'failed'));

    if ($request->isXmlHttpRequest()) {
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      return $this->renderText(json_encode(array('successful' => $success)));
    }

    if($success) {
      $this->getUser()->setFlash('notice', 'Biet-Automat aktiviert');
    } else {
      $this->getUser()->setFlash('error', 'Aktivierung fehlgeschlagen');
    }
    $this->redirect('bid/index');
  }

  /**
   * Request to buy a product directly, gets auction id and total price
   * @param sfWebRequest $request
   */
  public function executeDirectbuy(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasAttribute('participant_id'), 'Participant ID is not set!');
    #$this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
    #                                          sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));

    $bidder = Doctrine_Core::getTable('Bidder')->getBidder($request->getParameter('auction_id'), $this->getUser()->getAttribute('participant_id'));
    $success = $bidder->buyDirect($request->getParameter('directtotal'));

    $this->logMessage('Request for buy it now ' . ($success ? 'successfull' : 'failed'));

    if ($request->isXmlHttpRequest()) {
      $this->getResponse()->setHttpHeader('Content-type','application/json');
      return $this->renderText(json_encode(array('successful' => true)));
    }

    if($success) {
      $this->getUser()->setFlash('notice', 'Produkt gekauft');
    } else {
      $this->getUser()->setFlash('error', 'Kauf fehlgeschlagen');
    }
    $this->redirect('bid/index');
  }

}
