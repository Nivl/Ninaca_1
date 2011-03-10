<?php

/*
**  Definit une option.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/02/2009, 04:57 PM
**  @last	Nivl <nivl@free.fr> 03/28/2010, 04:23 PM
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

class TaskOption
{
  const BOOL = 1;
  const TEXT = 2;
  
  protected
    $type	 = self::TEXT,
    $name	 = null,
    $value	 = null,
    $default	 = null,
    $shortcut	 = null,
    $description = null;
  
  public function __construct($name, $description, $default, 
			      $shortcut = null, $type = self::TEXT)
  {
    $this->name	= $name;
    $this->description = _($description);
    $this->type = $type;
    $this->default = $default;
    $this->shortcut = $shortcut;

    if ( !in_array($type, array(self::BOOL, self::TEXT)) )
      $this->type = self::TEXT;
  }


  public function setValue($val)
  {
    return $this->value = $val;
  }
  
  
  public function hasValue()
  {
    return $this->value !== null;
  }
  
  
  public function hasShortcut()
  {
    return $this->shortcut !== null;
  }


  public function getName()
  {
    return $this->name;
  }


  public function getDescription()
  {
    return $this->description;
  }


  public function getValue()
  {
    return $this->value ?: $this->default;
  }


  public function getShortcut()
  {
    return $this->shortcut;
  }  


  public function isBool()
  {
    return $this->type === self::BOOL;
  }
}


