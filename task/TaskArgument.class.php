<?php

/*
**  Définit un argument.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	10/17/2009, 11:34 PM
**  @last	Nivl <nivl@free.fr> 03/28/2010, 03:56 PM
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

class TaskArgument
{
  const REQUIRED = 0x01;
  const OPTIONAL = 0x02;
  
  protected
    $type	 = self::REQUIRED,
    $name	 = null,
    $value	 = null,
    $default	 = null,
    $description = null;
    
    
  public function __construct($name, $description,
			      $type = self::REQUIRED, $default = null)
  {
    $this->name		= $name;
    $this->type		= $type;
    $this->default	= $default;
    $this->description	= _($description);
    
    if ( !in_array($type, array(self::REQUIRED, self::OPTIONAL)) )
      $this->type = self::OPTIONAL;
  }
  
  
  public function setValue($val)
  {
    return $this->value = $val;
  }
  
  
  public function hasValue()
  {
    return $this->value !== null;
  }

  
  public function isRequired()
  {
    return $this->type === self::REQUIRED;
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
    return (!empty($this->value)) ? $this->value : $this->default;
  }
}
