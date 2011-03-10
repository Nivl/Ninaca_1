<?php

/*
**  Classe qui gère les erreurs rencontrées par les formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	11/23/2009, 10:43 PM
**  @last	Nivl <nivl@free.fr> 03/14/2010, 06:28 PM
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

abstract class FormErrors extends Errors
{
  static public function compareFieldNotExists($name, $field1, $field2)
  {
    $msg = _('The field %s used to compare %s and %s does not exists.');
    $msg = sprintf($msg, $name, $field1, $field2);
    parent::display($msg);
  }


  static public function compareOperatorNotExists($op, $field1, $field2)
  {
    $msg = _('The operator %s used to compare %s and %s does not exists.');
    $msg = sprintf($msg, $op, $field1, $field2);
    parent::display($msg);
  }


  static public function alreadyValidated()
  {
    $msg = _('You cannot add or remove a field here.');
    parent::display($msg);
  }

  static public function cantAddOrRemoveField()
  {
    $msg = _('The form has already been validated.');
    parent::display($msg);
  }


  static public function badValidateFormat($field_name)
  {
    $msg = _('The class used to validate the field %s does not exists.');
    $msg = sprintf($msg, $field_name);
    parent::display($msg);
  }


  static public function badFieldFormat($field_name)
  {
    $msg = _('The class used to create the field %s does not exists.');
    $msg = sprintf($msg, $field_name);
    parent::display($msg);
  }


  static public function noFieldFound()
  {
    $msg = _('The form has no fields.');
    parent::display($msg);
  }


  static public function fieldNotExists($field, $trace)
  {
    $msg = _('The field %s called in %s at line %d does not exists.');
    $msg = sprintf($msg, $field, $trace[0], $trace[1]);
    parent::display($msg);
  }
}


