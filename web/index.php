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



/**
 * Defaults
**/
define ( 'NL', "\n" );
define ( 'TAB', "\t" );
define ( 'ARENAMODE', 'web' );

/**
 * Include site config
**/
session_start ( );
include_once ( 'config.php' );
header ( 'Cache-Control: no-store' );

/**
 * Prerequisites
**/
include_once ( 'lib/functions/functions.php' );
include_once ( 'lib/classes/database/cdatabase.php' );
include_once ( 'lib/classes/dbObjects/dbObject.php' );
include_once ( 'lib/classes/dbObjects/dbContent.php' );
include_once ( 'lib/classes/dbObjects/dbUser.php' );
include_once ( 'lib/classes/template/cPTemplate.php' );
include_once ( 'lib/classes/template/cDocument.php' );
include_once ( 'lib/classes/session/session.php' );
if ( !file_exists ( 'upload/index.html' ) )
{
	if ( $f = fopen ( 'upload/index.html', 'w+' ) )
	{
		fwrite ( $f, ' ' );
		fclose ( $f );
	}
}

/**
 * Setup the core database
**/
$corebase = new cDatabase ( );
include_once ( 'lib/lib.php' );
include_once ( 'lib/core_config.php' );
$corebase->Open ( );
dbObject::globalValueSet ( 'corebase', &$corebase );

/**
 * Setup the site database
**/
if ( !( $siteData = $corebase->fetchObjectRow ( 'SELECT * FROM `Sites` WHERE `SiteName`="' . SITE_ID . '"' ) ) )
{
	if ( file_exists ( 'install.php' ) )
		include ( 'install.php' );
	ArenaDie( 'Failed to initialize site: ' . SITE_ID );
}
define ( 'BASE_DIR', $siteData->BaseDir );

$database = new cDatabase ( );
$database->setUsername ( $siteData->SqlUser );
$database->setPassword ( $siteData->SqlPass );
$database->setHostname ( $siteData->SqlHost );
$database->setDb ( $siteData->SqlDatabase );
$database->Open ( );
$userbase =& $database; // <- Compatability with ARENA1, userbase is used

dbObject::globalValueSet ( 'sitedata', &$siteData );
dbObject::globalValueSet ( 'database', &$database );

/**
 * Session variables
**/
$Session = new Session ( $siteData->ID . 'web' );
$GLOBALS[ 'Session' ] =& $Session;

/**
 * Language
 * A site must always have one default language installed to
 * work!
**/
$langtest = new dbObject ( 'Languages' );
$langtest->addClause ( 'WHERE', '( UrlActivator IS NOT NULL && UrlActivator != "" )' );
if ( $langtest->findCount ( ) )
	$Session->HasUrlActivator = true;

if ( is_numeric ( $_REQUEST[ 'setlang' ] ) )
{
	$lang = new dbObject ( 'Languages' );
	if ( $lang->load ( $_REQUEST[ 'setlang' ] ) )
	{
		$Session->CurrentLanguage = $lang->ID;
		$Session->LanguageCode = $lang->Name;
	}
	else $Session->CurrentLanguage = 0;
}
else if ( !$Session->HasUrlActivator )
{
	list ( $langcode, ) = explode ( '/', $_GET[ 'route' ] );
	if ( $langcode )
	{
		$lang = new dbObject ( 'Languages' );
		$lang->addClause ( 'WHERE', 'Name="' . $langcode . '"' );
		if ( $lang = $lang->findSingle ( ) ) 
		{
			$Session->CurrentLanguage = $lang->ID;
			$Session->LanguageCode = $lang->Name;
		}
	}
}
else if ( $Session->HasUrlActivator )
{
	$search = str_replace ( 'www.', '', $_SERVER[ 'SERVER_NAME' ] );
	$lang = new dbObject ( 'Languages' );
	$lang->addClause ( 'ORDER BY', 'IsDefault DESC' );
	$lang->addClause ( 'WHERE', 'UrlActivator LIKE "%' . $search. '"' );
	if ( $lang = $lang->findSingle ( ) )
	{
		$Session->CurrentLanguage = $lang->ID;
		$Session->LanguageCode = $lang->Name;
		if ( $lang->BaseUrl )
			$Session->BaseUrl = $lang->BaseUrl;
	}
}
if ( !$Session->CurrentLanguage || !$Session->LanguageCode )
{
	if ( !$lang )
	{
		$lang = new dbObject ( 'Languages' );
		$lang->addClause ( 'WHERE', 'IsDefault=\'1\'' );
		$lang = $lang->findSingle ( );
		if ( !$lang )
		{
			$lang = new dbObject ( 'Languages' );
			$lang->Name = 'no';
			$lang->NativeName = 'Norsk';
			$lang->IsDefault = '1';
			$lang->save ( );
		}
	}
	if ( $lang->UrlActivator ) $Session->HasUrlActivator = true;
		
	if ( $lang->BaseUrl )
		$Session->CurrentLanguage = $lang->BaseUrl;
	$Session->CurrentLanguage = $lang->ID;
	$Session->LanguageCode = $lang->Name;
}

