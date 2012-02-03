<?php

/**
 * default actions.
 *
 * @package    bachelor
 * @subpackage default
 * @author     Florian Stallmann
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action, if loggend in redirect to assistant
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential('admin'))
    {
      $this->redirect('assistant/index');
    }
  }

  /**
   * Admin login
   * @param sfWebRequest $request
   */
  public function executeLogin(sfWebRequest $request)
  {
    if($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential('admin'))
    {
      $this->redirect('assistant/index');
    }

    if($request->isMethod(sfRequest::POST)) {
      if ( sfConfig::has('app_users_'.$request->getParameter('username'))
        && sfConfig::get('app_users_'.$request->getParameter('username')) == $request->getParameter('password'))
      {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->addCredential('admin');
        $this->getUser()->setFlash('notice', 'Login successful!');
        $this->redirect('assistant/index');
      } else {
        $this->getUser()->setFlash('error', 'Wrong username or password!');
      }
    }
  }

  /**
   * Logout user
   * @param sfWebRequest $request
   */
  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->setAuthenticated(false);
    $this->getUser()->clearCredentials();
    $this->redirect('default/index');
  }

}
