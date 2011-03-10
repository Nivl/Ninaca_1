<?php

/*
**  Gère les fichiers yaml en utilisant Spyc.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/29/2009, 03:29 AM
**  @last	Nivl <nivl@free.fr> 03/17/2010, 02:59 PM
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

class SpycYaml implements Yaml
{
  public $Spyc = null;
  
  public function __construct()
  {
    $this->Spyc = new Spyc();
  }


  public function putInFile($file_name, $value)
  {
    if ( is_array($value) )
      $value = $this->Syck->dump($value);

    if ( file_put_contents($file_name, $value, LOCK_EX) )
      return true;

    YamlErrors::fileNotWritable($file_name);
    return false;
  }


  public function arrayToYaml(array $array)
  {
    return $this->Syck->dump($array);
  }


  public function yamlToArray($content)
  {
    return $this->Spyc->load($content);
  }


  public function load($file_name)
  {
    if ( ($file = file_get_contents($file_name)) !== false )
      return $this->Spyc->load($file);

    YamlErrors::fileNotFound($file_name);
    return null;
  }
}


