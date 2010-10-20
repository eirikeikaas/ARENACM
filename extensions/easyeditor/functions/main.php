<?php

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

global $Session, $document;
include_once ( 'lib/classes/dbObjects/dbContent.php' );
include_once ( 'extensions/easyeditor/include/extrafields.php' );
$document->addResource ( 'javascript', 'extensions/easyeditor/javascript/main.js' );

if ( $_REQUEST[ 'cid' ] )
{
	$Session->Set ( 'EditorContentID', $_REQUEST[ 'cid' ] );
}
$page = new dbContent ( );
$content =& $page;

if ( !( $page->load ( $Session->EditorContentID ) ) )
{
	$Session->Del ( 'EditorContentID' );
	$page = new dbObject ( 'ContentElement' );
	$page->addClause ( 'WHERE', 'Parent=0' );
	$page->addClause ( 'WHERE', '!IsDeleted AND !IsTemplate' );
	$page->addClause ( 'WHERE', 'ID != MainID' );
	$page = $page->findSingle ();
	$Session->Set ( 'EditorContentID', $page->ID );
}
$document->addResource ( 'stylesheet', 'extensions/easyeditor/css/admin.css' );
$etpl = new cPTemplate ( 'extensions/easyeditor/templates/main.php' );
$etpl->page =& $page;

if ( $groups = explode ( ',', $page->ContentGroups ) )
{
	$out = array ();
	foreach ( $groups as $group )
	{
		if ( !trim ( $group ) ) continue;
		$out[] = '"' . trim ( $group . '"' );
	}
	$groups = implode ( ',', $out );
}
else $groups = '';

// Find content field
$db =& dbObject::globalValue ( 'database' );
if ( $field = $db->fetchObjectRow ( '
	SELECT *, "Big" AS `DataTable` FROM ContentDataBig 
	WHERE 
		ContentID = \'' . $page->ID . '\' AND 
		ContentTable = "ContentElement" AND 
		AdminVisibility >= 1
		' . ( $groups ? ( ' AND ContentGroup IN (' . $groups . ')' ) : '' ) . '
	ORDER BY SortOrder ASC LIMIT 1
' ) )
{
	$etpl->editableField = renderExtraField ( $field, $page );
}
else if ( $field = $db->fetchObjectRow ( '
	SELECT *, "Small" AS `DataTable` FROM ContentDataSmall
	WHERE 
		ContentID = \'' . $page->ID . '\' AND 
		ContentTable = "ContentElement" AND 
		AdminVisibility >= 1
		' . ( $groups ? ( ' AND ContentGroup IN (' . $groups . ')' ) : '' ) . '
	ORDER BY SortOrder ASC LIMIT 1
' ) )
{
	$etpl->editableField = renderExtraField ( $field, $page );
}
// Try to find corrupted field
else if ( $field = $db->fetchObjectRow ( '
	SELECT * FROM ContentDataBig 
	WHERE 
		ContentID = \'' . $page->ID . '\' AND 
		ContentTable = "ContentElement" 
		' . ( $groups ? ( ' AND ContentGroup IN (' . $groups . ')' ) : '' ) . '
	ORDER BY SortOrder ASC LIMIT 1
' ) )
{
	$db->query ( "UPDATE ContentDataBig SET AdminVisibility='1', IsVisible='1' WHERE ID='" . $field->ID . "'" );
	header( 'Location: admin.php?module=extensions&extension=easyeditor' ); die ( ); 
}
else if ( $field = $db->fetchObjectRow ( '
	SELECT * FROM ContentDataSmall
	WHERE 
		ContentID = \'' . $page->ID . '\' AND 
		ContentTable = "ContentElement" 
		' . ( $groups ? ( ' AND ContentGroup IN (' . $groups . ')' ) : '' ) . '
	ORDER BY SortOrder ASC LIMIT 1
' ) )
{
	$db->query ( "UPDATE ContentDataSmall SET AdminVisibility='1', IsVisible='1' WHERE ID='" . $field->ID . "'" );
	header( 'Location: admin.php?module=extensions&extension=easyeditor' ); die ( ); 
}
// Fallback should never happen!
else 
{
	$etpl->pageBody = stripslashes ( decodeArenaHTML ( $page->Body ) );
}

// Notes
if ( $note = $db->fetchObjectRow ( 'SELECT * FROM Notes WHERE ContentTable="ContentElement" AND ContentID=' . $page->ID ) )
{
	$etpl->note = str_replace ( array ( "\n", "\r" ), array ( "<br/>", "" ), stripslashes ( $note->Notes ) );
}
else $etpl->note = '';


$extension .= $etpl->render ();

?>
