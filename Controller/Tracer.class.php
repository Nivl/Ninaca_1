<?php

/*
**  Sert à créer un tracer.
**
**  @author	Nivl <nivl@free.fr>
**  @started	12/30/2009, 11:58 PM
**  @last	Nivl <nivl@free.fr> 01/02/2010, 05:44 PM
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


class Tracer
{
  protected
    $list = array();
  
  public function __construct() {}
  
  public function unshift($name, $link = null)
  {
    array_unshift($this->list, array('name' => $name, 'link' => $link));
  }
  
  
  public function unshiftList(array $traces)
  {
    foreach ( $traces as $trace )
      $this->unshift($trace['name'], $trace['link']);
  }
  
  
  public function unshiftArray(array $array, $name, $link)
  {
    $this->addArray($array, $name, $link);
  }


  public function push($name, $link = null)
  {
    $this->list[] = array('name' => $name, 'link' => $link);
  }
  
  
  public function pushList(array $traces)
  {
    foreach ( $traces as $trace )
      $this->push($trace['name'], $trace['link']);
  }
  

  public function pushArray(array $array, $name, $link)
  {
    $this->addArray($array, $name, $link, 'push');
  }

  
  protected function addArray(array $array, $name, $link, $type = 'push')
  {
    foreach ( $array as $values )
    {
      $real_link = $link;
      $list = array();
      $tmp = explode('{', $link);
      unset($tmp[0]);
      
      foreach ($tmp as $var)
      {
	$var = current(explode('}', $var));
	$real_link = str_replace('{'.$var.'}', $values[$var], $real_link);
      }
      
      if ( $type === 'push' )
	$this->push($values[$name], $real_link);
      else
	$this->unshift($values[$name], $real_link);
    }
  }
  
  
  public function getTraces()
  {
    return $this->list;
  }
}

