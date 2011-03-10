<?php

/*
**  Gestion du cache avec XCache.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/31/2009, 02:36 AM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 04:17 PM
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

class XCacheCache implements Cache
{
  public function __construct(){}
  
  
  /*
  ** Supprime une variable du cache.
  ** 
  ** @param string key [Nom du cache]
  ** 
  ** @return bool
  */
  public function delete($key)
  {
    return xcache_unset($key);
  }
  
  
  
  /*
  ** Récupère une variable en cache.
  ** 
  ** @param string key [Nom du cache]
  ** @param mixed var [Variable à mettre ne cache]
  ** 
  **@return mixed
  */
  public function get($key)
  {
    return xcache_get($key);
  }
  
  
  
  /*
  ** Vérifie l'existance d'une variable en cache.
  ** 
  ** @param string key [Nom du cache]
  **  
  ** @return bool
  */
  public function exists($key)
  {
    return xcache_isset($key);
  }
  
  
  
  /*
  ** Ajoute une variable en cache
  ** 
  ** @param string key [Nom du cache]
  ** @param mixed var [Variable à mettre ne cache]
  ** @param int lifetime [Temps de vie de cache en seconde]
  ** @param bool overwrite [On écrase les données déjà présente en cache]
  ** 
  **@return bool
  */
  public function store($key, $var, $lifetime = 0, $overwrite = true)
  {
    if ( $overwrite || !xcache_isset($key) )
      return xcache_set($key, $var, $lifetime);
  }
  
  
  /*
  ** Supprime tous les caches
  ** 
  ** @return bool
  */
  public function clear()
  {
    return false;
  }
}



