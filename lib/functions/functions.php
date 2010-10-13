<?


/*******************************************************************************
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/

Software distributed under the License is distributed on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations
under the License.

The Original Code is (C) 2004-2010 Blest AS.

The Initial Developer of the Original Code is Blest AS.
Portions created by Blest AS are Copyright (C) 2004-2010
Blest AS. All Rights Reserved.

Contributor(s): Hogne Titlestad, Thomas Wollburg, Inge Jørgensen, Ola Jensen, 
Rune Nilssen
*******************************************************************************/



class String
{
	var $string;

	function String ( $string = "" )
	{
		$this->string = $string;
	}

	function fromCamelCase ( $newstring = false )
	{
		if ( !( $newstring = ( $newstring ) ? $newstring : $this->string) ) return false;   // try to assign $newstring, return false if not possible

		$string = substr( trim( $newstring ), 1 );                                          // don't touch the first character
		$string = preg_replace ( "/([A-Z])/e", "' '.strtolower($1)", $string );                               // replace capitals
		$string = $newstring[0] . $string;                                                  // reappend the first character
		if ( is_object ( $this ) ) $this->string = $string;                                 // set $this->string if not called statically
		return $string;
	}

}


// Get base url with localized prefix (http://url.com/lang/)
function getLocalizedBaseUrl ( )
{
	global $Session;
	if ( $GLOBALS[ 'LocalizedBaseUrl' ] ) 
		return $GLOBALS[ 'LocalizedBaseUrl' ];
	$db =& dbObject::globalValue ( 'database' );
	if ( $activator = $db->fetchRow ( 'SELECT * FROM Languages WHERE UrlActivator != ""' ) )
	{
		$activator = true;
	}
	list ( $langs ) = $db->fetchRow ( 'SELECT COUNT(*) FROM Languages' );
	if ( $langs > 1 && !$activator )
	{
		$GLOBALS[ 'LocalizedBaseUrl' ] = BASE_URL . $Session->LanguageCode . '/';
	}
	else 
	{
		$GLOBALS[ 'LocalizedBaseUrl' ] = BASE_URL;
	}
	return $GLOBALS[ 'LocalizedBaseUrl' ];
}


function textToUrl ( $text )
{
  //$text = utf8_encode ( $text );
  $text = str_replace ( "Æ", "AE", $text );
  $text = str_replace ( "Ø", "OE", $text );
  $text = str_replace ( "Å", "AA", $text );
  $text = str_replace ( "æ", "ae", $text );
  $text = str_replace ( "ø", "oe", $text );
  $text = str_replace ( "å", "aa", $text );
  $text = strtolower ( $text );
  //$text = str_replace ( " ", "_", $text );
  $text = preg_replace ( "/[^\w\d\s_-]+/", "", $text );
  $text = preg_replace ( "/[\s+]+/", "_", $text );
  return $text;
}

function arrayToObject ( $array )
{
	$object = new object ();
	foreach ( $array as $key => $value )
		$object->$key = $value;
	return $object;
}


function filesizeToHuman ( $bytes )
{
  if ( $bytes >= pow ( 2, 40 ) ) 
  {
    $return = round ( $bytes / pow ( 1024, 4 ), 2 );
    $suffix = "tb";
  }
  else if ( $bytes >= pow ( 2, 30 ) ) 
  {
    $return = round ( $bytes / pow ( 1024, 3 ), 2 );
    $suffix = "gb";
  } 
  else if ($bytes >= pow ( 2, 20 ) ) 
  {
    $return = round ( $bytes / pow ( 1024, 2 ), 2 );
    $suffix = "mb";
  }
  else if ( $bytes >= pow ( 2, 10 ) ) 
  {
    $return = round ( $bytes / pow ( 1024, 1 ), 0 );
    $suffix = "kb";
  } 
  else 
  {
    $return = $bytes;
    $suffix = "b";
  }
  
  if ( $return == 1 ) return ( $return . " $suffix" );
  return ( $return . $suffix );
}


function println ( $var )
{
  print ( $var . "\n" );
}

function getln ( $type = "unix" )
{
	if ( $type == "windows" )
		return "\n\r";
	else if ( $type == "r" )
		return "\r";
	return "\n";
}

function gettab ( )
{
	return "\t";
}

function removeln ( $var )
{
	$var = str_replace (
		array ( "\n\r", "\r\n", "\n", "\r", chr( 10 ), chr ( 11 ) ),
		"",
		$var
	);
	return trim ( $var );
}


function debugValue ( $var )
{
  print   ( "<pre>" );
  print_r ( $var );
  print   ( "</pre>" );
}


function trimText ( $text, $length )
{
	if ( strlen ( $text ) > $length )
	{
		$text = substr ( $text, 0, $length ) . " ..";
	} 
	return $text;
}


function getMimeType ( $filename ) {
	global $mimes;
	
	if ( empty ( $mimes ) )
	{
		$mimefile = BASE_DIR . '/mime.types';
		if ( !is_file ( $mimefile ) || !is_readable ( $mimefile ) ) 
		  return false;
		$types = array ();
		$fp = fopen ( $mimefile, "r" );
		while ( false != ( $line = fgets ( $fp,4096 ) ) ) 
		{
			if ( !preg_match ( "/^\s*(?!#)\s*(\S+)\s+(?=\S)(.+)/", $line, $match ) ) continue;
			$tmp = preg_split ( "/\s/", trim ( $match [ 2 ] ) );
			foreach ( $tmp as $type ) $types [ strtolower ( $type ) ] = $match [ 1 ];
		}
		fclose ($fp);
		$mimes = $types;
	}
		
	preg_match ( "/.(\w*)$/", $filename, $extension );
	$extension = $extension [ 1 ];

	if ( isset ( $mimes [ $extension ] ) )
		return $mimes [ $extension ];
	else
		return false;
}


function glob2preg ( $str ) 
{ 
  return '/^' . str_replace ( '\\*', '.*', preg_quote ( $str ) ) . '$/'; 
} 

function fileMatchesGlob ( $file, $globArray ) 
{ 
  $globArray = array_map ( 'glob2preg', $globArray ); 
  $matched = false; 
  foreach ( $globArray as $glob ) 
  { 
    if ( preg_match ( strtolower ( $glob ), strtolower ( $file ) ) ) 
    { 
      $matched = true; 
      break; 
    } 
  } 
  return $matched; 
} 

function _TpParseText ( $str ) 
{
	return text2html ( $str );
}

function text2html ( $str, $method  )
{		
	if ( $method == "markdown" )
	{
		//$str = str_replace ( "\n", "  \n", $str );		
		$str = markdown ( $str );
		$str = preg_replace_callback ( 
			"/<p>([\w\W]+)\<\/p>/U",
			 create_function ( '$matches', 'return str_replace ( "\n", "<br />\n", $matches[0] ); ' ),
			 $str
		);
		return $str;
	}
	else
	{
		$str = preg_replace ( "/\\*\\*([\w\W]*)\\*\\*/", "<strong>$1</strong>" ,$str ); 	
		$str = preg_replace ( "/\\*([\w\W]*)\\*/", "<em>$1</em>" ,$str ); 	
		$str = preg_replace ( "/\\_([\w\W]*)\\_/", "<u>$1</u>" ,$str ); 	
		$str = str_replace ( "\n", " <br />\n", $str );		
		return $str;
	}
}

