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

global $Session;
i18nAddLocalePath ( 'lib/skeleton/modules/mod_blog/locale/' );
include_once ( 'lib/skeleton/modules/mod_blog/translations.php' );

$mtpldir = 'skeleton/modules/mod_blog/templates/';
$GLOBALS[ 'document' ]->addResource ( 'stylesheet', $mtpldir . '../css/admin.css' );

if ( !$Session->mod_blog_initialized )
{
	$db =& dbObject::globalValue ( 'database' );
	$tb = new cDatabaseTable ( 'BlogItem' );
	if ( !$tb->load ( ) )
	{
		
		$db->query ( '
			CREATE TABLE `BlogItem`
			(
				`ID` int(11) auto_increment NOT NULL,
				`UserID` bigint(20) NOT NULL,
				`ContentElementID` bigint(20) NOT NULL,
				`AuthorName` varchar(255) NOT NULL,
				`IsPublished` tinyint(4) NOT NULL DEFAULT 0,
				`Title` varchar(255) NOT NULL,
				`Leadin` text NOT NULL,
				`Body` text NOT NULL,
				`Tags` varchar(255) NOT NULL,
				`DatePublish` datetime NOT NULL,
				`DateCreated` datetime NOT NULL,
				`DateUpdated` datetime NOT NULL,
				PRIMARY KEY(ID)
			)
		' );
	}
	$tb = new cDatabaseTable ( 'BlogTag' );
	if ( !$tb->load ( ) )
	{
		$db->query ( '
			CREATE TABLE `BlogTag`
			(
				`ID` int(11) auto_increment NOT NULL,
				`Name` varchar(255) NOT NULL,
				`Rating` bigint(20) NOT NULL default 0,
				`DateUpdated` datetime,
				PRIMARY KEY(ID)
			)
		' );
	}
}

if ( !function_exists ( 'listBlogs' ) )
{
	function listBlogs ( $contentid, $start = 0, $lim = 10 )
	{
		global $Session;
		if ( !$contentid ) $contentid = $GLOBALS[ 'content' ]->MainID;
		if ( !$start ) $start = 0;
		$content = new dbContent ( $contentid );
		$blogs = new dbObject ( 'BlogItem' );
		$blogs->addClause ( 'WHERE', 'ContentElementID=' . $content->MainID );
		$cnt = $blogs->findCount ( );
		$blogs->addClause ( 'LIMIT', (string)$start . ', ' . (string)$lim );
		$blogs->addClause ( 'ORDER BY', 'DatePublish DESC, ID DESC' );
		if ( $blogs = $blogs->find ( ) )
		{
			$str = '<table class="Overview LayoutColumns">';
			foreach ( $blogs as $blog )
			{
				$str .= '<tr class="' . ( $sw = ( $sw == 'sw2' ? 'sw1' : 'sw2' ) ) . '">';
				$str .= '<td><strong>' . $blog->Title . '</strong></td><td>av ' . $blog->AuthorName . '</td>';
				$str .= '<td>Dato: ' . ArenaDate ( DATE_FORMAT, $blog->DateUpdated ) . '</td>';
				$str .= '<td>' . ( $blog->IsPublished ? 'Publisert' : 'Arkivert' ) . '</td>';
				$str .= '<td>Vises fra: ' . ArenaDate ( DATE_FORMAT, $blog->DatePublish ) . '</td>';
				$str .= '</tr>';
				$str .= '<tr class="' . ( $sw = ( $sw == 'sw2' ? 'sw1' : 'sw2' ) ) . '"><td colspan="5">' . $blog->Leadin . '</td></tr>';
				if ( $contentid == $Session->EditorContentID )
				{
					$str .= '<tr class="' . $sw . '"><td colspan="5">';
					$str .= '<button type="button" onclick="mod_blog_edit(' . $blog->ID . ')"><img src="admin/gfx/icons/page_white_edit.png"/> Endre</button>';
					$str .= '<button type="button" onclick="mod_blog_delete(' . $blog->ID . ')"><img src="admin/gfx/icons/page_white_delete.png"/> Slett</button>';
					$str .= '</td></tr>';
				}
			}
			$str .= '</table>';
			
			$navigation = '';
			if ( $start > 0 )
			{
				$np = $start - $lim; if ( $np < 0 ) $np = "0";
				$navigation .= '<button type="button" onclick="mod_blog_pos(\'' . $np . '\')"><img src="admin/gfx/icons/arrow_left.png"> Forrige side</button>';
			}
			if ( $start + $lim < $cnt )
			{
				$np = $start + $lim; 
				$navigation .= '<button type="button" onclick="mod_blog_pos(\'' . $np . '\')">Neste side <img src="admin/gfx/icons/arrow_right.png"></button>';
			}
			
			return $str . ( $navigation ? "<hr>$navigation" : '' );
		}
		return '<p>Ingen blogger finnes.</p>';
	}
}	
switch ( $_REQUEST[ 'modaction' ] )
{
	case 'preview':
		i18nAddLocalePath ( BASE_DIR . '/locale' );
		// Some other config vars
		$cfg = explode ( "\t", $fieldObject->DataMixed );
		$cfgComments = $cfg[0];
		$cfgShowAuthor = $cfg[1];
		$mtpl = new cPTemplate ( "{$mtpldir}adm_preview.php" );
		$blog = new dbObject ( 'BlogItem' );
		$blog->load ( $_REQUEST[ 'bid' ] );
		$mtpl->blog =& $blog;
		$mtpl->bloghtml = new cPTemplate ( "{$mtpldir}web_blog.php" );
		$mtpl->bloghtml->blog =& $blog;
		$mtpl->bloghtml->content =& $content;
		$mtpl->bloghtml->cfgShowAuthor = $cfgShowAuthor;
		$mtpl->bloghtml->cfgComments = $cfgComments;
		$mtpl->bloghtml = $mtpl->bloghtml->render ( );
		die ( $mtpl->render ( ) );
		
	case 'savesettings':
		$fld = new dbObject ( 'ContentDataSmall' );
		$fld->load ( $fieldObject->ID );
		$fld->DataInt = $_POST[ 'limit' ];
		$fld->DataMixed = 	$_POST[ 'comments' ] . "\t" . 
							$_POST[ 'showauthor' ] . "\t" . 
							$_POST[ 'tagbox' ] . "\t" . 
							$_POST[ 'tagbox_placement' ] . "\t" . 
							$_POST[ 'searchbox' ] . "\t" . 
							$_POST[ 'detailpage' ] . "\t" .
							$_POST[ 'sourcepage' ] . "\t" .
							$_POST[ 'leadinlength' ] . "\t" .
							$_POST[ 'titlelength' ] . "\t" .
							$_POST[ 'sizex' ] . "\t" .
							$_POST[ 'sizey' ] . "\t" . 
							$_POST[ 'headertext' ] . "\t" . 
							$_POST[ 'hidedetails' ] . "\t" .
							$_POST[ 'facebooklike' ] . "\t" .
							$_POST[ 'facebooklikedimensions' ];
		$fld->save ( );
		die ( 'ok' );
		
	case 'delete':
		$blog = new dbObject ( 'BlogItem' );
		if ( $blog->load ( $_REQUEST[ 'bid' ] ) )
			$blog->delete ( );
		$std = new cPTemplate ( $mtpldir . 'adm_std.php' );
		$std->blogs = listBlogs ( $fieldObject->ContentID, $_REQUEST[ 'bpos' ], $fieldObject->DataInt ? $fieldObject->DataInt : 10 );
		die ( $std->render ( ) );
	
	case 'saveimage':
		$blog = new dbObject ( 'BlogItem' );
		if ( $blog->load ( $_REQUEST[ 'bid' ] ) )
		{
			// Remove old images
			if ( $images = $blog->getObjects ( 'ObjectType = Image' ) )
			{
				foreach ( $images as $image )
				{
					$blog->removeObject ( $image );
					$image->delete ( );
				}
			}
			$img = new dbImage ( );
			$img->receiveUpload ( $_FILES[ 'Image' ] );
			if ( $img->save ( ) )
			{
				$blog->addObject ( $img );
				die ( '
					<script> 
						parent.document.getElementById ( \'BlogImagePreview\' ).innerHTML = "<a href=\"javascript:mod_blog_removeimage(' . $blog->ID . ')\">' . 
							addslashes ( $img->getImageHTML ( 32, 32, 'framed' ) ) . '</a>"; 
					</script>
				' );
			}
		}		
		die ( '<script> parent.document.getElementById ( \'BlogImagePreview\' ).innerHTML = "Kunne ikke laste opp bildet."; </script>' );
		
	case 'removeimage':
		$blog = new dbObject ( 'BlogItem' );
		if ( $blog->load ( $_REQUEST[ 'bid' ] ) )
		{
			// Remove old images
			if ( $images = $blog->getObjects ( 'ObjectType = Image' ) )
			{
				foreach ( $images as $image )
				{
					$blog->removeObject ( $image );
					$image->delete ( );
				}
			}
		}
		break;
		
	case 'save':
		$db =& dbObject::globalValue ( 'database' );
		// Clean some messy stuff
		$db->query ( 'DELETE FROM BlogItem WHERE ContentElementID <= 0' );
		$blog = new dbObject ( 'BlogItem' );
		if ( $_REQUEST[ 'bid' ] )
		{
			$blog->load ( $_REQUEST[ 'bid' ] );
			$blog->DateUpdated = date ( 'Y-m-d H:i:s' );
		}
		else
		{
			$blog->DateCreated = date ( 'Y-m-d H:i:s' );
			$blog->DateUpdated = $blog->DateCreated;
		}
		$tags = explode ( ',', $_REQUEST[ 'Tags' ] );
		foreach ( $tags as $tag )
		{
			$t = new dbObject ( 'BlogTag' );
			$t->Name = trim ( $tag );
			$t->load ( );
			$t->DateUpdated = date ( 'Y-m-d H:i:s' );
			$t->save ( );
		}
		$cnt = new dbContent ( $GLOBALS[ 'Session' ]->EditorContentID );
		$blog->ContentElementID = $cnt->MainID;
		$blog->receiveForm ( $_POST );
		if ( !$blog->UserID && $GLOBALS[ 'user' ]->_dataSource != 'core' ) 
			$blog->UserID = $GLOBALS[ 'user' ]->ID;
		$blog->save ( );
		
		// Maintain publish queue
		$queue = new dbObject ( 'PublishQueue' );
		$queue->ContentElementID = $content->MainID;
		$queue->ContentID = $blog->ID;
		$queue->ContentTable = "BlogItem";
		$queue->FieldName = 'IsPublished';
		$queue->load ( );
		if ( (int)$blog->IsPublished <= 0 ) 
		{
			$queue->LiteralName = 'Blog';
			$queue->Title = $blog->Title;
			$queue->save ( );
		}
		else if ( (int)$queue->ID > 0 )
			$queue->delete ( );

		// Die with the ID nr of the blog item
		die ( $blog->ID . ' - ' . $blog->DatePublish );
		
	case 'new':
		$mtpl = new cPTemplate ( $mtpldir . 'adm_blog.php' );
		die ( $mtpl->render ( ) );
	
	case 'authentication':
		$mtpl = new cPTemplate ( $mtpldir . 'adm_authentication.php' );
		$blogs = new dbObject ( 'BlogItem' );
		$blogs = $blogs->find ( 'SELECT * FROM BlogItem WHERE !IsPublished AND ContentElementID = 0 ORDER BY DateUpdated DESC' );
		$mtpl->blogs =& $blogs;
		die ( $mtpl->render ( ) );
		
	case 'edit':
		$mtpl = new cPTemplate ( $mtpldir . 'adm_blog.php' );
		$blog = new dbObject ( 'BlogItem' );
		$blog->load ( $_REQUEST[ 'bid' ] );
		$mtpl->blog =& $blog;
		die ( $mtpl->render ( ) );
		
	case 'standard':
	default:	
		$act = 'lib/skeleton/modules/mod_blog/actions/' . $_REQUEST[ 'modaction' ] . '.php';
		if ( file_exists ( $act ) )
		{
			include_once ( $act );
		}
		else
		{
			$settings = new Dummy ( );
			$test = explode ( "\t", $fieldObject->DataMixed );
			$settings->Comments = $test[0];
			$settings->ShowAuthor = $test[1];
			$settings->TagBoxEnabled = $test[2];
			$settings->TagBoxPosition = $test[3];
			$settings->SearchBox = $test[4];
			$settings->Detailpage = $test[5];
			$settings->Sourcepage = $test[6];
	
			$cnt = new dbContent ( );
			if ( $settings->Sourcepage )
				$cnt->load ( $settings->Sourcepage );
			else $cnt->load ( $Session->EditorContentID );
	
			$mtpl = new cPTemplate ( $mtpldir . 'adm_main.php' );
			$std = new cPTemplate ( $mtpldir . 'adm_std.php' );
			$std->settings =& $settings;
			$std->blogs = listBlogs ( $cnt->ID, $_REQUEST[ 'bpos' ], $fieldObject->DataInt ? $fieldObject->DataInt : 10 );
			$mtpl->standard = $std->render ( );
			if ( $_REQUEST[ 'modaction' ] == 'standard' )
				die ( $std->render ( ) );
			break;
		}
}
if ( $mtpl )
	$module = $mtpl->render ( );
?>
