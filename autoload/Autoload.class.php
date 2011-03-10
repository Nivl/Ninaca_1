<?php

/*
**  Classe gérant l'autoload
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/06/2009, 01:29 AM
**  @last	Nivl <nivl@free.fr> 03/17/2010, 03:02 PM
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

abstract class Autoload
{
  static private $libs		= array();
  static private $libsInfo	= array();
  static private $tasks		= array();
  
  
  static public function setAutoload()
  {
    if ( self::$libs === array() )
    {
      spl_autoload_register(array(__CLASS__, 'tempAutoload'));
      self::loadLibs();
      spl_autoload_unregister(array(__CLASS__, 'tempAutoload'));
      spl_autoload_register(array(__CLASS__, 'autoloadLibs'));

      spl_autoload_register(array('Doctrine', 'autoload'));
    }
  }
  
  
  
  static public function tempAutoload($class)
  {
    if ( is_file('libs/vendors/ninaca/'.$class.'.class'.PHP) )
      require 'libs/vendors/ninaca/'.$class.'.class'.PHP;
    else if ( strpos($class, 'Yaml') !== false )
    {
      if ( $class === 'Yaml' )
        require 'libs/vendors/ninaca/yaml/Yaml.int'.PHP;
      else
        require 'libs/vendors/ninaca/yaml/'.$class.'.class'.PHP;
    }
    else if ( $class === 'Spyc' )
      require 'libs/vendors/ninaca/vendors/spyc-0.4.2/Spyc.class'.PHP;
  }
  
  
  
  static public function autoloadLibs($class)
  {
    if ( isset(self::$libs[$class]) )
      include self::$libs[$class];
  }
  
  
  
  static private function loadLibs()
  {
    if ( is_file('config/libs'.PHP) )
      self::$libs = unserialize(include $root.'/config/libs'.PHP);
    else
    {
      self::$libsInfo	= self::getlibsInfo();
      self::$libs	= Ftp::getFilesFromYaml(self::$libsInfo);
    }
  }
  
  
  
  static private function getLibsInfo()
  {
    return YamlFactory::Factory()->load('config/autoload.yaml');
  }
}

