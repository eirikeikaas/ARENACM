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



if ( $_REQUEST[ 'templatename' ] )
{
	include_once ( 'admin/modules/contents/include/main.php' );
	include_once ( 'lib/classes/dbObjects/dbImage.php' );

	$content = new dbContent ( );
	if ( $Session->contentID )
		$content->load ( $Session->contentID );
	
	// Make copy
	$content->ID = 0;
	$content->MenuTitle = $_REQUEST[ 'templatename' ];
	$content->Title = $_REQUEST[ 'templatename' ];
	$content->Intro = '';
	$content->Body = '';
	$content->Parent = 0;
	$content->SystemName = '';
	$content->IsTemplate = '1';
	$content->_isLoaded = false;
	$content->IsPublished = false;
	$content->SortOrder = 0;
	$content->save ( );
	$content->MainID = $content->ID;
	$content->save ( );

	/** 
	* Duplicate extra fields
	**/
	$modes = Array ( 'Big', 'Small' );
	foreach ( $modes as $mode )
	{
		$ex = new dbObject ( "ContentData$mode" );
		$ex->addClause ( "WHERE", "ContentID = '{$Session->contentID}'" );
		$ex->addClause ( "WHERE", "ContentTable = \"ContentElement\"" );
		
		if ( $ex = $ex->find ( ) )
		{
			foreach ( $ex as $exs )
			{
				$exs->ID = 0;
				$exs->ContentID = $content->ID;
				$exs->_isLoaded = false;
				
				switch ( $exs->Type )
				{
					case 'pagelisting':
					case 'newscategory':
						break;
					default:
						if ( $mode == 'Big' )
						{
							$exs->DataText = '';
						}
						else
						{
							$exs->DataString = '';
							$exs->DataInt = '';
							$exs->DataDouble = '';
						}
						break;
				}
				$exs->save ( );
				$newids[] = $exs->ID;
			}
		}
	}
}		
ob_clean ( );
Header ( 'location: admin.php?module=contents' );
die ( '' );
?>
