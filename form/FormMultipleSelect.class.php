<?php

/*
**  Classe qui gère les champs de type select multiple des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	01/16/2010, 12:02 AM
**  @last	Nivl <nivl@free.fr> 02/20/2010, 02:34 AM
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

class FormMultipleSelect extends FormInput
{
  protected
    $valuesList = array(),
    $optClasses = array(),
    $size = 20;
  
  public function __construct(array $list = array(), $size = 20,
			      array $opt = array(), array $classes = array(),
			      array $optClasses = array() )
  {
    $this->valuesList = $list;
    $this->size = $size;
    
    parent::__construct($opt, $classes);
  }
  
  public function getField()
  {
    $values = ($values = $this->getValue()) ? $values : array();
    $class = $this->getClasses(FormField::GET_AS_HTML);
    
    $ret = '<select name="'.$this->name.'" id="'.$this->id.'" '.$class.
      ' multiple="multiple" size="'.$this->size.'">';
    
    foreach ( $this->valuesList as $id => $name )
    {
      $select = in_array($id, $values) ? 'selected="selected"' : null;
      $ret .= '<option value="'.$id.'" '.$select.'>'.$name.'</option>'."\n";
    }
    
    return $ret.'</select>';
  }


  public function addOptClasses(array $Classes)
  {
    $this->optClasses = array_merge($this->optClasses, $classes)
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


