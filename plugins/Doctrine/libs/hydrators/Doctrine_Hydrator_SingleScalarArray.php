<?php

class Doctrine_Hydrator_SingleScalarArray extends Doctrine_Hydrator_Abstract
{
  public function hydrateResultSet($stmt)
  {
    $result = array();
    
    while ($data = $stmt->fetchColumn())
      $result[] = $data;
    
    return $result;
  }
}