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



$content = str_replace ( "\n", "<br/>", strip_tags ( str_replace ( array ( '</table>', '</div>', '</ul>', '<li>', '</li>', '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>', '<p>', '</p>', '<br>', '<br/>', '<br />' ), "\n", urldecode ( $_REQUEST[ 'content' ] ) ) ) );
ob_clean ( );
die ( $content );
?>
