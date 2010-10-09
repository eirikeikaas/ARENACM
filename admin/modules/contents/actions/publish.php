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
$current = new dbContent ( );
if ( $current->load ( $Session->contentID ) )
{
	if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $current, 'Publish', 'admin' ) )
	{
		$original = new dbContent ( );
		if ( $original->load ( $current->MainID ) )
		{
			// Update published version from working copy
			foreach ( $original->_table->getFieldNames() as $field )
				if ( $field != 'ID' ) $original->$field = $current->$field;
			$original->save ( );

			// Copy from working copy to published version
			$original->copyExtraFields ( $current->ID );
			$original->copyObjects ( $current->ID );
			$original->copyPermissions ( $current->ID );
			
			// Sync dates etc
			$original->DateModified = date ( 'Y-m-d H:i:s' );
			$current->DateModified = $original->DateModified;
			$current->save ( );
			$original->save ( );
			die ( $current->ID . '|' . 'All is good with ' . $current->MainID . ' - ' . "\nOriginal: " . $original->DateModified . "\nCurrent: " . $current->DateModified ); 
		}
	}
	die ( '0|Could not get permission!' );
}	
die ( '0|Coultn\'t load content' );
?>
