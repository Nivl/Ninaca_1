<?php

/*
**  Cette classe s'occupe des règles d'autorisation.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/19/2009, 01:47 AM
**  @last	Nivl <nivl@free.fr> 11/29/2009, 06:48 PM
**  @link	http://nivl.free.fr
**  @copyright	Copyright (C) 2009 Laplanche Melvin
**  
**  This program is free software: you can redistribute it and/or modify
**  it under the terms of the GNU General Public License as published by
**  the Free Software Foundation, either version 3 of the License, or
**  (at your option) any later version.
**
**  This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
**  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Rules
{  
  static private $Instance	= null;
  private $rules		= array();
  private $Cache		= null;


  static public function getInstance()
  {
    if ( self::$Instance === null )
      self::$Instance = new Rules();
    
    return self::$Instance;
  }
  
  
  
  /*
  ** Retourne l'id d'une règle en fonction de son nom.
  **
  ** @param string rule [Nom de la règle]
  ** 
  ** @return int
  */
  public function getId($rule)
  {
    $tmp = array_search($rule, $this->rules, true);
    
    if ( !empty($tmp) )
      return $tmp;
    else
    {
      RuleError::notExists($rule);
      return -1;
    }
  }
  
  
  
  /*
  ** Ajoute des règles
  ** 
  ** @param string|array rules [Règle(s) à ajouter]
  */
  public function addRules($rules)
  {
    exit('FIX ME');
    $tuples = array();
    $arg = array();
    $const = sqlConsts();
    $table = tableList();
    
    if ( !is_array($rules) )
      $rules = array($rules);
    
    if ( !empty($rules) )
    {
      foreach ( $rules as $rule )
      {
	$tuples[] = '('.$const[SQL_DBMS]['default'].', ?)';
	$arg[] = $rule;
      }
      
      $sql = 'INSERT INTO '.SQL_PREFIX.'auth_rules
              (id, name)
              VALUES '.implode(','. $tuples).';';
      
      $this->execPrepStat($sql, $arg, Misc::whoCalledMe());
      $this->reloadRules();
    }
  }
  
  
  private function __construct()
  {
    $this->Cache = CacheFactory::getInstance();
    $this->getRulesList();
  }
  
  
  /*
  ** Récupère la liste des règles.
  */
  private function getRulesList()
  {
    //if ( ($this->rules = $this->Cache->get('system/rulesList')) === false )
      $this->reloadRules();
  }
  
  
  
  /*
  ** Récupère et met en cache la liste des règles dans la BDD.
  */
  private function reloadRules()
  {
    $this->rules = array();
    $data = Doctrine_Query::create()
      ->select('id, name')
      ->from('Rule')
      ->fetchArray();

    foreach ( $data as $key => $array )
      $this->rules[$array['id']] = $array['name'];
    
    //$this->Cache->store('system/rulesList', $this->rules, 0);
  }
}




