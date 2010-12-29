<?php

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

// Get sorted list of folders
$folderList = explode ( ':', $settings->Folders );
$folderList = implode ( ', ', $folderList );
$db =& dbObject::globalValue ( 'database' );

$mstr = '';

function listImages ( $pfolder, $where, $settings )
{
	// Fetch images sorted by sortorder and date
	$cstr = '';
	$cols = $settings->ThumbColumns;
	if ( $settings->SortMode == 'listmode_sortorder' )
		$where = ' ORDER BY SortOrder ASC';
	else $where = ' ORDER BY DateModified DESC';
	$imgs = new dbImage ();
	if ( $imgs = $imgs->find ( 'SELECT * FROM `Image` WHERE ImageFolder=\'' . $pfolder->ID . '\'' . $where ) )
	{
		// Thumbs
		if ( $settings->ArchiveMode == 'thumbs' )
		{
			$cstr .= '<table>';
			$col = 1;
			foreach ( $imgs as $im )
			{
				if ( $col == 1 ) $cstr .= '<tr>';
				$cstr .= '<td>';
				$cstr .= '<div class="FolderImage">';
				$cstr .= '<a href="' . $im->getImageUrl ( $settings->DetailWidth, $settings->DetailHeight, 'framed' ) . '">';
				$cstr .= $im->getImageHTML ( $settings->ThumbWidth, $settings->ThumbHeight, 'framed' );
				$cstr .= '</a>';
				$cstr .= '</div>';
				$cstr .= '</td>';
				if ( $col++ >= $cols )
				{
					$col = 1; $cstr .= '</tr>';
				}
			}
			if ( $col != 1 ) $cstr .= '</tr>';
			$cstr .= '</table>';
		}
		// List
		else
		{
			$cstr .= '<table width="100%" class="List">';
			$cstr .= '<tr>';
			$cstr .= '<th class="Image">#</th>';
			$cstr .= '<th class="Title">' . i18n ( 'Title' ) . ':</th>';
			$cstr .= '<th class="Filename">' . i18n ( 'Filename' ) . ':</th>';
			$cstr .= '<th class="Filesize">' . i18n ( 'Filesize' ) . ':</th>';
			$cstr .= '<th class="Date">' . i18n ( 'Date' ) . ':</th>';
			$cstr .= '</tr>';
			
			foreach ( $imgs as $im )
			{
				$cstr .= '<tr class="sw' . ( $sw = ( $sw == 1 ? 2 : 1 ) ) . '">';
				$cstr .= '<td class="Image">';
				$cstr .= '<a href="' . $im->getImageUrl ( $settings->DetailWidth, $settings->DetailHeight, 'framed' ) . '">';
				$cstr .= $im->getImageHTML ( $settings->ThumbWidth, $settings->ThumbHeight, 'framed' );
				$cstr .= '</a>';
				$cstr .= '</td>'; 
				$cstr .= '<td class="Title">' . $im->Title . '</td>';
				$cstr .= '<td class="Filename">' . $im->Filename . '</td>';
				$cstr .= '<td class="Filesize">' . filesizetohuman ( $im->Filesize ) . '</td>';
				$cstr .= '<td class="Date">' . ArenaDate ( DATE_FORMAT, $im->DateUpdated ) . '</td>';
				$cstr .= '</tr>';
			}
			$cstr .= '</table>';
		}
	}
	return $cstr;
}

if ( isset ( $_REQUEST[ 'fid' ] ) )
{
	$pfolder = new dbObject ( 'Folder' );
	$pfolder->load ( $_REQUEST[ 'fid' ] );
	$mstr .= '<div class="Block SelectedFolder">';
	$mstr .= '<h2>' . $pfolder->Name . '</h2>';
	$mstr .= '<hr class="SelectedFolder">';
	$mstr .= listImages ( $pfolder, $whgere, &$settings );
	$mstr .= '</div>';
	$folders = $db->fetchObjectRows ( 'SELECT * FROM `Folder` WHERE Parent=\'' . $_REQUEST[ 'fid' ] . '\' ORDER BY Name ASC' );
}
else $folders = $db->fetchObjectRows ( 'SELECT * FROM `Folder` WHERE ID IN (' . $folderList . ') ORDER BY Name ASC' );

if ( $folders )
{
	foreach ( $folders as $f )
	{
		$cstr = '';
		$cstr .= '<div class="Block FolderContainer">';
		if ( $settings->Recursion == '1' )
			$cstr .= '<a href="' . $content->getUrl () . '?fid=' . $f->ID . '">';
		$cstr .= '<h3 class="FolderName ' . texttourl ( $f->Name ) . '"><span>' . $f->Name . '</span></h3>';
		if ( $settings->Recursion == '1' )
			$cstr .= '</a>';
		$cstr .= '<div class="Block Folder">';
		
		// List out selected folder if using recursions
		// Else just list out images by folder
		if ( $settings->Recursion != '1' )
			$fid = $f->ID;
		else $fid = $_REQUEST[ 'fid' ];
		
		if ( !isset ( $_REQUEST[ 'fid' ] ) && $settings->Recursion != '1' )
			$istr = listImages ( $f, $where, &$settings );
		
		$cstr .= $istr;
		$cstr .= '</div>';
		$cstr .= '</div>';
		$cstr .= '<hr class="FolderDivider"/>';
		
		// Don't list empty folders in nonrecursive mode
		if ( !trim ( $istr ) && $settings->Recursion != '1' )
			$cstr = '';	
		$mstr .= $cstr;
	}
}

// Parent link
if ( $_REQUEST[ 'fid' ] && $settings->Recursion == '1' )
{
	// Directive to load parent folder or initial state
	$pfolder = new dbObject ( 'Folder' );
	if ( $pfolder->load ( $_REQUEST[ 'fid' ] ) )
	{
		if ( in_array ( $_REQUEST[ 'fid' ], explode ( ',', str_replace ( ' ', '', $folderList ) ) ) )
		{
			$fid = '';
		}
		else $fid = '?fid=' . $pfolder->Parent;
	}
	$mstr .= '<hr class="ParentFolder"/>';
	$mstr .= '<div class="Block ParentFolder">';
	$mstr .= '<a href="' . $content->getUrl () . $fid . '"><span>' . i18n ( 'Parent folder' ) . '</span></a>';
	$mstr .= '</div>';
}


$mtpl->folders = $mstr;

?>
