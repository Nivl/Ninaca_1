<?php

/*  
**  Valide une URL Youtube.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	03/07/2010, 06:01 PM
**  @last	Nivl <nivl@free.fr> 03/07/2010, 07:29 PM
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

class ValidateYoutube extends Validate
{
  public function execute($value)
  {
    $pattern = array(
      '`(http://)?(www.)?dailymotion.com/video/([a-z0-9]+)(_(.*))?`i',
      'http://www.dailymotion.com/swf/$3');
    if (!preg_match($pattern[0], $value))
      $this->errors[] = _('This link is not a valid dailymotion link.');
    else
      $value = preg_replace($pattern[0], $pattern[1], $value);
    
    return $value;
  }
}

