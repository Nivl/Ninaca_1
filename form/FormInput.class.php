<?php

/*
**  Classe mère qui gère les champs input des formulaires.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	08/13/2009, 12:51 AM
**  @last	Nivl <nivl@free.fr> 03/28/2010, 01:12 AM
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

abstract class FormInput extends FormField
{
  protected $label = null;
  protected $help = null;
  
  public function __construct(array $options = array(),
			      array $classes = array())
  {
    $this->label = (isset($options['label'])) ? $options['label'] : null;
    
    parent::__construct($options, $classes);
  }


  public function check()
  {
    if ( !self::hasLabel() )
    {
      $label = String::ucFirst(str_replace('_', ' ', $this->name));
      self::setLabel($label);
    }
  }
  
  
  
  /*
  ** Retourne le champ courant avec son label et ses erreurs.
  ** 
  ** @return string
  */
  public function display()
  {
    return "<tr>\n<th>".$this->getLabel().$this->getHelp()."</th>\n".
      "<td>".$this->getErrors().$this->getField()."</td>\n</tr>";
  }
  
  
  /*
  ** Vérifie si le champ contient un label.
  **
  ** @return bool
  */
  public function hasLabel()
  {
    return !empty($this->label);
  }
  
  
  
  /*
  ** Définie un label pour le champ courant.
  ** 
  ** @param string label
  **
  ** @return FormField [Référence sur le champ courant]
  */
  public function setLabel($label)
  {
    $this->label = _($label);
    return $this;
  }
  

  /*
  ** Retourne le label du champ courant.
  ** 
  ** @return string
  */
  public function getLabel()
  {
    return '<label for="'.$this->id.'">'.$this->label.'</label>';
  }
  
  
  /*
  ** Vérifie si le champ contient une aide.
  **
  ** @return bool
  */
  public function hasHelp()
  {
    return !empty($this->help);
  }
  
  
  
  /*
  ** Définie une aide pour le champ courant.
  ** 
  ** @param string help
  **
  ** @return FormField [Référence sur le champ courant]
  */
  public function setHelp($help)
  {
    $this->help = _($help);
    return $this;
  }

  
  /*
  ** Retourne l'aide du champ courant.
  ** 
  ** @return string
  */
  public function getHelp()
  {
    return '<span>'.$this->help.'</span>';
  }
}


