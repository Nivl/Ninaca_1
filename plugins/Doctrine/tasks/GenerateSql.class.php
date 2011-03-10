<?php

/*
**  Génère les requêtes SQL à partir des modèles.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	11/10/2009, 12:18 AM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 06:08 PM
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

class GenerateSql extends DoctrineTaskBase
{
  protected function configure()
  {
    $this->name		= 'generate-sql';
    $this->namespace	= 'doctrine';
    $this->description	= _('Generate the SQL statements from a set'.
			    ' of models.');
  }
  
  
  public function exec()
  {
    echo Doctrine_Core::createTablesFromModels(
      $this->getOptionsValue('models-path')
    );
  }
}