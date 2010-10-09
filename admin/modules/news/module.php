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



// Prerequisites
$tplDir = 'admin/modules/news/templates';

include_once ( 'lib/functions/functions.php' );
$db = &dbObject::globalValue ( 'database' );
$table = new cDatabaseTable ( 'NewsCategory' );
if ( $table->load ( ) )
{
	$hasBackPage = false;
	foreach ( $table->getFieldNames ( ) as $f )
	{
		if ( $f == 'BackPageID' )
			$hasBackPage = true;
	}
	if ( !$hasBackPage ) $db->query ( 'ALTER TABLE `NewsCategory` ADD `BackPageID` int(11) NOT NULL default 0 AFTER `ContentElementID`' );
}

if ( $_GET[ 'cid' ] ) 
{
	switch ( $_GET[ 'cid' ] )
	{
		case 'all':
			$Session->Del ( 'news_currentCategory' );
			break;
		default:
			$Session->Set ( 'news_currentCategory', $_GET[ 'cid' ] );
			break;
	}
}

$category = new dbObject ( 'NewsCategory' );

// On language change, also check for category existance
if ( $_GET[ 'language' ] )
{
	$GLOBALS[ 'Session' ]->Set ( 'CurrentLanguage', $_GET[ 'language' ] );
	$Session->Del ( 'news_currentCategory' );
	$category->addClause ( 'WHERE', "Language='{$GLOBALS["Session"]->CurrentLanguage}'" );
	$category = $category->findSingle ( );
	if ( !$category )
	{
		$category = new dbObject ( 'NewsCategory' );
		$category->SystemName = 'default';
		$category->Name = 'Hovedkategori';
		$category->Language = $GLOBALS[ 'Session' ]->CurrentLanguage;
		$category->Parent = 0;
		$category->save ( );
		$Session->Set ( 'news_currentCategory', $category->ID );
	}
}
// Normal steps
else
{
	if ( !$category->findCount ( ) )
	{
		$category->SystemName = 'default';
		$category->Name = 'Hovedkategori';
		$category->Parent = 0;
		$category->save ( );
		$Session->Set ( 'news_currentCategory', $category->ID );
	}
	else if ( !$Session->news_currentCategory )
	{
		$category->addClause ( 'WHERE', "Language='{$GLOBALS["Session"]->CurrentLanguage}' AND SystemName='default'" );
		$category = $category->findSingle ( );
	}
	else
		$category->load ( $Session->news_currentCategory );
}

// Main, default stuff 

if ( $action ) include ( $action );
if ( $function ) include ( $function );

// Defaults

$module->Category = $category;
?>
