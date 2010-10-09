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


include_once ( 'admin/modules/contents/include/main.php' );
include_once ( 'lib/classes/dbObjects/dbImage.php' );
global $Session;
$efScripts = Array ( ); // extrafield scripts - so they can be updated

if ( $_POST[ 'MenuTitle' ] )
{
	$db =& dbObject::globalValue ( 'database' );
	$content = new dbContent ( );
	if ( $_POST[ 'ID' ] ) 
	{
		$content->load ( $_POST[ 'ID' ] );
	}
	else 
	{
		$db =& dbObject::globalValue ( 'database' );
		$sortorder = $db->fetchObjectRow ( 'SELECT MAX(SortOrder) AS MaxSortOrder FROM ContentElement' );
		$content->SortOrder = $sortorder->MaxSortOrder + 1;
		$content->Language = $rootContent->Language;
	}
	
	if ( $Session->AdminUser->checkPermission ( $content, 'Write', 'admin' ) )
	{
		$content->receiveForm ( $_POST );
		$content->MenuTitle = str_replace ( array ( '<p>', '</p>' ), '', $content->MenuTitle );
		
		$content->Intro = decodeArenaHTML ( cleanHTMLElement ( $content->Intro ) );
		$content->Body = decodeArenaHTML ( cleanHTMLElement ( $content->Body ) );
		
		if ( $content->MainID == $content->ID ) $content->ID = 0;
			
		$content->DateModified = date ( 'Y-m-d H:i:s' );
		$content->save ( );
		
		$Session->Set ( 'contentID', $content );
	
		// Update extra fields from form
		$efScripts = $content->updateExtraFields ( );
		
		/** 
		* If we're having a module prefs panel, include the save routine 
		**/
		if ( file_exists ( "admin/modules/{$content->ContentType}/actions/pageconfig.php" ) )
		{
			include_once ( "admin/modules/{$content->ContentType}/actions/pageconfig.php" );
			
			// Save content
			$content->save ( );
		}
	}
	

	ob_clean ( );
	die ( "
	<html>
		<head><title>Action</title></head>
		<body>
			<script type=\"text/javascript\"> 
				var fullupdate = true;
				if ( parent.document.getElementById ( 'pageTemplate' ) )
				{
					if ( parent.document.getElementById ( 'pageTemplate' ).className != 'page' )
					{
						parent.showMainContent ( '{$content->ID}', 'tabTemplate' );
						fullupdate = false;
					}
				}
				if ( fullupdate )
				{
					parent.getPublishButton ( '{$content->ID}' ); 
					parent.showEditButtons ( '{$content->ID}' );
					parent.getVisibilityState ( '{$content->ID}' );
				}
				" . implode ( "\n", $efScripts ) . "
				parent.showStructure ( '{$content->ID}' ); 
			</script>
		</body>
	</html>
	" );
}
else
{
	ob_clean ();
	die ( '<script> alert ( "Failed to save document!" ); </script>' );
}
?>
