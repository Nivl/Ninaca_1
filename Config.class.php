<?php

/*
**  Classe qui gère la configuration.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/13/09
**  @last	Nivl <nivl@free.fr> 11/22/2009, 12:00 AM
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

class Config
{
  static private $config = array();

  static public function read($path)
  {
    return Arrays::read($path, self::$config);
  }


  static public function exists($path)
  {
    return Arrays::exists($path, self::$config);
  }


  static public function write($path, $value)
  {
    Arrays::write($path, $value, self::$config);
  }


  static public function delete($path)
  {
    Arrays::delete($path, self::$config);
  }


  static public function load()
  {
    $Yml = YamlFactory::factory();
    self::$config = $Yml->load('config/config.yaml');

    if ( !empty(self::$config['import-file']) )
    {
      foreach ( self::$config['import-file'] as $name => $link )
      {
	if ( is_file($link) )
	  self::$config[$name] = $Yml->load($link);
	else
	  exit('config/config.yaml: The file '.$link.' doesn\'t exists.');
      }
      unset(self::$config['import-file']);
    }
  }  
  
  private function __construct(){}
  private function __clone(){}
}




