<?php

/*
**  Classe mère de la validation de données.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 12:21 AM
**  @last	Nivl <nivl@free.fr> 03/09/2010, 06:53 PM
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

abstract class Validate
{
  protected $options	= array();
  protected $errors	= array();
  
  public function __construct(array $options = array())
  {
    $defOptions = array('allowEmpty'=>false, 'required'=>true);
    $this->options = array_merge($defOptions, $options);
  }
  
  
  public function validation($value)
  {
    if ( !empty($this->options['callback']) &&
	 is_array($this->options['callback']) )
    {
      foreach ( $this->options['callback'] as $callback )
	$value = call_user_func($callback, $value);
    }
    if ( !is_array($value) )
      $value = trim($value);
    if ( Misc::isEmpty($value) )
    {
      if ( $this->options['allowEmpty'] )
	return $value;
      else
	$this->errors[] = _("This field must be filled-in.");
    }
    else if ( $value === null && $this->options['required'] )
      $this->errors[] = _("This field is required.");
    else
      $value = $this->execute($value);
    return $value;
  }
  
  
  public function addError($error)
  {
    $this->errors[] = $error;
  }
  
  
  
  public function getErrors()
  {
    return $this->errors;
  }
  
  
  abstract protected function execute($value);
}

