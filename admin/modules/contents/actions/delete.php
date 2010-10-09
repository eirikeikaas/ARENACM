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
$content = new dbContent ();

if ( $_REQUEST[ 'varid' ] )
	$GLOBALS[ 'Session' ]->Set ( 'contentID', $_REQUEST[ 'varid' ] );
	
$content->load ( $GLOBALS[ "Session" ]->contentID );
$pid = $content->Parent;

if ( $Session->AdminUser->checkPermission ( $content, 'Structure', 'admin' ) )
{
	if ( $content->Parent > 0 )
	{
		if ( $content->MainID != $content->ID )
		{
			$live = new dbContent ( );
			$live->load ( $content->MainID );
			$live->IsDeleted = 1;
			$live->save ( );
		}
		$content->IsDeleted = 1;
		$content->save ( );
	}
	// Die with parent id
	$parent = new dbContent ( );
	if ( $parent = $parent->findSingle ( 'SELECT * FROM ContentElement WHERE MainID=\'' . $pid . '\' AND ID != MainID' ) )
	{
		die ( $parent->ID );
	}
	// Die with root
	else
	{
		$db =& dbObject::globalValue ( 'database' );
		$row = $db->fetchObjectRow ( 'SELECT * FROM ContentElement WHERE MainID != ID AND Parent=0 AND !IsTemplate AND !IsDeleted' );
		die ( $row->ID );
	}
}
die ( $content->ID );
?>
