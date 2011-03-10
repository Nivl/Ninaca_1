<?php

/*
**  Fonctions divers
**
**  @package	fanfan_groupe
**  @author	Nivl <nivl@free.fr>
**  @started	07/17/2009, 12:01 AM
**  @last	Nivl <nivl@free.fr> 03/26/2010, 07:09 PM
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

class Misc
{
  static public function getLibPath()
  {
    return dirname(__FILE__);
  }


  static public function whoCalledMe($deep = 1, $back = 0)
  {
    ob_start();
    debug_print_backtrace();
    $infos = ob_get_contents();
    ob_end_clean();
    
    $id = '#'.++$deep;
    $entry = str_replace(mb_strstr($infos, $id), "", $infos);
    
    if ( !$entry )
      $entry = $infos;
    
    $it_was_me = mb_strrchr(mb_strrchr($entry, '#'), '/');
    $it_was_me = explode(':',mb_substr($it_was_me, 1, -2));

    if ( !empty($it_was_me[0]) )
      return $it_was_me;
    else
    {
      if ( $back > 0 )
	return self::whoCalledMe(++$deep, --$back);
      else 
	return array();
    }
  }



  static public function makeCookie($name, $value, $lifetime)
  {
    return setcookie($name, $value, time() + $lifetime,
		     Config::read('cookie.path'),
		     Config::read('cookie.domain'),
		     (bool)Config::read('security.use_ssl'),
		     (bool)Config::read('cookie.http_only'));
  }



  static public function UrlPrefix()
  {
    return (URL_REWRITING) ? ROOT : 'index'.PHP.'?url=';
  }



  static public function isEmpty($val)
  {
    return in_array($val, array(null, '', array()));
  }
}




