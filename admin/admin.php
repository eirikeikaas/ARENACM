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
 * Start session 
 */
session_start ( );
header ( 'Cache-Control: no-cache, must-revalidate, proxy-revalidate, max-age=0' );

/**
 * Include our config
 */
require ( 'config.php' );
if ( !defined ( 'ADMIN_LANGUAGE' ) ) define ( 'ADMIN_LANGUAGE', 'no' );
if ( !defined ( 'LOCALE' ) ) define ( 'LOCALE', ADMIN_LANGUAGE );
define ( 'ARENAMODE', 'admin' );

/**
 * Include only the basic classes
 */

require ( 'lib/classes/database/cdatabase.php' );
require ( 'lib/classes/dbObjects/dbObject.php' );
require ( 'lib/classes/dbObjects/dbUser.php' );
require ( 'lib/classes/template/ctemplate.php' );
require ( 'lib/classes/template/cPTemplate.php' );
require ( 'lib/classes/template/cDocument.php' );
require ( 'lib/classes/session/session.php' );

$Session = new Session ( SITE_ID . 'admin' );
$Session->Set ( 'LanguageCode', LOCALE );
$GLOBALS[ 'Session' ] =& $Session;

/**
 * Setup the core database
 */
 
$corebase = new cDatabase ( );
include_once ( 'lib/lib.php' );
include_once ( 'lib/core_config.php' );
$corebase->Open ( );
dbObject::globalValueSet ( 'corebase', &$corebase );

/**
 * Setup the site database
 */
if ( !( $siteData = $corebase->fetchObjectRow ( 'SELECT * FROM `Sites` WHERE `SiteName`="' . SITE_ID . '"' ) ) )
{
	if ( $_REQUEST[ 'installer' ] )
	{
		include_once ( 'install.php' );
	}
	else
	{
		ArenaDie ( 'Failed to initialize site: ' . SITE_ID );
	}
}

/**
 * Set up the database and controls
**/

$database = new cDatabase ( );
$database->setUsername ( $siteData->SqlUser );
$database->setPassword ( $siteData->SqlPass );
$database->setHostname ( $siteData->SqlHost );
$database->setDb ( $siteData->SqlDatabase );
$database->Open ( );
$userbase =& $database;
dbObject::globalValueSet ( 'sitedata', &$siteData );
define ( 'BASE_URL', $siteData->BaseUrl );
define ( 'BASE_DIR', $siteData->BaseDir );
dbObject::globalValueSet ( 'database', &$database );

/**
 * Make sure we clean up after ourselves
**/

function shutdown_arena2 ( $ar )
{
	$ar[ 'corebase' ]->Close ( );
	$ar[ 'database' ]->Close ( );
}
register_shutdown_function ( shutdown_arena2, array ( 'corebase'=>&$corebase, 'database'=>&$database ) );

/**
 * Control the database one time
**/
if ( !$Session->TableControlled )
{
	include_once ( 'admin/table_control.php' );
	$Session->Set ( 'TableControlled', true );
}

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
 * Create user object
 */
$user = new dbUser ( );

// Document object
$document = new cDocument ( );

// Browser test (Only login with compatible browsers)
$uagent =  $_SERVER[ 'HTTP_USER_AGENT' ];
if ( 
	strstr ( $uagent, 'MSIE 6' ) ||
	strstr ( $uagent, 'Opera' ) 
)
{
	header ( 'Content-type: text/html; charset=UTF-8' );
	$document->load ( 'admin/templates/notcompatible.php' );
	$document->_isAdmin = true;
	die ( $document->render ( ) );
}
$notAuthenticated = true;
if ( $user->authenticate ( ) && !$_REQUEST[ 'logout' ] )
{
	if ( $user->IsAdmin || $user->_dataSource == 'core' )
	{
		$Session->Set ( 'AdminUser', &$user );
		$document->load ( 'admin/templates/main.php' );
		$notAuthenticated = false;
		// If core and database is same, it is a core user
		if ( 
			$database->hostname == $corebase->hostname && 
			$database->username == $corebase->username &&
			$database->db == $corebase->db
		)
		{
			$user->_dataSource = 'core';
			$user->_database =& $corebase;
		}
		include_once ( 'admin/system.php' );
	}
}
if ( $notAuthenticated || $_REQUEST[ 'logout' ] )
{
	if ( $_REQUEST[ 'logout' ] )
	{
		$user->logout ( );
		ob_clean ( );
		header ( 'Location: admin.php' );
		die ( );
	}
	else
	{
		$document->load ( 'admin/templates/login.php' );
		if ( $_REQUEST[ 'action' ] == 'newpassword' )
		{
			$us = new dbUser ( );
			$us->Email = $_REQUEST[ 'email' ];
			if ( $us->load ( ) )
			{
				$us->makePassword ( );
				$us->save ( );
				$us->sendUpdateMail ( );
				die ( 'Vi har nå sendt deg et nytt passord.' );
			}
			else die ( "Kunne ikke finne e-post adressen i databasen.\nVennligst kontakt support." );
		}
		$user->logout ( );
	}
}

if ( $document->_template )
{
	/**
	 * Set the header
	 */
	header ( 'Content-type: text/html; charset=UTF-8' );
	$document->_isAdmin = true;
	$document->user =& $user;
	echo ( $document->render ( ) );
}
else echo ( 'Blest ARENA failed.' );

?>
