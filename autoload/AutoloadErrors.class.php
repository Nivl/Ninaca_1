<?php

/*
**  Gère les erreurs de l'autoload.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/09/2009, 04:01 PM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 03:59 PM
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

class AutoloadErrors Extends Errors
{
  static public function noDirs($path)
  {
    $msg = _('The autoload file has no directories defined. '.
	     'The file location is: %s.');
    parent::print(sprintf($msg, $path));
  }


  static public function noExts($path)
  {
    $msg = _('The autoload file has no extensions defined. '.
	     'The file location is: %s.');
    parent::print(sprintf($msg, $path));
  }
}

