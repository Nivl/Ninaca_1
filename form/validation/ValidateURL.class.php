<?php

/*  
**  Valide une URL.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 11:53 PM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 06:09 PM
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

class ValidateURL extends Validate
{
  public function execute($value)
  {
    if ( !filter_var($value, FILTER_VALIDATE_URL) )
    {
      if ( !filter_var('http://'.$value, FILTER_VALIDATE_URL) )
	$this->errors[] = __('This field is not a valid link.');
      else
	$value = 'http://'.$value;
    }

    return $value;
  }
}

