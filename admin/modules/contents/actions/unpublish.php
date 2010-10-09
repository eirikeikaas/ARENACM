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



$published = new dbContent ( );
if ( $published->load ( $Session->contentID )
{
	if ( $published->MainID != $published->ID )
		$published->load ( $published->MainID );
	
	if ( $GLOBALS[ "Session"]->AdminUser->checkPermission ( $published, 'Write', 'admin' ) )
	{
		$workcopy = new dbContent ( );
		$workcopy->addClause ( 'WHERE', 'ID != MainID' );
		$workcopy->addClause ( 'WHERE', 'MainID=' . $published->ID );
		if ( $workcopy = $workcopy->findSingle ( ) )
		{
			// Update published version from working copy
			foreach ( $workcopy->_table->getFieldNames() as $field )
				if ( $field != 'ID' ) $workcopy->$field = $published->$field;
			$workcopy->save ( );

			// Copy from working copy to published version
			$workcopy->copyExtraFields ( $published->ID );
			$workcopy->copyPermissions ( $published->ID );
			
			// Sync dates etc
			$published->DateModified = date ( 'Y-m-d H:i:s' );
			$published->save ( );
			$workcopy->DateModified = $published->DateModified;
			$workcopy->save ( );
			die ( $workcopy->ID );
		}
	}
}	
ob_clean ( );
die ( $Session->contentID );
?>
