<?php

/*
**  Fonctions qui gère les chaines de caractères.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/29/2009, 04:07 PM
**  @last	Nivl <nivl@free.fr> 03/28/2010, 03:46 PM
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

class String
{
  static public function clean($val)
  {
    return trim($val, " \t\n\r\0\x7f..\xff\x0..\x1f");
  }
  
  
  static public function length($str)
  {
    return mb_strlen($str);
  }
  
  
  static public function lcFirst($str)
  {
    if ( !empty($str) )
      $str[0] = mb_strtolower($str[0]);

    return $str;
  }
  
  
  static public function lower($str)
  {
    return mb_strtolower($str);
  }


  static public function isLower($str)
  {
    return $str == mb_strtoupper($str);
  }
  
  
  static public function ucFirst($str)
  {
    if ( !empty($str) )
      $str[0] = mb_strtoupper($str[0]);
    
    return $str;
  }
  
  
  static public function upper($str)
  {
    return mb_strtoupper($str);
  }
  
  
  static public function isUpper($str)
  {
    return $str === mb_strtoupper($str);
  }


  static public function wrap80($string, $nl = '')
  {
    $strings = explode(PHP_EOL, $string);
    foreach ( $strings as $key => $str )
    {
      $str = wordwrap($str, 80, PHP_EOL);
      $strings[$key] = str_replace(PHP_EOL, PHP_EOL.$nl, $str);
    }
    return implode(PHP_EOL, $strings);
  }


  static function countCharOccurrence($char, $string)
  {
    if ( Misc::isEmpty($char) || Misc::isEmpty($string) )
      return false;
    
    $len = self::lenght($string);
    $occ = 0;

    for ( $i = 0; $i<$len; ++$i)
    {
      if ( $string[$i] == $char )
	++$occ;
    }

    return $occ;
  }


  static function substr($string, $start, $length = null)
  {
    if ( $length === null )
      $length = String::length($string) - $start;

      return mb_substr($string, $start, $length);
  }


  static function strstr($haystack, $needle, $part = false)
  {
    return mb_strstr($haystack, $needle, $part);
  }


  static function strpos($haystack, $needle, $offset = 0)
  {
    return mb_strpos($haystack, $needle, $offset);
  }
}




