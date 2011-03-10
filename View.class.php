<?php

/*
**  Gère les vues.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/12/2009, 05:10 PM
**  @last	Nivl <nivl@free.fr> 12/29/2009, 04:34 PM
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


class View implements ArrayAccess
{
  protected
    $file = null,
    $layout = null,
    $layoutVars = array(),
    $vars = array();
  

  public function __construct($file)
  {
    $this->setView($file);
  }

  public function setView($file)
  {
    if ( !is_file($file) )
      exit("The file $file doesn't exists.");
    
    $this->file = $file;
  }


  public function setLayout($file, array $vars = array())
  {
    if ( !is_file('globals/views/layouts/'.$file) )
      exit("The file $file doesn't exists.");
    
    $this->layout = 'globals/views/layouts/'.$file;
    $this->layoutVars = $vars;
  }


  public function __isset($name)
  {
    return $this->varExists($name);
  }


  public function __get($name)
  {
    return $this->getVar($name);
  }


  public function __set($var, $value)
  {
    $this->setVar($var, $value);
  }


  public function __unset($name)
  {
    $this->unsetVar($name);
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
    $this->unsetVar($offset);
  }


  public function varExists($offset)
  {
    return isset($this->vars[$offset]);
  }


  public function getVar($offset)
  {
    return $this->vars[$offset];
  }


  public function setVar($offset, $value)
  {
    $this->vars[$offset] = $value;
  }


  public function unsetVar($offset)
  {
    unset($this->vars[$offset]);
  }



  private function getFilePath(array $filename)
  {
    if ( count($filename) === 2 )
    {
      if ( $filename[0] === 'globals' )
	$path = 'globals/views/'.$filename[1].PHP;
      else
	$path = 'apps/views/'.$filename[0].'/'.$filename[1].PHP;
    }
    else
      exit("Error, filename must be an array of two values.");

    if ( !is_file($path) )
      exit("Error, the path $path doesn't exists.");
    
    return $path;
  }


  public function render(array $_private_filename = array(),
			 array $_private_vars = array() )
  {
    if ( $this->layout <> null )
    {
      $_private_vars = $this->layoutVars;
      $_private_file = $this->layout;
      $this->layout = null;
    }
    else if ( empty($filename) )
    {
      $_private_vars = $this->vars;
      $_private_file = $this->file;
    }
    else
      $_private_file = $this->getFilePath($filename);

    foreach ( $_private_vars as $key => $val )
    {
      if ( String::substr($key, 0, 9) !== '_private_' )
	${$key} = $val;
    }

    require $_private_file;
  }
}

