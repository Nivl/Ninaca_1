<?php

/*
**  Valide un captcha avec reCAPTCHA.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	11/26/2009, 07:06 PM
**  @last	Nivl <nivl@free.fr> 11/27/2009, 12:37 AM
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

class ValidateReCaptcha extends Validate
{
  public function __construct(array $options = array())
  {
    if ( !function_exists('recaptcha_get_html') )
      require Misc::getLibPath().'/vendors/recaptcha/recaptchalib.php';
 
    $options['allowEmpty'] = true;
    $options['require'] = false;
    
    parent::__construct($options);
  } 
  
  
  protected function execute($value)
  {
    if ( !is_array($value) )
    {
      $this->addError(_('The words did not match.'));
      return null;
    }

    $private_key = Config::read('modules.Recaptcha.keys.private');

    $Resp = recaptcha_check_answer($private_key,
				   $_SERVER["REMOTE_ADDR"],
				   $value['challenge'],
				   $value['response']);
    if ( !$Resp->is_valid )
      $this->addError(_('The words did not match.'));

    return null;
  }
}



