<?php

/*
**  Class qui s'occupe d'instancier la classe qui gèrera la fichiers yaml.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/28/2009, 02:35 AM
**  @last	Nivl <nivl@free.fr> 11/15/2009, 01:51 AM
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

abstract class YamlFactory
{
  static private $Instance = null;
  

  /*
  ** Retourne une instance d'une classe fille.
  **
  ** @return YamlFactory
  */
  static public function getInstance()
  {
    if ( self::$Instance === null )
    {
      $type = self::getType();
      $class_name = "{$type}Yaml";
      self::$Instance = new $class_name();
    }
    
    return self::$Instance;
  }
  
  

  /*
  ** Alias de getInstance.
  **
  ** @return YamlFactory
  */
  static public function factory()
  {
    return self::getInstance();
  }



  static private function getType()
  {
    if ( extension_loaded('syck') )
      return 'Syck';
    else
      return 'Spyc';
  }
  
  private function __construct(){}
  private function __clone(){}
}


