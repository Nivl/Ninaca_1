<?php

/*
**  Classe qui gère les CAPTCHAs avec reCAPTCHA.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	11/26/2009, 06:27 PM
**  @last	Nivl <nivl@free.fr> 02/26/2010, 12:35 AM
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

class FormInputReCaptcha extends FormInput
{
  public function __construct(array $options = array())
  {
    if ( !function_exists('recaptcha_get_html') )
      require Misc::getLibPath().'/vendors/recaptcha/recaptchalib.php';

    parent::__construct($options);
  }

  public function getField()
  {
    return recaptcha_get_html(Config::read('modules.Recaptcha.keys.public'),
			      null,
			      (bool)Config::read('security.use_ssl'));
  }
}


