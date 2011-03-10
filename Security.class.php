<?php

/*
**  Gère les fonction de sécurité.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/28/2009, 10:59 PM
**  @last	Nivl <nivl@free.fr> 02/23/2010, 07:10 PM
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

class Security
{
  static public function hash($algo = 'sha1', array $args, $start = 0)
  {
    $salts = Config::read('security.salt.list');
    $nb_salt = count($salts);
    $start = ($start > $nb_salt) ? 0 : $start;
    $rand = ($start < 1) ? mt_rand(1, $nb_salt) : $start;
    $output = $salts[$rand];
    $i = $rand + 1;

    foreach ( $args as $arg )
    {
      if ( $i > $nb_salt )
	$i = 1;
      
      $output .= $arg.$salts[$i++];
    }
    
    return array($rand, hash($algo, $output));
  }
  
  
  static public function hashPassword($psw, $algo = 'sha1')
  {
    $salt1 = Config::read('security.salt.login.1');
    $salt2 = Config::read('security.salt.login.2');
    return $algo($salt1.$psw.$salt2);
  }
  
  
  static public function noHTML($var, $type = ENT_QUOTES)
  {
    if ( is_array($var) )
    {
      foreach ( $var as $key => $val )
	$var[$key] = self::noHTML($val, $type);
      
      return $var;
    }
    else
      return htmlspecialchars($var, $type, 'UTF-8');
  }
}



