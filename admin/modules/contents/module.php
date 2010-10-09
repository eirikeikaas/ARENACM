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
 * Prerequisites - requirements for this module
 */
global $Session;
$db =& dbObject::globalValue ( 'database' );
require_once ( 'lib/classes/dbObjects/dbContent.php' );


/**
 * Find the current content ID
 */
if ( $_REQUEST[ 'cid' ] == 'root' ) 
	$Session->Del ( 'contentID' );
else if ( intval ( $_REQUEST[ 'cid' ] ) > 0 )
{
	$cnt = $db->fetchRow ( 'SELECT ID, MainID FROM ContentElement WHERE !IsDeleted && ID=' . $_REQUEST[ 'cid' ] );
	if ( $cnt[ 'ID' ] == $cnt[ 'MainID' ] )
		$cnt = $db->fetchRow ( 'SELECT ID FROM ContentElement WHERE MainID=' . $cnt[ 'ID' ] . ' AND MainID != ID' );
	$Session->Set ( 'contentID', $cnt[ 'ID' ] );
}

/**
 * Request to change language
**/
if ( trim ( $_REQUEST[ 'setlang' ] ) > 0 && $_REQUEST[ 'setlang' ] != $Session->CurrentLanguage )
{
	$obj = new dbObject ( 'Languages' );
	if ( $obj->load ( $_REQUEST[ 'setlang' ] ) )
	{
		$Session->Set ( 'CurrentLanguage', $obj->ID );
	}
	$Session->Del ( 'contentID' );
}

/**
 * DB check for new fields that will be added in new versions
**/
if ( !$Session->CheckedContentDb )
{
	include_once ( 'include/db_checks.php' );
	$Session->Set ( 'CheckedContentDb', true );
}

/**
 * Skip some things if we need speed
**/
if ( !$_GET[ 'needspeed' ] )
{
	/**
	 * Load in some settings we need
	**/
	$Settings = new Dummy ( );
	$Settings->surpress_intro = new dbObject ( 'Setting' ); //
	$Settings->surpress_intro->SettingType = 'contents';	//  This is so we don't show the "Ingress" field
	$Settings->surpress_intro->Key = 'surpress_intro';		//
	$Settings->surpress_intro->load ( );					//
	
	/**
	 * If there is only published content then make all the work content
	**/
	$test = new dbContent ( );
	$test->addClause ( 'WHERE', 'ID != MainID' );
	if ( !( $test = $test->findSingle ( ) ) )
	{
		$pubs = new dbContent ( );
		if ( $pubs = $pubs->find ( ) )
		{
			foreach ( $pubs as $pub )
			{
				unset ( $pub->ID );
				$pub->save ( );
			}
		}
	}
}

/**
 * Make sure we have a root page
 */
$rootContent = new dbContent ( );
$rootContent = $rootContent->getRootContent ( array ( 'editmode' => 1 ) );
if ( !$Session->contentID )
{
	$Session->Set ( 'contentID', $rootContent->ID );
}

/**
 * Main, default stuff 
**/
if ( $action ) include ( $action );
if ( $function ) include ( $function );
?>
