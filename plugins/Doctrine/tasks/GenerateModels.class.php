<?php

/*
**  Génère les modèles à partir de la BDD ou d'un fichier yaml.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/30/2009, 11:01 PM
**  @last	Nivl <nivl@free.fr> 11/14/2009, 07:07 PM
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

class GenerateModels extends DoctrineTaskBase
{
  protected function configure()
  {
    $this->name	     = 'generate-model';
    $this->namespace = 'doctrine';
    $this->description = _('Generate models from an existing database or '.
			   'an YAML file.');
    
    $name   = 'Name of the doctrine’s connection or the YAML file which'.
      ' contains the SQL schema.';
    $source = 'Generate models from an YAML schema (yaml) or from an existing'.
      ' database (db)?';
    
    $this->addOption(new TaskOption('source', $source, 'yaml'));
    $this->addArgument(new TaskArgument('name', $name,
					TaskArgument::OPTIONAL, 'schema.yml'));
  }
  
  
  public function exec()
  {
    $src = $this->getOptionsvalue('source');
    
    if ( !in_array($src, array('yaml', 'db')) )
      exit(_('The possible sources are only “yaml” or “db”.'));
    
    if ( $src === 'yaml' )
      $this->generateModelFromYaml();
    else
      $this->generateModelFromDb();

    $this->moveTableClasses();
  }


  private function moveTableClasses()
  {
    if ( $this->getOptionsValue('generate-table') === true )
    {
      if ( !Misc::isEmpty(($to = $this->getOptionsValue('table-dir'))) )
      {
	$from = $this->getOptionsValue('models-path');
	$to = $from.'/'.$to;
	$opt = array('suffix'=>'Table'.self::getOptionsValue('suffix'));
	Ftp::moveFiles($from, $to, false, false, $opt);
      }
    }
  }
  
  
  private function generateModelFromDb()
  {
    $con_name = $this->getArgumentsvalue('name');
    
    if ( $con_name === 'schema.yml' )
      $con_name = $this->Con->getName();
    
    \Doctrine_Core::generateModelsFromDb(
      $this->getOptionsValue('models-path'),
      array($con_name),
      $this->getImportOptions()
    );
  }
  
  
  
  private function generateModelFromYaml()
  {
    $file_path = $this->getOptionsValue('yaml-schema-path');
    $file_path .= '/'.$this->getArgumentsValue('name');
    
    \Doctrine_Core::generateModelsFromYAML(
      $file_path,
      $this->getOptionsValue('models-path'),
      $this->getImportOptions()
    );
  }
}