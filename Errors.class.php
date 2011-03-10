<?php

/*
**  Affiche les messages d'erreurs.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/29/2009, 01:39 AM
**  @last	Nivl <nivl@free.fr> 11/23/2009, 11:31 PM
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


abstract class Errors
{
  static public function display($msg, $type = E_USER_NOTICE)
  {
    $sapi = substr(PHP_SAPI, 0, 3);

    if ( $sapi === 'cli' || $sapi === 'cgi' )
      self::printAsCommandLine($msg);
    else
      self::printAsHtml($msg, $type);
  }
  
  
  static public function printAsCommandLine($msg)
  {
    exit($msg.PHP_EOL);
  }
  
  
  static public function printAsHtml($msg, $type)
  {
    $msg .= '<br /><br />This error has been generated';
    trigger_error($msg, $type);
  }
}



