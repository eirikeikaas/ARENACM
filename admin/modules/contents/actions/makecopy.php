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



include_once ( "admin/modules/contents/include/main.php" );
include_once ( "lib/classes/dbObjects/dbImage.php" );


$content = new dbContent ( );
$content->load ( $Session->contentID );

// Make copy
$content->ID = 0;
$content->MenuTitle = "Kopi";
$content->Title = "Kopi";
$content->Intro = "";
$content->Body = "";
$content->_isLoaded = false;
$content->IsPublished = false;

$db =& dbObject::globalValue ( "database" );
$sortorder = $db->fetchObjectRow ( "SELECT MAX(SortOrder) AS MaxSortOrder FROM ContentElement WHERE Parent='{$content->Parent}'" );
$content->SortOrder = $sortorder->MaxSortOrder + 1;

$content->save ( );

$content->MainID = $content->ID;
$content->save ( );

/** 
 * Make workable copy
**/
if ( function_exists ( 'clone' ) )
	$copy = clone ( $content );
else $copy = $content;
$copy->ID = 0;
$copy->MainID = $content->ID;
$copy->save ( );

/** 
* Duplicate extra fields
**/
$modes = Array ( "Big", "Small" );
foreach ( $modes as $mode )
{
	$ex = new dbObject ( "ContentData$mode" );
	$ex->addClause ( "WHERE", "ContentID = '{$Session->contentID}'" );
	$ex->addClause ( "WHERE", "ContentTable = \"ContentElement\"" );
	if ( $ex = $ex->find ( ) )
	{
		foreach ( $ex as $exs )
		{
			$exs->ContentID = $content->ID;
			$exs->ID = 0;
			$exs->_isLoaded = false;
			if ( $mode == "Big" )
			{
				$exs->DataText = "";
			}
			else
			{
				$exs->DataString = "";
				$exs->DataInt = "";
				$exs->DataDouble = "";
			}
			$exs->save ( );
			
			// Also for copy
			$exs->ID = 0;
			$exs->ContentID = $copy->ID;
			$exs->save ( );
		}
	}
}		
ob_clean ( );
Header ( "location: admin.php?module=contents" );
die ( "" );

?>
