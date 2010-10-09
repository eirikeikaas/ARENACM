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
$db =& dbObject::globalValue ( 'database' );
ob_clean ( );

function getMainID ( $id )
{
	$content = new dbObject ( 'ContentElement' );
	if ( $content->load ( $id ) ) return $content->MainID;
	return 0;
}


$parentContent = new dbContent ( );
$parentContent->load ( $GLOBALS[ "Session"]->contentID );

if ( $Session->AdminUser->checkPermission ( $parentContent, 'Structure', 'admin' ) )
{
	
	if ( $_REQUEST[ "template" ] )
	{
		switch ( $_REQUEST[ "template" ] )
		{
			case '__normal':
				$content = new dbContent ( );
				$content->Parent = $GLOBALS[ "Session"]->contentID;
			
				$parentpage = new dbContent ( );
				if ( $parentpage->load ( $content->Parent ) )
				{
					$content->Template = $parentpage->Template;
					$content->TemplateID = $parentpage->TemplateID;
					$content->TemplateArchived = $parentpage->TemplateArchived;
				}
				
				$db = &dbObject::globalValue ( "database" );
				
				$content->MenuTitle = $_REQUEST[ 'menutitle' ];
				$content->Title = $_REQUEST[ 'title' ];
				$content->RouteName = texttourl ( $_REQUEST[ 'menutitle' ] );
				$content->IsDeleted = 0;
				$content->Parent = getMainId ( $GLOBALS[ "Session"]->contentID );
				$content->IsMain = 1;
				$content->Version = 0;
				$content->VersionPublished = 0;
				$content->Language = $Session->CurrentLanguage;
				$content->SortOrder = $db->fetchObjectRow ( "SELECT MAX(SortOrder) AS MaxSortOrder FROM ContentElement WHERE Parent='{$content->Parent}'" );
				$content->SortOrder = $content->SortOrder->MaxSortOrder + 1;
				$content->save ( );
				$content->MainID = $content->ID;
				$content->save ( );

				/**
				 * Working copy
				**/
				$content->_isLoaded = false;
				$content->MainID = $content->ID;
				$content->ID = 0;
				$content->save ( );
				$Session->Set ( 'contentID', $content->ID );
				
				// Copy permissions from parent
				$permissions = new dbObject ( 'ObjectPermission' );
				$permissions->ObjectType = 'ContentElement';
				$permissions->ObjectID = $parentpage->MainID;
				if ( $permissions = $permissions->find ( ) )
				{
					foreach ( $permissions as $p )
					{
						$p->ID = 0;
						$p->ObjectID = $content->ID;
						$p->save ( );
						$p->ID = 0;
						$p->ObjectID = $content->MainID;
						$p->save ( );
					}
				}
				
				
				break;
			default:
				/* .. get template .. */
				
				include_once ( 'admin/modules/contents/include/main.php' );
				include_once ( 'lib/classes/dbObjects/dbImage.php' );
				$content = new dbContent ( );
				if ( $content->load ( $_REQUEST[ "template" ] ) )
				{
					// Make copy
					$content->ID = 0;
					$content->MenuTitle = $_REQUEST[ 'menutitle' ];
					$content->Title = $_REQUEST[ 'title' ];
					$content->RouteName = texttourl ( $_REQUEST[ 'menutitle' ] );
					$content->Intro = '';
					$content->Body = '';
					$content->Parent = getMainId ( $GLOBALS[ 'Session' ]->contentID );
					$content->TemplateID = $_REQUEST[ 'template' ];
					$content->IsTemplate = '0';
					$content->_isLoaded = false;
					$content->IsPublished = false;
					$sortorder = $db->fetchObjectRow ( "SELECT MAX(SortOrder) AS MaxSortOrder FROM ContentElement WHERE Parent='{$content->Parent}'" );
					$content->SortOrder = $sortorder->MaxSortOrder + 1;
					$content->Language = $Session->CurrentLanguage;
					$content->save ( );
					$content->MainID = $content->ID;
					$content->save ( );
				
					// Duplicate extra fields from template
					$content->copyExtraFields ( $_REQUEST[ 'template' ] );
					
					// Create a working copy
					$oid = $content->ID;
					$content->_isLoaded = false;
					$content->MainID = $content->ID;
					$content->ID = 0;
					$content->save ( );
					$Session->Set ( 'contentID', $content->ID );
					$content->copyExtraFields ( $oid );
					addDefaultPermissions ( $content );
				}
				break;
		}
	}
}
header ( "Location: admin.php?module=contents" );
die ( );
?>
