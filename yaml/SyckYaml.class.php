<?php

/*
**  Gère les fichiers yaml en utilisant Syck.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/28/2009, 11:32 PM
**  @last	Nivl <nivl@free.fr> 10/29/2009, 03:20 AM
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

class SyckYaml implements Yaml
{
  public function __construct() {}


  public function putInFile($file_name, $value)
  {
    if ( is_array($value) )
      $value = syck_dump($value);

    if ( file_put_contents($file_name, $value, LOCK_EX) )
      return true;

    YamlErrors::fileNotWritable($file_name);
    return false;
  }


  public function arrayToYaml(array $array)
  {
    return syck_dump($array);
  }


  public function yamlToArray($content)
  {
    return syck_load($content);
  }


  public function load($file_name)
  {
    if ( ($file = file_get_contents($file_name)) !== false )
      return syck_load($file);

    YamlErrors::fileNotFound($file_name);
    return null;
  }
}


