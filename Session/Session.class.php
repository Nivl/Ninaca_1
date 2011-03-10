<?php

/*
**  Gère les sessions.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/19/2009, 06:13 PM
**  @last	Nivl <nivl@free.fr> 03/08/2010, 04:20 PM
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


class Session
{
  static private $Instance;

  static public function getInstance()
  {
    if ( self::$Instance === null )
      self::$Instance = new Session();

    return self::$Instance;
  }
  
  
  static public function read($path)
  {
    return Arrays::read($path, $_SESSION);
  }
  
  
  static public function write($path, $value)
  {
    return Arrays::write($path, $value, $_SESSION);
  }
  
  
  static public function delete($path)
  {
    return Arrays::delete($path, $_SESSION);
  }
  
  
  static public function exists($path)
  {
    return Arrays::exists($path, $_SESSION);
  }


  protected function __construct()
  {
    if ( function_exists('init_set') )
      $this->initSet();
    else
      $this->init();
    
    $this->createNew();
  }
  
  
  /*
  ** Régénère la session.
  */
  public function regen()
  {
    $this->destroy();
    $this->createNew();
  }
  
  
  /*
  ** Créer une nouvelle session.
  */
  private function createNew()
  {
    session_start();
    $this->valid();
  }
  


  /*
  ** Applique quelque changement aux sessions
  */
  private function init()
  {
    session_name(Config::read('session.name'));
    session_cache_expire(Config::read('session.cache.lifetime'));
    session_set_cookie_params(Config::read('session.cookie.lifetime'),
			      Config::read('cookie.path'),
			      Config::read('cookie.domain'),
			      Config::read('security.use_ssl'),
			      Config::read('cookie.http_only'));
  }
  


  /*
  ** Si on a accès à la fonction init_set, on applique quelques modifications.
  */
  private function initSet()
  {
    if ( Config::read('security.check.referer') &&
	 !empty($_SERVER['HTTP_HOST']) )
      $referer = $_SERVER['HTTP_HOST'];
    else
      $referer = "";

    init_set('session.use_only_cookies', Config::read('session.only_cookie'));
    init_set('session.cache_expire', Config::read('session.cache.lifetime'));
    init_set('session.cookie_lifetime', 
	     Config::read('session.cookie.lifetime'));
    init_set('session.use_trans_sid', 1);
    init_set('session.use_cookies', 1);
    init_set('session.use_only_cookies', 0);
    init_set('session.referer_check', $referer);
    init_set('session.name', Config::read('session.name'));
    init_set('session.cookie_secure', Config::read('security.use_ssl'));
    init_set('session.cookie_httponly', Config::read('cookie.http_only'));
    init_set('session.cookie_path', Config::read('cookie.path'));
    init_set('session.cookie_domain', Config::read('cookie.domain'));
  }
  
  
  /*
  **  Rend une session valide.
  */
  private function valid()
  {
    $u_agent = (string)$_SERVER['HTTP_USER_AGENT'];
  
    if ( $this->exists('config') )
    {
      if ( Config::read('security.check.uagent') &&
	   $this->read('config.uagent') <> $u_agent )
      {
	$this->destroy();
	$this->createNew();
      }
    }
    else
      $this->write('config.uagent', $u_agent);
  }
  

  /*
  **  Détruit la session.
  */
  private function destroy()
  {
    $_SESSION = array();
    session_destroy();
    setcookie(Config::read('session.name'), '', time() - 3600);
  }

  private function __clone(){}
}

