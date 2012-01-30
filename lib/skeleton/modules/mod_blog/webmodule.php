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

global $webuser;

/**
 * Blog engine output
**/
$bid = 0;
$lim = $fieldObject->DataInt ? $fieldObject->DataInt : 10;
$pos = $_REQUEST[ 'blogpos' ] > 0 ? $_REQUEST[ 'blogpos' ] : '0';

// Add language
i18nAddLocalePath ( 'lib/skeleton/modules/mod_blog/locale' );

// Check captcha
if ( $_REQUEST[ 'checkcaptcha' ] )
{
	if ( $_REQUEST[ 'c' ] == $_SESSION[ 'captcha_value' ] )
	{
		ob_clean ( );
		die ( 'ok' );
	}
}

// Some other config vars
$cfg = explode ( "\t", $fieldObject->DataMixed );
$cfgComments = $cfg[0];
$cfgShowAuthor = $cfg[1];
$cfgTagbox = $cfg[2];
$cfgTagboxPlacement = $cfg[3];
$cfgSearchbox = $cfg[4];
$cfgDetailpage = $cfg[5];
$cfgSourcepage = $cfg[6];
$cfgLeadinlength = $cfg[7];
$cfgTitlelength = $cfg[8];
$cfgSizeX = $cfg[9];
$cfgSizeY = $cfg[10];
$cfgHeaderText = $cfg[11];
$cfgHideDetails = $cfg[12];
$cfgFacebookLike = $cfg[13];
list ( $cfgFacebookLikeWidth, $cfgFacebookLikeHeight, ) = explode ( ':', $cfg[14] );

// Check if a user has permission to comment
$canComment = $webuser ? $webuser->checkPermission ( $content, 'Write' ) : dbUser::checkGlobalPermission ( $content, 'Write' );

// Source and detailpages
$sourcepage = new dbContent ( );
if ( $cfgSourcepage ) $sourcepage->load ( $cfgSourcepage );
else $sourcepage->load ( $content->MainID );
$detailpage = false;
if ( $cfgDetailpage > 0 )
{
	$detailpage = new dbContent ( );
	$detailpage->load ( $cfgDetailpage );	
}

