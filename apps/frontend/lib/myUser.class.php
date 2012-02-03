<?php

/**
 * myUser class, provides functions to get and refresh the unique ip address.
 *
 * @package    bachelor
 * @subpackage frontend
 * @author     Florian Stallmann
 */
class myUser extends sfBasicSecurityUser
{
  /**
   * If user already has unique ip, return it, otherwise create unique ip with
   * remote address appended by random key
   *
   * @return string Unique User-IP
   */
  public function getUniqueIp()
  {
    if( !$this->hasAttribute('uniqueip') ) {
      $this->setAttribute('uniqueip', $this->generateUniqueIp() );
    }
    return $this->getAttribute('uniqueip');
  }

  /**
   * Generate a new unique ip, e.g. for new session
   * @return string
   */
  public function refreshUniqueIp()
  {
    $this->setAttribute('uniqueip', $this->generateUniqueIp() );
    return $this->getAttribute('uniqueip');
  }

  /**
   * Helper method to generate unique ip
   * @return string
   */
  protected function generateUniqueIp()
  {
    return sfContext::getInstance()->getRequest()->getRemoteAddress() .'+' . substr(md5(uniqid(mt_rand(), true)), 0, 5);
  }
}
