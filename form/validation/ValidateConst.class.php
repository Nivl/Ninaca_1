<?php

/*
**  Valide une Constante.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/26/2009, 07:12 PM
**  @last	Nivl <nivl@free.fr> 12/26/2009, 07:17 PM
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

class ValidateConst extends Validate
{
  protected $constant = null;

  public function __construct($value, array $opt = array())
  {
    $this->constant = $value;
    
    $opt['required'] = true;
    $opt['allowEmpty'] = false;
    parent::__construct($opt);
  }


  protected function execute($value)
  {
    return $this->constant;
  }
}

?>