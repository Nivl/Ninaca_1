<?php

/*
**  Classe d'autentification d'utilisateur.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/13/09
**  @last	Nivl <nivl@free.fr> 01/05/2010, 06:54 PM
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

class Auths
{
  private $Cache	= null;
  private $Rules	= null;
  private $Session	= null;
  private $tables	= array();
  private $auth		= array();

  public function __construct()
  {
    $this->Session = Session::getInstance();
    $this->Rules = Rules::getInstance();
    $this->Cache = CacheFactory::factory();
    $this->tables = Config::read('modules.Session.User.Auth.tables');
    $this->auths = array();
  }
  
  
  public function check($rule, $x_id = 0)
  {
    $rid = $this->Rules->getId($rule);
    
    if ( $rid > 0 )
    {
      if ( $x_id )
	return (bool)@$this->auths[$rid][$x_id];
      else
	return (bool)@$this->auths[$rid] && @!is_array($this->auths[$rid]);
    }
    else
      return false;
  }
  
  
  public function loadUserAuths($uid, array $gid)
  {
//    if ( !$this->Cache->exists('users/auths/'.$uid) )
      $this->getUserAuths($uid, $gid);
//    else
//      $this->auths = $this->Cache->get('users/auths'.$uid);
  }
  
  
  private function getUserAuths($uid, array $gid)
  {
    foreach ( $this->tables as $name )
    {
      $data = $this->getUserAuthsQuery($name, $gid);
      foreach ( $data as $key => $dat )
      {
	if ( !isset($dat['xid']) )
	  @(int)$this->auths[$dat['rid']] |= (int)$dat['value'];
	else
	  @(int)$this->auths[$dat['rid']][$dat['xid']] |= (int)$dat['value'];
      }
    }
    
    //$this->Cache->store('users/auths/'.$uid, $this->auths, ONE_MINUTE*5);
  }
  
  
  
  private function getUserAuthsQuery($table, array $gid)
  {
    if ( is_array($table) )
    {
      $name = key($table);
      $x_id = $table[$name];
    }
    else
      $name = $table;

    $name = ($name === 'Auth') ? null : $name;
    
    $Sql = Doctrine_Query::create()
      ->select('rule_id as rid, value')
      ->from($name.'Auth')
      ->WhereIn('group_id', $gid);
     
    if ( isset($x_id) )
      $Sql->addSelect($x_id.' as xid');
    
    return $Sql->fetchArray();
  }
}


