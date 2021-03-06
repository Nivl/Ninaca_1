<?php

/*
**  Classe qui gère les champs input des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/20/2009, 03:37 AM
**  @last	Nivl <nivl@free.fr> 02/20/2010, 02:05 AM
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

class FormInputPassword extends FormInput
{
  public function __construct(array $options = array())
  {
    if ( !isset($options['bound']) )
      $options['bound'] = false;

    parent::__construct($options);
  }
  
  
  public function getField()
  {
    $class = $this->getClasses(FormField::GET_AS_HTML);
    
    return '<input name="'.$this->name.'" id="'.$this->id.'" '.$class.
      ' type="password" value="'.$this->bound().'" />';
  }
}


