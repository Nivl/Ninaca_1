<?php

/*
**  Génère une application.
**
**  @author	Nivl <nivl@free.fr>
**  @started	11/14/2009, 06:50 PM
**  @last	Nivl <nivl@free.fr> 11/28/2009, 11:50 PM
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


class GenerateApp extends Task
{
  protected function configure()
  {
    $this->name = 'generate-app';
    $this->namespace = 'ninaca';
    $this->description = _('Generate an application.');

    $this->addArgument(new TaskArgument('name',_('Name of the application.')));
        
    $ext = 'Controller’s extention.';
    $enable = 'Don’t enable the Application?';
    $extend = 'Class to extend.';
    $indent_size = 'Indentation’s size.';
    $this->addOptions(array(
			new TaskOption('extension', $ext, '.class'.PHP),
			new TaskOption('indent-size', $indent_size, 2),
			new TaskOption('extend', $extend, 'Controller'),
			new TaskOption('not-enable', $enable,
				       false, 'e', TaskOption::BOOL),
		      ));
  }
  
  
  public function execute()
  {
    $name = $this->getArgumentsValue('name');
    $ext = $this->getOptionsValue('extension');
    $extend = $this->getOptionsValue('extend');
    $indent = str_repeat(' ', (int)$this->getOptionsValue('indent-size'));
    $class = $name.'Controller';
    $file = $name.'Controller'.$ext;
    $content = "<?php".PHP_EOL.PHP_EOL."class $class extends $extend".
      PHP_EOL.'{'.PHP_EOL.$indent.'public function main()'.PHP_EOL.
      $indent.'{'.PHP_EOL.$indent.'}'.PHP_EOL.'}';

    $this->globalChecks($file, $content, $name);
    
    if ( !$this->getOptionsValue('not-enable') )
    {
      $list = YamlFactory::factory()->load('config/apps.yaml');
      if ( !in_array($name, $list) )
      {
	$list[] = $name;
	YamlFactory::factory()->putInFile('config/apps.yaml', $list);
      }
    }
  }


  private function globalChecks($file, $content, $name)
  {
    if ( is_file('apps/controllers/'.$file) )
      exit("This application already exists.\n");

    if ( !file_put_contents("apps/controllers/$file", $content) )
      exit("The file apps/controllers/$file can't be created.\n");

    if ( !Ftp::makeDir('apps/views/'.$name) )
      exit("The file apps/views/$name can't be created.");
  }
}

