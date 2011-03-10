<?php

class Doctrine_Hydrator_OneTuple extends Doctrine_Hydrator_AllTuples
{
  public function hydrateResultSet($stmt)
  {
    $cache = array();
    
    $data = $stmt->fetch(Doctrine_Core::FETCH_ASSOC);
    return $data ? $this->_gatherRowData($data, $cache) : array();
  }
}