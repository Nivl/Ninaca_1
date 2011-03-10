<?php

/*
**  Valide une boite de sélection simple.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/08/2009, 06:46 PM
**  @last	Nivl <nivl@free.fr> 03/09/2010, 06:49 PM
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

class ValidateSelect extends Validate
{
  protected $valuesList = array();
  
  public function __construct(array $list, array $opt = array())
  {
    $this->valuesList = $list;
    parent::__construct($opt);
  }
  
  
  protected function execute($value)
  {
    if ( !isset($this->valuesList[$value]) )
      $this->addError('You selected a value which doesn’t exists.');
    
    return $value;
  }
}



