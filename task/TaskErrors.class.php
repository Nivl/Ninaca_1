<?php

/*
**  Classe qui gère les erreurs rencontrées par les tâches.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	10/22/2009, 06:49 PM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 05:50 PM
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

abstract class TaskErrors extends Errors
{
  static public function noTasksFound()
  {
    $msg = _('No tasks found.');
    parent::display($msg);
  }


  static public function taskNotExists($task)
  {
    $msg = _('%s is not an existing task.');
    $msg = sprintf($msg, $task);
    parent::display($msg);
  }


  static public function duplicatedOption($name)
  {
    $msg = _('There are two or more options named %s.');
    $msg = sprintf($msg, $name);
    parent::display($msg);
  }


  static public function duplicatedShortcut($name)
  {
    $msg = _('The shortcut %s is used by two or more options.');
    $msg = sprintf($msg, $name);
    parent::display($msg);
  }


  static public function badOptionType()
  {
    $msg = _('Options must be instances of TaskOption.');
    parent::display($msg);
  }


  static public function badArgumentType()
  {
    $msg = _('Arguments must be instances of TaskArgument.');
    parent::display($msg);
  }


  static public function optionNotExists($task, $option)
  {
    $msg = _('%s has no options named %s.');
    $msg = sprintf($msg, $task, $option);
    parent::display($msg);
  }


  static public function argumentsMissing($arguments)
  {
    $msg = _('These following arguments are missing: ');
    
    foreach ( $arguments as $arg )
      $msg .= PHP_EOL." $arg";
    
    parent::display($msg);
  }


  static public function argumentNotExists($task, $argument)
  {
    $msg = _('%s has no arguments named %s.');
    $msg = sprintf($msg, $task, $argument);
    parent::display($msg);
  }

  
  static public function shortcutNotExists($task, $shortcut)
  {
    $msg = _('%s has no options with the shortcut %s.');
    $msg = sprintf($msg, $task, $shortcut);
    parent::display($msg);
  }

  
  static public function optionWithoutValue($option)
  {
    $msg = _('The option %s must have a value.');
    $msg = sprintf($msg, $option);
    parent::display($msg);
  }


  static public function tooManyArguments($nb_arg)
  {
    $msg = _('You gave too many arguments, this task only takes %d.');
    $msg = sprintf($msg, $nb_arg);
    parent::display($msg);
  }
}


