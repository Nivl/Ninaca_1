<?php

/*
**  Gère les URLs
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/10/2009, 11:11 PM
**  @last	Nivl <nivl@free.fr> 11/19/2009, 12:54 AM
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


class Url implements ArrayAccess
{
  static public $Instance = null;

  protected
    $vars = array();


  static public function getInstance()
  {
    if ( self::$Instance === null )
      self::$Instance = new Url();

    return self::$Instance;
  }


  public function getUrl()
  {
    $url = null;
    
    foreach ( $this->vars as $key => $value )
      $url .= '/'.$key.':'.$value;
    
    return String::substr($url, 1);
  }
  
  
  public function varExists($var_name)
  {
    return isset($this->vars[$var_name]);
  }
  
  
  public function getVar($var_name)
  {
    return ($this->varExists($var_name)) ? $this->vars[$var_name] : false;
  }


  public function setVar($name, $value)
  {
    $this->vars[$name] = $value;
  }


  public function offsetExists($offset)
  {
    return $this->varExists($offset);
  }


  public function offsetGet($offset)
  {
    return $this->getVar($offset);
  }


  public function offsetSet($offset, $value)
  {
    $this->setVar($offset, $value);
  }
  
  
  public function offsetUnset($offset)
  {
    exit("You can't remove the variable $offset.");
  }
  
  
  private function getAllVarsFromURL()
  {
    if ( !empty($_GET['url']) )
    {
      $vars = explode('/', $_GET['url']);
      $args = array(0=>'controller', 1=>'action', 2=>'id');
      $i = 0;
      
      foreach ( $vars as $var )
      {
	if ( !empty($var) )
	{
	  if ( $i < count($args) && !strpos($var, ':') )
	    $this->vars[$args[$i]] = $var;
	  else
	  {
	    $key = String::strstr($var, ':', true);
	    $value = String::substr(String::strstr($var, ':'), 1);
	    if ( !empty($key) && !empty($value) )
	      $this->vars[$key] = $value;
	  }
	
	  ++$i;
	}
      }
    }
  }
  
  private function __construct()
  {
    $this->getAllVarsFromURL();
  }
  private function __clone(){}
}

