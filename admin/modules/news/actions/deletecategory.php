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



switch( $_REQUEST[ 'deletestep' ] )
{
	case 2:
		if ( $_REQUEST[ 'newsMoveContent' ] == 'move' && intval( $_REQUEST[ 'newcontentfolder' ]  ) > 0 )
		{
				$db = &$GLOBALS[ 'database' ];
				
				$sql = 'UPDATE News SET CategoryID = ' . $_REQUEST[ 'newcontentfolder' ] . ' WHERE CategoryID = ' . $_REQUEST[ 'cid' ];		
				$db->query( $sql );

			
				$sql = 'UPDATE NewsCategory SET Parent = ' .  $_REQUEST[ 'newcontentfolder' ]  . ' WHERE Parent = ' . $_REQUEST[ 'cid' ];		
				$db->query( $sql );
		}
		else if( $_REQUEST[ 'newsMoveContent' ] == 'move' )
		{
			// no delete here... but thsi should not happen at all ! =====================================
			ob_clean ( );
			header ( 'Location: admin.php?module=news' );
			die ( );
		}
		
		if ( ( $_REQUEST[ 'newsMoveContent' ] == 'move' ) || ( $_REQUEST[ 'newsMoveContent' ] == 'delete' ) )
		{
			$obj = new dbObject ( 'NewsCategory' );
			$obj->load ( $_REQUEST[ 'cid' ] );
			
			$Session->news_currentCategory = $obj->Parent;
			
			$obj->delete ( );				
		}


		ob_clean ( );
		header ( 'Location: admin.php?module=news' );
		die ( );
		break;
		
	case 1:
	default:


		$db =& dbObject::globalValue ( 'database' );
		$cats = $db->fetchObjectRows ( "SELECT * FROM NewsCategory WHERE Language='{$GLOBALS["Session"]->CurrentLanguage}' ORDER BY Name ASC" );
		function listCategories ( $parent = 0, $r = "", $cats, $exclude )
		{
			$len = count ( $cats );
			$oStr = "";
			for ( $a = 0; $a < $len; $a++ )
			{
				if ( $cats[ $a ]->Parent == $parent )
				{
					if ( $cats[ $a ]->ID == $exclude ) continue;
					
					$oStr .= "<option value=\"" . $cats[ $a ]->ID . "\">$r" . $cats[ $a ]->Name . "</option>";
					$oStr .= listCategories ( $cats[ $a ]->ID, $r . "&nbsp;&nbsp;", $cats, $exclude );
				}
			}
			return $oStr;
		}
		
		
		$nc = new dbObject( 'NewsCategory' );
		$nc->ID = $_REQUEST[ 'cid' ];
		if( $nc->load() )
		{
			
			// simple check for contents
			$sql1 = 'SELECT COUNT( ID ) as `Count` FROM News WHERE CategoryID IN ( SELECT ID FROM NewsCategory WHERE Parent = '.$nc->ID.' ) OR CategoryID = ' . $nc->ID;
			$sql2 = 'SELECT COUNT( ID ) as `Count` FROM NewsCategory WHERE Parent = '.$nc->ID.'';
			
			$countcats = $db->fetchRow( $sql1 );
			$countcons = $db->fetchRow( $sql2 );
			
			$countcats = $countcats[ 'Count' ];
			$countcons = $countcons[ 'Count' ];
			
			$tpl = new cPTemplate( "$tplDir/deletecategory.php" );
			$tpl->cat = $nc;
			$tpl->catscount = $countcats;
			$tpl->contcount = $countcons;
			
			$tpl->otherfolders =  listCategories ( 0, "", $cats, $nc->ID );
			ob_clean();
			die( $tpl->render() );				
		}
		else
		{
			die( 'Kan ikke finne kategorien.' );
		}
}
?>
