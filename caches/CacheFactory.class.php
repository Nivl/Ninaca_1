<?php

/*
**  Cette classe gère le système de cache.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/20/08
**  @last	Nivl <nivl@free.fr> 11/22/2009, 12:14 AM
**  @link	http://nivl.free.fr
**  @copyright	Copyright (C) 2008 Laplanche Melvin
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

abstract class CacheFactory
{
  static private $Instance = null;
  
  static public function getInstance()
  {
    if ( self::$Instance === null )
    {
      $type = self::getType();
      $class_name = "{$type}Cache";
      self::$Instance = new $class_name();
    }

    return self::$Instance;
  }
  
  
  static public function factory()
  {
    return self::getInstance();
  }


  static private function getType()
  {
    if ( extension_loaded('xcache') )
      return 'XCache';
    else if ( extension_loaded('apc') )
      return 'APC';
    else if ( extension_loaded('eaccelerator') )
      return 'Eaccelerator';
    else if ( (PHP_OS === 'WINNT' && init_get('safe_mode')) )
      return 'SQL';
    else
    {
      if ( !is_dir('caches') )
	@mkdir('caches', 0755);

      return (!is_writable('caches')) ? 'SQL' : 'FTP';
    }
  }
  
  private function __construct(){}
  private function __clone(){}
}



