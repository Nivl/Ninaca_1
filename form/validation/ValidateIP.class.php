<?php

/*
**  Valide une IP.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/16/2009, 11:04 PM
**  @last	Nivl <nivl@free.fr> 09/09/2009, 06:09 PM
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

class ValidateIP extends Validate
{
  public function execute($value)
  {
    $type = (isset($this->options['type'])) ? $this->options['type'] : null;
    
    if ( $type === 'ipv4' )
    {
      if ( filter_var($value, FILTER_VALIDATE_IP, FILTER_VALIDATE_IPV4) )
	$this->errors[] = _('This field is not a valid IPv4 adress.');
    }
    else if ( $type === 'ipv6' )
    {
      if ( filter_var($value, FILTER_VALIDATE_IP, FILTER_VALIDATE_IPV6) )
	$this->errors[] = _('This field is not a valid IPv6 adress.');
    }
    else
    {
      if ( filter_var($value, FILTER_VALIDATE_IP) )
	$this->errors[] = _('This field is not a valid IP adress.');
    }

    return $value;
  }
}


