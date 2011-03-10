<?php

/*
**  Valide un booléen.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/17/2009, 12:05 AM
**  @last	Nivl <nivl@free.fr> 01/14/2010, 06:06 PM
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
** You should have received a copy of the GNU General Public License
** along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class ValidateBool extends Validate
{
  public function __construct(array $options = array())
  {
    if ( !isset($options['required']) )
      $options['required'] = false;
    
    if ( !isset($options['allowEmpty']) )
      $options['allowEmpty'] = true;

    parent::__construct($options);
  }
  
  
  protected function execute($value)
  {
    //var_dump($value);
    $true = array('1','on','true','yes');
    $false = array('0','off','false','no');
    
    if ( Misc::isEmpty($value) || in_array($value, $false) )
      $value = false;
    else if ( in_array($value, $true) )
      $value = true;
    else
      $this->errors[] = _("This field must be a boolean.");
    //var_dump($value);
    return $value;
  }
}



