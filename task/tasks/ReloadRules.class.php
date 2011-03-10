<?php

/*
**  Regénère le cache des règles.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/22/2009, 03:16 PM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 05:59 PM
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


class ReloadRules extends Task
{
  protected function configure()
  {
    $this->name = 'reload-rules';
    $this->namespace = 'ninaca';
    $this->description = _('Reload rules’ cache.');
  }
  
  
  public function execute()
  {
    $this->rules = array();
    $Sql = Doctrine_Query::create()
      ->select('id, name')
      ->from('Rule');
    
    while ( $data = $Sql->fetchArray() )
      $this->rules[$data['id']] = $data['name'];
    
    CacheFactory::factory()->store('system/rulesList', $this->rules, 0);
  }
}

