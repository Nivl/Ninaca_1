<?php

/*
**  Fonctions sur les fichiers et dossier.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	09/13/2009, 06:53 PM
**  @last	Nivl <nivl@free.fr> 04/04/2010, 06:50 PM
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

class Ftp
{
  /*
  ** Récupère la liste des fichiers contenu dans un dossier.
  **
  ** @param string path [Dossier à parcourir]
  ** @param &array files [Reférence vers l'array qui contiendra la liste]
  ** @param array except [Liste des fichiers/dossiers à ne pas parcourir]
  ** @param array exts [Liste des extensions autorisées]
  ** @param bool rec [On parcours les sous-dossiers ?]
  ** @param string cb_key [Fonction de callback à appliquer sur le nom du
  **                             fichier]
  ** @param string cb_value [Fonction de callback à appliquer sur le
  **                               chemin du fichier]
  */
  static public function getFilesFromDir($path,
					 array &$files,
					 array $exts = array(),
					 array $except = array(),
					 $rec = true,
					 $cb_key = null,
					 $cb_value = null)
  {
    foreach (glob($path) as $file)
    {
      $filename = basename($file);
      if ( !in_array($file, $except) )
      {
	if ( $rec && is_dir($file) && !in_array($filename, array('.','..')) )
	  self::getFilesFromDir($file.'/*', $files, $exts, $except, $rec, 
				$cb_key, $cb_value);
	else if ( is_file($file) )
	{
	  if ( self::checkFilesExtension($filename, $exts) )
	  {
	    $key   = ($cb_key) ? $cb_key($filename, $file) : $filename;
	    $value = ($cb_value) ? $cb_value($file,$key) : $file;
	    $files[$key] = $value;
	  }
	}
      }
    }
  }
  
  
  /*
  ** Vérifie si un fichier a une extension correcte.
  ** Si aucune extension n'est fournie, la fonction retournera true.
  **
  ** @param string filename
  ** @param array $exts
  **
  ** @return bool
  */
  static public function checkFilesExtension($filename, array $exts)
  {
    if ( Misc::isEmpty($exts) )
      return true;

    foreach ( $exts as $ext )
    {
      if ( mb_substr($filename, -mb_strlen($ext)) === $ext )
	return true;
    }
    
    return false;
  }



  static public function getFilesFromYaml(array $file, $with_ns = true,
					 $no_ext = true )
  {
    self::checkYamlFile($file);
    $files = array();

    foreach ( $file as $namespace => $info)
    {
      $prefix = ($namespace==='\\' || !$with_ns) ? null : $namespace.'\\';

      if ( $no_ext )
	$key = create_function('$file',
			       "return '$prefix'.mb_substr(\$file, 0,
                                mb_strpos(\$file, '.'));");
      //$key = function($file) use ($prefix)
      //{
      //    return $prefix.mb_substr($file, 0, mb_strpos($file, '.'));
      //  };
      else
	$key = null;
      
      self::browseYamlFile($info, $files, $prefix, $key);
    }
    
    return $files;
  }


  
  static private function browseYamlFile(array $info, &$files, $prefix,$cb_key)
  {
    foreach ( $info['dirs'] as $dir )
    {
      if (mb_substr($dir,-1) !== '*' && mb_substr($dir,-2) !== '*/')
	$dir .= (mb_substr($dir,-1) !== '/') ? '/*' : '*';
      self::getFilesFromDir($dir, $files, $info['extensions'],
			    $info['exceptions'], true, $cb_key);
    }
    
    foreach ( $info['files'] as $file )
    {
      if ( is_file($file) )
      {
	$base = basename($file);
	$key = ($cb_key) ? $cb_key($base) : $base;
	$files[$key] = $file;
      }
    }
  }

  
  static private function checkYamlFile(array &$file)
  {
    if ( !is_array($file) )
      $file = array();

    foreach ( $file as $ns => &$info )
    {
      if ( !isset($info['dirs']) )
	$info['dirs'] = array();
      
      if ( !isset($info['exceptions']) )
	$info['exceptions'] = array();
      
      if ( !isset($info['extensions']) )
	$info['extensions'] = array();
      
      if ( !isset($info['files']) )
	$info['files'] = array();
    }
  }


  /*
  ** déplace des fichiers.
  **
  ** @param string from [Dossier à parcourir]
  ** @param string to [Dossier de destination]
  ** @param bool rec [Recursif ?]
  ** @param bool keep_dir [Re-créer les sous-dossiers]
  ** @param array options [Tableau d'option]
  **   string prefix
  **   string suffix [Avec l'extension]
  **   array  extensions [extention des fichiers à déplacer]
  */
  static public function moveFiles($from, $to, $rec = true, $keep_dir = true,
				   array $options = array())
  {
    if ( !self::makeDir($to) )
      exit("Error: The path “{$to}” is not writable.");
    
    $ext = (!empty($options['extensions'])) ? $options['extensions'] : array();
    $pref = (!empty($options['prefix'])) ? $options['prefix'] : null;
    $suf = (!empty($options['suffix'])) ? $options['suffix'] : null;

    $dir = opendir($from);
    while ( $file = readdir($dir) )
    {
      if ( $rec && is_dir($from.'/'.$file) )
      {
	$new_to = ($keep_dir) ? $to.'/'.$file : $to;
	self::moveFiles($from.'/'.$file, $new_to, $rec, $keep_dir, $options);
      }
      else if ( is_file($from.'/'.$file) &&
		self::checkFilesExtension($file, $ext) )
      {
	if ( (!$pref || mb_substr($file, 0, strlen($pref)-1) == $pref) &&
	     (!$suf || mb_substr($file, -strlen($suf)) == $suf) )
	  self::moveFile($from.'/'.$file, $to.'/'.$file);
      }
    }
    closedir($dir);
  }


  /*
  ** Déplace un fichier
  **
  ** @param string from
  ** @param string to
  */
  static public function mv($from, $to){self::moveFile($from, $to);}
  static public function moveFile($from, $to)
  {
    if ( !copy($from, $to) )
      exit("Error: The file “{$to}” can't be copied.");
    
    if ( !unlink($from) )
      exit("Error: The file “{$from}” can't be deleted.");
  }


  /*
  ** Supprime des fichiers
  **
  ** @param array files
  ** @param string to
  */
  static public function rmFiles(array $files)
  {
    $success = true;
    foreach ( $files as $file )
    {
      if (is_file($file))
	$success = $success && unlink($file);
    } 
    return $success;
  }


  /*
  ** Créer un dossier s'il n'existe pas déjà.
  **
  ** @param string dir
  ** @param int chmod
  ** @param bool recursive
  ** @param bool umask [Utiliser umask pour appliquer les droits]
  ** @param bool umask_value [Valeur à donner à umask]
  **
  ** @return bool
  */
  static public function makeDir($dir, $chmod = 0755, $recursive = true,
				 $umask = true, $umask_value = 0)
  {
    if (is_dir($dir))
      return true;
    if ($umask)
    {
      $um = umask($umask_value);
      $mk = @mkdir($dir, $chmod, $recursive);
      umask($um);
      return $mk;
    }
    else
      return @mkdir($dir, $chmod, $recursive);
  }


  /*
  ** Retourne un array contenant la liste des dossiers d'un dossier.
  **
  ** @param string dir
  ** @param array except [Dossier à ne pas lister]
  **
  ** @return array
  */
  static public function getDirs($dir, array $except = array('.','..'))
  {
    if ( Misc::isEmpty($dir) || !(@$directory = opendir($dir)) )
      return false;

    $dirs = array();
    
    while ( $file = readdir($directory) )
    {
      if ( is_dir($dir.'/'.$file) && !in_array($file, $except) )
	$dirs[] = $file;
    }

    closedir($directory);
    return $dirs;
  }



  static public function getFileSize($file)
  {
    if ( !is_file($file) )
      exit("$file is not an existing file.");

    return sprintf('%u', filesize($file));
  }
  
  
  /*
  ** Convertisseur d'octet: convertie la valeur de $size en $to.
  ** Les suffixes textuels sont accepté Ex: 1M, 5k, 10G
  **
  ** @param mixed size
  ** @param char to
  ** @param si [1M = 1000o si true, sinon 1M = 1024]
  **
  ** @return numeric
  */
  static public function octetConverter($size, $to = 'o', $si = false)
  {
    $to = strtolower($to);
    $units = array('o', 'k', 'm', 'g', 't');
    
    if ( !in_array($to, $units) )
      exit("Error: $to is not an existing unit.");
    
    $unit = (is_numeric($size)) ? 'o' : strtolower($size[strlen($size)-1]);
    $base = ($si) ? 10 : 2;

    if ( $si )
      $pow = array('o'=>1, 'k'=>3, 'm'=>6, 'g'=>9, 't'=>12);
    else
      $pow = array('o'=>1, 'k'=>10, 'm'=>20, 'g'=>30, 't'=>40);
    
    if ( !in_array($unit, $units) )
      exit("Error: $unit is not an existing unit.");

    if ( $to === $unit )
      return $size * 1;
    else if ( Arrays::getKey($to, $units) < Arrays::getKey($unit, $units))
      return $size*pow($base, $pow[$unit]);
    else
      return $size/pow($base, $pow[$to]);
  }


  /*
  ** Retourne le type mime d'un fichier.
  **
  ** @param string file
  **
  ** @return text
  */
  static public function getMimeType($file)
  {
    if ( !is_file($file) )
      exit("Error: $file is not an existing file.");
    
    if (class_exists('finfo') && is_int(FILEINFO_MIME_TYPE))
    {
      $Finfo = new finfo(FILEINFO_MIME_TYPE);
      return $Finfo->file($file);
    }
    else
      return mime_content_type($file);
  }


  /*
  ** Déplace un fichier uploadé dans le bon dossier en fonction de son id, et 
  ** retourne le nouveau chemin.
  **
  ** @param string file
  ** @param string dir
  ** @param int id
  **
  ** @return text
  */
  static public function moveUploadedFile($file, $dir, $id)
  {
    $id_max = 1000;
    
    while ( $id > $id_max )
      $id_max += 1000;
    
    $inter = ($id_max - 999).'_'.$id_max;
    $dir = str_replace('//', '/', "uploads/$dir/$inter");
    $ext = self::getExtFromMimeType($file);
    
    self::makeDir($dir, 0777);
    move_uploaded_file($file, "$dir/$id.$ext");
    return "$dir/$id.$ext";
  }


  /*
  ** Supprime un fichier uploadé.
  **
  ** @param string dir
  ** @param int id
  **
  ** @return bool
  */
  static public function deleteUploadedFile($dir, $id)
  {
    $id_max = 1000;
    
    while ( $id > $id_max )
      $id_max += 1000;
    
    $inter = ($id_max - 999).'_'.$id_max;
    $dir = str_replace('//', '/', "uploads/$dir/$inter");

    $file = glob("$dir/$id.*");

    if ( isset($file[0]) )
      return unlink($dir.'/'.$file[0]);

    return true;
  }
  
  

  /*
  ** Retourne l'extension d'un fichier en fonction de son type mime.
  **
  ** @param string file
  **
  ** @return text
  */
  static public function getExtFromMimeType($file)
  {
    $list = YamlFactory::factory()->load('config/mimeTypes.yaml');
    $mime = self::getMimeType($file);
    
    if ( isset($list[$mime]) )
      return $list[$mime];

    $info = pathinfo($file);
    return $info['extension'];
  }
  
  
  
  /*
  ** Calcule les nouvelles dimensions d'une image à redimensionner.
  **
  ** @param int w
  ** @param int h
  ** @param int max_h
  ** @param int max_w
  **
  ** @return
  */
  static public function resizeImage($w, $h, $max_w = 200, $max_h = 200)
  {
    if ( $w > $max_w || $h > $max_h )
    {
      if ( ($w / $max_w) < ($h / $max_h)  )
      {
	$new_h = $max_h;
	$red = ($new_h * 100) / $h;
	$new_w = ($w * $red) / 100;
      }
      else
      {
	$new_w = $max_w;
	$red = ($new_w * 100) / $w;
	$new_h = ($h * $red) / 100;
      }
    }
    else
    {
      $new_w = $w;
      $new_h = $h;
    }

    return array($new_w, $new_h);
  }
}