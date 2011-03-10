<?php

/*
**  Gère les controlleurs.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/11/2009, 02:50 PM
**  @last	Nivl <nivl@free.fr> 04/03/2010, 07:47 PM
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


abstract class Controller
{
  protected 
    $Url = null,
    $View = null,
    $definedViews    = array(),
    $disabledViews   = array(),
    $disabledActions = array(),
    $abstractClasses = array(),
    $Session = null,
    $User = null,
    $defaultAction = null,
    $defaultActionForUnknown = false,
    $reloadForDefaultAction = false;
  
  public function __construct()
  {
    $this->Url = Url::getInstance();
    $this->checkAction();

    if ( ($view = $this->getView()) !== null )
      $this->View = new View($view);

    $this->checkModule();

    if ( method_exists($this, 'beforeAction') )
      $this->beforeAction();

    $this->{$this->Url['action']}();
  }


  static public function error404($title = null, $msg = null)
  {
    header("HTTP/1.1 404 Not found");

    $title = $title ? $title : _('Not found');
    $msg = $msg ? $msg : _('The requested URL was not found on this server.');
    
    require 'globals/views/layouts/404'.PHP;
    exit;
  }


  static public function messageBox($msg, $auto = 0,$url = ROOT)
  {
    if ( $url !== ROOT )
      $url = Misc::UrlPrefix().$url;
    require 'globals/views/layouts/messageBox'.PHP;
    exit;
    }


  static public function redirect($url = ROOT)
  {
    if ( $url !== ROOT )
      header("Location: ".Misc::UrlPrefix().$url);
    else
      header("Location: ".ROOT);
    exit;
  }


  public function render()
  {
    if (method_exists($this, 'beforeRender'))
      $this->beforeRender();
    if ($this->View instanceof View)
      $this->View->render();
  }


  protected function checkAction()
  {
    if (($action = $this->Url['action']) === false)
      $change = ($action = $this->defaultAction) !== null;
    $tmp = array();
    foreach ($this->abstractClasses as $class)
      $tmp = array_merge($tmp, get_class_methods($class));
    $tmp = array_diff(get_class_methods($this), get_class_methods(__CLASS__),
		      $tmp,			$this->disabledActions);
    foreach ($tmp as $method)
      $methods[String::lower($method)] = $method;
    if (isset($methods[String::lower($action)]))
      $this->Url->setVar('action', $methods[String::lower($action)]);
    else
    {
      if ($this->defaultActionForUnknown && !empty($this->defaultAction))
	$change = ($this->Url['action'] = $this->defaultAction);
      else
	self::error404();
    }
    if (!empty($change) && $this->reloadForDefaultAction)
      $this->redirect($this->Url['controller'].'/'.$this->Url['action']);
  }
  
  
  
  protected function checkModule()
  {
    if (Config::exists('modules.Session.User.enabled'))
    {
      if (Config::read('modules.Session.User.enabled') === true)
	$this->User = UserSession::getInstance();
    }
    else
    {
      if (Config::exists('modules.Session.enabled'))
      {
	if (Config::read('modules.Session.User.enabled') === true)
	  $this->Session = Session::getInstance();
      }
    }
  }


  protected function getView()
  {
    $action = $this->Url->getVar('action');
    $view = null;
    if (!in_array($action, $this->disabledViews))
    {
      if (isset($this->definedViews[$action]))
      {
	$tmp = $this->definedViews[$action];
	if (is_array($tmp) && count($tmp) === 2 )
	{
	  if ($tmp[0] === 'global')
	    return 'globals/views/'.$tmp[1].PHP;
	  return 'apps/views/'.$tmp[0].'/'.$tmp[1].PHP;
	}
	else
	  $action = is_array($tmp) ? $tmp[0] : $tmp;
      }
      $view = String::substr(get_class($this), 0,
			     -String::length('Controller'));
      return 'apps/views/'.$view.'/'.$action.PHP;
    }
  }
  
  
  protected function changeView($view)
  {
    if (is_array($view))
    {
      if (count($view) === 2)
      {
	if ($view[0] === 'global')
	  return 'globals/views/'.$view[1].PHP;
	return 'apps/views/'.$view[0].'/'.$view[1].PHP;
      }
      else
	$view = $view[0];
    }
    $cont = String::substr(get_class($this), 0, -String::length('Controller'));
    return 'apps/views/'.$cont.'/'.$view.PHP;
  }
}

