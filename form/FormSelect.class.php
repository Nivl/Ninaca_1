<?php

/*
**  Classe qui gère les champs de type select des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/06/2009, 10:24 PM
**  @last	Nivl <nivl@free.fr> 03/09/2010, 06:53 PM
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

class FormSelect extends FormInput
{
  protected
    $valuesList = array(),
    $optClasses = array();

  public function __construct(array $list = array(), array $opt = array(),
			      array $classes = array(),
			      array $optClasses = array())
  {
    $this->valuesList = $list;
    parent::__construct($opt, $classes);
  }

  public function getField()
  {
    $value = $this->getValue();
    $ret = '<select name="'.$this->name.'" id="'.$this->id.'">';
    $class = $this->getClasses(FormField::GET_AS_HTML);
    $optClass = 'class="'.implode(' ', $this->optClasses).'"';

    foreach ( $this->valuesList as $id => $name )
    {
      $select = ($id == $value) ? 'selected="selected"' : null;
      $ret .= '<option '.$optClass.' value="'.$id.'" '.$select.'>'.
	$name.'</option>'."\n";
    }

    return $ret.'</select>';
  }
  
  
  public function addOptClasses(array $Classes)
  {
    $this->optClasses = array_merge($this->optClasses, $classes);
      }
  
  
  public function addOptClass($class)
  {
    $this->optClasses[] = $class;
  }
  

  public function deleteOptClasses(array $classes)
  {
    $this->optClasses = Arrays::rmKeysArray($classes, $this->optClasses);
  }

  
  public function deleteOptClass($class)
  {
    $this->optClasses = Arrays::rmKeys($class, $this->optClasses);
  }
}


