		<h1>
			Oversikt over publiserte blogger
		</h1>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<h2>
				Velg hvilke blogger som skal med i oversikten:
			</h2>
		</div>
		<div class="SpacerSmall"></div>
		<div id="mod_blogoverview_list" class="SubContainer">
			<table>
				<tr>
					<th>
						Blognavn
					</th>
					<th>
	   					Antall artikler vist
	   				</th>
	   				<th>
	   					Sidenavigasjon
	   				</th>
	   				<th>
	   					Utlistingsheading
	   				</th>
	   			</tr>
				<tr>
<?
    $db =& dbObject::globalValue('database');

    $blogids = array();
    $noOfArticles = array();
    $navigation = array();
    
    if ($this->datamixed)
    {
        $blogdata = explode('#', $this->datamixed);
    
        if (strstr($blogdata[0], '_')) $blogids = explode('_', $blogdata[0]);
        else if ($blogdata[0]) $blogids[] = $blogdata[0];
        
        if (strstr($blogdata[1], '_')) $noOfArticles = explode('_', $blogdata[1]);
        else if ($blogdata[1]) $noOfArticles[] = $blogdata[1];

        if (strstr($blogdata[2], '_')) $navigation = explode('_', $blogdata[2]);
        else if ($blogdata[2]) $navigation[] = $blogdata[2];
        
        if (strstr($blogdata[3], "\t\t")) $titles = explode ( "\t\t", $blogdata[ 3 ] );
        else if ( $blogdata[3] ) $titles = Array ( $blogdata[ 3 ] );
        
    }

    $q = 'SELECT c.* from ContentDataSmall e, ContentElement c WHERE e.DataString = "mod_blog" AND c.ID = c.mainID AND e.ContentID = c.mainID';

   	$str = '';
    if ($blogpages = $db->fetchObjectRows($q))
    {
        foreach ( $blogpages as $key=>$blogpage )
        {
            $hit = false;
            $nav = $navigation[ $key ];
            if ( in_array( $blogpage->MainID, $blogids ) )
            {
                $hit = true;
                $selected = array_search ( $blogpage->MainID, $blogids );
                $amount = $noOfArticles[ $selected ];
                $title = str_replace ( "%hash%", "#", count ( $titles ) ? $titles[ $selected ] : '' );
            }
            else 
            {
            	$title = ''; 
            	$amount = 4;
            }
            
            $str.= '
            	<tr>
            		<td>
            			<input type="checkbox"' . ( $hit ? ' checked="checked"' : '' ) . ' id="blog_' . $blogpage->MainID . '">
            			<span>' . $blogpage->Title . '   </span>
            		</td>
            		<td>
            			<input type="text" id="blog_amount_' . $blogpage->MainID . '" class="BlogAntall" size="3" value="' . $amount . '">
            		</td>
            		<td>
		        		<select id="navigateselect' . $blogpage->MainID . '" name="navigate">
		        			<option ' . ($nav == 'on' ? 'selected ' : '') . 'value="on">P&aring</option><option ' . ($nav == 'off' ? 'selected ' : '') . 'value="off">Av</option>
		        		</select>
		        	</td>
		        	<td>
		        		<input type="text" value="' . $title . '" name="title_' . $blogpage->MainID . '">
		        	</td>
            	</tr>
            ';
        }
    }
    
        
    return $str;
?>
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<h2>
				<?= i18n ( 'blogoverview_List_mode' ) ?>:
			</h2>
		</div>
		<div class="SpacerSmall"></div>
		<div class="SubContainer">
			<select id="mod_blog_listmode">
				<?
					list ( , , , ,$listmode ) = explode ( '#', $this->datamixed );
					$str = '';
					foreach ( array ( 'titles', 'full' ) as $mode )
					{
						$sel = $mode == $listmode ? ' selected="selected"' : '';
						$str .= '<option value="' . $mode . '"' . $sel . '>' . i18n ( 'blogoverview_mode' . $mode ) . '</option>';
					}
					return $str;
				?>
			</select>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="mod_blog_overview_save()">
			<img src="admin/gfx/icons/page_go.png"> <span id="mod_blog_saveblog">Lagre blogoversikt</span>
		</button>

		<button onclick="updateStructure ( ); removeModalDialogue ( 'blogoversikt_new' )" type="button">
			<img src="admin/gfx/icons/cancel.png"/> Lukk
		</button>

