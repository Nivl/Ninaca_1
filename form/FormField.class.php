<?php

/*
**  Classe mère qui gère les champs des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/13/2009, 12:51 AM
**  @last	Nivl <nivl@free.fr> 02/25/2010, 06:52 PM
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

abstract class FormField
{
  protected
    $id		= null,
    $name	= null,
    $value	= null,
    $bound      = true,
    $def_value	= null,
    $Validator	= null,
    $classes	= array(),
    $options	= array();

  const GET_AS_HTML = 1;
  const GET_AS_ARRAY = 2;
  const GET_AS_STRING = 3;

  abstract public function getField();
  abstract public function display();
  abstract public function check();
  
  public function __construct(array $options = array(),
			      array $classes = array())
  {
    $this->id = (isset($options['id'])) ? $options['id'] : null;
    $this->bound = (isset($options['bound'])) ? (bool)$options['bound'] : true;
    
    if ( isset($options['default']) )
    {
      $this->def_value = $options['default'];
      $this->value = $options['default'];
    }
    
    $this->options = $options;
    $this->classes = $classes;
  }


  protected function getClasses($output = self::GET_AS_ARRAY)
  {
    if ( $output == self::GET_AS_ARRAY )
      return $this->classes;
    else
    {
      $classes = implode(' ', $this->classes);
      return $output == self::GET_AS_HTML ? "class=\"$classes\"" : $classes;
    }
  }


  public function addClasses(array $Classes)
  {
    $this->classes = array_merge($this->classes, $classes);
  }
  
  
  public function addClass($class)
  {
    $this->classes[] = $class;
  }
  

  public function deleteClasses(array $classes)
  {
    $this->classes = Arrays::rmKeysArray($classes, $this->classes);
  }

  
  public function deleteClass($class)
  {
    $this->classes = Arrays::rmKeys($class, $this->classes);
  }


  public function addError($error)
  {
    $this->Validator->addError($error);
  }
  
  
  /*
  ** Définie un validateur pour le champ courant.
  **
  ** @param Validate Validator
  */
  public function setValidator(Validate $Validator)
  {
    if ( $this->Validator === null )
      $this->Validator = $Validator;
  }
  
  
  
  /*
  ** Valide un champ.
  ** 
  ** @param string data
  ** 
  ** @return mixed
  */
  public function valid($data)
  {
    $data = $this->Validator->validation($data);
    $this->value = $data;
    return $data;
  }
  
  
  
  /*
  ** Vérifie si le champ contient des erreurs.
  **
  ** @return bool
  */
  public function hasErrors()
  {
    $value = $this->Validator->getErrors();
    return !empty($value);
  }
  
  
  
  /*
  ** Retourne les erreurs liés au champ.
  **
  ** @return array
  */
  public function getErrors()
  {
    $list =  $this->Validator->getErrors();
    $ret = null;

    if ( !empty($list) )
    {
      foreach ( $list as $error )
	$ret .= "<li>$error</li>";
      
      $ret = '<ul class="errorsList">'.$ret.'</ul>';
    }
    
    return $ret;
  }

  
  
  /*
  ** Retourne la valeur du champ courrant.
  ** 
  ** @param bool protect [On protege contre les failles XSS]
  **
  ** @return string
  */
  public function getValue($protect = false)
  {
    if ( is_array($this->value) )
    { 
      if ( $protect )
	return array_map(array('Security','noHTML'), $this->value);
      else
	return $this->value;
    }
    else if ( !Misc::isEmpty($this->value) )
      return ($protect) ? Security::noHTML($this->value) : $this->value;
    else if ( $this->bound && !Misc::isEmpty($this->def_value) )
      return  ($protect)? Security::noHTML($this->def_value): $this->def_value;
    
    return '';
  }


  /*
  ** Retourne la valeur du champ courrant afin de pré-remplire le formulaire.
  ** 
  ** @return string
  */
  protected function bound($protect = true)
  {
    if ( $this->bound )
      return $this->getValue($protect);

    return null;
  }
  
  
  /*
  ** Définie un id pour le champ courant.
  **
  ** @param string id
  **
  ** @return FormField [Référence sur le champ courant]
  */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }



  /*
  ** Définie un nom pour le champ courant.
  **
  ** @param string name
  **
  ** @return FormField [Référence sur le champ courant]
  */
  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }
  

  /*
  ** Vérifie si le champ contient un id.
  **
  ** @return bool
  */
  public function hasId()
  {
    return !empty($this->id);
  }
  
  
  
  /*
  ** Définie une valeur par defaut pour le champ courant.
  ** 
  ** @param string value
  **
  ** @return FormField [Référence sur le champ courant]
  */
  public function setDefaultValue($value)
  {
    $this->def_value = $value;
    return $this;
  }
}


