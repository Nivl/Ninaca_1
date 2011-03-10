<?php

/*
**  Valide un envoie de fichier.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/03/2009, 11:30 PM
**  @last	Nivl <nivl@free.fr> 04/04/2010, 06:56 PM
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

class ValidateFile extends Validate
{
  protected $mimeTypes = array();

  public function __construct(array $mimeType, array $options = array())
  {
    $this->mimeTypes = $mimeType;
    if ( !isset($options['maxSize']) )
      $options['maxSize'] = ini_get('upload_max_filesize');
    $options['maxSize'] = Ftp::octetConverter($options['maxSize']);
    parent::__construct($options);
  }
  
  
  protected function execute($file)
  {
    if ( $this->options['allowEmpty'] && isset($file['error']) && 
	 $file['error'] === UPLOAD_ERR_NO_FILE )
      return null;
    if ( !is_array($file) )
    {
      $this->addError(_('No Files has been send.'));
      return null;
    }
    if ( !isset($file['error']) || $file['error'] != UPLOAD_ERR_OK )
    {
      $this->checkUploadError($file);
      return null;
    }
    if ( Ftp::getFileSize($file['tmp_name']) > $this->options['maxSize'] )
    {
      $size_error = _("You can’t upload a file bigger than %d octets.");
      $this->addError(sprintf($size_error, $this->options['maxSize']));
    }
    if ( !in_array(Ftp::getMimeType($file['tmp_name']), $this->mimeTypes) )
      $this->addError(_('This file has not a valid mime type.'));
    return $file;
  }



  private function checkUploadError($file)
  {
    if ( !isset($file['error']) )
      $this->addError(_('Unknown error.'));
    else if ( $file['error'] === UPLOAD_ERR_NO_FILE )
      $this->addError(_('No Files has been uploaded.'));
    else if ( $file['error'] === UPLOAD_ERR_PARTIAL )
      $this->addError(_('The file has been partially uploaded.'));
    else if ( $file['error'] === UPLOAD_ERR_INI_SIZE )
    {
      $error = _("You can’t upload a file bigger than %d octets.");
      $max = Ftp::octetConverter(ini_get('upload_max_filesize'));
      $this->addError(sprintf($error, $max));
    }
    else if ( $file['error'] === UPLOAD_ERR_FORM_SIZE )
      $this->addError(_("The file’s size is bigger than the maximum size ".
			"specified in the form."));
    else if ( $file['error'] ===  UPLOAD_ERR_NO_TMP_DIR )
      $this->addError(_('The temporary directory is missing.'));
    else if ( $file['error'] ===  UPLOAD_ERR_CANT_WRITE )
      $this->addError(_("The file can’t be wrote on the disk."));
    else if ( $file['error'] ===  UPLOAD_ERR_EXTEMSION )
      $this->addError(_('The file’s extention is not allowed.'));
    else
      $this->addError(_('Unknown error.'));
  }
}