#!/usr/bin/php5-cgi
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



define ( CONTRIBUTORS, 'Hogne Titlestad, Thomas Wollburg, Inge Jørgensen, Ola Jensen, 
Rune Nilssen' );

function checkFiles ( $parent = '', $space = '' )
{
	if ( !$parent )
		$parent = getcwd();
	if ( $dir = opendir ( $parent ) )
	{
		while ( $file = readdir ( $dir ) )
		{
			if ( $file{0} == '.' ) continue;
			if ( is_dir ( $parent . '/' . $file ) )
			{
				echo ( "{$space}Entering '$parent/$file/'\n" );
				checkFiles ( $parent . '/' . $file, $space . "\t" );
			}
			else if ( substr ( $file, -4, 4 ) != '.php' && substr ( $file, -3, 3 ) != '.js' )
			{
				continue;
			}
			else
			{
				echo ( "{$space}Processing '$file'\n" );
				$string = file_get_contents ( $parent . '/' . $file );
				if ( preg_match ( '/(\/\*[\w\W]*?http\:\/\/www\.mozilla\.org\/MPL\/[\w\W]*?\*\/)/i', $string, $matches ) )
				{
					$string = str_replace ( $matches[1], '/*******************************************************************************
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

Contributor(s): ' . CONTRIBUTORS . '
*******************************************************************************/',
					$string );
					if ( $f = fopen ( $parent . '/' . $file, 'w+' ) )
					{
						fwrite ( $f, $string );
						fclose ( $f );
						echo ( "{$space}Done processing '$parent/$file'\n" );
					}
					else echo ( "{$space}Failed to write to '$parent/$file'\n" );
				}
			} 
		}
		closedir ( $dir );
	}
}
checkFiles();
?>
