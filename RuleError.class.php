<?php

/*
**  Gère les erreurs sur les règles
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/29/2009, 12:17 AM
**  @last	Nivl <nivl@free.fr> 11/29/2009, 12:21 AM
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


class RuleError extends Errors
{
  static public function notExists($rule)
  {
    $msg = _("The rule ($rule) you tried to use does not exists.");
    $msg = sprintf($msg, $rule);
    parent::display($msg);
  }
}

