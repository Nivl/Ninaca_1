<?php

/*
**  Valide une chaîne de caractère.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 12:20 AM
**  @last	Nivl <nivl@free.fr> 02/24/2010, 02:24 AM
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

class ValidateString extends Validate
{
  protected function execute($value)
  {
    if ( isset($this->options['max_length']) )
      $this->maxLength($value);

    if ( isset($this->options['min_length']) )
      $this->minLength($value);
    
    if ( isset($this->options['match']) && $this->options['match'] <> $value)
      $this->errors[] = _('The value you gave is not correct.');

    return $value;
  }

  
  private function maxLength($value)
  {
    $max = (string)$this->options['max_length'];
    $len = String::length($value);

    if ( ctype_digit($max) && $len > $max )
    {
      $this->errors[] = __('This field must be of %d character maximum.',
			   'This field must be of %d characters maximum.',
			   $max);
    }
  }
  
  
  private function minLength($value)
  {
    $min = (string)$this->options['min_length'];
    $len = String::length($value);
    
    if ( ctype_digit($min) && $len < $min )
    {
      $this->errors[] = __('This field must be of %d character at least.',
			   'This field must be of %d characters at least.',
			   $min);
    }
  }
}



