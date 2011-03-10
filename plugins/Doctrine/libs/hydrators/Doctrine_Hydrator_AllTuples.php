<?php

class Doctrine_Hydrator_AllTuples extends Doctrine_Hydrator_Abstract
{
  public function hydrateResultSet($stmt)
  {
    $cache = array();
    $result = array();
    
    while ( $data = $stmt->fetch(Doctrine_Core::FETCH_ASSOC) )
      $result[] = $this->_gatherRowData($data, $cache);

    return $result;
  }
  

  protected function _gatherRowData($data, &$cache, $aliasPrefix = true)
  {
    $rowData = array();

    foreach ($data as $key => $value)
    {
      if ($key == 'DOCTRINE_ROWNUM')
	continue;

      if ( ! isset($cache[$key]))
	$this->setCache($cache, $key, $value);
	      
      $table = $this->_queryComponents[$cache[$key]['dqlAlias']]['table'];
      $fieldName = $cache[$key]['fieldName'];
      
      if ($cache[$key]['isSimpleType'] || $cache[$key]['isAgg'])
	$rowData[$fieldName] = $value;
      else
	$rowData[$fieldName] = $table->prepareValue($fieldName, $value,
						    $cache[$key]['type']);
    }
    return $rowData;
  }


  protected function setCache(&$cache, $key, $value)
  {
    $e = explode('__', $key);
    $columnName = array_pop($e);
    $alias = $this->_tableAliases[implode('__', $e)];
    $cache[$key]['dqlAlias'] = $alias;
    $table = $this->_queryComponents[$alias]['table'];

    if ( isset($this->_queryComponents[$alias]['agg'][$columnName]) )
    {
      $fieldName = $this->_queryComponents[$alias]['agg'][$columnName];
      $cache[$key]['isAgg'] = true;
    }
    else
    {
      $fieldName = $table->getFieldName($columnName);
      $cache[$key]['isAgg'] = false;
    }
    
    $cache[$key]['fieldName'] = $fieldName;
    $type = $table->getTypeOfColumn($columnName);
    $cache[$key]['isSimpleType'] = ($type == 'integer' || $type == 'string');
    $cache[$key]['type'] = $type;
  }
}