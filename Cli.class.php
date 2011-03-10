<?php

/*
**  Classe gérant la partie CLI.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/01/2009, 01:29 AM
**  @last	Nivl <nivl@free.fr> 05/05/2010, 03:16 PM
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

class Cli
{
  protected
    $tasksDirs	= array(),
    $tasks	= array();

  public function __construct()
  {
    $this->tasksDirs = $this->getTasksDirs();
    $this->tasks     = $this->getTasks();
  }


  public function getTasks()
  {
    $tasks = array();
    $list = Ftp::getFilesFromYaml($this->tasksDirs);

    foreach ( $list as $name => $path )
    {
      $Tmp = new $name();

      if ( !$Tmp->hasName() )
	$Tmp->setName($name);

      if ( !$Tmp->hasDescription() )
	$Tmp->setDescription(_('No descriptions.'));

      $new_name = ($Tmp->hasNamespace()) ? $Tmp->getNamespace().':' : null;
      $new_name .= $Tmp->getName();
      
      $tasks[$new_name] = $Tmp;
    }
    return $tasks;
  }


  public function run()
  {
    if ( $_SERVER['argc'] < 2 )
      TaskErrors::NoTasksFound();

    $task = &$_SERVER['argv'][1];

    if ( $task === 'help' )
      $this->displayHelp();
    else
    {
      if ( !isset($this->tasks[$task]) )
	TaskErrors::taskNotExists($task);
      for ( $i=2; $i < $_SERVER['argc']; ++$i )
      {
	$size = strlen($_SERVER['argv'][$i]);

	if ( mb_substr($_SERVER['argv'][$i],0, 2) === '--' && $size > 2 )
	  $this->catchOption(mb_substr($_SERVER['argv'][$i],2), $task);
	else if ( $_SERVER['argv'][$i][0] === '-' && $size > 1 )
	  $this->catchShortcut(mb_substr($_SERVER['argv'][$i],1), $task);
	else
	  $this->catchArg($_SERVER['argv'][$i], $task);
      }
      $this->process($this->tasks[$task]);
    }
  }



  /*
  ** Fait les vérifications finales, et lance la tâche.
  **
  ** @param Task task
  */
  protected function process($Task)
  {
    if ( $Task->hasRequiredArguments() )
      TaskErrors::argumentsMissing($Task->getRequiredArguments());

    $Task->execute();
  }



  /*
  ** Vérifie l'existence de l'option, et la met à jour.
  **
  ** @param string name
  ** @param string task [nom de la tâche]
  */
  private function catchOption($name, $task)
  {
    $info = explode('=', $name);

    if ( $this->tasks[$task]->hasOption($info[0]) )
    {
      if ( !$this->tasks[$task]->getOption($info[0])->isBool() &&
	   !isset($info[1]) )
	TaskErrors::optionWithoutValue($info[0]);

      $val = (isset($info[1])) ? $info[1] : true;
      $this->tasks[$task]->getOption($info[0])->setValue($val);
    }
    else
      TaskErrors::optionNotExists($task, $info[0]);
  }



  /*
  ** Vérifie l'existence du raccourci, et met à jour l'option correspondante.
  **
  ** @param string name
  ** @param string task [nom de la tâche]
  */
  private function catchShortcut($name, $task)
  {
    $size = strlen($name);
    
    for ( $i=0; $i<$size; ++$i )
    {
      if ( $this->tasks[$task]->shortcutExists($name[$i]) )
	$this->tasks[$task]->getOptionFromShortcut($name[$i])->setValue(true);
      else
	TaskErrors::shortcutNotExists($task, $name[$i]);
    }
  }
  
  
  
  /*
  ** Ajoute un argument.
  **
  ** @param string value
  ** @param string task [nom de la tâche]
  */
  private function catchArg($value, $task)
  {
    $this->tasks[$task]->setArgumentsValue($value);
  }
  
  
  
  /*
  ** Retourne la liste des dossiers qui contiennent des tâches.
  **
  ** @return array
  */
  private function getTasksDirs()
  {
    return YamlFactory::Factory()->load('config/autoload_tasks.yaml');
  }
  


  /*
  ** Affiche l'aide.
  */
  private function displayHelp()
  {
    if ( isset($_SERVER['argv'][2], $this->tasks[$_SERVER['argv'][2]]) )
      $this->tasks[$_SERVER['argv'][2]]->displayHelp();
    else
    {
      $tasks = array();
      foreach ( $this->tasks as $name => $Task )
	$tasks[$name] = $Task->getDescription();
      echo Arrays::PrintInCLI($tasks).PHP_EOL;
    }
  }
}

