<?php

/*
**  Affiche du html.
**
**  @package	Ninaca
**  @author	Nivl <nivl@free.fr>
**  @started	12/07/09
**  @last	Nivl <nivl@free.fr> 03/14/2010, 04:30 PM
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

class HTML
{
  const ONE_MINUTE = 60;
  const ONE_HOUR = 3600;
  const TWO_HOURS = 7200;
  
  /*
  ** Ajoute un/des fichier(s) CSS
  **
  ** @param mixed files [Nom des fichiers]
  ** @param string path [Dossier qui contient les fichiers]
  **
  ** @return text
  */
  static public function css($files, $path = null)
  {
    $output = null;

    if ( !is_array($files) )
      $files = array($files);

    if ( $path === null )
      $path = 'globals/views/css';
      
    $path = (URL_REWRITING) ? ROOT.$path : $path;

    foreach ( $files as $file )
    {
      $output .= '<link rel="stylesheet" type="text/css" '.
	'href="'.$path.'/'.$file.'.css" />';
    }

    return $output;
  }



  /*
  ** Ajoute un/des fichier(s) javascript
  **
  ** @param mixed files [Nom des fichiers]
  ** @param string path [Dossier qui contient les fichiers]
  **
  ** @return text
  */
  static public function js($files, $path = null)
  {
    $output = null;

    if ( !is_array($files) )
      $files = array($files);

    if ( $path === null )
      $path = 'globals/views/js';
      
    $path = (URL_REWRITING) ? ROOT.$path : $path;

    foreach ( $files as $file )
    {
      $output .= '<script type="text/javascript" '.
	'src="'.$path.'/'.$file.'.js"></script>';
    }

    return $output;
  }
  
  
  /*
  ** Affiche une image redimensionnée.
  **
  ** @param string url
  ** @param string alt
  ** @param int max_w
  ** @param int max_h
  ** @param string classes
  ** @param string id
  **
  ** @return text
  */
  static public function thumb($url, $alt = null, $max_w = 200, $max_h = 200,
			       $classes = null, $id = null )
  {
    list($w, $h) = getimagesize($url);
    list($new_w, $new_h) = Ftp::resizeImage($w, $h, $max_w, $max_h);
    return self::image($url, $alt, $classes, $id, $new_w, $new_h);
  }
  
  
  /*
  ** Affiche une image
  **
  ** @param string url
  ** @param string alt
  ** @param string classes
  ** @param string id
  ** @param int w
  ** @param int h
  **
  ** @return text
  */
  static public function image($url, $alt = null, $classes =  null,
			       $id = null, $w = 0, $h = 0 )
  {
    $class = (!empty($classes)) ? 'class="'.$classes.'"' : null;
    
    $size = (!empty($h)) ? 'height="'.$h.'" ' : null;
    $size .= (!empty($w)) ? 'width="'.$w.'" ' : $size;
    
    $alt = (empty($alt)) ? _('Image') : $alt;
    $title = 'title="'.$alt.'"';
    $alt = 'alt="'.$alt.'"';

    $url = String::substr($url, 0, 4) === 'http' ? $url : self::validURL($url);

    return "<img $id $class $title $alt $size src=\"$url\" />";
  }
  
  
  
  /*
  ** Retourne un lien valide
  **
  ** @param string url
  **
  ** @return text
  */
  static public function link($url)
  {
    return Misc::UrlPrefix().$url;
  }
  
  
  /*
  ** Retourne un lien valide dans une balise <a>
  **
  ** @param string name
  ** @param string url
  ** @param string classes
  ** @param string id
  **
  ** @return text
  */
  static public function linkTo($name, $url = null, $title = null,
				$classes = null, $id = null )
  {
    $class = (!empty($classes)) ? 'class="'.$classes.'"' : null;
    
    if ( !empty($title) )
      $title = 'title="'.$title.'"';

    if ( !empty($id) )
      $id = 'id="'.$id.'"';
    
    return "<a $title $class $id href=\"".self::validURL($url)."\">$name</a>";
  }


  static public function imageBox($name, $url = null, $title = null,
				  $rel = "lightbox", $classes = null,
				  $id = null)
  {
    $class = (!empty($classes)) ? 'class="'.$classes.'"' : null;
    $rel = 'rel="'.$rel.'"';

    if ( !empty($title) )
      $title = 'title="'.$title.'"';

    if ( !empty($id) )
      $id = 'id="'.$id.'"';
    
    $url = String::substr($url, 0, 4) === 'http' ? $url : self::validURL($url);

    return "<a $title $class $id $rel href=\"$url\">$name</a>";
  }
  
  
  /*
  ** Rend un lien valide
  **
  ** @param string url
  **
  ** @return text
  */
  static public function validURL($url = null/*, $min_arg = 2, $return = '#'*/)
  {
    if ( $url === null )
      return ROOT;
    
    /*if ( count(explode('/', $url)) < $min_arg )
      return $return;
      else*/
    
    return Misc::UrlPrefix().$url;
  }



  /*
  ** Parse le BBCode.
  **
  ** @param string text
  **
  ** @return text
  */
  static public function bbcodeSmiliesParser($text, &$list)
  {
    $img = self::link('globals/views/images/smilies/');
    $in = array("`(?<!\w):\)(?!\w)`iU",  "`(?<!\w):D(?!\w)`iU",
		"`(?<!\w):\@(?!\w)`iU",  "`(?<!\w):/(?!\w)`iU",
		"`(?<!\w):'\((?!\w)`iU", '`(?<!\w):\$(?!\w)`iU',
		"`(?<!\w)-_-(?!\w)`iU",  "`(?<!\w):lol\:(?!\w)`iU",
		"`(?<!\w):o(?!\w)`iU",   "`(?<!\w):\((?!\w)`iU",
		"`(?<!\w):p(?!\w)`iU",   "`(?<!\w);\)(?!\w)`iU",);
    $out = array(
      '<img alt="" class="bbcodeSmiley" src="'.$img.'smile.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'angry.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'biggrin.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'confused.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'cry.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'embarassed.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'fim.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'lol.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'oh.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'sad.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'tongue.png" />',
      '<img alt="" class="bbcodeSmiley" src="'.$img.'wink.png" />');
    $list['in'] = array_merge($list['in'], $in);
    $list['out'] = array_merge($list['out'], $out);
  }
  
  
  
  /*
  ** Parse le BBCode.
  **
  ** @param string text
  **
  ** @return text
  */
  static public function bbcodeParser($text)
  {
    $list = array();
    $list['in'] = array('`\[b\](\s*)(.*?)(\s*)\[/b\]`is',
			'`\[i\](\s*)(.*?)(\s*)\[/i\]`is',
			'`\[u\](\s*)(.*?)(\s*)\[/u\]`is',
			'`\[code\](\s*)(.*?)(\s*)\[/code\]`is',
			'`\[big\](\s*)(.*?)(\s*)\[/big\]`is',
			'`\[tiny\](\s*)(.*?)(\s*)\[/tiny\]`is',
		       );  
    $list['out'] = array('<strong>$2</strong>',
			 '<em>$2</em>',
			 '<u>$2</u>',
			 '<pre><code>$2</code></pre>',
			 '<span class="big">$2</span>',
			 '<span class="tiny">$2</span>',
			);
    $text = self::bbcodeListQuoteParser($text, $list);
    self::bbcodeLinkParser($text, $list);
    self::bbcodeSmiliesParser($text, $list);
    $text = preg_replace($list['in'], $list['out'], $text);
    return $text;
  }


  /*
  ** Parse les list et les quote.
  **
  ** @param string text
  **
  ** @return text
  */
  static protected function bbcodeListQuoteParser($text, &$list)
  {
    $in = '`\[quote(?:\=?)(.*?)\](\s*)(.*?)(\s*)\[/quote\]`is';
    $out = '<div class="quoteAuthor">'._('Quote: ').
      '$1</div><div class="quoteContent">$3</div>';
    while ( preg_match($in, $text) )
      $text = preg_replace($in, $out, $text);
    $in = '`\[list(.*?)\](.*?)\[\*\](.*?)(\[\*\].*?\[\/list\]|\[\/list\])`is';
    $out = '[list$1]$2<li>$3</li>$4';
    while ( preg_match($in, $text) )
      $text = preg_replace($in, $out, $text);
    $list['in'] = array_merge(
      $list['in'],
      array('`\[list\](\s*)(.*?)(\s*)\[\/list\]`is',
	    '`\[list=(\d)\](\s*)(.*?)(\s*)\[\/list\]`is',
	   ));
    $list['out'] = array_merge(
      $list['out'],
      array('<ul>$2</ul>',
	    '<ol>$3</ol>',
	   ));
    return $text;
  }


  /*
  ** Parse les liens et images.
  **
  ** @param string text
  **
  ** @return text
  */
  static protected function bbcodeLinkParser($text, &$list)
  {
    $list['in'] = array_merge(
      $list['in'],
      array('`([^=\]])((?:https?|ftp)://\S+[[:alnum:]]/?)`si',
	    '`([^=\]])((?<!//)(www\.\S+[[:alnum:]]/?))`si',
	    '`\[url(?:\=?)(.*?)\](.*?)\[/url\]`is',
	    '`\[img(.*?)\](.*?)\[/img\]`is',
	   ));  
    $list['out'] = array_merge(
      $list['out'],
      array('<a href="$2">$2</a>',
	    '<a href="http://$2">$2</a>',
	    '<a href="$1">$2</a>',
	    '<img $1 src="$2" />',
	   ));
  }
  

  /*
  ** Affiche les boutons de BBCode
  **
  ** @param string textarea [nom du textarea]
  ** @param string preview [class de la zone de preview]
  ** @param string bbcode [list des bbcodes]
  **
  ** @return text
  */
  static public function bbcode($textarea, $preview,array $bbcodes = array())
  {
    $list = array('bold','italic','underline','link','quote','code','image',
		  'usize','dsize','nlist','blist','litem','back','forward');
    $smilies = array('smile','angry','biggrin','confused','cry','embarassed',
		     'fim','lol','oh','sad','tongue','wink');
    $bbcodes = array_intersect($list, $bbcodes);
    if ( empty($bbcodes) )
      $bbcodes = $list;
    $ret = self::bbcodeJs($textarea, $preview, $bbcodes, $smilies);
    $ret .= '<div class="bbcodeButtons">';
    foreach ( $bbcodes as $bbcode )
      $ret .= "<div class=\"bbcodeButton $bbcode\" title=\"$bbcode\"></div>";
    $ret .= '</div><div class="bbcodeSmilies">';
    foreach ( $smilies as $name )
      $ret .= "<img class=\"bbcodeSmiley $name\" title=\"$name\" src=\"".
      self::link("globals/views/images/smilies/$name.png")."\" />";
    return $ret.'</div>';
  }
  
  
  
  /*
  ** Retourne la partie JS des formulaires.
  **
  ** @param string textarea [nom du textarea]
  ** @param string preview [class de la zone de preview]
  ** @param string bbcode [list des bbcodes]
  **
  ** @return text
  */
  static private function bbcodeJs($textarea, $preview, $bbcodes, $smilies)
  {
    $ret = '<script type="text/javascript">'.
      "\$(function(){\$('textarea[name=$textarea]').bbcodeeditor({";
    foreach ( $smilies as $smiley )
      $ret .= $smiley.':$(\'.'.$smiley.'\'),';
    foreach ( $bbcodes as $bbcode )
      $ret .= $bbcode.':$(\'.'.$bbcode.'\'),';
    if (in_array('back', $bbcodes))
      $ret .= "back_disable:'bbcodeButton back_disable',";
      if (in_array('forward',$bbcodes))
      $ret .= "forward_disable:'bbcodeButton forward_disable',";
    $ret .= 'exit_warning:true';
    if ($preview)
      $ret .= ",preview:\$('.$preview')";
    return $ret.'});});</script>';
  }

  
  /*
  ** Retourne une pagination
  **
  ** @param Pager pager
  ** @param int nb [Nombre de page à regrouper]
  ** @param int separator [Séparateur de groupe]
  */
  static public function pagination(Pager $Pager, $nb = 3, $separator = '...')
  {
    $ret = null;
    
    if ( $Pager->current_page <> 1 )
      $ret = Html::linkTo(_('Previous'), $Pager->link.($Pager->current_page-1),
			  array('prev')).' ';
    $pages = $Pager->getArray($nb, $separator);
    foreach ( $pages as $page )
    {
      if ( $page === $Pager->current_page )
	$ret .= '<span class="current">'.$page.'</span>';
      else if ( $page <> $separator )
	$ret .= Html::LinkTo($page, $Pager->link.$page, array('page'));
      else
	$ret .= $page;
      
      $ret .= ' ';
    }
    if ( $Pager->current_page <> $Pager->nb_pages )
      $ret .=' '.Html::linkTo(_('Next'), $Pager->link.($Pager->current_page+1),
			      array('next'));
    return $ret;
  }


  /*
  ** Retourne une pagination
  **
  ** @param string link
  ** @param int total
  ** @param int nb_per_page
  ** @param int nb [Nombre de page à regrouper]
  ** @param int separator [Séparateur de groupe]
  */
  static public function listOfPages($link, $total, $nb_per_page = 50,
				     $var_name = 'page', $nb = 3,
				     $separator = '...')
  {
    $ret = null;
    $nb_pages = ceil($total / $nb_per_page);
    $nb_pages = ($nb_pages) ? $nb_pages : 1;
    $link .= '/'.$var_name.':';

    for ( $i=1; $i<=$nb_pages; ++$i )
    {
      if ( $i <> $separator )
	$ret .= Html::LinkTo($i, $link.$i, array('page'));
      else
	$ret .= $i;
      
      $ret .= ' ';
    }
    
    return $ret;
  }


  static public function time($time, $format = 12, $current = 0)
  {
    $current = ($current) ? $current : time();
    $diff = $current - $time;
    
    if ( $diff < self::TWO_HOURS )
    {
      if ( $diff < self::ONE_HOUR )
      {
	$min = floor($diff / self::ONE_MINUTE);
	
	if ( $min < 1 )
	  return __('%d seconde ago', '%d secondes ago', $diff);
	else
	  return __('%d minute ago', '%d minutes ago', $min);
      }
      else
      {
	$min = floor(($diff - self::ONE_HOUR) / self::ONE_MINUTE);
	return __('1 hour and %d minute ago','1 hour and %d minutes ago',$min);
      }
    }
    
    return ( $format == 24 ) ? strftime('%T',$time) : strftime('%r',$time);
  }
  
  
  static public function date($date, $current = 0, $format = 12)
  {
    $current = ($current) ? $current : time();
    $timeDate = getdate($date);
    $now = getdate($current);
    $time = self::time($date, $format, $current);

    if ( ($current - $date) < self::TWO_HOURS )
      return $time;
    else if ( $now['year'] === $timeDate['year'] )
    {
      if ( $now['yday'] === $timeDate['yday'] )
	return sprintf(_('Today at %s'), $time);
      else if ( $now['yday'] - $timeDate['yday'] == 1 )      
	return sprintf(_('Yesterday at %s'), $time);
    }

    return sprintf(_('%s %s %d %s at %s'), _(strftime('%A', $date)),
		   _(strftime('%B', $date)), strftime('%d', $date),
		   strftime('%Y', $date), $time);
  }
  
  
  static public function displayTracer(Tracer $Tracer, $separator = ' » ',
				       $protect = true)
  {
    $traces = $Tracer->getTraces();
    
    if ( !empty($traces) )
    {
      $output = '';
    
      foreach ( $traces as $trace )
      {
	$name = ($protect) ? Security::noHtml($trace['name']) : $trace['name'];
	
	if ( $trace['link'] )
	  $output .= Html::LinkTo($name, $trace['link']).$separator;
	else
	  $output .= $name.$separator;
      }
      
      return String::substr($output, 0, -(String::length($separator)));
    }
    else
      return null;
  }
  
  // Alias
  
  static public function a($name, $url = null, $title = null, $classes = null,
			   $id = null)
  {
    return self::linkTo($name, $url, $title, $classes, $id);
  }
  
  static public function img($url, $alt = null, $classes = null,
			     $id = null, $w = 0, $h = 0 )
  {
    return self::image($url, $alt, $classes, $id, $w, $h);
  }
}




