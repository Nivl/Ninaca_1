<?php

/*
**  Classe qui gère les boutons submit.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/15/2009, 07:31 PM
**  @last	Nivl <nivl@free.fr> 03/15/2010, 08:47 PM
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

class FormSubmit extends FormField
{
  protected $static_value = null;

  public function __construct($value = null, array $opt = array(),
			      array $classes = array())
  {
    $this->static_value = $value;
    parent::__construct($opt, $classes);
  }


  public function display()
  {
    return $this->getField();
  }

  public function getField()
  {
    $class = $this->getClasses(FormField::GET_AS_HTML);

    return '<input name="'.$this->name.'" id="'.$this->id.'" '.$class.
      ' type="submit" value="'._($this->static_value).'" />';
  }

  
  public function check()
  {
    if ( $this->static_value == null )
      $this->static_value = String::ucFirst(str_replace('_', ' ',$this->name));
    $this->static_value = _($this->static_value);
  }
}


