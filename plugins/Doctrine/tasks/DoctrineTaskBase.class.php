<?php

/*
**  Tâche pour doctrine.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/30/2009, 02:19 PM
**  @last	Nivl <nivl@free.fr> 11/08/2009, 05:52 PM
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

abstract class DoctrineTaskBase extends Task
{
  protected
    $Con = null,
    $configDoctrine = array();

  abstract protected function exec();
  
  public function __construct()
  {
    $this->namespace = 'doctrine';
    $this->configDoctrine = $this->getDoctrineConfig();
    $this->configDoctrine = $this->configDoctrine['doctrine'];
    $this->Con = Doctrine_Manager::connection();
    $this->setDefaultOptions();

    parent::__construct();
  }


  public function execute()
  {
    $this->exec();
    Doctrine_Manager::getInstance()->closeConnection($this->Con);
  }

  
  /*
  ** Appelle les fonctions qui ajoutent des options.
  */
  protected function setDefaultOptions()
  {
    $this->setDefaultPathsOptions();
    $this->setDefaultPackagesOptions();
    $this->setDefaultBaseClassesOptions();
    $this->setDefaultTableClassesOptions();
    $this->setDefaultGeneralsOptions();
    $this->setDefaultGenerationsOptions();
    $this->setDefaultPhpDocOptions();
  }
  

  /*
  ** Ajoute des options générales.
  */
  protected function setDefaultGeneralsOptions()
  {
    $this->addOptions(
      array(
	new TaskOption('suffix', 'Files’ suffix.',
		       $this->configDoctrine['import']['suffix']),
      ));
  }


  /*
  ** Ajoute les options qui concernent les chemins d'accès.
  */
  protected function setDefaultPathsOptions()
  {
    $this->addOptions(
      array(
	new TaskOption('yaml-schema-path', 'Path to your yaml schema.',
		       $this->configDoctrine['paths']['yaml_schema']),
	
	new TaskOption('models-path', 'Path to your models.',
		       $this->configDoctrine['paths']['models']),
	
	new TaskOption('data-fixtures-path', 'Path to your fixtures’ data.',
		       $this->configDoctrine['paths']['data_fixtures']),
	
	new TaskOption('migration-path', 'Path to your migrations.',
		       $this->configDoctrine['paths']['migrations']),
	
	new TaskOption('data-sql-path', 'Path to your sql’s data.',
		       $this->configDoctrine['paths']['sql']),
      ));
  }


  
  /*
  ** Ajoute les options qui concernent les packages.
  */
  protected function setDefaultPackagesOptions()
  {
    $packages = &$this->configDoctrine['import']['packages'];
    
    $this->addOptions(
      array(
	new TaskOption('package-prefix', 'Packages’ prefix.',
		       $packages['prefix']),
	new TaskOption('package-path', 'Packages’ path.',
		       $packages['path']),
	new TaskOption('package-folder', 'Packages’ folder name.',
		       $packages['folderName']),
      ));
  }



  /*
  ** Ajoute les options qui concernent les générations de fichier.
  */
  protected function setDefaultGenerationsOptions()
  {
    $generate = &$this->configDoctrine['import']['generate'];
    
    $this->addOptions(
      array(
	new TaskOption('generate-base', 'Generate base classes?',
		       $generate['base'], 'B', TaskOption::BOOL),
	
	new TaskOption('generate-table', 'Generate table classes?',
		       $generate['table'], 'T', TaskOption::BOOL), 
	
	new TaskOption('generate-accessors', 'Generate accessors classes?',
		       $generate['accessors'], 'A', TaskOption::BOOL),
      ));
  }


  /*
  ** Ajoute les options qui concernent les classes de base.
  */
  protected function setDefaultBaseClassesOptions()
  {
    $baseClasses = &$this->configDoctrine['import']['baseClasses'];
    
    $this->addOptions(
      array(
	new TaskOption('base-prefix', 'Base classes’ prefix.',
		       $baseClasses['prefix']),
	new TaskOption('base-dir', 'Base classes’ directory.',
		       $baseClasses['directory']),
	new TaskOption('base-class-name', 'Name of the base class’ parent.',
		       $baseClasses['name']),
      ));
  }



  /*
  ** Ajoute les options qui concernent les classes de table.
  */
  protected function setDefaultTableClassesOptions()
  {
    $tableClasses = &$this->configDoctrine['import']['tableClasses'];
    
    $this->addOption(
      new TaskOption('table-dir', 'Table classes’ directory.',
		     $tableClasses['directory'])
    );
  }
  

  /*
  ** Ajoute les options qui concernent la documentation.
  */
  protected function setDefaultPhpDocOptions()
  {
    $doc = &$this->configDoctrine['import']['php_doc'];
    
    $this->addOptions(
      array(
	new TaskOption('doc-package-name', 'Package’s name.',
		       $doc['package']),
	new TaskOption('doc-subpackage-name', 'Subpackage’s name.',
		       $doc['subpackage']),
	new TaskOption('doc-author-name', 'Author’s name.',
		       $doc['name']),
	new TaskOption('doc-author-email', 'Author’s email address.',
		       $doc['email']),
      ));
  }
  
  
  /*
  ** Retourne les options d'importation sous forme d'array compatible
  ** avec doctrine.
  **
  ** @return array
  */
  protected function getImportOptions()
  {
    return array(
      'packagesPrefix' => $this->options['package-prefix']->getValue(),
      'packagesPath' => $this->options['package-path']->getValue(),
      'packagesFolderName' => $this->options['package-folder']->getValue(),
      'suffix' => $this->options['suffix']->getValue(),
      'generateBaseClasses' => $this->options['generate-base']->getValue(),
      'generateTableClasses' => $this->options['generate-table']->getValue(),
      'generateAccessors' => $this->options['generate-accessors']->getValue(),
      'baseClassesPrefix' => $this->options['base-prefix']->getValue(),
      'baseClassesDirectory' => $this->options['base-dir']->getValue(),
      'baseClassName' => $this->options['base-class-name']->getValue(),
      'phpDocPackage' => $this->options['doc-package-name']->getValue(),
      'phpDocSubpackage' => $this->options['doc-subpackage-name']->getValue(),
      'phpDocName' => $this->options['doc-author-name']->getValue(),
      'phpDocEmail' => $this->options['doc-author-email']->getValue(),
    );
  }


  /*
  ** Récupère et vérifie la configuration de Doctrine.
  **
  ** @return array
  */
  private function getDoctrineConfig()
  {
    return YamlFactory::factory()->load('config/doctrine.yaml');
  }
}

