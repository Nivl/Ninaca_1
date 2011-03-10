<?php

/*
**  Gestion du cache avec APC.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	07/29/2009, 03:54 PM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 04:15 PM
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

class APCCache implements Cache
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
    return apc_delete($key);
  }
  
  
  
  /*
  ** Récupère une variable en cache.
  ** 
  ** @param string key [Nom du cache]
  ** @param mixed var [Variable à mettre ne cache]
  ** 
  ** @return mixed
  */
  public function get($key)
  {
    return apc_fetch($key);
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
    return (apc_fetch($key) !== false) ? true : false;
  }
  
  
  
  /*
  ** Ajoute une variable en cache
  ** 
  ** @param string key [Nom du cache]
  ** @param mixed var [Variable à mettre ne cache]
  ** @param int lifetime [Temps de vie de cache en seconde]
  ** @param bool overwrite [On écrase les données déjà présente en cache]
  ** 
  ** @return bool
  */
  public function store($key, $var, $lifetime = 0, $overwrite = true)
  {
    if ($overwrite)
      return apc_store($key, $var, $lifetime);
    else
      return apc_add($key, $var, $lifetime);
  }
  
  
  /*
  ** Supprime tous les caches
  ** 
  ** @return bool
  */
  public function clearAll()
  {
    return apc_clear_cache('user');
  }
  


  /*
  **  Supprime tous les fichiers d'un dossier cache.
  **
  ** @param string dir
  ** @param bool recursive
  ** @param string pre
  ** @param string suf
  **
  ** @return bool
  */
  public function clear($dir = '', $recursive = false, $pre = null,
			$suf = null)
  {
    $ret = true;
    $dir .= (substr($dir, -1) !== '/' && !empty($dir)) ? '/' : null;
    $cacheInfo = apc_cache_info('user');
    
    if ( empty($cacheInfo['cache_list']) )
      return true;
    foreach ( $cacheInfo['cache_list'] as $info )
    {
      $tmp = str_replace($dir, '', $info['info']);

      if ( $tmp !== $info['info'] || empty($dir) )
      {
	if ( strpos($tmp,'/') === false || $recursive)
	{
	  $key = strrpos($info['info'], '/');
	  $key = str_replace('/', '', substr($info['info'], $key));

	  if ( ($pre === null || substr($key, 0, strlen($pre)) === $pre) &&
	       ($suf === null || substr($key, -strlen($suf)) === $suf) )
	    $ret = apc_delete($info['info']) && $ret;
	}
      }
    }

    return $ret;
  }
}



