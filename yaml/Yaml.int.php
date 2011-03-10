<?php

/*
**  Interface pour les classes qui gère les fichiers yaml.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/29/2009, 10:12 PM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 05:13 PM
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

interface Yaml
{
  public function putInFile($file_name, $value);
  public function arrayToYaml(array $array);
  public function yamlToArray($content);
  public function load($file_name);
}




