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



if ( is_object ( $corebase ) )
{
	if ( $_REQUEST[ 'installer' ] != '2' )
	{
		$tpl = new cPTemplate ( 'admin/templates/install.php' );
		ob_clean ( );
		die ( $tpl->render ( ) );
	}
	else
	{
		if ( $f = fopen ( 'lib/arena2_structure.sql', 'r' ) )
		{
			$sql = fread ( $f, filesize ( 'lib/arena2_structure.sql' ) );
			
			if ( $sqls = explode ( ';', $sql ) )
			{
				// Create and fill new database
				$oldDb = $corebase->db;
				$corebase->query ( 'CREATE DATABASE ' . $_REQUEST[ 'SqlDatabase' ] );
				$corebase->query ( 'USE ' . $_REQUEST[ 'SqlDatabase' ] );
				foreach ( $sqls as $sql )
				{
					$corebase->query ( $sql );
				}
				
				// Set it up
				$corebase->query ( 'USE ' . $oldDb );
				$corebase->query ( '
					INSERT INTO Sites 
					( SiteName, SqlUser, SqlPass, SqlHost, SqlDatabase, BaseUrl, BaseDir ) VALUES
					( 
						"' . $_REQUEST[ 'SiteName' ] . '", "' . $_REQUEST[ 'SqlUser' ] . '",
						"' . $_REQUEST[ 'SqlPass' ] . '", "' . $_REQUEST[ 'SqlHost' ] . '",
						"' . $_REQUEST[ 'SqlDatabase' ] . '", "' . $_REQUEST[ 'BaseUrl' ] . '",
						"' . $_REQUEST[ 'BaseDir' ] . '" 
					);
				' );
				$id = mysql_insert_id ( );
				// Settings module
				$corebase->query ( '
					INSERT INTO ModulesEnabled
					( SiteID, Module, SortOrder, ModuleName, ModuleIcon ) VALUES 
					( ' . $id . ', "settings", 0, "Innstillinger", "wrench.png" )
				' );
				// Contents module
				$corebase->query ( '
					INSERT INTO ModulesEnabled
					( SiteID, Module, SortOrder, ModuleName, ModuleIcon ) VALUES 
					( ' . $id . ', "contents", 1, "Innhold", "wrench.png" )
				' );
			}
			fclose ( $f );
			header ( 'Location: admin.php' );
		}
		else
		{
			ArenaDie ( 'Failed to open arena2 database structure!' );
		}
	}
}
else
{
	die ( 'Failed to find corebase object!' );
}
?>
