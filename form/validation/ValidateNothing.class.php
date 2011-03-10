<?php

/*
**  Valide rien, retourne seulement la valeur donnée.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/15/2009, 11:44 PM
**  @last	Nivl <nivl@free.fr> 12/16/2009, 01:50 PM
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

class ValidateNothing extends Validate
{
  public function __construct(array $opt = array())
  {
    $opt['required'] = false;
    $opt['allowEmpty'] = true;
    parent::__construct($opt);
  }


  protected function execute($value)
  {
    return $value;
  }
}

?>