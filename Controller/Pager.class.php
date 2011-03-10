<?php

/*
**  Gère la pagination.
**
**  @author	Nivl <nivl@free.fr>
**  @started	12/23/2009, 04:35 PM
**  @last	Nivl <nivl@free.fr> 03/07/2010, 01:05 AM
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

class Pager
{
  protected 
    $Url = null,
    $Table = null,
    $current_page = 1,
    $nb_pages = 1,
    $nb_record = 0,
    $limit_start = 0,
    $limit_end = 0,
    $browse_record = false;


  public
    $link = null,
    $var_page = null,
    $var_record = null;


  /*
  ** Constructeur
  **
  ** @param string model [Nom du model]
  ** @param int id [Id de la catégorie à parcourire]
  ** @param bool browse_record [Activer la recherche automatique de page]
  ** @param string var_page [Nom de la variable du numéro de la page]
  ** @param string var_record [Nom de la variable du numéro d'un
                               enregistrement]
  **
  ** @return
  */
  public function __construct($model, $id = 0, $browse_record = false,
			      $var_page = 'page', $var_record = 'rec')
  {
    $this->Url = Url::getInstance();
    $this->link = $this->Url->getUrl();
    $this->Table = Doctrine_Core::getTable($model);
    $this->id = $id;
    $this->var_page = $var_page;
    $this->var_record = $var_record;
    $this->browse_record = $browse_record;
  }
  
  
  public function exec($nb_per_page = 50)
  {
    if ( $this->browse_record && isset($this->Url[$this->var_record]) )
    {
      $i = 1;
      $rec = $this->Url[$this->var_record];
      $records = $this->Table->getAllIdsFrom($this->id);
      
      foreach ( $records as $record )
      {
	if ( $record == $rec )
	  break;	
	++$i;
      }
      $page = floor(($i-1) / $nb_per_page) + 1;
      $this->Url->setVar($this->var_page, $page);
      $this->Url->setVar($this->var_record, $i);
      $this->link = str_replace("/{$this->var_record}:$rec", '', $this->link);
    }
    $this->findInfo($nb_per_page);
  }
  
  
  protected function findInfo($nb_per_page)
  {
    if ( isset($this->Url[$this->var_page]) )
    {
      $page = (int)$this->Url[$this->var_page];
      $this->link = str_replace(
	"/{$this->var_page}:{$this->Url[$this->var_page]}", '', $this->link);
    }
    else
      $page = 1;
    $this->nb_pages = ceil($this->Table->getNbOfRecords($this->id)
			   / $nb_per_page);
    $this->nb_pages = ($this->nb_pages) ? $this->nb_pages : 1;
    if ( $page < 1 )
      $page = 1;
    else if ( $page > $this->nb_pages )
      $page = $this->nb_pages;
    $this->current_page = $page;
    $this->link .= '/'.$this->var_page.':';
    $this->offset = ($this->current_page - 1) * $nb_per_page;
    $this->limit = $nb_per_page;
  }

  
  public function __get($name)
  {
    if ( isset($this->$name) )
      return $this->$name;
  }
  
  
  public function getArray($nb = 3, $separator = '...')
  {
    $ret = array();
    
    for ( $i=1; $i<=$this->nb_pages; ++$i )
    {
      if ( ($i <= $nb) || ($i > $this->nb_pages - $nb) ||
	   (($i <= $this->current_page + $nb) &&
	    ($i >= $this->current_page - $nb)) )
	$ret[] = $i;
      else
      {
	if ( $i > $nb && $i <= $this->current_page - $nb )
	  $i = $this->current_page - $nb - 1;
	elseif ( $i >= $this->current_page + $nb &&
		 $i <= $this->nb_pages - $nb )
	  $i = $this->nb_pages - $nb;
	
	$ret[] = $separator;
      } 
    }
    return $ret;
  }
}

