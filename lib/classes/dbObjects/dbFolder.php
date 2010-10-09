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



class dbFolder extends dbObject
{
	var $_tableName = "Folder";
	var $_files = Array ( );
	var $_folders = Array ( );
	
	function dbFolder ( $id = false )
	{
		$this->loadTable ( );
		if ( $id ) $this->load ( $id );
	}
	
	/**
	 * Get a list of folders
	**/
	function getFolders ( $filter = false )
	{
		if ( !$this->_isLoaded )
			$this->load ( );
		if ( !$this->_isLoaded ) return false;
		
		$folders = new dbFolder ( );
		$folders->addClause ( "WHERE", "Parent='{$this->ID}'" );
		$folders->addClause ( "ORDER BY", "Name ASC, ID ASC" );
		$this->_folders = $folders->find ( );
		return $this->_folders;
	}
	
	/**
	 * Get a list of files to later be found in $object->_files
	**/
	function getFiles ( $filter = false )
	{
		if ( !$this->_isLoaded )
			$this->load ( );
		if ( !$this->_isLoaded ) return false;
		
		if ( count ( $this->_files ) )
			return $this->_files;
		
		$db =& $this->getDatabase ( );
		if ( $rows = $db->fetchObjectRows ( "
			SELECT * FROM 
			(
				(
					SELECT ID, \"Image\" AS `Type`, Filename, Title, Filesize, ImageFolder as Folder FROM `Image` 
					WHERE ImageFolder='{$this->ID}' 
				)
				UNION
				(
					SELECT ID, \"File\" AS `Type`, Filename, Title, Filesize, FileFolder AS Folder FROM `File` 
					WHERE FileFolder='{$this->ID}'
				)
			) AS z;
		" ) )
		{
			$this->_files = Array ( );
			foreach ( $rows as $row )
			{
				$this->_files[] = $row;
			}
			return $this->_files;
		}
	}
	
	/**
	 * Reload files list
	**/
	function reloadFiles ( )
	{
		$this->_files = false;
		$this->getFiles ();
	}
	
	/**
	 * Create real objects out of the files list
	**/
	function makeFileObjects ( )
	{
		if ( count ( $this->_files ) )
		{
			foreach ( $this->_files as $k=>$v )
			{
				if ( $this->_files[ $k ]->Type == "Image" )
				{
					$obj = new dbImage ( );
					$obj->load ( $this->_files[ $k ]->ID );
					$this->_files[ $k ] = $obj;
				}
				else if ( $this->_files[ $k ]->Type == "File" )
				{
					$obj = new dbFile ( );
					$obj->load ( $this->_files[ $k ]->ID );
					$this->_files[ $k ] = $obj;
				}
			}
		}
	}
	
	/**
	 * Get root folder
	**/
	function getRootFolder ( )
	{
		$fld = new dbFolder ( );
		if ( !( $fld = $fld->findSingle ( "SELECT * FROM Folder WHERE Parent='0'" ) ) )
		{
			$fld = new dbFolder ( );
			$fld->Parent = 0;
			$fld->Name = "Arkiv";
			$fld->DateCreated = $fld->DateModified = date ( 'Y-m-d H:i:s' );
			$fld->save ( );
		}
		return $fld;
	}
	
	/**
	 * Get by path
	**/
	function getByPath ( $path )
	{
		$path = explode ( '/', $path );
		$type = get_class ( $this );
		$fld = new $type ( );
		$fld = $fld->getRootFolder ( );
		while ( count ( $path ) )
		{
			$newfold = new $type ( );
			$newfold->Parent = $fld->ID;
			$newfold->Name = $path[ 0 ];
			if ( $newfold->load ( ) )
			{
				$path = array_reverse ( $path );
				array_pop ( $path );
				$path = array_reverse ( $path );
				$fld = $newfold;
			}
			else 
			{
				die ( $newfold->_lastQuery );
				return false;
			}
		}
		return $newfold;
	}
	
	/**
	 * Delete all images in this folder
	**/
	function deleteImages ( )
	{
		require_once ( 'dbImage.php' );
		if ( !$this->ID ) return false;
		$images = new dbImage ( );
		$images->ImageFolder = $this->ID;
		if ( $images = $images->find ( ) )
		{
			foreach ( $images as $image )
				$image->delete ( );
			return true;
		}
		return false;
	}
	
	/**
	 * Delete all files in this folder
	**/
	function deleteFiles ( )
	{
		require_once ( 'dbFile.php' );
		if ( !$this->ID ) return false;
		$files = new dbFile ( );
		$files->FileFolder = $this->ID;
		if ( $files = $files->find ( ) )
		{
			foreach ( $files as $file )
				$file->delete ( );
			return true;
		}
		return false;
	}
}
?>
