<?php

/*
**  Valide une adresse e-mail.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 11:44 PM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 05:09 PM
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

class ValidateEmail extends Validate
{
  protected function execute($value)
  {
    if ( !filter_var($value, FILTER_VALIDATE_EMAIL) )
      $this->errors[] = _('This field is not a valid e-mail address.');

    return $value;
  }
}

?>