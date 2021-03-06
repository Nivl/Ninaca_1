<?php

/*  
**  Valide une URL YourMusicUpload.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	03/13/2010, 04:16 PM
**  @last	Nivl <nivl@free.fr> 03/13/2010, 06:32 PM
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

class ValidateYourMusicUpload extends Validate
{
  public function execute($value)
  {
    $pattern = array(
      '`(http://)?(www\.)?yourmusicupload.com/'.
      '(audio/play|node)/([0-9]+)(.*)?`i',
      
      'http://www.yourmusicupload.com/audio/play/$4');
    if (!preg_match($pattern[0], $value))
      $this->errors[] = _('This link is not a valid yourMusicUpload link.');
    else
      $value = preg_replace($pattern[0], $pattern[1], $value);
    return $value;
  }
}

