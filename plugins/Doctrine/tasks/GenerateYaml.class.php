<?php

/*
**  Génère un fichier yaml à partir des modèles ou de la BDD.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	11/08/2009, 04:37 PM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 06:12 PM
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

class GenerateYaml extends DoctrineTaskBase
{
  protected function configure()
  {
    $this->name		= 'generate-yaml';
    $this->namespace	= 'doctrine';
    $this->description	= _('Generate a YAML schema from an existing'.
			    ' database or a set of models.');
    
    $output_name = 'Name of the YAML file';
    $conn_name   = 'Name of the doctrine’s connection.';
    $source = 'Generate the file from an existing database (db) or a set'.
      ' of models (models)?';
    
    $this->addOption(new TaskOption('source', $source, 'models'));
    $this->addArgument(new TaskArgument('output-name', $output_name,
					TaskArgument::OPTIONAL, 'schema.yml'));
    $this->addArgument(new TaskArgument('connection-name', $conn_name,
					TaskArgument::OPTIONAL, '{default}'));
  }
  
  
  public function exec()
  {
    $src = $this->getOptionsvalue('source');
    
    if ( !in_array($src, array('db', 'models')) )
      exit(_('The possible sources are only “models” or “db”.'));
    
    if ( $src === 'models' )
      $this->generateYamlFromModels();
    else
      $this->generateYamlFromDb();
  }
  
  
  private function generateYamlFromDb()
  {
    $con_name = $this->getArgumentsvalue('connection-name');
    
    if ( $con_name === '{default}' )
      $con_name = $this->Con->getName();
    
    $file_path = $this->getOptionsValue('yaml-schema-path');
    $file_path .= '/'.$this->getArgumentsValue('output-name');

    \Doctrine_Core::generateYamlFromDb(
      $file_path,
      array($con_name),
      $this->getImportOptions()
    );
  }
  
  
  
  private function generateYamlFromModels()
  {
    $file_path = $this->getOptionsValue('yaml-schema-path');
    $file_path .= '/'.$this->getArgumentsValue('output-name');
    
    \Doctrine_Core::generateYamlFromModels(
      $file_path,
      $this->getOptionsValue('models-path')
    );
  }
}