function pulldownsToDate ( $array, $prefix = false )
{
	$day   = $array [ $prefix."Day" ];
	$month = $array [ $prefix."Month" ];
	$year  = $array [ $prefix."Year" ];
	return mktime (0, 0, 0, $month, $day, $year );
}

function dateToPulldowns ( $inputfield, $date=false, $showtime=false )
{
	if ( strstr ( $inputfield, ':' ) )
		$inputfield = str_replace ( Array ( '-', '_' ), '', texttourl ( 'field_' . $inputfield ) );
	
	$options = dateToPulldownOptions ( $date );
	
	// To take the result
	if ( $inputfield )
	{
		$return .= "<input type=\"hidden\" name=\"$inputfield\" id=\"{$inputfield}_id\" value=\"" . ( $date ? $date : '' ) . "\" />";
		$ch = "document.getElementById('{$inputfield}_id').value";
	}
	
	$fields = "document.getElementById ( '{$inputfield}_year' ).value + '-' +" .
						"document.getElementById ( '{$inputfield}_month' ).value + '-' +" .
						"document.getElementById ( '{$inputfield}_day' ).value";
	if ( $showtime )
	{
		$fields .= " + ' ' + document.getElementById ( '{$inputfield}_hour' ).value + ':' +" .
							 "document.getElementById ( '{$inputfield}_minute' ).value + ':00'";
	}
	else $fields .= "";
	
	// Start table
	$return .= "<table class=\"Layout\"><tr><td>";
	$return .= "<strong>Dato:</strong> ";
	$return .= "</td><td>";
	
	// Days
	$return .= "<select onchange=\"$ch = ( $fields )\" id=\"{$inputfield}_day\">\n";
	$return .= $options [ "day" ];
	$return .= "</select>";		
	// Months
	$return .= "<select onchange=\"$ch = ( $fields )\" id=\"{$inputfield}_month\">\n";
	$return .= $options [ "month" ];
	$return .= "</select>";		
	// Years
	$return .= "<select onchange=\"$ch = ( $fields )\" id=\"{$inputfield}_year\">\n";
	$return .= $options [ "year" ];
	$return .= "</select>";		
	$return .= "</td></tr>";
	
	if ( $showtime )
	{
		$return .= "<tr><td>";
		$return .= "<strong>Klokke:</strong> ";
		$return .= "</td><td>";
		$return .= "<select onchange=\"$ch = ( $fields )\" id=\"{$inputfield}_hour\">\n";
		$return .= $options[ "hour" ];
		$return .= "</select>\n";
		$return .= "<select onchange=\"$ch = ( $fields )\" id=\"{$inputfield}_minute\">\n";
		$return .= $options[ "minute" ];
		$return .= "</select>\n";
		$return .= "</td></tr>";
	}
	
	// End table
	$return .= "</table>";

	return $return;
}

function dateToPulldownOptions ( $date=false )
{
	if ( !$date ) $date = time ();
	if ( is_string ( $date ) ) $date = strtotime ( $date );
	
	$months = array (
		1  => "Januar",
		2  => "Februar",
		3  => "Mars",
		4  => "April",
		5  => "Mai",
		6  => "Juni",
		7  => "Juli",
		8  => "August",
		9  => "September",
		10 => "Oktober",
		11 => "November",
		12 => "Desember",
	);
	$year  = date ( "Y", $date );
	$month = date ( "n", $date );
	$day   = date ( "j", $date );
	$hour   = date ( "H", $date );
	$minute   = date ( "i", $date );
	$return = array ();
	
	// Days
	for ( $i = 1; $i <= 31; $i++ )
	{
		if ( $i == $day )
			$selected = " selected=\"selected\"";
		else
			$selected = "";
		$ii = str_pad ( $i, 2, "0", STR_PAD_LEFT );
		$return[ "day" ] .= "<option value=\"$i\"$selected>$ii</option>\n";
	}

	// Month
	for ( $i = 1; $i <= 12; $i++ )
	{
		if ( $i == $month )
			$selected = " selected=\"selected\"";
		else
			$selected = "";
		$return[ "month" ] .= "<option value=\"$i\"$selected>{$months[$i]}</option>\n";
	}

	// Year
	for ( $i = 2010; $i >= 1980; $i-- )
	{
		if ( $i == $year )
			$selected = " selected=\"selected\"";
		else
			$selected = "";
		$return[ "year" ] .= "<option value=\"$i\"$selected>$i</option>\n";
	}
	
	// Hour
	for ( $i = 0; $i <= 23; $i++ )
	{
		if ( $i == $hour )
			$selected = " selected=\"selected\"";
		else
			$selected = "";
		$ii = str_pad ( $i, 2, "0", STR_PAD_LEFT );
		$return[ "hour" ] .= "<option value=\"$i\"$selected>$ii</option>\n";
	}
	
	// Minute
	for ( $i = 0; $i <= 59; $i++ )
	{
		if ( $i == $minute )
			$selected = " selected=\"selected\"";
		else
			$selected = "";
		$ii = str_pad ( $i, 2, "0", STR_PAD_LEFT );
		$return[ "minute" ] .= "<option value=\"$i\"$selected>$ii</option>\n";
	}

	return $return;
}

// VAR SAFE - REMEMBER TO SYNC CODE WITH JS EQUIVALENT!

// Read a string that has been made safe for transport
function readVarSafe ( $var )
{
	// Tab
	$var = str_replace ( "_[taB]_", "\t", $var );
	$var = str_replace ( "_[taB2]_", "_[taB]_", $var ); // <- unlikely but needed
	
	// Line ending		
	$var = str_replace ( "_[newlinE]_", "\n", $var );
	$var = str_replace ( "_[newlinE2]_", "_[newlinE]_", $var ); // <- unlikely but needed
	
	return $var;
}

// Write a string in a way that it's safe to transport
function writeVarSafe ( $var )
{
	// Tab
	$var = str_replace ( "_[taB]_", "_[taB2]_", $var ); // <- unlikely but needed (precaution)
	$var = str_replace ( "\t", "_[taB]_", $var );
	// Line ending
	$var = str_replace ( "_[newlinE]_", "_[newlinE2]_", $var ); // <- unlikely but needed (precaution)
	$var = str_replace ( "\n", "_[newlinE]_", $var );
	
	return $var;
}