/**
 * Base url
**/
if ( !$Session->BaseUrl ) $Session->BaseUrl = $siteData->BaseUrl;
define ( 'BASE_URL', $Session->BaseUrl );

/**
 * Make sure we're on the correct domain
**/
if ( $_SERVER[ 'HTTPS' ] )
	$http = 'https://';
else $http = 'http://';
list ( $burl, ) = explode ( '/', str_replace ( array ( 'https://', 'http://' ), '', BASE_URL ) );
if ( $_SERVER[ 'HTTP_HOST' ] != $burl )
{
    header ( 'Location: ' . $http . str_replace ( array ( 'https://', 'http://' ), '', BASE_URL ) );
	die ( );
}

/**
 * Web User
**/
$webuser = new dbUser ( );
$webuser->authenticate ( 'web', $Session->SessionPrefix );
if ( $_REQUEST[ 'logout' ] && is_object ( $webuser ) )
{
	$webuser->logout ( );
	ob_clean ( );
	list ( $url, ) = explode ( '?', $_SERVER[ 'REQUEST_URI' ] );
	header ( 'Location: ' . $url );
	die ( );
}
if ( !$webuser->is_authenticated ) $webuser = false;
dbObject::globalValueSet ( 'webuser', &$webuser );

/** 
 * Check mode
**/
switch ( $_REQUEST[ 'mode' ] )
{
	case 'image':
		include_once ( 'lib/classes/dbObjects/dbImage.php' );
		$img = new dbImage ( );
		$img->load ( $_REQUEST[ 'iid' ] );
		
		if ( strstr ( strtolower ( $_REQUEST[ 'filename' ] ), '.png' ) )
			$img->setOutputMode ( 'PNG' );
		else if ( strstr ( strtolower ( $_REQUEST[ 'filename' ] ), '.gif' ) )
			$img->setOutputMode ( 'GIF' );
			
		$width = $_REQUEST[ 'width' ];
		$height = $_REQUEST[ 'height' ];
		$scalemode =  ( $_REQUEST[ 'scalemode' ] != '0' && isset ( $_REQUEST[ 'scalemode' ] ) ) ? $_REQUEST[ 'scalemode' ] : false;
		$effects =  ( $_REQUEST[ 'effects' ] != '0' && isset ( $_REQUEST[ 'effects' ] ) ) ? urldecode ( $_REQUEST[ 'effects' ] ) : false;
		$bgcolor =  ( $_REQUEST[ 'bgcolor' ] != '0' && isset ( $_REQUEST[ 'bgcolor' ] ) ) ? $_REQUEST[ 'bgcolor' ] : false;
		
		// Try to do it the hard way if regexp can't handle it....
		if ( !$effects )
		{
			list ( ,,$effects ) = explode ( '/', $_REQUEST[ 'filename' ] );
			list ( ,,,$effects, ) = explode ( '_', $effects );
		}
		$img->setBackgroundColor ( string2hex ( $bgcolor ) );
		
		$f = fopen ( 'upload/test.txt', 'w+' );
		fwrite ( $f, print_r ( $_REQUEST, 1 ) . ' ' . $img->_mode . ' ' . hex2string ( $img->_bgcolor ) . ' ' . $bgcolor . "    {$_REQUEST['bgcolor']}\n" );
		fclose ( $f );
		
		ob_clean ( );
		$url = BASE_DIR . '/' . str_replace ( BASE_URL, '', $img->getImageUrl ( $width, $height, $scalemode, $effects ) );
		if ( $fp = fopen ( $url, 'r' ) )
		{
			$data = fread ( $fp, filesize ( $url ) );
			fclose ( $fp );
			switch ( $img->_mode )
			{
				case 'gif':
					header ( 'Content-type: image/gif' );
					break;
				case 'png':
					header ( 'Content-type: image/png' );
					break;
				default:
					header ( 'Content-type: image/jpeg' );
					break;
			}
			echo ( $data );
		}
		die ( );
	
	default:
		// We will not be delivering images in this mode
		$testroutei = strtolower ( $_REQUEST[ 'route' ] );
		if ( 
			strpos ( $testroutei, '.png' ) ||
			strpos ( $testroutei, '.jpg' ) ||
			strpos ( $testroutei, '.png' )
		)
		{
			ob_clean ( );
			header ( 'HTTP/1.0 404 Not Found' );
			die ( '404. Unimplemented response.' );
		}
		header ( 'Content-type: text/html; charset=utf-8' );
		$page = new dbContent ( );
		$document = new cDocument ( );
		if ( isset ( $ContentID ) ) $page->load ( $ContentID );
		if ( isset ( $ContentPath ) ) $page = $page->getByPath ( $ContentPath );
		if ( !$page->ID )
		{
			// Test for javascript request (no 404 for missing .js)
			if ( substr ( $_REQUEST[ 'route' ], -3, 3 ) == '.js' ) die ( '// Javascript not found' );

			// If we fail to grab page by path
			if ( !( $page = $page->getByPath ( $_REQUEST[ 'route' ] ) ) )
			{
				// Try to find an older page location and redirect
				$obj = new dbObject ( 'ContentRoute' );
				$obj->Route = $_REQUEST[ 'route' ];
				$obj->ElementType = 'ContentElement';
				if ( $obj->load ( ) )
				{
					$page = new dbContent ( );
					$page->load ( $obj->ElementID );
					ob_clean ( );
					header ( 'Location: ' . BASE_URL . $page->getPath ( ) );
					$page = false;
				}
				else
				{
					// Try to fetch a parentpage if it is an extension
					$page = new dbContent ( );
					list ( $route, ) = explode ( '?', $_REQUEST[ 'route' ] );
					$route = explode ( '/', str_replace ( '/index.html', '', $route ) );
					$route = implode ( '/', $route ) . '/index.html';
					
					if ( ( $page = $page->getByPath ( $route ) ) && $page->ContentType == 'extensions' )
					{
						// Dupe #1
						$config = explode ( "\n", $page->Intro );
						foreach ( $config as $c )
						{
							list ( $e, $v ) = explode ( "\t", $c );
							if ( $e == 'ExtensionName' )
								$page->extension = $v;
						}
						if ( file_exists ( 'extensions/' . $page->extension . '/webmodule_preparse.php' ) )
							include_once ( 'extensions/' . $page->extension . '/webmodule_preparse.php' );
					}
					else
					{
						// Just end up with 404 - page is totally deleted and/or non existant
						$document->load ( $document->findTemplate ( '404.php', array ( 'templates/', 'web/templates/' ) ) );
						echo $document->render ( );
						$page = false;
					}
				}
			}
			else
			{
				if ( $page->ContentType == 'extensions' )
				{
					// Dupe #2
					$config = explode ( "\n", $page->Intro );
					foreach ( $config as $c )
					{
						list ( $e, $v ) = explode ( "\t", $c );
						if ( $e == 'ExtensionName' )
						{
							$page->extension = $v;
							break;
						}
					}
					if ( file_exists ( 'extensions/' . $page->extension . '/webmodule_preparse.php' ) )
						include_once ( 'extensions/' . $page->extension . '/webmodule_preparse.php' );
				}
			}
		}
		if ( $page )
		{
			if ( !$page->IsPublished )
			{
				$document->load ( $document->findTemplate ( 'page_not_published.php', array ( 'templates/', 'web/templates/' ) ) );
				$document->page =& $page;
				$parentPage = new dbContent ( );
				$parentPage->load ( $page->Parent );
				$document->parentPage =& $parentPage;
			}
			else
			{
				if ( $page->ContentType == 'link' ) header ( 'Location: ' . $page->Link );
				
				$page->loadExtraFields ( array ( 'OnlyPublished'=>true ) );
				$access = true;
				if ( $page->IsProtected )
				{
					// Check global permissions if no user is logged in
					if ( !( $webuser = dbObject::globalValue ( 'webuser' ) ) )
					{
						$access = dbUser::checkGlobalPermission ( $page, 'Read' );
					}
					else if ( !$webuser->checkPermission ( $page, 'Read' ) )
						$access = false;
				}
				if ( !$access )
				{
					// Check for override to access_denied.php thingie
					$setting = new dbObject ( 'Setting' );
					$setting->SettingType = 'ContentsProtectedSymlink';
					$setting->Key = $page->ID;
					if ( $setting->load ( ) )
					{
						$page = new dbContent ( );
						$page->load ( $setting->Value );
						if ( $page->Template )
						{
							$document->load ( 'templates/' . $page->Template );
						}
						else 
						{
							$document->_templateFilename = false;
						}
						if ( !$document->_templateFilename )
						{
							$document->load ( $document->findTemplate ( 'page.php', array ( 'templates/', 'web/templates/' ) ) );
						}
						$document->page =& $page;
					}
					else
					{
						$document->load ( $document->findTemplate ( 'access_denied.php', array ( 'templates/', 'web/templates/' ) ) );
						$document->page =& $page;
					}
					$parentPage = new dbContent ( $page->Parent );
					$document->parentPage =& $parentPage;
				}
				else
				{
					$document->load ( ( $page->Template ? ( 'templates/' . $page->Template ) : 'web/templates/page.php' ) );
					$document->page =& $page;
				}
				if ( defined ( 'DOCTYPE' ) )
				{
					$document->xmlns = defined ( 'DOCTYPE_XMLNS' ) ? DOCTYPE_XMLNS : '';
					$document->doctype = '<!DOCTYPE ' . DOCTYPE . ">\n";
					$document->docinfo = defined ( 'DOCTYPE_INFO' ) ? ( DOCTYPE_INFO . "\n" ) : '';
				}
				else
				{
					$document->xmlns = ' xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"';
					$document->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
					$document->docinfo = '<' . '?xml version="1.0"?' . '>'."\n";
				}
				if ( defined ( 'DOCTYPE_OVERRIDE' ) )
				{
					$document->xmlns = '';
					$document->doctype = DOCTYPE_OVERRIDE;
					$document->docinfo = '';
				}
			}
			switch ( $_REQUEST[ 'arenamode' ] )
			{
				// Flashmode only outputs content in XML
				case 'flash':
				case 'xml':
					if ( $_REQUEST[ 'encoding' ] )
						$document->_encoding = $_REQUEST[ 'encoding' ];
					echo $document->renderFlashXML ();
					break;
				case 'objectinfo':
					if ( $_REQUEST[ 'encoding' ] )
						$document->_encoding = $_REQUEST[ 'encoding' ];
					if ( $img = dbObject::get ( $_REQUEST[ 'id' ], $_REQUEST[ 'type' ] ) )
					{
						header ( 'Content-type: text/xml' );
						die ( '<' . '?xml version="1.0" charset="utf-8"?' . '>
<xml>
<element type="' . $_REQUEST[ 'type' ] . '" src="' . BASE_URL . 'upload/images-master/' . $img->Filename . '" name="' . $img->Title . '" id="' . $img->ID . '">
	<![CDATA[' . $img->Description . ']]>
</element>
</xml>' );
					}
					break;
				default:
					$db =& $page->getDatabase ( ); 
					$db->query ( 'UPDATE ContentElement SET SeenTimes = \'' . ( ( int )$page->SeenTimes + 1 ) . '\' WHERE ID=\'' . $page->MainID . '\' OR MainID=\'' . $page->MainID . '\'' );
					if ( !$Session->VersionCheck )
					{
						$Session->set ( 'VersionCheck', 1 );
						include_once ( 'admin/include/sanitize_upgrade.php' );
					}
					if ( !( $cs =& $Session->Get ( 'ContentStatistics' ) ) )
						$cs = array ( );
					if ( !$cs[ $page->MainID ] )
					{
						$cs[ $page->MainID ] = 1;
						$db->query ( 'UPDATE ContentElement SET SeenTimesUnique = \'' . ( ( int )$page->SeenTimesUnique + 1 ) . '\' WHERE ID=\'' . $page->MainID . '\' OR MainID=\'' . $page->MainID . '\'' );
					}
					$Session->set ( 'ContentStatistics', $cs );
					$document->LanguageCode = $Session->LanguageCode;
					echo $document->render ( );
					break;
			}
		}
		break;
}


/**
 * Close database
 */
$database->Close ( );
$corebase->Close ( );

?>
