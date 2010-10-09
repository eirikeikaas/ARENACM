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

if ( $_REQUEST[ "ids" ] )
{
	$ids = explode ( ",", $_REQUEST[ "ids" ] );
	$str = Array ( );
	foreach ( $ids as $id )
	{
		$str[] = "ID='$id'";
	}
	$str = implode ( " OR ", $str );
	$db =& dbObject::globalValue ( "database" );
	if ( $rows = $db->fetchObjectRows ( "SELECT ID FROM ContentElement WHERE !IsDeleted AND IsTemplate AND ( $str )" ) );
	{
		foreach ( $rows as $row )
		{
			$db->query ( "DELETE FROM ContentElement WHERE ID='{$row->ID}'" );
			$db->query ( "DELETE FROM ContentDataSmall WHERE ContentID='{$row->ID}' AND ContentTable='ContentElement'" );
			$db->query ( "DELETE FROM ContentDataBig WHERE  ContentID='{$row->ID}' AND ContentTable='ContentElement'" );
		}
	}
}

die ( );
?>
