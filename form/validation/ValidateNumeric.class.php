<?php

/*
**  Valide une chaîne de caractère.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 12:20 AM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 05:33 PM
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

class ValidateNumeric extends Validate
{
  protected function execute($value)
  {
    $type = (isset($this->options['type'])) ? $this->options['type'] : null;
    
    if ( $type === 'digit' && !ctype_digit($value) )
      $this->errors[] = _("This field must contain only digits.");
    if ( $type === 'int' && ((int) $value) <> $value )
      $this->errors[] = _("The value must be an integer.");
    else if ( $type === 'float' && ((float) $value) <> $value )
      $this->errors[] = _("The value must be a decimal.");
    else if ( !is_numeric($value) )
      $this->errors[] = _("This field must be numeric.");
    else
    {
      if ( isset($this->options['maxLength']) )
	$this->maxLength($value);
      if ( isset($this->options['max']) )
	$this->max($value);
      if ( isset($this->options['min']) )
	$this->min($value);
    }
    
    return $value;
  }
  
    
  private function maxLength($value)
  {
    $max = $this->options['maxLength'];
    if ( String::length($value) > $max )
      $this->errors[] = __('The maximum length is %d number.',
			   'The maximum length is %d numbers.', $max);
  }
  
  
  private function max($value)
  {
    $max = $this->options['max'];
    if ( $value > $max )
      $this->errors[] = sprintf(_('The maximum value is %d.'), $max);
  }
  
  
  private function min($value)
  {
    $min = $this->options['min'];
    if ( $value < $min )
      $this->errors[] = sprintf(_('The minimum value is %d.'), $min);
  }
}



