<?php

/*
**  Cette classe gère le système de cache en utilisant le FTP.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/20/08
**  @last	Nivl <nivl@free.fr> 09/09/2009, 04:16 PM
**  @link	http://nivl.free.fr
**  @copyright	Copyright (C) 2008 Laplanche Melvin
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

class FTPCache implements Cache
{
  private $time = 0;

  public function __construct()
  {
    $this->time = time();
  }
  

  /*
  ** Supprime une variable du cache.
  ** 
  ** @param string key [Nom du cache]
  ** @param bool clear_cache [Vidage du cache de PHP]
  ** 
  ** @return bool
  */
  public function delete($key, $clear_cache = true)
  {
    if ( substr($key, 0, 6) !== 'caches' )
      $key = 'caches'.$key;
    
    @unlink($key.'_ttl');
    $ret = unlink($key);
    
    if ( $clear_cache )
      clearstatcache();
    
    return $ret;
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
    $key = 'caches/'.$key.PHP;
    $this->checkTTL($key, true);
    $ret = @include $key;

    return ($ret === false) ? false : unserialize($ret);
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
    $this->checkTTL($key, true);
    
    return is_file('caches/'.$key.PHP);
  }
  
  
  /*
  ** Ajoute une variable en cache
  ** 
  ** @param string file [Nom du cache]
  ** @param mixed var [Variable à mettre ne cache]
  ** @param int ttl [Temps de vie de cache en seconde]
  ** @param bool overwrite [On écrase les données déjà présente en cache]
  ** 
  ** @return bool
  */
  public function store($file, $var, $ttl = 0, $overwrite = true)
  {
    $file = 'caches/'.$file.PHP;

    if ( !$overwrite && (is_file($file) && $this->checkTTL($file)) )
      return false;

    $dir = substr($file, 0, strrpos($file, '/'));
    
    if ( !is_dir($dir) )
    {
      if ( !@mkdir($dir, 0700, true) )
	return false;
    }
    
    if ( ($fp = @fopen($file, 'wb')) !== false )
    {
      $var = var_export(serialize($var), true);
      fwrite($fp, '<?php $var = '.$var.'; return $var ?>');
      fclose($fp);
      
      @unlink($file.'_ttl');
      return ($ttl) ? touch($file.'_ttl', $this->time + $ttl ) : true;
    }
    else
      return false;
  }


  private function checkTTL($key, $del = false)
  {
    if ( is_file($key.'_ttl') )
    {
      if ( filemtime($key.'_ttl') > $this->time )
	return true;
      else if ( $del )
      {
	unlink($key);
	unlink($key.'_ttl');
	clearstatcache();
      }
    }

    return false;
  }
  
  
  /*
  ** Sert pour supprimer tout les fichiers cache
  **
  ** @return bool
  */
  public function clearAll()
  {
    return $this->clear('caches', true);
  }


  /*
  ** Supprime tous les fichier d'un dossier cache
  **
  ** @param string dir
  ** @param bool recursive
  ** @param string pre
  ** @param string suf
  ** 
  ** @return bool
  */
  public function clear($dir = 'caches/', $recursive = false, $pre = null,
			$suf = null)
  {
    $dir = (substr($dir, 0, 6) !== 'caches') ? 'caches/'.$dir : $dir;
    $dir .= (substr($dir, -1) !== '/') ? '/' : null;
    $folder = opendir(str_replace('..', '', $dir));
    $pre_s = mb_strlen($pre);
    $suf_s = mb_strlen($suf) + strlen(PHP);
    $ret = true;
    
    while ( ($file = readdir($folder)) !== false )
    {
      if ( $file !== '.' && $file !== '..' )
      {
	if ( is_dir($dir.$file) && $recursive )
	  $ret = ( $this->clear($dir.$file, $recursive, $pre, $suf) &&
		   rmdir($dir.$file) &&
		   $ret );
	else if ( !is_dir($dir.$file) &&
		  ($pre === null || mb_substr($file, 0, $pre_s) === $pre) &&
		  ($suf === null || mb_substr($file, -$suf_s) === $suf.PHP) )
	  $ret = $this->delete($dir.$file, false) && $ret;
      }
    }
    
    closedir($folder);
    clearstatcache();
    return $ret;
  }
}


