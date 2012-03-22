<?php

/**
 * SessionTable
 * 
 * This class provides functions to get a specific session.
 *
 * @package    bachelor
 * @subpackage model
 * @author     Florian Stallmann
 */
class SessionTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     * @return SessionTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Session');
    }

    /**
     * Get active session
     * @param Doctrine_Query $q
     * @return Session
     */
    public function getActiveSession(Doctrine_Query $q = null)
    {
      if (is_null($q))
      {
        $q = Doctrine_Query::create()
          ->from('Session s');
      }
   
      $alias = $q->getRootAlias();
   
      $q->andWhere($alias . '.active = 1');
        #->addOrderBy($alias . '.created_at DESC');

      if($q->count() > 1) {
        throw new Exception('There is more than 1 active session!');
      }
   
      return $q->fetchOne();
    }
}