function dateFormat ( $date, $format, $lang = "default" )
{
	switch ( $lang )
	{
		case "english":
			$months = Array (
				"January", "February", "March", "April", "May", "June", "July",
				"August", "September", "October", "November", "Desember"
			);
			$days = Array (
				"Monday", "Thuesday", "Wednesday", "Thursday", "Friday",
				"Saturday", "Sunday"
			);
			break;
		default:
			$months = Array ( 
				"Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli",
				"August", "September", "Oktober", "November", "Desember"
			);		 
			$days = Array (
				"Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"
			);
			break;
	}
	
	switch ( $format )
	{
		case "list":
			$time = strtotime ( $date );
			return ( date ( "j. ", $time ) . 
				$months[ date ( "n", $time ) - 1 ] . " " . 
				date ( "Y", $time ) );
			break;
		default:
			return $date;
			break;
	}
}
	
/* Write a simple timestamped debug text to a /tmp location */
function writeDebugText ( $filename, $text )
{
	if ( $filename )
	{
		$fp = fopen ( "/tmp/$filename", "a+" );
		fwrite ( $fp, date ( "Y-m-d H:i:s\n-----------------\n" ) . $text . "\n\n" );
		fclose ( $fp );
	}
}

/**
	*  Return an array with the color components (r,g,b) from
	*	an integer value
	*	@param integer
	*  @return array
	*
*/
function colorFromInt ( $int )
{
	return Array (
		( $int >> 16 ) & 0xff,
		( $int >> 8 ) & 0xff,
		( $int ) & 0xff 
	);
} 

/**
	*  Return a css color "rgb(n,n,n)" from
	*	an integer value
	*	@param integer
	*  @return string
	*
**/
function cssColorFromInt ( $int )
{
	$ar = colorFromInt ( $int );
	return "rgb(" . $ar[ 0 ] . "," . $ar[ 1 ] . "," . $ar[ 2 ] . ")";
}

/**
	*  Return a int color from color values n n n 
	*  @param char $n $n $n
	*  @return integer
	*
**/ 
function colorToInt ( $r, $g, $b )
{
	$int = ( $r << 16 ) | ( $g << 8 ) | ( $b );
	return $int;
}

function textPad ( $text, $length, $fill, $pad = STR_PAD_LEFT )
{
	$text = utf8_decode ( $text );
	
	$len = strlen ( $text );
	$fillen = strlen ( $fill );
	$out = "";
	
	if ( $pad == STR_PAD_RIGHT )
	{
		for ( $a = 0; $a < $length; $a++ )
		{
			$b = $a % $fillen;
			if ( $a < $len )
				$out .= $text{$a};
			else $out .= $fill{$b};
		}
	}
	else if ( $pad == STR_PAD_LEFT )
	{
		for ( $a = $length - 1; $a >= 0; $a-- )
		{
			$b = $a % $fillen;					// rotate in fill
			$c = $len - $a;
			if ( $a > $len )
				$out .= $fill{$b};
			else
				$out .= $text{$c};
		}
	}
	
	return ( utf8_encode ( $out ) );
}

function uniqueFilename ( $filename )
{
	$filename = safeFilename ( $filename );
	list ( $name, $ext ) = explode ( ".", $filename );
	$filename = $name . rand ( 0, 9 ) . "." . $ext;
	return $filename;
}

function safeFilename ( $filename )
{
	$filename = str_replace ( " ", "_", $filename );
	$filename = str_replace ( array ( 'æ', 'ø', 'å', 'Æ', 'Ø', 'Å' ), array ( 'ae', 'oe', 'aa', 'AE', 'OE', 'AA' ), $filename );
	$filename = str_replace ( "/", "_", $filename );
	$filename = str_replace ( "!", "_", $filename );
	$filename = str_replace ( "#", "_", $filename );
	$filename = str_replace ( "&", "_", $filename );
	$filename = str_replace ( "?", "_", $filename );
	$filename = str_replace ( "'", "_", $filename );
	$filename = str_replace ( "\"", "_", $filename );
	$filename = str_replace ( "|", "_", $filename );
	return $filename;
}

/**
 * Browser specifics 
**/
function isIE ( )
{
	if ( $GLOBALS[ "isIE" ] ) return true;
	if ( strpos ( $_SERVER[ "HTTP_USER_AGENT" ], "MSIE" ) > 0 )
	{
		$GLOBALS[ "isIE" ] = true;
		return true;
	}
	return false;
}

function isSafari ( )
{
	global $isSafari;
	if ( $isSafari ) return true;
	if ( strpos ( $_SERVER[ "HTTP_USER_AGENT" ], "Safari" ) > 0 )
	{
		$GLOBALS[ "isSafari" ] = true;
		return true;
	}
	return false;
}

function isKonqueror ( )
{
	global $isKonqueror;
	if ( $isKonqueror ) return true;
	if ( strpos ( $_SERVER[ "HTTP_USER_AGENT" ], "Konqueror" ) > 0 )
	{
		$GLOBALS[ "isKonqueror" ] = true;
		return true;
	}
	return false;
}

function i18nAddLocalePath ( $path )
{
	global $i18n_translation_paths;
	if ( !$i18n_translation_paths )
		$GLOBALS[ "i18n_translation_paths" ] = Array ( );
	$GLOBALS[ "i18n_translation_paths" ][] = $path;
	$GLOBALS[ "locale_rescan" ] = true;
}

function i18n ( $word, $lang = false, $path = false, $admin = false )
{
	global $locale_rescan, $i18n_translation_paths;
	
	if ( ARENAMODE == 'admin' ) $admin = true;
	
	if ( !$lang && is_object ( $GLOBALS[ "Session" ] ) ) 
		$lang = $GLOBALS[ "Session" ]->LanguageCode;
	else if ( !$lang ) return $word;
	if ( is_numeric ( $word ) ) return $word; 
	
	$trans = Array ( );
	if ( empty ( $GLOBALS[ "locale_$lang" ] ) || in_array ( 'locale_rescan', $GLOBALS ) )
	{
		if ( $path )
		{
			$paths = Array ( $path );
		}
		else
		{
			$paths = array ( $admin ? 'lib/locale' : false );
			if ( $i18n_translation_paths )
				$paths = array_merge ( $i18n_translation_paths, $paths );	
			if ( !$admin )
				$paths = array_merge ( Array ( 'upload', 'locale' ), $paths );
			if ( $locale_rescan )
				$GLOBALS[ 'locale_rescan' ] = false;
		}
		
		$out = '';		
		foreach ( $paths as $path )
		{
			if ( file_exists ( "$path/$lang.locale" ) )
			{
				if ( filesize ( "$path/$lang.locale" ) )
				{
					$fp = fopen ( "$path/$lang.locale", 'r' );
					$string = fread ( $fp, filesize ( "$path/$lang.locale" ) );
					fclose ( $fp );
					$out .= $string;
				}
			}
		}
		$out = str_replace ( "\\\n", "", $out );
		$out = explode ( "\n", $out );
		foreach ( $out as $v )
		{
			if ( !strlen ( $v ) )
				continue;
			if ( $v{0} != "#" && $v{0} != "\n" && $v{0} != "" )
			{
				list ( $wrd, $translation ) = explode ( ":", $v );
				$wrd = trim ( $wrd );
				if ( !array_key_exists ( $wrd, $trans ) ) $trans[ $wrd ] = trim ( $translation );
			}
		}
		$GLOBALS[ "locale_$lang" ] = $trans;
	}
	else
	{
		$trans = $GLOBALS[ "locale_$lang" ];
	}
	$result = $trans[ $word ] ? $trans[ $word ] : $word;
	$GLOBALS[ 'translations' ][ $word ] = $trans[ $word ] ? $trans[ $word ] : $word;
	return $result;
}

