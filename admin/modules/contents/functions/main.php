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



global $Session;
$module = new cPTemplate ( "$tplDir/main.php" );

include_once ( "admin/modules/contents/include/main.php" );

/**
 * Load current page and connect it to template
 */
$content = new dbContent ( );
$content->_editmode = true;
$content->load ( $Session->contentID );
if ( !$content->ID ) 
	$content = $rootContent;
// We must not have the master content element
// we must always work on a copy
if ( $content->MainID == $content->ID )
{
	$db =& dbObject::globalValue ( 'database' );
	if ( $row = $db->fetchObjectRow ( 'SELECT ID FROM ContentElement WHERE ID != ' . $content->ID . ' AND MainID = ' . $content->ID ) )
		$content->load ( $row->ID );
	else
	{
		$content->ID = 0;
		$content->save ( );
	}
	$content->_editmode = true;
	$Session->Set ( 'contentID', $content->ID );
}


if ( !$Session->AdminUser->checkPermission ( $content, 'Read', 'admin' ) )
{
	ArenaDie ( 'Du har ikke nok rettigheter til å lese dette dokumentet.' );
}


/**
 * Important dataconnections
 */
$module->content = &$content;
$module->rootContent = &$rootContent;

/**
 * Add languages
**/
$languages = new dbObject ( "Languages" );
if ( !$language = $languages->findSingle ( ) )										// Create default language if one doesn't exist
{
	$language = new dbObject ( 'Languages' );
	$language->Name = 'Default';
	$language->ShortName = 'c';
	$language->save ( );
}

if ( $languages = $languages->find ( ) )
{
	foreach ( $languages as $language )
	{
		if ( $language->ID == $Session->CurrentLanguage ) $s = " selected=\"selected\"";
		else $s = "";
		$module->languages .= "<option value=\"{$language->ID}\"$s>{$language->NativeName} ({$language->Name})</option>";
	}
}
?>
