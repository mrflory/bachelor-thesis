<?php

require_once dirname(__FILE__).'/../lib/auctionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/auctionGeneratorHelper.class.php';

/**
 * auction actions.
 *
 * @package    bachelor
 * @subpackage auction
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class auctionActions extends autoAuctionActions
{
  /**
   * Start auction
   * @param sfWebRequest $request
   */
  public function executeStartAuction(sfWebRequest $request)
  {
    $this->forward404Unless($auction = Doctrine_Core::getTable('Auction')->find($request->getParameter('id')),
                                              sprintf('Object auction does not exist (%s).', $request->getParameter('id')));
    $auction->startAuction();

    $this->redirect('monitor/index');
  }

  /**
   * Redirect to monitor to show this auction
   * @param sfWebRequest $request
   */
  public function executeMonitor(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $this->redirect('monitor/index?auction_id=' . $request->getParameter('id'));
  }
}
