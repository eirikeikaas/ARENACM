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

function getPreview ( $settings, $field )
{
	$str = '';
	
	if ( !$settings->ThumbWidth ) 
		$settings->ThumbWidth = 80;
	if ( !$settings->ThumbHeight ) 
		$settings->ThumbHeight = 60;
	if ( !$settings->ThumbColumns ) 
		$settings->ThumbColumns = 4;
	
	$folders = explode ( ',', trim ( $settings->Folders ) );
	if ( $settings->currentMode == 'gallery' )
	{
		if ( count ( $folders ) )
		{
			foreach ( $folders as $fld )
			{
				$imgs = new dbImage ();
				$imgs->addClause ( 'WHERE', 'ImageFolder=\'' . $fld . '\'' );
				if ( $settings->SortMode == 'listmode_sortorder' )
				{
					$imgs->addClause ( 'ORDER BY', 'SortOrder ASC' );
				}
				else
				{
					$imgs->addClause ( 'ORDER BY', 'DateModified DESC' );
				}
				
				if ( $images = $imgs->find ( ) )
				{
					$i = 0;
					foreach ( $images as $image )
					{
						$str .= $image->getImageHTML ( $settings->ThumbWidth, $settings->ThumbHeight, 'framed' );
						if ( ++$i >= $settings->ThumbColumns )
						{
							$str .= '<br/>';
							$i = 0;
						}
					}
				}
			}
		}
		if ( !$str ) $str = '<p>Ingen bilder er lagt til.</p>'; 
	}
	// else slideshow
	else
	{
		if ( count ( $folders ) )
		{
			$i = new dbImage ( );
			$i->addClause ( 'WHERE', 'ImageFolder=\'' . $folders[0] . '\'' );
			if ( $i = $i->findSingle () )
				$str = $i->getImageHTML ( 400, 300, 'framed' );
		}
		if ( !$str ) $str = '<p>Ingen bilder er lagt til.</p>'; 
	}
	return $str;
}

function listFolders ( $str, $field )
{
	if ( $ids = explode ( ',', trim ( $str ) ) )
	{
		$str = '<table cellspacing="0" cellpadding="0" border="0" width="100%" class="Gui">';
		foreach ( $ids as $id )
		{
			$fld = new dbObject ( 'Folder' );
			if ( $fld->load ( $id ) )
			{
				$str .= '<tr class="sw' . ( $sw = ( $sw == 1 ? 2 : 1 ) ) . '">';
				$str .= '<td width="100%">' . $fld->Name . '</td>';
				$str .= '<td><a onclick="agalRemoveBrick(' . $fld->ID . ', ' . $field->ID . ')" href="javascript:void(0)"><img src="admin/gfx/icons/brick_delete.png" border="0"/></a></td>';
				$str .= '</tr>';
			}
		}
		return $str . '</table>';
	}
	return '<p>Ingen mapper er lagt til.</p>';
}

function saveGalSettings ( $settings, $field )
{
	foreach ( $settings as $k=>$v )
	{
		$out[] = "$k\t$v";
	}
	$f = new dbObject ( 'ContentDataSmall' );
	$f->load ( $field->ID );
	$f->DataMixed = implode ( "\n", $out );
	$f->save ( );
}

?>
