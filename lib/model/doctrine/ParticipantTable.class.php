<?php

/**
 * ParticipantTable
 * 
 * This class has provides functions to obtain a participant.
 *
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 */
class ParticipantTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     * @return ParticipantTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Participant');
    }

    /**
     * If there is still a free participant in active session, lock it to the
     * user session and return it, otherwise return false
     * @return boolean|Participant
     */
    public function getFreeParticipantAndLock()
    {
      $session = Doctrine_Core::getTable('Session')->getActiveSession();
      if($session == false) {
        return false;
      }
      
      $userip = sfContext::getInstance()->getUser()->getUniqueIp();

      $q = Doctrine_Query::create()
        ->update('Participant p')
        ->andWhere('p.ip IS NULL')
        ->andWhere('p.session_id = ?', $session->getId())
        ->set('p.money', 'p.init_money')
        ->set('p.ip', '?', $userip)
        ->limit(1);

      $rows = $q->execute();
      if( $rows == 0 ) {
        return false;
      }

      return $this->findOneBy('ip', $userip);
    }

}