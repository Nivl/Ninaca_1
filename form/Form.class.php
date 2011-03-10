<?php

/*
**  Gestion des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/12/2009, 02:09 AM
**  @last	Nivl <nivl@free.fr> 03/26/2010, 06:16 PM
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

abstract class Form implements ArrayAccess
{
  private
    $id			= null,
    $data		= array(),
    $fields		= array(),
    $compare		= array(),
    $globalErrors	= array(),
    $errors_on_fields	= false,
    $has_been_validated	= false;
  
  protected
    $extraFields	= array(), // Ne dépend pas des variables qui suivent.
    $filter_extra	= true,
    $allow_extra_fields	= true;
  
  
  
  abstract protected function configure(array $conf = array());
  
  public function __construct(array $conf = array())
  {
    $this->configure($conf);
    
    if ( !empty($_POST) || !empty($_FILES) )
      $this->data = array_merge($_POST, $_FILES);
  }
  
  
  
  /*
  ** Methode servant à pouvoir utiliser l'objet comme un array.
  ** Cette methode vérifie l'existance du champ demandé
  **
  ** @param string field_name
  **
  ** @return bool;
  */
  public function offsetExists($field_name)
  {
    return isset($this->fields[$field_name]);
  }
  
  
  
  public function setIdentifier($id)
  {
    $this->id = $id;
    $this->setField('formIdentifier',
		    new FormHidden($id),
		    new ValidateConst($id));
  }
  
  
  /*
  ** Methode servant à pouvoir utiliser l'objet comme un array.
  ** Cette methode retourne le champ demandé.
  **
  ** @param string field_name
  **
  ** @return FormField
  */
  public function offsetGet($field_name)
  {
    if ( isset($this->fields[$field_name]) )
      return $this->fields[$field_name];
    
    FormErrors::fieldNotExists($field_name, Misc::whoCalledMe());
  }
  
  
  
  /*
  ** Methode servant à pouvoir utiliser l'objet comme un array.
  ** Cette methode ajoute un champ.
  **
  ** @param string field_name
  ** @param string value
  */
  public function offsetSet($field_name, $value)
  {
    FormErrors::cantAddOrRemoveField();
  }
  
  
  
  /*
  ** Methode servant à pouvoir utiliser l'objet comme un array.
  ** Cette methode ajoute un champ.
  ** 
  ** @param string field_name
  */
  public function offsetUnset($field_name)
  {
    FormErrors::cantAddOrRemoveField();
  }
  
  
  /*
  ** Affiche le formulaire.
  **
  ** @return string
  */
  public function __toString()
  {
    return $this->display();
  }


  /*
  ** Affiche le formulaire.
  **
  ** @return string
  */
  public function display()
  {
    $output = $this->getGlobalErrors();
    $output = '<tr><td class="globalErrors" colspan="2">'.$output.'</td></tr>';
    foreach ( $this->fields as $Field )
      $output .= $Field->display();

    return $output;
  }
  
  
  /*
  ** Vérifie si des données ont été envoyées ppour ce formulaire
  **
  ** @return bool
  */
  public function hasData()
  {
    if ( $this->id !== null && !empty($this->data) )
    {
      if ( isset($this->data['formIdentifier']) )
	return $this->data['formIdentifier'] == $this->id;

      return false;
    }

    return (!empty($this->data));
  }



  /*
  ** Vérifie si des données ne faisant pas partie du formulaire ont été
  ** envoyées
  **
  ** @return bool
  */
  public function hasExtraFields()
  {
    return !empty($this->extraFields);
  }
  
  
  
  /*
  ** retourne les données envoyées ne faisant pas partie du formulaire.
  **
  ** @return array
  */
  public function getExtraFields()
  {
    return $this->extraFields;
  }
  
  
  
  /*
  ** Vérifie si il y a des erreurs globales.
  **
  ** @return bool
  */
  public function hasGlobalErrors()
  {
    return !empty($this->globalErrors);
  }
  
  
  
  /*
  ** Ajoute une erreur globale.
  **
  ** @param string error
  */
  public function addGlobalError($error)
  {
    $this->globalErrors[] = $error;
  }
  
  
  
  /*
  ** Retourne les erreurs globales.
  **
  ** @param bool html
  **
  ** @return mixed
  */
  public function getGlobalErrors($html = true)
  {
    if ( !$html )
      return $this->globalErrors;
    
    $ret = null;
    
    if ( !empty($this->globalErrors) )
    {
      foreach ( $this->globalErrors as $error )
	$ret .= "<li>$error</li>";
      
      $ret = '<ul class="errorsList">'.$ret.'</ul>';
    }

    return $ret;
  }
  
  
  
  /*
  ** Vérifie si le formulaire contient des erreurs.
  **
  ** @return bool
  */
  public function hasErrors($check_again = false)
  {
    if ( $check_again )
    {
      $this->errors_on_fields = false;
      
      foreach ( $this->fields as $name => $Field )
      {
	if ( !isset($this->data[$name]) )
	  $this->data[$name] = null;
	
	$this->errors_on_fields = $this->errors_on_fields||$Field->hasErrors();
      }
    }

    return $this->errors_on_fields || !empty($this->globalErrors);
  }
  
  
  
  public function fill(array $values)
  {
    foreach ( $values as $key => $value )
    {
      if ( is_array($value) )
	$this->fillDeep($value, $key);
      else if ( isset($this->fields[$key]) )
	$this->fields[$key]->setDefaultValue($value);
    }
  }
  
  
  
  private function fillDeep(array $values, $name)
  {
    foreach ( $values as $key => $value )
    {
      if ( is_array($value) )
	$this->fillDeep($value, $name.'['.$key.']');
      else if ( isset($this->fields[$name.'['.$key.']']) )
	$this->fields[$name.'['.$key.']']->setDefaultValue($value);
    }
  }
  
  
  /*
  ** Valide le formulaire.
  **
  ** @return bool
  */
  public function isValid()
  {
    if ( !$this->has_been_validated)
    {
      $this->has_been_validated = true;
      $this->cleanData();
      
      foreach ( $this->fields as $name => $Field )
      {
	if ( !isset($this->data[$name]) )
	  $this->data[$name] = null;
	
	$this->data[$name] = $Field->valid($this->data[$name]);
	$this->errors_on_fields = $this->errors_on_fields||$Field->hasErrors();
      }
      
      $this->compare();
      return !$this->hasErrors();
    }
    else
      FormErrors::alreadyValidated();
  }
  
  
  
  /*
  ** Compare les champs qui doivent l'être.
  **
  ** @return bool
  */
  private function compare()
  {
    foreach ( $this->compare as $info )
    {
      if ( isset($this->data[$info[0]], $this->data[$info[2]]) )
      {
	$cond = false;
	
	if ( $info[1] === '==' )
	  $cond = $this->data[$info[0]] === $this->data[$info[2]];
	else if ( $info[1] === '<>' || $info[1] === '!=' )
	  $cond = $this->data[$info[0]] !== $this->data[$info[2]];
	else if ( $info[1] === '<' )
	  $cond = $this->data[$info[0]] < $this->data[$info[2]];
	else if ( $info[1] === '<=' )
	  $cond = $this->data[$info[0]] <= $this->data[$info[2]];
	else if ( $info[1] === '>' )
	  $cond = $this->data[$info[0]] > $this->data[$info[2]];
	else if ( $info[1] === '>=' )
	  $cond = $this->data[$info[0]] >= $this->data[$info[2]];

	if ( !$cond )
	  $this->globalErrors[] = $info[3];
      }
    }
  }



  /*
  ** Ajoute des champs à la liste des champs à comparer.
  **
  ** @param string field1 [nom du premier champ à comparer]
  ** @param string operator [Opérateur de comparaison]
  ** @param string field2 [nom du deuxième champ à comparer]
  ** @param string error [Erreur à afficher en cas d'erreur]
  */
  protected function compareField($field1, $operator, $field2, $error = null)
  {
    $operators = array('==' => 'equal to', '!=' => 'different to',
		       '<=' => 'equal or lower than', '<' => 'lower than',
		       '>=' => 'equal or greater than', '>' => 'greater than');
    
    $bad_field_1 = !isset($this->fields[$field1]);
    $bad_operator = !isset($operators[$operator]);
    $bad_field_2 = !isset($this->fields[$field2]);
    
    if ( $bad_field_1 )
      FormErrors::compareFieldNotExists($field1, $field1, $field2);
    
    if ( $bad_operator )
      FormErrors::compareOperatorNotExists($operator, $field1, $field2);
    
    if ( $bad_field_2 )
      FormErrors::compareFieldNotExists($field2, $field1, $field2);
    
    if ( !$bad_field_1 && !$bad_operator && !$bad_field_2 )
    {
      if ( $error === null )
	$error = sprintf(_("The field %s must be %s %s."),
			 $field1, _($operators[$operator]), $field2);

      $this->compare[] = array($field1, $operator, $field2, $error);
    }
  }
  
  
  
  
  /*
  ** Prépare la validation des données.
  */
  private function cleanData()
  {
    $ret = array_diff_key($this->data, $this->fields);

    if ( !empty($ret))
    {
      $this->checkArrays($ret);
      $this->checkExtraFields($ret);
      
      if ( !$this->allow_extra_fields )
      {
	foreach ( $ret as $field => $value )
	  $this->errors['global'][] = sprintf(_('Extra field %s.'),
					      Security::noHTML($field));
      }
      
      if ( $this->filter_extra )
      {
	foreach ( $ret as $field => $value )
	  unset($this->data[$field]);
      }
    }
  }
  
  
  private function checkArrays(&$ret)
  {
    foreach ( $ret as $name => $arg )
    {
      if ( !is_array($arg) )
	continue;

      if ( $this->checkArraysDeep($arg, $name) )
	unset($ret['name'], $this->data['name']);
    }
  }


  private function checkArraysDeep(&$array, $name, $stat = true)
  {
    foreach ( $array as $key => $arg )
    {
      if ( is_array($arg) )
	$stat = $this->checkArraysDeep($arg, $name.'['.$key.']', $stat);
      else if ( isset($this->fields[$name.'['.$key.']']) &&
		!isset($this->data[$name.'['.$key.']']) )
	$this->data[$name.'['.$key.']'] = $arg;
      else if ( isset($this->fields[$name.'['.$key.']'.'[]']) &&
		!isset($this->data[$name.'['.$key.']'.'[]']) )
	$this->data[$name.'['.$key.']'.'[]'] = $arg;
      else
	$stat = false;
    }
    
    return $stat;
  }

  
  private function checkExtraFields(&$ret)
  {
    if ( !empty($this->extraFields) )
    {
      foreach ( $this->extraFields as $name => $arg )
      {
	foreach ( $arg as $key => $val )
	{
	  if ( is_numeric($key) && isset($ret[$val]) )
	    $k = $val;
	  else if ( isset($ret[$key]) )
	    $k = $key;
	  
	  $this->data[$name][$val] = $ret[$k];
	}
      }
    }
  }
  
  
  
  /*
  ** Ajoute des champs au fomulaire.
  **
  ** @param array fields
  */
  protected function setFields(array $fields)
  {
    if ( empty($fields) )
      FormErrors::noFieldFound();

    foreach ( $fields as $name => $classes )
    {
      if ( $this->checkField($name, $classes[0]) )
      {
	if ( $classes[1] instanceof Validate )
	{
	  $this->fields[$name] = $classes[0];
	  $this->fields[$name]->setValidator($classes[1]);
	}
	else
	  FormErrors::badValidateFormat($name);
      }
    }
  }
  
  
  
  /*
  ** Ajoute un champ au fomulaire.
  **
  ** @param array fields
  */
  protected function setField($name, FormField $Field, Validate $Validator)
  {
    $this->checkField($name, $Field);
    $this->fields[$name] = $Field;
    $this->fields[$name]->setValidator($Validator);
  }
  
  
  
  /*
  ** Vérifie la validité des champs du formulaire.
  **
  ** @param string name
  ** @param FormField fields
  */
  private function checkField($name, $Field)
  {
    if ( $Field instanceof FormField )
    {
      $Field->setName($name);
      
      if ( !$Field->hasId() )
	$Field->setId($name);
      
      $Field->check();

      return true;
    }
    else
      FormErrors::badFieldFormat($name);

    return false;
  }
}

