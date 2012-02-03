<?php

/**
 * assistant actions.
 *
 * @package    bachelor
 * @subpackage assistant
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class assistantActions extends sfActions
{
 /**
  * Executes index action
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->auction = Doctrine_Core::getTable('Auction')->getActiveAuction();

    if($this->auction == false) {
      $this->session = Doctrine_Core::getTable('Session')->getActiveSession();
      if($this->session == false) {
        $this->sessions = Doctrine_Core::getTable('Session')
          ->createQuery('a')
          ->execute();
      } else {
        $this->auctions = $this->session->getAuctions();
      }
    } else {
      $this->session = $this->auction->getSession();
    }

    $this->form = new AsSessionForm();
  }

  /**
   * First step of assistant, create forms for new session, auctions,
   * participants
   * @param sfWebRequest $request 
   */
  public function executeAssistantCreate(sfWebRequest $request)
  {
    $form = new AsSessionForm();
    $form->bind($request->getParameter('session'));
    if ($form->isValid()) {
      $this->data = $form->getValues();
    } else {
      $this->getUser()->setFlash('error', 'Session konnte nicht angelegt werden!');
      $this->redirect('assistant/index');
    }
  }

  /**
   * Second step of assistant, create forms for each participant and each
   * auction to edit bidders
   * @param sfWebRequest $request 
   */
  public function executeAssistantBidders(sfWebRequest $request)
  {
    sfForm::disableCSRFProtection();
    $conn = Doctrine_Manager::connection();

    try {
      $conn->beginTransaction();

      $session_form = new SessionForm();
      $session_form->bind($request->getParameter('session'));
      $this->processForm($session_form);
      $this->session = $session_form->getObject();

      $this->participants = array();
      $part_data = $request->getParameter('participant');
      foreach($part_data as $i => $participant) {
        $participant['session_id'] = $this->session->getId();
        $participant['money'] = $participant['init_money'];
        $part_form = new ParticipantForm();
        $part_form->bind($participant);
        $this->processForm($part_form);
        $this->participants[] = $part_form->getObject();
      }

      $this->auctions = array();
      $auct_data = $request->getParameter('auction');
      $auct_files = $request->getFiles('auction');
      foreach($auct_data as $i => $auction) {
        $auction['session_id'] = $this->session->getId();
        $auct_form = new AuctionForm();
        $auct_form->bind($auction, $auct_files[$i]);
        $this->processForm($auct_form);
        $this->auctions[] = $auct_form->getObject();
      }

      $this->bidders = array();
      foreach($this->auctions as $i => $auction) {
        foreach($this->participants as $j => $participant) {
          $tmp = new Bidder();
          $tmp->setAuction($auction);
          $tmp->setParticipant($participant);
          $tmp->save();
          $this->bidders[$i][$j] = $tmp;
        }
      }

      $conn->commit();
    } catch(Exception $e) {
      #throw $e;
      $this->getUser()->setFlash('error', 'Neue Sitzung konnte nicht angelegt werden! (' . $e->getMessage() . ')');
      $conn->rollback();
      $this->redirect('assistant/index');
    }

  }

  /**
   * Process form data, throws exception is data is not valid
   * @param sfForm $form
   */
  protected function processForm(sfForm $form)
  {
    if ($form->isValid()) {
      $form->save();
    } else {
      /*$errors = array();
      foreach ($form as $widget):
        $errors[] = $widget->renderRow();
      endforeach;
      throw new Exception(implode(" \n ", $errors));*/
      throw new Exception('Form ' . $form->getName() . ' failed!');
    }
  }

  /**
   * Last step of assistant, save new session, auctions, participants, bidders
   * @param sfWebRequest $request 
   */
  public function executeAssistantSave(sfWebRequest $request)
  {
    sfForm::disableCSRFProtection();
    $error = false;
    $bidder_table = Doctrine_Core::getTable('Bidder');
    $bidders = $request->getParameter('bidder');
    foreach($bidders as $bidder) {
      if(!empty($bidder['valuation']) || !empty($bidder['bid_fee'])) {
        $tmp = $bidder_table->find($bidder['id']);
        $bidder['auction_id'] = $tmp->getAuctionId();
        $bidder['participant_id'] = $tmp->getParticipantId();
        $bidder_form = new BidderForm($tmp);
        $bidder_form->bind($bidder);
        if($bidder_form->isValid()) {
          $bidder_form->save();
        } else {
          $error = true;
        }
      }
    }

    if($error) {
      $this->getUser()->setFlash('error', 'Sitzung wurde angelegt, allerdings konnten nicht alle Bieter-Einstellungen übernommen werden!');
    } else {
      $this->getUser()->setFlash('notice', 'Neue Sitzung erfolgreich angelegt!');
    }
    $this->redirect('assistant/index');
  }

  /**
   * Start auction
   * @param sfWebRequest $request
   */
  public function executeAuctionStart(sfWebRequest $request)
  {
    $this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));
    $auction->startAuction();

    $this->redirect('monitor/index');
  }

  /**
   * Stop auction
   * @param sfWebRequest $request
   */
  public function executeAuctionStop(sfWebRequest $request)
  {
    $this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('auction_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('auction_id')));

    if($request->getParameter('submit') == 'Auktion unterbrechen') {
      $auction->interruptAuction();
    } else {
      $auction->stopAuction();
    }

    $this->redirect('assistant/index');
  }

  /**
   * Activate session
   * @param sfWebRequest $request
   */
  public function executeSessionActivate(sfWebRequest $request)
  {
    $this->forward404Unless($session = Doctrine_Core::getTable('Session')->find($request->getParameter('session_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('session_id')));
    $session->active = 1;
    $session->save();

    $this->redirect('assistant/index');
  }

  /**
   * Deactivate session
   * @param sfWebRequest $request
   */
  public function executeSessionDeactivate(sfWebRequest $request)
  {
    $this->forward404Unless($session = Doctrine_Core::getTable('Session')->find($request->getParameter('session_id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('session_id')));
    $session->active = 0;
    $session->save();

    $this->redirect('assistant/index');
  }
}
