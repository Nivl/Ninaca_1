<?php

/*
**  Gère les arrays.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/17/2009, 12:08 AM
**  @last	Nivl <nivl@free.fr> 03/28/2010, 03:54 PM
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

class Arrays
{
  /*
  ** Vérifie si une clef existe ou non dans un array.
  ** 
  ** @param array|string path [Chemin et nom de la variable]
  ** @param array array [Array à lire]
  ** 
  ** Ex :
  ** $path = 'Config.user.name';
  ** $path = array('Config'=>array('user'=>array('name')));
  ** 
  ** @return bool
  */
  static public function read($path, array &$array)
  {
    $var = &$array;
    
    if ( !is_array($path) )
      $path = explode('.', $path);
    
    $last = end($path);
    reset($path);
    
    foreach ( $path as $name )
    {
      if ( $name === $last )
	return ( isset($var[$name]) ) ? $var[$name] : false; 
      else if ( isset($var[$name]) && is_array($var[$name]) )
	$var = &$var[$name];
      else
	return false;
    }
    
    return $var;
  }
  
  
  
  /*
  ** Lit une variable dans un array.
  ** 
  ** @param array|string path [Chemin et nom de la variable]
  ** @param array array [Array à lire]
  **
  ** Ex :
  ** $path = 'Config.user.name';
  ** $path = array('Config'=>array('user'=>array('name')));
  ** 
  ** @return mixed
  */
  static public function exists($path, array &$array)
  {
    $var = &$array;
    
    if ( !is_array($path) )
      $path = explode('.', $path);
    
    $last = end($path);
    reset($path);
    
    foreach ( $path as $name )
    {
      if ( $name === $last && isset($var[$name]) )
	return true;
      else if ( isset($var[$name]) && is_array($var[$name]) )
	$var = &$var[$name];
      else
	return false;
    }
    
    return false;
  }
  
  
  
  /*
  **  Créer/modifie une entrée dans variable.
  ** 
  ** @param array|string path [Chemin et nom de la variable]
  ** @param mixed value [valeur à affecter]
  ** @param array array [Array à lire]
  ** 
  ** Ex :
  ** $path = 'Config.user.name';
  ** $path = array('Config'=>array('user'=>array('name')));
  **/
  static public function write($path, $value, array &$array)
  {
    $var = &$array;
    
    if ( !is_array($path) )
      $path = explode('.', $path);
    
    $last = end($path);
    reset($path);
    
    foreach ( $path as $name )
    {
      if ( $name === $last )
	$var[$name] = $value;
      else if ( !isset($var[$name]) )
	$var[$name] = array();
      
      $var = &$var[$name];
    }
  }
  
  
  /*
  ** Lit une variable dans un array.
  ** 
  ** @param array|string path [Chemin et nom de la variable]
  ** @param array array [Array à lire]
  **
  ** Ex :
  ** $path = 'Config.user.name';
  ** $path = array('Config'=>array('user'=>array('name')));
  ** 
  ** @return mixed
  */
  static public function delete($path, array &$array)
  {
    $var = &$array;
    
    if ( !is_array($path) )
      $path = explode('.', $path);

    $last = end($path);
    reset($path);
    
    foreach ( $path as $name )
    {
      if ( $name === $last )
	unset($var[$name]);
      else if ( isset($var[$name]) && is_array($var[$name]) )
	$var = &$var[$name];
      else
	break;
    }
  }


  
  /*
  ** Vérifie que l'array 2 contient les mêmes clefs que l'array 1.
  **
  ** @param array array1
  ** @param array array2
  ** @param bool recursive
  **
  ** @return bool
  */
  static public function compareArrayKeys(array $ar1, array $ar2,
					  $recursive = true)
  {
    foreach ( $ar1 as $key => $value )
    {
      if ( !isset($ar2[$key]) )
	return false;
      
      if ( $recursive && is_array($value) )
      {
	if ( !self::compareArrayKeys($ar1[$key], $ar2[$key]) )
	  return false;
      }
    }
    return true;
  }


  /*
  ** Complète les clefs de l'array par la droite, en fonction de la clef la
  ** plus grande.
  **
  ** @param array array1
  ** @param array array2
  ** @param bool recursive
  **
  ** @return array
  */
  static public function padKeys(array $array, $str = ' ')
  {
    $newArray = array();
    $max = self::biggestKeysSize($array);

    foreach ( $array as $key => $value )
      $newArray[$key.str_repeat($str, ($max - mb_strlen($key)))] = $value;

    return $newArray;
  }



  /*
  ** Retourne la taille de la clef la plus grande.
  **
  ** @param array array
  **
  ** @return int
  */
  static public function biggestKeysSize(array $array)
  {
    $max = 0;
    
    foreach ( $array as $key => $value )
      $max = (mb_strlen($key) > $max) ? mb_strlen($key) : $max;

    return $max;
  }


  static public function printInCLI(array $array)
  {
    $output = null;
    $array = Arrays::padKeys($array);

    foreach ( $array as $name => $descr )
      $output .= PHP_EOL.' '.sprintf(_('%s: %s'), $name, $descr);
    
    reset($array);
    $nb_nl_space = mb_strlen(key($array)) + 3;
    $nb_nl_space += mb_substr_count(' '._('%s: %s'), ' ');

    return String::wrap80($output, str_repeat(' ', $nb_nl_space));
  }

  
  static public function inIArray($needle, array $haystack)
  {
    return in_array(String::lower($needle),
		    array_map(array('String', 'lower'), $haystack));
  }


  /*
  ** Retourne une clef à partir d'une valeur.
  **
  ** @param mixed val
  ** @param array array
  **
  ** @return mixed
  */
  static public function getKey($val, array $array)
  {
    foreach ( $array as $key => $value )
    {
      if ( $value == $val )
	return $key;
    }
    
    return null;
  }


  


  /*
  ** Supprime des clefs à partir d'une valeur.
  **
  ** @param mixed val
  ** @param array array
  **
  ** @return mixed
  */
  static public function rmKeys($val, array $array)
  {
    while ( $key = Arrays::getKey($val, $array) )
      unset($this->classes[$key]);
  }


  /*
  ** Supprime des clefs à partir de plusieurs valeurs.
  **
  ** @param array val
  ** @param array array
  **
  ** @return mixed
  */
  static public function rmKeysArray(array $vals, array $array)
  {
    foreach ( $vals as $val )
    {
      while ( $key = Arrays::getKey($val, $array) )
	unset($this->classes[$key]);
    }
  }
}




