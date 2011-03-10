<?php

/*
**  Gère les sessions utilisateurs.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/20/2009, 12:20 AM
**  @last	Nivl <nivl@free.fr> 03/21/2010, 11:55 PM
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

class UserSession
{
  static private $Instance = null;

  public $Auth = null;

  protected
    $Cache = null,
    $autolog = false,
    $uid = 0,
    $anon_id = 0,
    $table = null,
    $name = null,
    $groups = array(),
    $Session = null;
  
  
  static public function getInstance()
  {
    if ( self::$Instance === null )
      self::$Instance = new UserSession();

    return self::$Instance;
  }


  public function getGroups()
  {
    return $this->groups;
  }


  public function getName()
  {
    return $this->name;
  }
  
  
  public function getId()
  {
    return $this->uid;
  }
  
  
  private function __construct()
  {
    $this->Cache = CacheFactory::factory();
    $this->Session = Session::getInstance();
    $this->table = Config::read('modules.Session.User.table');
    $this->anon_id = Config::read('modules.Session.User.anonymous_id');
    $this->autolog = (bool)Config::read('modules.Session.User.Autologin.'.
					'enabled');
    if ( Config::read('modules.Session.User.Auth.enabled') === true )
      $this->Auth = new Auths();
    
    $this->loadUser();
  }


  private function loadUser($get_id = true)
  {
    if ( $get_id )
    {
      $this->uid = 0;
      
      if ( $this->Session->exists('user.id') )
	$this->uid = $this->Session->read('user.id');
      else if ( $this->autolog )
	$this->uid = $this->autologin();

      if ( $this->uid === 0 )
	$this->loadAnonymousUser();
    }

    $this->loadUserCaches();
    $this->storeUserInSession();
  }


  public function isLogged()
  {
    return $this->uid <> $this->anon_id;
  }
  
  
  private function loadAnonymousUser()
  {
    $this->uid = $this->anon_id;
  }

  
  /*
  ** Met les infos principales de l'utilisateur dans la session.
  */
  private function storeUserInSession()
  {
    $this->Session->write('user.id', $this->uid);
  }


  /*
  ** Suite de la methode loadUser
  */
  private function loadUserCaches()
  {
    $this->loadUserGroups();
    $this->loadUserName();
    
    if ( $this->Auth <> null )
      $this->Auth->loadUserAuths($this->uid, $this->groups);
  }



  /*
  ** Charge le nom de l'utilisateur.
  */
  private function loadUserName()
  {
    /*if ( $this->Cache->exists('users/name/'.$this->uid) )
      $this->name = $this->Cache->get('users/name/'.$this->uid);
      else*/
      $this->name = $this->getUserName();
  }



  /*
  ** Retourne le nom de l'utilisateur à partir de la BDD, et met en cache
  ** le résultat si besoin.
  **
  ** @return string
  */
  private function getUserName()
  {
    $f_name = Config::read('modules.Session.User.fields.name');

    $name = Doctrine_Query::create()
      ->select("u.$f_name AS name")
      ->from('User u')
      ->where('id = ?', $this->uid)
      ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

    //$this->Cache->store("users/name/$this->uid", $name, 0);
    return $name;
  }



  /*
  **  Charge les groups de l'utilisateur.
  */
  private function loadUserGroups()
  {
    /*if ( $this->Cache->exists('users/groups/'.$this->uid) )
      $this->groups = $this->Cache->get('users/groups/'.$this->uid);
      else*/
      $this->groups = $this->getUserGroups();
  }
  
  
  
  /*
  **  Retourne les groupes de l'utilisateur à partir de la BDD, et met en cache
  ** le résultat si besoin.
  */
  private function getUserGroups()
  {
    $groups = array();
    $lists = Doctrine_Query::create()
      ->select('group_id')
      ->from('UserGroup')
      ->where('user_id = ?', $this->uid)
      ->fetchArray();
    
    foreach ( $lists as $list )
      $groups[] = $list['group_id'];
    
    //$this->Cache->store("users/groups/$this->uid", $groups, 0);
    return $groups ? $groups : array(3); // 3 = visiteur
  }
  


  /*
  ** Retourne la requête qui contient des informations sur l'utilisateur à
  ** connecter.
  **
  ** @param array arg de la requête (nom et password)
  **
  ** @return string
  */
  private function getLoginInfo($arg)
  {
    $name = Config::read('modules.Session.User.fields.login');
    $psw = Config::read('modules.Session.User.fields.password');
    return Doctrine_Query::create()
      ->select('id')
      ->from('User')
      ->where("$name = ?", $arg['name'])
      ->andWhere("$psw = ?", $arg['password'])
      ->andWhereNotIn('id', array(0, $this->anon_id))
      ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }




  private function autologin()
  {
    $uagent = null;
    $pref = Config::read('cookie.prefix');
    $c_id = $pref.Config::read('modules.Session.User.Autologin.cookie.uid');
    $c_key = $pref.Config::read('modules.Session.User.Autologin.cookie.key');
    
    if ( isset($_COOKIE[$pref.'rand'], $_COOKIE[$c_id], $_COOKIE[$c_key]) &&
	 !in_array($_COOKIE[$c_id], array(0, $this->anon_id)) &&
	 ctype_digit($_COOKIE[$c_id]) )
    {
      if ( $data = $this->getAutologInfo($_COOKIE[$c_id]) )
      {
	if ( Config::read('security.check.uagent') )
	  $uagent = $this->Session->read('config.uagent');
	
	$info = array($data['psw'], String::lower($data['name']), $uagent);
	$hash = Security::hash('sha1', $info, (int)$_COOKIE[$pref.'rand']);
	
	if ( $hash[1] === $_COOKIE[$c_key] )
	  return $_COOKIE[$c_id];
      }
    }
    return 0;
  }
  
  
  /*
  ** Retourne la requête qui contient des informations sur l'utilisateur à
  ** connecter automatiquement.
  **
  ** @param int id
  **
  ** @return array
  */
  private function getAutologInfo($id)
  {
    $name = Config::read('modules.Session.User.fields.login');
    $psw = Config::read('modules.Session.User.fields.password');
    
    return Doctrine_Query::create()
      ->select("u.$name AS name, u.$psw AS psw")
      ->from('User u')
      ->where('u.id = ?', $id)
      ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
  }


  /*
  ** Déconnecte l'utilisateur.
  */
  public function logout()
  {
    $this->unsetAutologin();
    $this->Session->regen();
    $this->loadAnonymousUser();
    //$this->loadUser(false);
    $this->loadUserCaches();
    $this->storeUserInSession();
  }


  /*
  ** Connecte l'utilisateur.
  **
  ** @param Forms Form
  **
  ** @return bool
  */
  public function login($Form, $field_autolog = 'autologin')
  {
    $salt1 = Config::read('security.salt.login.1');
    $salt2 = Config::read('security.salt.login.2');
    $field_psw = Config::read('modules.Session.User.fields.password');
    $field_name = Config::read('modules.Session.User.fields.login');
    $pass = $Form[$field_psw]->getValue(false);
    $uname = $Form[$field_name]->getValue(false);
    $arg = array( 'name' => $uname,
		  'password' => sha1($salt1.$pass.$salt2));
    if ( $data = $this->getLoginInfo($arg) )
    {
      $this->Session->regen();
      $this->uid = $data['id'];
      $this->loadUser(false);
      if ( $this->autolog && isset($Form[$field_autolog]) )
      {
	if ( $Form[$field_autolog]->getValue(false) === true )
	  $this->makeAutologin($arg['password'], $arg['name']);
      }
      return true;
    }
    return false;
  }


  
  /*
  ** Détruit les cookies qui servent à la connexion automatique.
  */
  public function unsetAutologin()
  {
    if ( $this->autolog )
    {
      $pref = Config::read('cookie.prefix');
      $c_id = $pref.Config::read('modules.Session.User.Autologin.cookie.uid');
      $c_key = $pref.Config::read('modules.Session.User.Autologin.cookie.key');
    
      Misc::makeCookie($pref.'rand', null, -3600);
      Misc::makeCookie($c_key, null, -3600);
      Misc::makeCookie($c_id, null, -3600);
    } 
  }



  /*
  ** Créer les cookies qui servent à la connexion automatique.
  **
  ** @param string password [Mot de passe hashé]
  */
  public function makeAutologin($password, $uname)
  {
    if ( $this->isLogged() && $this->autolog )
    {
      $pref = Config::read('cookie.prefix');
      $c_id = $pref.Config::read('modules.Session.User.Autologin.cookie.uid');
      $c_key = $pref.Config::read('modules.Session.User.Autologin.cookie.key');
      $lifetime = Config::read('modules.Session.User.Autologin.lifetime');
      
      if ( Config::read('security.check.uagent') )
	$uagent = $this->Session->read('config.uagent');
      else
	$uagent = null;

      $info = array($password, String::lower($uname), $uagent);
      $hash = Security::hash('sha1', $info);
      Misc::makeCookie($pref.'rand', $hash[0], $lifetime);
      Misc::makeCookie($c_key, $hash[1], $lifetime);
      Misc::makeCookie($c_id, $this->uid, $lifetime);
    }
  }
}

