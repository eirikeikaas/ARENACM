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



ob_clean ( );
if ( !$_POST[ "contentid" ] )
	die ( );
	
$db =& dbObject::globalValue ( "database" );

list ( $maxs, ) = $db->fetchRow ( 'SELECT MAX(SortOrder) FROM ContentDataSmall WHERE ContentID=\'' . $_POST[ 'contentid' ] . '\' AND ContentTable="' . $_POST[ 'table' ] . '"' );
list ( $maxb, ) = $db->fetchRow ( 'SELECT MAX(SortOrder) FROM ContentDataBig WHERE ContentID=\'' . $_POST[ 'contentid' ] . '\' AND ContentTable="' . $_POST[ 'table' ] . '"' );
$max = ( $maxs > $maxb ? $maxs : $maxb ) + 1;

switch ( $_POST[ "type" ] )
{
	case "text":
		$obj = new dbObject ( "ContentDataBig" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataText = "";
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	case "leadin":
		$obj = new dbObject ( "ContentDataBig" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataText = "";
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use datastring for short text
	case "formprocessor":
		$obj = new dbObject ( "ContentDataBig" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataText = "";
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use datastring for short text
	case "varchar":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataString = "";
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use datastring for short text
	case "extension":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataString = "";
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use dataint for resulting image
	case "image":
	// Will use dataint for resulting file
	case "file":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataInt = 0;
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use object connections to this one
	case "objectconnection":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataInt = $_POST[ "contentid" ];
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Will use DataInt for the page root of the listing
	case "pagelisting":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataInt = 0;
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	case "newscategory":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataInt = 0;
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	// Styles and scripts
	case "style":		
	case "script":
		$obj = new dbObject ( "ContentDataSmall" );
		$obj->ContentID = $_POST[ "contentid" ];
		$obj->Name = $_POST[ "name" ];
		$obj->DataInt = 0;
		$obj->Type = $_POST[ "type" ];
		$obj->SortOrder = $max;
		$obj->ContentTable = $_POST[ "table" ];
		$obj->save ( );
		break;
	default:
		break;
}
die ( );
?>