// Blog details
if ( preg_match ( '/.*?\/blogitem\/([0-9]*?)\_.*?/', $_REQUEST[ 'route' ], $matches ) && $cfgHideDetails != 1 )
{
	$GLOBALS[ 'document' ]->addHeadScript ( 'lib/javascript/arena-lib.js' );
	$GLOBALS[ 'document' ]->addHeadScript ( 'lib/javascript/bajax.js' );
	$GLOBALS[ 'document' ]->addHeadScript ( 'lib/skeleton/modules/mod_blog/javascript/web.js' );
	$bid = $matches[ 1 ];
	$blog = new dbObject ( 'BlogItem' );
	$blog->load ( $bid );
	
	// If we're using a different page
	if ( $detailpage && $content->MainID != $detailpage->MainID )
	{
		ob_clean ( );
		header ( 'Location: ' . BASE_URL . $detailpage->getRoute ( ) . '/blogitem/' . $matches[1] . '_' . texttourl ( $blog->Title ) . '.html' );
		die ( );
	}
	
	// Receive post
	if ( $_POST[ 'Message' ] && $_REQUEST[ 'Captcha' ] == $_SESSION[ 'captcha_value' ] )
	{
		$comment = new dbObject ( 'Comment' );
		$comment->Message = $_POST[ 'Message' ];
		$comment->Nickname = $_POST[ 'Name' ];
		$comment->ElementTable = 'BlogItem';
		$comment->ElementID = $blog->ID;
		$comment->UserID = $GLOBALS[ 'webuser' ]->ID ? $GLOBALS[ 'webuser' ]->ID : 0;
		$comment->DateCreated = date ( 'Y-m-d H:i:s' );
		$comment->DateModified = date ( 'Y-m-d H:i:s' );
		$comment->Subject = $_POST[ 'Subject' ];
		$comment->save ( );
		
		ob_clean ( );
		$_SESSION[ 'captcha_value' ] = '';
		header ( 'Location: ' . $content->getRoute ( ) . '/blogitem/' . $bid . '_' . texttourl ( $blog->Title ) . '.html' );
		die ( );
	}
	
	$btpl = new cPTemplate ( 'lib/skeleton/modules/mod_blog/templates/web_blog.php' );
	$btpl->blog =& $blog;
	$btpl->cfgComments = $cfgComments;
	$btpl->canComment = $canComment;
	$btpl->cfgShowAuthor = $cfgShowAuthor;
	$btpl->content =& $content;
	$btpl->sizeX = $cfgSizeX;
	$btpl->sizeY = $cfgSizeY;
	$btpl->comments = '';
	
	if ( $cfgComments )
	{
		$comment = new dbObject ( 'Comment' );
		$comment->ElementTable = 'BlogItem';
		$comment->ElementID = $blog->ID;
		$comment->addClause ( 'ORDER BY', 'DateCreated ASC, ID ASC' );
	
		if ( $comments = $comment->find ( ) )
		{
			$ctpl = new cPTemplate ( 'lib/skeleton/modules/mod_blog/templates/web_blog_comment.php' );
			$str = '';
			foreach ( $comments as $comment )
			{
				$ctpl->comment =& $comment;
				$str .= $ctpl->render ( );
			}
			$btpl->comments = $str;
		}
	}
	
	$module = $btpl->render ( );
	
}
// List all blogs
else
{
	// List out tagbox
	if ( $cfgTagbox )
	{
		$tags = '';
		if ( $tagO = $GLOBALS[ 'database' ]->fetchObjectRows ( '
			SELECT DISTINCT(b.Tags) c FROM BlogItem b WHERE b.ContentElementID=' . $sourcepage->MainID . '
		' ) )
		{
			$options = array ();
			foreach ( $tagO as $t )
			{
				$t = explode ( ',', str_replace ( ' ', ',', trim ( $t->c ) ) );
				foreach ( $t as $st ) $options[$st] = $st;
			}
			if ( count ( $options ) )
			{
				$tags .= '<label>' . i18n ( 'Tagbox_Tags' ) . '<span>:</span></label>';
				foreach ( $options as $t )
				{
					if ( isset ( $_REQUEST['tag'] ) && trim ( $t ) == $_REQUEST[ 'tag' ] )
						$w = ' class="current"';
					else $w = '';
					$tags .= '<a href="' . $sourcepage->getUrl () . '?tag=' . $t . '"' . $w . '><span>' . $t . '</span></a>, ';
				}
				$tags = substr ( $tags, 0, strlen ( $tags ) - 2 );
			}
		}
		$o = new stdclass ();
		$o->ContentGroup = $cfgTagboxPlacement;
		$o->Name = 'Tagbox';
		$o->Type = 'varchar';
		$o->IsVisible = true;
		$content->_extra_TagBox = '<div id="TagBox">' . $tags . '</div>';
		$content->_field_TagBox = $o;
		$content->TagBox = $content->_extra_TagBox;
	}
	
	$blogs = new dbObject ( 'BlogItem' );
	$blogs->addClause ( 'WHERE', 'ContentElementID=' . $sourcepage->MainID );
	if ( $_REQUEST[ 'month' ] )
	{
		$month = date ( 'Y-m', $_REQUEST[ 'month' ] );
		$nmonth = date ( 'Y-m', strtotime ( $month . '-01' ) + 2764800 );
		$blogs->addClause ( 'WHERE', 'IsPublished AND DatePublish <= NOW() AND DatePublish >= \'' . $month . '-01\' AND DatePublish < \'' . $nmonth . '-01\'' ); 
	}
	else
	{
		$blogs->addClause ( 'WHERE', 'IsPublished AND DatePublish <= NOW()' );
	}
	// Be mindful of the tags!
	if ( $cfgTagbox && isset ( $_REQUEST[ 'tag' ] ) )
	{
		$blogs->addClause ( 'WHERE', 'Tags LIKE "' . $_REQUEST[ 'tag' ] . '"' );
	}
	$cnt = $blogs->findCount ( );
	$blogs->addClause ( 'ORDER BY', 'DatePublish DESC, ID DESC' );
	$blogs->addClause ( 'LIMIT', $pos . ',' . $lim );
	
	if ( $blogs = $blogs->find ( ) )
	{
		$btpl = new cPTemplate ( 'lib/skeleton/modules/mod_blog/templates/web_blog_listed.php' );
		$str = '';
		if ( trim ( $cfgHeaderText ) )
			$str .= '<h2 class="BlogListHeader">' . $cfgHeaderText . '</h2>';
		// Add numbers so one can change the first ten items with extraclass
		$num = -1;
		$numbers = Array ( 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth' );
		foreach ( $blogs as $blog )
		{
			$btpl->blog =& $blog;
			$btpl->extraClass = ++$num <= 10 ? ( ' ' . $numbers[ $num ] ) : '';
			$btpl->content =& $content;
			if ( $cfgComments )
			{
				$comment = new dbObject ( 'Comment' );
				$comment->ElementTable = 'BlogItem';
				$comment->ElementID = $blog->ID;
				$btpl->commentcount = $comment->findCount ( );
			}
			$btpl->facebookLike = $cfgFacebookLike;
			if ( $btpl->facebookLike == 1 )
			{
				$btpl->facebookLikeUrl = BASE_URL . $sourcepage->getRoute ( ) . '/blogitem/' . $blog->ID . '_' . texttourl ( $blog->Title ) . '.html';
				$btpl->facebookLikeWidth = $cfgFacebookLikeWidth;
				$btpl->facebookLikeHeight = $cfgFacebookLikeHeight;
			}
			$btpl->cfgComments = $cfgComments;
			$btpl->canComment = $canComment;
			$btpl->cfgShowAuthor = $cfgShowAuthor;
			$btpl->detailpage = $detailpage;
			$btpl->leadinLength = $cfgLeadinlength;
			$btpl->titleLength = $cfgTitlelength;
			$btpl->sizeX = $cfgSizeX;
			$btpl->sizeY = $cfgSizeY;
			$btpl->hideDetails = $cfgHideDetails;
			$str .= $btpl->render ( );
		}
		$module = $str;
		unset ( $str );
		
		// Navigation
		if ( $cfgTagbox )
			$tagOp = isset ( $_REQUEST['tag'] ) ? ( '&tag=' . $_REQUEST['tag'] ) : '';
		else $tagOp = '';
		$next = $prev = $sep = '';
		if ( $pos > 0 )
			$prev = '<a href="' . $content->getUrl ( ) . '?blogpos=' . ( $pos - $lim ) . $tagOp . '" class="Prev"><span>' . i18n ( 'Newer blogs' ) . '</span></a>';
		if ( $pos + $lim < $cnt )
			$next = '<a href="' . $content->getUrl ( ) . '?blogpos=' . ( $pos + $lim ) . $tagOp . '" class="Next"><span>' . i18n ( 'Older blogs' ) . '</span></a>';
		if ( $prev && $next ) 
			$sep = ' <span class="Separator">&nbsp;</span> ';
		else $sep = '';
		if ( $prev || $next )
			$module .= '<div id="mod_blog_navigation"><hr/><p>' . $prev . $sep . $next . '</p></div>';
	}
	else $module = '<p>Ingen blogger finnes.</p>';
}
?>
