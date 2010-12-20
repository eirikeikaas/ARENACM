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

global $document;
$document->addResource ( 'stylesheet', 'skeleton/modules/mod_gallery/css/web.css' );
$document->addResource ( 'javascript', 'skeleton/modules/mod_gallery/javascript/web.js' );
$mtpldir = 'skeleton/modules/mod_gallery/templates/';
i18nAddLocalePath ( 'skeleton/modules/mod_gallery/locale');
$settings = CreateObjectFromString ( $fieldObject->DataMixed );
if ( $settings->currentMode == 'gallery' )
{
	$mtpl = new cPTemplate ( $mtpldir . 'web_gallery.php' );
	$str = '';
	if ( trim ( $settings->Heading ) )
		$str .= '<h1 class="Heading">' . trim ( $settings->Heading ) . '</h1>';
	if ( !$settings->ThumbWidth ) $settings->ThumbWidth = 80;
	if ( !$settings->ThumbHeight ) $settings->ThumbHeight = 60;
	if ( !$settings->ThumbColumns ) $settings->ThumbColumns = 4;
	$folders = explode ( ':', trim ( $settings->Folders ) );
	if ( count ( $folders ) )
	{
		foreach ( $folders as $fld )
		{
			$imgs = new dbImage ();
			$imgs->addClause ( 'WHERE', 'ImageFolder=\'' . $fld . '\'' );
			if ( $settings->SortMode == 'listmode_sortorder' )
				$imgs->addClause ( 'ORDER BY', 'SortOrder ASC' );
			else $imgs->addClause ( 'ORDER BY', 'DateModified DESC' );
			
			if ( $images = $imgs->find ( ) )
			{
				$i = 0;
				foreach ( $images as $image )
				{
					$detail = new dbImage ( $image->ID );
					if ( !$settings->DetailWidth || !$settings->DetailHeight )
						$fn = 'upload/images-master/' . $image->Filename;
					else
						$fn = $detail->getImageUrl ( $settings->DetailWidth, $settings->DetailHeight, 'proximity' );
					
					$url = $image->getImageUrl ( $settings->ThumbWidth, $settings->ThumbHeight, 'framed' );
					
					$str .= '<a href="javascript:void(0)" onclick="showImage(this.getAttribute(\'link\'))" link="' . $fn . '"><img src="' . $url . '" width="' . $image->cachedWidth . '" height="' . $image->cachedHeight . '"/></a>';
					if ( ++$i >= $settings->ThumbColumns )
					{
						$str .= '<br/>';
						$i = 0;
					}
				}
			}
			if ( substr ( $str, -5, 5 ) != '<br/>' )
				$str .= '<br/>';
		}
	}
	if ( !$str ) $str = '<p>Ingen bilder er lagt til.</p>'; 
	$mtpl->output = $str;
}
else
{
	$mtpl = new cPTemplate ( $mtpldir . 'web_main.php' );
}
$mtpl->settings =& $settings;
$mtpl->field =& $fieldObject;
$module .= $mtpl->render ( );
?>