function i18nAdmin ( $word, $lang, $path = false )
{
	return i18n ( $word, $lang, $path, true );
}

function i18nDate ( $date, $lang = false, $path = false )
{
	return $date;
}

function i18nGetContentLanguage ( &$content )
{
	if ( $content->LanguageCode ) 
		return $content->LanguageCode;
	else
	{
		$l = new dbObject ( "Languages" );
		$l->load ( $content->Language );
		$content->LanguageCode = $l->Name;
	}
	return $content->LanguageCode;
}

function getCountries ( $letter = false )
{
	$countries = file_get_contents ( BASE_DIR . '/lib/include/countries.txt' );
	$countries = explode ( "\n", $countries );
	if ( $letter )
	{
		$out = Array ( );
		$l = strtolower ( $letter );
		foreach ( $countries as $c )
		{
			if ( strtolower ( $c{0} ) == $l )
				$out[] = $c;
		}
		return $out;
	}
	return $countries;
}


/**
 * Executes the webmodule of the content object, unless 
 * another webmodule is forced through $forceModule
**/
function executeWebModule ( $content, $forceModule = false )
{
	i18nAddLocalePath ( 'lib/locale/' );
	// Add downloads automagically to Felt1
	if ( strstr ( $content->ContentGroups, 'Felt1' ) )
	{
		if ( $rows = $GLOBALS[ 'database' ]->fetchObjectRows ( '
			SELECT f.* FROM ObjectConnection o, File f 
			WHERE 
				o.ObjectType="ContentElement" AND o.ObjectID=' . $content->ID . ' AND 
				o.Label="PageAttachment" AND f.ID = o.ConnectedObjectID AND o.ConnectedObjectType="File"
		' ) )
		{
			$str .= '
								<div id="PageAttachments">';
			$str .= '
									<h2 class="Downloads">' . i18n ( 'Downloads' ) . '</h2>';
			foreach ( $rows as $row )
			{
				$str .= '
									<div class="Download ClearBoth">
										<div class="Icon_' . ( strtolower( substr ( $row->Filename, -3, 3 ) ) ) . '">
											<div class="Name FloatLeft">
												<a href="upload/' . $row->Filename . '">' . $row->Title . '</a>
											</div>
											<div class="Filesize FloatLeft">
												(' . filesizeToHuman ( filesize ( BASE_DIR . '/upload/' . $row->Filename ) ) . ')
											</div>
										</div>
									</div>
				';
			}
			$str .= '
								</div>';
			
			$dummy = new Dummy ();
			$dummy->_isLoaded = true;
			$dummy->ID = '1';
			$dummy->Type = 'text';
			$dummy->DataText = $str;
			$dummy->ContentGroup = 'Felt1';
			$dummy->IsVisible = true;
			$content->_extra_Downloads = $dummy;
			$content->_field_Downloads = $dummy;
			$content->Downloads = $str;
		}
	}
	
	$contentType = $forceModule ? $forceModule : $content->ContentType;
	switch ( $contentType )
	{
		case '':
		case 'text':
		case NULL:
			$str = '
							' . ( $content->Title ? ( '<h1 id="PageTitle">' . $content->Title . '</h1>' ) : '' ) . '
							' . ( $content->Intro ? ( '<div id="Intro">' . $content->ProcessText ( $content->Intro ) . '</div>' ) : '' ) . '
							' . ( $content->Body ? ( '<div id="Body">' . $content->ProcessText ( $content->Body ) . '</div>' ) : '' ) . '
			';
			if ( defined ( 'WEBMODULE_CONTENTGROUP' ) )
				return preg_replace ( '/(\<div id\=\"' . WEBMODULE_CONTENTGROUP . '\"\>)/', '$1' . $str, $content->renderExtraFields ( ) );
			return '
							<div id="WebModule__">
							' . $str . '
							</div>
							' . $content->renderExtraFields ( );
		default:
			// Get module config
			$contentGroup = '';
			// Extensions
			if ( $contentType == 'extensions' )
			{
				$config = new Dummy ( );
				if ( $_REQUEST[ 'ue' ] )
					$contentType = $_REQUEST[ 'ue' ];
				else
				{
					$conf = explode ( "\n", $content->Intro );
					foreach ( $conf as $c )
					{
						$c = explode ( "\t", $c );
						if ( $c[0] )
							$config->$c[0] = $c[1];
						switch ( trim ( $c[0] ) )
						{
							case 'ExtensionName':
								$contentType = trim ( $c[1] );
								break;
							case 'ExtensionContentGroup':
								$contentGroup = trim ( $c[1] );
								break;
						}
					}
				}
				if ( file_exists ( "extensions/$contentType/webmodule.php" ) )
				{
					include ( "extensions/$contentType/webmodule.php" );
				}
			}
			// Builtin modules
			else
			{
				if ( file_exists ( "web/modules/$contentType/module.php" ) )
					include ( "web/modules/$contentType/module.php" );
			}
			$grp = defined ( 'WEBMODULE_CONTENTGROUP' ) ? WEBMODULE_CONTENTGROUP : '';
			if ( $contentGroup ) $grp = $contentGroup;
			if ( $grp ) $module = preg_replace ( '/(\<div id\=\"' . $grp . '\"\>)/', '$1' . $module, $content->renderExtraFields ( ) );
			else
			{
				$module = '
							<div id="WebModule__">
							' . $module . '
							</div>
							' . $content->renderExtraFields ( );
			}				
			return $module;
	}
}

function parseTabbedConfig ( $searchkey, $config )
{
	$encstring = explode ( "\n", $config );
	foreach ( $encstring as $pair )
	{
		list ( $key, $value ) = explode ( "\t", $pair );
		if ( $key == $searchkey ) return $value;
	}
}

function unfoldTabbedConfig ( $config )
{
	$conf = new Dummy ( );
	$encstring = explode ( "\n", $config );
	foreach ( $encstring as $pair )
	{
		list ( $key, $value ) = explode ( "\t", $pair );
		if ( $key = trim ( $key ) )
		{
			$value = trim ( $value );
			$conf->$key = $value;
		}
	}
	return $conf;
}

if ( !defined ( 'PHP_MAILER_INCLUDED' ) ) 
{
	if ( file_exists ( 'lib/3rdparty' ) )
	{
		require_once( "lib/3rdparty/phpmailer/class.phpmailer.php" );
	}
}
	
function mail_ ( $to, $subject, $message, $headers, $html = true )
{
	list ( , $from ) = explode ( "From:", $headers );
	list ( $from, ) = explode ( "\n", $from );
	$from = trim ( strip_tags ( $from ) );
	
	$mail = new PHPMailer ( );
	
	if ( $headerdata = explode ( "\n", $headers ) )
	{
		foreach ( $headerdata as $d )
		{
			$d = explode ( ':', $d );
			switch ( strtolower ( trim ( $d[ 0 ] ) ) )
			{
				case 'content-type':
					if ( strstr ( strtolower ( $d[ 1 ] ), 'html' ) )
						$html = true;
					else $html = false;
					if ( strstr ( strtolower ( $d[ 1 ] ), 'utf-8' ) )
						$mail->CharSet = 'UTF-8';
					break;
				default:
					break;
			}
		}
	}
	
	
	if ( strstr ( $to, "," ) )
	{
		$to = explode ( ",", $to );
		foreach ( $to as $k=>$v )
		{
			$to[ $k ] = trim ( $v );
		}
	}
	
	if ( $html )
		$mail->isHTML ( true );
	else $mail->isHTML ( false );
	
	$mail->IsSMTP ( ); // telling the class to use SMTP
	$mail->Host = MAIL_SMTP_SERVER; // SMTP server
	$mail->helo = 'helo';
	$mail->SMTPAuth = TRUE;
	$mail->Username = MAIL_USERNAME;
	$mail->Password = MAIL_PASSWORD;
	
	$mail->From = $mail->Username;
	$mail->FromName = MAIL_FROMNAME;
	$mail->AddReplyTo ( MAIL_REPLYTO, MAIL_FROMNAME );
	
	if ( is_array ( $to ) )
	{
		foreach ( $to as $k=>$t )
		{
			if ( !( trim ( $t ) ) ) continue;
			$to[ $k ] = strip_tags ( $t );
			$t = strip_tags ( $t );
			$mail->AddAddress ( trim ( $t ), "" );
		}
	}
	else $mail->AddAddress ( strip_tags ( $to ) );
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	if ( $html ) $mail->AltBody = strip_tags ( $message );
	$mail->WordWrap = 50;
	
	if ( !$mail->Send ( ) )
	{
		mail ( 
			"hogne@blest.no", 
			"Sending av mail til " . SITE_ID . " feilet", 
			"Feilet fordi SMTP ikke er riktig satt opp (fra $from)\n\n" . 
				$mail->ErrorInfo . "\n\nSkulle sendes til " . print_r ( $to, true ) . " " . "\n\nBeskjeden var:\n\n" . $message, 
			"Content-type: text/html;\nFrom: noreply@blest.no" 
		);
	}
}

function ArenaDie( $msg )
{
	$fc = @file_get_contents( dirname( __FILE__ ) . '/../error.html' );
	if( $fc != '' && strpos( $fc, '%MSG%' ) != false )
		die( str_replace( '%MSG%', $msg, $fc ) );
	else
		die( $msg );

} // end of arenadie

function ArenaDate ( $dateformat, $time )
{
	if ( is_string ( $time ) ) $time = strtotime ( $time );
	$letters = Array (
		'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'W', 'F', 'm', 'M', 'n', 't', 'L', 'o', 'Y',
		'y', 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'e', 'I', 'O', 'P', 'T', 'Z',
		'c', 'r',	 'U'
	);
	$ostr = '';
	for ( $a = 0; $a < strlen ( $dateformat ); $a++ )
	{
		if ( $dateformat{$a} == "\\" ){ $ostr .= $dateformat{$a} . $dateformat{$a+1}; $a++; continue; }
		if ( in_array ( $dateformat{$a}, $letters ) )
		{
			$ostr .= i18n ( date ( $dateformat{$a}, $time ) );
		}
		else $ostr .= $dateformat{$a};
	}
	return stripslashes ( $ostr );
}

function ArenaTextfieldSafe ( $string )
{
	$string = str_replace ( '<input', '<hr style="width: 200px; height: 20px; background: #eee; border: 1px solid #aaa"', $string );
	$string = preg_replace ( "/(<textarea[^>]*>)/i", '<hr style="width: 200px; height: 20px; background: #eee; border: 1px solid #aaa" />', $string );
	$string = str_replace ( '</textarea>', '', $string );
	$string = preg_replace ( "/(<select[^>]*>)/i", '<hr style="width: 200px; height: 20px; background: #eee; border: 1px solid #aaa" />', $string );
	$string = str_replace ( '<option', '<!--', $string );
	$string = str_replace ( '</option>', ' --!>', $string );
	$string = str_replace ( '</select>', '', $string );
	$string = preg_replace ( "/(<form[^>]*>)/i", '', $string );
	$string = str_replace ( '</form>', '', $string );
	return $string;
}

function cleanHTML ( $string )
{
	return preg_replace ( 
		array ( 
			"/([n|N][a|A][m|M][e|E][\s]*?=)([\s]*?[\"]{0,1}[\_]*?action[\"]{0,1})/", 
			"/([n|N][a|A][m|M][e|E][\s]*?=)([\s]*?[\"]{0,1}[\_]*?module[\"]{0,1})/",
		),
		"name=\"illegal_field_name\"", 
		$string
	);
}


//			mode : "textareas", 
//			content_css : "lib/3rdparty/tiny_mce/themes/blest/css/editor.css",
//			language : "no",

//			theme : "blest",
/* This is version 3 of the tinyMCE software */ 

function enableTextEditor ( $options = false )
{
	global $document;
	if ( isset ( $GLOBALS[ 'texteditor_initialized' ] ) )
		return;
	$GLOBALS[ 'texteditor_initialized' ] = 1;
	if ( defined ( 'NEWEDITOR' ) )
	{
		$document->addHeadScript ( 'lib/javascript/texteditor.js' );
		$customStyles = 'false';
		// try both upload/ (first) and then css/
		$filename = file_exists ( 'upload/texteditor.css' ) ? 'upload/texteditor.css' : false;
		if ( !$filename ) $filename = file_exists ( 'css/texteditor.css' ) ? 'css/texteditor.css' : false;
		if ( !$filename ) $filename = 'lib/templates/texteditor.css';
		if ( $filename )
		{
			$customStyles = array ( );
			$cnt = file_get_contents ( $filename );
			$cnt = preg_replace ( '/\/\*.*?\*\//', '', $cnt );
			$cnt = str_replace ( "\n", "", $cnt );
			$cnt = str_replace ( "{", ":", $cnt );
			$cnt = str_replace ( "}", "\n", $cnt );
			$cnt = explode ( "\n", $cnt );
			foreach ( $cnt as $cn )
			{
				// Skip empty lines
				if ( !trim ( $cn ) ) continue;
				if ( $cn{0} == '.' )
				{
					list ( $name, ) = explode ( ':', $cn );
					$name = str_replace ( '.', '', $name );
					$customStyles[] = "'$name'";
				}
			}
			$customStyles = 'Array ( ' . implode ( ',', $customStyles ) . ' )';
		}
		if ( $options )
		{
			$ostr = '';
			foreach ( $options as $k=>$v )
			{
				$ostr .= $k . ':' . $v . ';';
			}
			$options = $ostr;
		}
		else $options ='';
		
		$document->addBottomData ( "
		<script type=\"text/javascript\">
			document.editor = new EditorAbstraction ( 'blest', '" . $options . "' );
			texteditor.setStylesheet ( '" . ( $filename ? $filename : '' ) . "' );
			texteditor.setCustomStyles ( $customStyles );
			texteditor.mode = '" . ARENAMODE . "';
			texteditor.init ( {classNames : \"mceSelector\"} );
			var editor = document.editor;
		</script>
		" );
		return '';
	}
	else
	{
		$document->addHeadScript ( 'lib/3rdparty/tiny_mce/tiny_mce.js' );
		return <<<EOL
		<script language="javascript" type="text/javascript">

			tinyMCE.init(
			{
				mode : "specific_textareas",
				editor_selector : "mceSelector",
				width : "100%",
				height : "500",
				language : "no",
				theme : "advanced",
				extended_valid_elements : "map[name],area[shape|coords|href|target|alt],iframe[src|width|height|frameborder|scrolling|marginheight|marginwidth|id],button[type|name|id|class|onclick],object[width|height|src|name|param|id|class|style|data|type|allowFullScreen],embed[wmode|width|height|type|src|name|param|class|style],param[name|value],label[id|for|class],arenaform[name|id|action|method|enctype|accept-charset|onsubmit|onreset|target],input[id|class|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect],textarea[id|name|rows|class|cols|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect],option[name|id|value|selected],select[id|class|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|length|options|selectedIndex]",
				remove_linebreaks : false,
				plugins : "safari,spellchecker,style,layer,table,save,advhr,arenalink,arenaimage,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak,imagemanager,filemanager",
				theme_advanced_buttons1_add : "fontselect,fontsizeselect",
				theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
				theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
				theme_advanced_buttons3_add_before : "tablecontrols,separator",
				theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,spellchecker,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage,example",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				plugin_insertdate_dateFormat : "%Y-%d-%m",
				plugin_insertdate_timeFormat : "%H:%M:%S",
				external_link_list_url : "example_data/example_link_list.js",
				external_image_list_url : "example_data/example_image_list.js",
				flash_external_list_url : "example_data/example_flash_list.js",
				template_external_list_url : "example_data/example_template_list.js",
				spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
			});
			document.editor = new EditorAbstraction ( 'tinymce' );
			var editor = document.editor;
		</script>
EOL;
	}

}



function getModuleSettings ( $modulename )
{
	$Setting = new dbObject ( 'Setting' );
	$Setting->addClause ( 'WHERE', 'SettingType="' . $modulename . '"' );
	$Result = new Dummy ( );
	if ( $Settings = $Setting->find ( ) )
	{
		foreach ( $Settings as $Setting )
		{
			$Result->{$Setting->Key} = $Setting->Value;
		}
	}
	return $Result;
}

function getLoaderBox ( $text )
{
	$tpl = new cPTemplate ( 'admin/templates/loadingbox.php' );
	$tpl->text = $text;
	return $tpl->render ( );
}

function htmlToFlashml ( $data )
{
	$h1style = $h2style = $h3style = '';
	if ( defined ( 'FlashH1Color' ) ) $h1style = ' color="' . FlashH1Color . '"';
	if ( defined ( 'FlashH2Color' ) ) $h2style = ' color="' . FlashH2Color . '"';
	if ( defined ( 'FlashH3Color' ) ) $h3style = ' color="' . FlashH3Color . '"';
	
	$data = str_replace ( '<p>', '<span class="Paragraph">', $data );
	$data = str_replace ( '</p>', '</span><br>', $data );
	$data = str_replace ( '<em>', '<font size="-1">', $data );
	$data = str_replace ( '</em>', '</font>', $data );
	$data = str_replace ( '<strong>', '<span class="Strong">', $data );
	$data = str_replace ( '</strong>', '</span>', $data );
	$data = str_replace ( '<br/>', '<br>', $data );
	$data = str_replace ( '<br />', '<br>', $data );
	$data = str_replace ( '<h1>', '<br><span class="H1"><font' . $h1style . ' size="+4">', $data );
	$data = str_replace ( '</h1>', '</font></span><br><br>', $data );
	$data = str_replace ( '<h2>', '<span class="H2"><font' . $h2style . ' size="+3">', $data );
	$data = str_replace ( '</h2>', '</font></span><br>', $data );
	$data = str_replace ( '<h3>', '<span class="H3"><font' . $h3style . ' size="+2">', $data );
	$data = str_replace ( '</h3>', '</font></span><br>', $data );
	$data = str_replace ( array ( "\n", "\r" ), '', $data );
	$data = str_replace ( 'img src=\"', 'img class="Image" src=\"' . BASE_URL, $data );
	$data = preg_replace ( "/(<img[^>]*>)/i", "\$1<br><br><br><br><br><br><br><br><br><br><br><br><br>", $data );
	$data = str_replace ( '<li>', '<li><span class="List">', $data );
	$data = str_replace ( '</li>', '</span></li>', $data );
	return $data;
}

function NumberFormat ( $value, $decimals = 2 ) 
{ 
	$thousands_sep = '.'; 
	$decimal_point = ','; 
	return number_format( doubleval ( $value ), $decimals, $decimal_point, $thousands_sep ); 
} 

function NumberExtract ( $str )
{ 
	$thousands_sep = '.'; 
	$decimal_point = ','; 
	list( $dollars, $cents ) = explode( $decimal_point, $str ); 
	if ( $cents == '' ) $cents = 0;
	$centsvalue = doubleval($cents) / ( pow( 10.0, strlen($cents) ) ); 
	$dollarsvalue = doubleval ( implode ( '', explode ( $thousands_sep, $dollars ) ) );
	return ( $dollarsvalue + $centsvalue ); 
}

function GetSetting ( $type, $key )
{
	$setting = new dbObject ( 'Setting' );
	$setting->SettingType = $type;
	$setting->Key = $key;
	if ( $setting->load ( ) )
		return $setting;
	return false;
}

function GetSettingValue ( $type, $key )
{
	$setting = GetSetting ( $type, $key );
	return is_object ( $setting ) ? $setting->Value : false;
}

function SetSetting ( $type, $key, $value )
{
	$setting = new dbObject ( 'Setting' );
	$setting->SettingType = $type;
	$setting->Key = $key;
	$setting->load ( );
	$setting->Value = $value;
	return $setting->save ( );
}

/**
 * Converts a hex number to a visible string
**/
function hex2string ( $hex )
{
	$h = Array ( 0, 0, 0 );
	
	$h[ 0 ] = ( $hex >> 16 ) & 0xff;
	$h[ 1 ] = ( ( $hex << 8 ) >> 16 ) & 0xff;
	$h[ 2 ] = ( ( $hex << 16 ) >> 16 ) & 0xff;
	
	$hnums = Array ( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f' );
	$string = '';
	
	for ( $a = 0; $a < 3; $a++ )
	{
		$n = $h[ $a ];
		$n1 = 0;
		$n2 = 0;
		for ( $b = 0; $b < $n; $b++ )
		{
			$n1++;
			if ( $n1 > 15 )
			{
				$n1 = 0;
				$n2++;
			}
		}
		$string .= $hnums[ $n2 ] . $hnums[ $n1 ];
	}
	return $string;
}

/**
 * Converts a string to a hexadecimal number
**/
function string2hex ( $hex )
{
	$hex = str_replace ( array ( "#", '0x' ), '', $hex );
	$hex = strtolower ( $hex );
	$hnums = Array ( '0'=>0, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, 'a'=>10, 'b'=>11, 'c'=>12, 'd'=>13, 'e'=>14, 'f'=>15 );
	if ( strstr ( $hex, 'x' ) )
		list ( ,$hex ) = explode ( 'x', $hex );
	$number = 0;
	$mul = 16;
	for ( $a = 0; $a < 6; $a ++ )
	{	
		$mul = $mul * 16;
		$z = 5 - $a;
		$num = $hnums[ $hex{$z} ];
		$number += $num * $mul;
	}
	return $number >> 8;
}

function getSiteStructureOptions ( $current, $parent = '0', $r = '' )
{
	global $Session;
	$db =& dbObject::globalValue ( 'database' );
	$oStr = '';
	$language = $Session->LanguageID;
	if ( !$language ) $language = 1;
	if ( $content = $db->fetchObjectRows ( 'SELECT * FROM ContentElement WHERE MainID = ID AND !IsDeleted AND !IsTemplate AND Parent=\'' . $parent . '\' AND `Language`=\'' . $language . '\' ORDER BY SortOrder ASC, ID ASC' ) )
	{
		foreach ( $content as $cnt )
		{
			if ( $cnt->ID )
			{
				if ( !$cnt->Title ) $cnt->Title = $cnt->MenuTitle;
				if ( !$cnt->Title ) $cnt->Title = $cnt->SystemName;
				if ( !$cnt->Title ) $cnt->Title = $cnt->ID;
				
				$s = ( $cnt->ID == $current ) ? $s = ' selected="selected"' : '';
				
				$oStr .= "<option value=\"{$cnt->ID}\"$s>$r{$cnt->Title}</option>";
				$oStr .= getSiteStructureOptions ( $current, $cnt->ID, $r . "&nbsp;&nbsp;&nbsp;&nbsp;" );
			}
		}
	}
	return $oStr;
}

/**
 * Clean the text with regards to clean HTML
 * @param $level defaults to 0 for now
**/
function cleanHTMLElement ( $string, $level = 0 )
{
	$string = preg_replace ( "/\s*line-height:[^;\"]*;?/i", '', $string );
	$string = preg_replace ( "/\s*font-stretch:[^;\"]*;?/i", '', $string );
	$string = preg_replace ( "/\s*font-size:[^;\"]*;?/i", "", $string );
	$string = preg_replace ( "/\s*font-variant:[^;\"]*;?/i", '', $string );
	$string = preg_replace ( "/\s*font-size-adjust:[^;\"]*;?/i", '', $string );
	$string = preg_replace ( "/(<font [^>]*>)/i", '', $string );
	$string = preg_replace ( "/(<form [^>]*>)/i", '', $string );
	$string = str_replace ( 'style=""', '', $string );
	$string = str_replace ( '<div', '<p', $string );
	$string = str_replace ( '</div>', '</p>', $string );
	$string = str_replace ( '</font>', '', $string );
	$string = str_replace ( '</form>', '', $string );
	$string = str_replace ( '="' . BASE_URL, '="', $string );
	
	if ( substr ( $string, 0, 11 ) == '&nbsp;<tabl' )
		$string = '<tabl' . substr ( $string, 6, strlen ( $string ) - 6 );
	else if ( substr ( $string, 0, 11 ) == '&nbsp;<span' )
		$string = '<span' . substr ( $string, 6, strlen ( $string ) - 6 );
	$string = preg_replace ( '/\<[a|p][^>]*\>[\ ]*\<\/[a|p]\>/', '', $string );
	
	// Always use paragraphs
	$test = trim ( $string );
	if ( $test{0} != '<' && strlen ( $test ) > 0 )
	{
		$string = '<p>' . "\n" . $string . "\n" . '</p>';
	}
	
	$string = preg_replace ( '/([\w\W]*?)\&nbsp\;/i', '$1 ', $string );
	
	return $string;
}

function arenaDebug ( $string = false )
{
	if ( $string )
	{
		$_SESSION[ '__arenaDebug__' ] .= "\n" . $string;
	}
	return $_SESSION[ '__arenaDebug__' ];
}

// Take "arena html" from admin and convert to displayable html --------------->
function decodeArenaHTML_callback_objects ( $matches )
{
	if ( strstr ( strtolower ( stripslashes ( $matches[1] ) ), 'type="movie"' ) )
	{
		$string = stripslashes ( $matches[1] );
		$string = preg_replace ( '/arenatype\=\"[^"]*?\"/i', '', $string );
		$string = preg_replace ( '/style\=\"[^"]*?\"/i', '', $string );
		if ( !preg_match ( '/\<param/i', $string ) )
		{
			preg_match ( '/width\=\"([^"]*)\"/i', $string, $width );
			preg_match ( '/height\=\"([^"]*)\"/i', $string, $height );
			preg_match ( '/data\=\"([^"]*)\"/i', $string, $data );
			$matches[2] = '<param name="width" value="' . trim($width[1]) . '"/>';
			$matches[2] .= '<param name="height" value="' . trim($height[1]) . '"/>';
			$matches[2] .= '<param name="wmode" value="transparent"/>';
			$matches[2] .= '<param name="movie" value="' . trim($data[1]) . '"/>';
		}
		$string = '<object' . $string . '>' . stripslashes ( $matches[2] ) . '</object>';
		return addslashes ( $string );
	}
	return '<span' . $matches[1] . '>' . $matches[2] . '</span>';
}
function decodeArenaHTML ( $string )
{
	// Remove empty tags
	$string = preg_replace ( '/\<\!\-\-[\ ]{0,3}arenaform(.*?)\-\-\>/', '<form $1>', $string );
	$string = preg_replace ( '/\<\!\-\-[\ ]{0,3}\/arenaform(.*?)\-\-\>/', '</form>', $string );
	$string = str_replace ( array ( '<br>', '<BR>' ), '<br/>', $string );
	$singleTags = array ( 'area', 'param', 'img', 'br', 'hr', 'input' );
	foreach ( $singleTags as $st )
		$string = preg_replace ( '/(\<' . $st . ')(.*?)>/', '$1$2/>', $string );
	$string = str_replace ( '//>', '/>', $string );
	$string = addslashes ( str_replace ( ' target="_self"', '', stripslashes ( $string ) ) );
	// Remove empty attributes
	$string = addslashes ( preg_replace ( '/([a-z|A-Z]*)(\ [a-zA-Z]*?\=\"\")/', '$1', stripslashes ( $string ) ) );
	// Flash divs
	$string = preg_replace_callback ( '/\<span([^>]*?)\>([\w\W]*?)\<\/span\>/i', 'decodeArenaHTML_callback_objects', $string );
	
	// Remove body tags etc
	$string = preg_replace ( '/\<[\/]{0,1}body[^>]*\>/i', '', $string );
	$string = preg_replace ( '/\<[\/]{0,1}html[^>]*\>/i', '', $string );
	$string = preg_replace ( '/\<b\>/i', '<strong>', $string );
	$string = preg_replace ( '/\<\/b\>/i', '<\/strong>', $string );
	
	return cleanHTMLElement ( $string );
}
// Done "arena html" from admin and convert to displayable html ---------------<
// Convert TO "arena html" from web html -------------------------------------->
function encodeArenaHTML_callback_objects ( $matches )
{
	$string = '&nbsp;<span arenatype="movie" style="!W!; !H!; display: block; border: 2px dotted #aaa; background: #ccc url(admin/gfx/arenaicons/page_flash_64.png) no-repeat center center"' . $matches[ 1 ] . '>' . $matches[ 2 ] . '</span>&nbsp;';
	preg_match ( '/width\=\"([^"]*)\"/i', $matches[1], $width );
	preg_match ( '/height\=\"([^"]*)\"/i', $matches[1], $height );
	$string = str_replace ( array ( '!W!', '!H!' ), array ( 'width:'.$width[1].'px', 'height:'.$height[1].'px' ), $string );
	$string = preg_replace ( '/\<param(.*?)\/\>/i', '<param$1></param>', $string );
	return $string; 
}
function encodeArenaHTML ( $string )
{
	$elements = array ( 'h', 'span', 'strong', 'b', 'div' );
	foreach ( $elements as $el )
	{
		$preg = "/\<{$el}[^>]*?\>\<\/{$el}[^>]*?\>/i";
		while ( preg_match ( $preg, $string, $matches ) )
		{
			$string = str_replace ( $matches[0], '', $string );
		}
	}// Singlequotes
	$string = str_replace ( '&quot;', '\'', $string );
	// Form fields
	$string = preg_replace ( '/\<form([^>]*)\>/i', '<!-- arenaform$1 -->', $string );
	$string = preg_replace ( '/(\<)textarea([^>]*)(\>)/i', '&lt;textarea$2&gt;', $string );
	$string = preg_replace ( '/(\<)\/textarea(\>)/i', '&lt;/textarea&gt;', $string );
	$string = preg_replace ( '/\<\/form[\ ]{0,1}\>/i', '<!-- /arenaform -->', $string );
	// Strip body and html tags...
	$string = preg_replace ( '/\<[\/]{0,1}body[^>]*\>/i', '', $string );
	$string = preg_replace ( '/\<[\/]{0,1}html[^>]*\>/i', '', $string );
	$string = str_replace ( '  ', ' ', $string );
	// Remove office mso stuff
	$string = preg_replace ( '/mso\-[^:]*?\:[^;"]*/i', '', $string );
	$string = str_replace ( '="; ', '="', $string );
	// Remove empty styles
	$string = preg_replace ( '/style\=\"\"/i', '', $string );
	$string = preg_replace ( '/style\=\"[ ;]*\"/i', '', $string );
	// Remove attributeless span tags
	$string = preg_replace ( '/\<span\>([\w\W]*?)\<\/span\>/i', '$1', $string );
	// Remove office paragraphs
	$string = preg_replace ( '/\<o\:p\>([\w\W]*?)\<\/o\:p\>/i', '<p>$1</p>', $string );
	// Flash
	while ( preg_match ( '/\<object[\w\W]*?\>/i', $string ) )
	{
		$string = preg_replace_callback ( 
			'/\<object([\w\W]*?)\>([\w\W]*?)\<\/object\>/', 
			'encodeArenaHTML_callback_objects', 
			$string
		);
	}
	// Strip comments from inside paragraphs with OFFICE CRAP!
	$string = preg_replace ( '/\<p\>\<!\-[\w\W]*?MsoNormal[\w\W]*?\<\/p\>/i', '<p></p>', $string );
	// Remove empty paragraphs and replace with br's for easier composition
	$string = preg_replace ( array ( '/\<p\>\<\/p\>/i', '/\<p\>\<br[^>]*?\>\<\/p\>/i' ), '<br/>', $string );
	// Get external images
	$rootfolder = dbImageFolder::getRootFolder ( );
	if ( $allImages = preg_match_all ( "/\<img.*?src\=\"(.*?)\".*?\>/i", $string, $matches ) )
	{
		$i = 0;
		foreach ( $matches[1] as $img )
		{
			if ( strstr ( $img, 'images-master' ) || strstr ( $img, 'arena-images' ) )
				continue;
			if ( $cnt = @file_get_contents ( $img ) )
			{
				$lastbit = explode ( '/', $img );
				$filename = $lastbit[ count($lastbit)-1 ];
				$ext = explode ( '.', $filename );
				switch ( strtolower ( $ext ) )
				{
					case 'jpg':
					case 'jpeg':
						$ext = 'jpg';
						break;
					default:
						$ext = strtolower ( $ext );
						break;
				}
				$f = new dbImage ( );
				if ( file_exists ( 'upload/images-master/' . $filename ) )
				{
					$oldfile = new dbImage ( );
					$oldfile->Filename = $filename;
					if ( $oldfile->load ( ) )
					{
						$oldfile->delete ( );
					}
				}
				$f->ImageFolder = $rootfolder->ID;
				$f->Filename = $filename;
				$fi = fopen ( 'upload/images-master/' . $filename, 'w+' );
				fwrite ( $fi, $cnt, strlen ( $cnt ) );
				fclose ( $fi );
				$f->Filetype = $ext;
				$f->save ( );
				$string = str_replace ( $img, 'upload/images-master/' . $filename, $string );
			}
			$i++;
		}
	}
	return trim ( $string );
}
// Done TO "arena html" from web html --------------------------------------<
// Creates a object from a newline and tab separated string
function CreateObjectFromString ( $string, $properties = false )
{
	$d = new dummy ( );
	
	if ( $properties )
		foreach ( $properties as $p ) $d->$p = '';
	
	if ( $rows = explode ( "\n", $string ) )
	{
		for ( $a = 0; $a < count ( $rows ); $a++ )
		{
			list ( $k, $v ) = explode ( "\t", $rows[$a] );
			if ( trim ( $k ) )
				$d->$k = $v;
		}
	}
	return $d;
}
// Creates a string from the object
function CreateStringFromObject ( $object )
{
	$s = array ();
	foreach ( $object as $k=>$v )
	{
		$s[] = "$k\t$v";
	}
	return implode ( "\n", $s );
}

?>
