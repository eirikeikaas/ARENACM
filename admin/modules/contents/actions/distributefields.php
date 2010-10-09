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

$db =& dbObject::globalValue ( 'database' );

if ( count ( $_REQUEST[ 'fields' ] ) > 0 && count ( $_REQUEST[ 'pages' ] ) > 0 )
{
	foreach ( $_REQUEST[ 'fields' ] as $field )
	{
		list ( $id, $table ) = explode ( '_', $field );
		
		$obj = new dbObject ( 'ContentData' . $table );
		
		if ( $obj->load ( $id ) )
		{
			foreach ( $_REQUEST[ 'pages' ] as $page )
			{
				/**
				* Test if we already have such a field
				**/
				if ( !( $row = $db->fetchObjectRow ( 'SELECT * FROM `ContentData' . $table . '` WHERE ContentID=' . $page . ' AND Name = "' . $obj->Name . '"' ) ) )
				{
					/**
					* If not, then make a copy for this extra field
					**/
					
					$test = $obj->clone ( );
					$test->ID = 0;
					$test->_isLoaded = 0;
					$test->ContentID = $page;
					
					if ( $row = $db->fetchObjectRow ( 'SELECT ID FROM ContentElement WHERE MainID = \'' . $page . '\' AND MainID != ID LIMIT 1' ) )
					{
						$test->ContentID = $row->ID;
						if ( !$test->load ( ) )
							$test->save ( );
					}
				}
			}
		}
	}
}
header ( 'Location: admin.php?module=contents' );
die ( );
?>
