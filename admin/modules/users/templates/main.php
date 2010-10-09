<script type="text/javascript" src="admin/modules/users/javascript/main.js"></script>
<link rel="stylesheet" href="admin/modules/users/css/main.css" />
<div class="ModuleContainer">
	<table class="LayoutColumns">
		<tr>
			<td width="290px" style="padding-right: <?= MarginSize ?>px">
				<h1>
					<img src="admin/gfx/icons/group_gear.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> Gruppeoversikt
				</h1>
				<div id="GroupList" class="Container">
					<?= renderGroupList ( ) ?>
					<script type="text/javascript">
						if ( document.getElementById ( 'GroupList' ).getElementsByTagName ( 'ul' )[0] )
							makeCollapsable ( document.getElementById ( 'GroupList' ).getElementsByTagName ( 'ul' )[0] );
					</script>
				</div>
				
				<div class="SpacerSmall"><em></em></div>
				
				<div class="Container" style="padding: <?= MarginSize ?>px">
					<button onclick="editGroup()">
						<img src="admin/gfx/icons/group_add.png" /> Legg til en gruppe
					</button>
				</div>
				
				<div class="SpacerSmallColored"><em></em></div>
				
				<h1>
					<img src="admin/gfx/icons/vcard.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> Brukerenheter
				</h1>
				<div id="UnitList" class="Container">
					<?= renderUnitList ( ) ?>
					<script type="text/javascript">
						if ( document.getElementById ( 'UnitList' ).getElementsByTagName ( 'ul' )[0] )
							makeCollapsable ( document.getElementById ( 'UnitList' ).getElementsByTagName ( 'ul' )[0] );
					</script>
				</div>
				
				<div class="SpacerSmall"><em></em></div>
				
				<div class="Container" style="padding: <?= MarginSize ?>px">
				
					<button onclick="editCollection()">
						<img src="admin/gfx/icons/building_add.png" /> Legg til en brukerenhet
					</button>
					<?if ( $GLOBALS[ 'Session' ]->UsersCollectionID ) { ?>
					<button onclick="document.location='admin.php?module=users&collectionid=none'" class="Small">
						<img src="admin/gfx/icons/building_error.png"> Ikke vis enheter
					</button>
					<?}?>
					
				</div>
				
				<div class="SpacerSmallColored"><em></em></div>
				
				<h1>
					<img src="admin/gfx/icons/magnifier.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> Søk etter brukere
				</h1>
				
				<div class="Container">
					
					<form method="post">
						
						<input type="hidden" name="pos" value="0"/>
						<p>
							<strong>Søkeord: </strong> &nbsp; <input type="text" name="keywords" value="<?= $_REQUEST[ "keywords" ] ?>" size="26" />					
						</p>
						
						<button type="submit">
							<img src="admin/gfx/icons/magnifier.png" /> Start søk
						</button>
						<?if ( $_REQUEST[ "keywords" ] ) { ?>
						<button type="button" onclick="document.location='admin.php?module=users'">
							<img src="admin/gfx/icons/cancel.png" /> Nullstill søk
						</button>
						<?}?>
						
					</form>
					
				</div>
				
			</td>
			<td>
				<h1>
					<img src="admin/gfx/icons/user_suit.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> 
					<div style="float: right; padding: 1px 1px 1px 3px; margin: -6px -6px 0 0">
						Brukere pr. side: <select onchange="document.location='admin.php?module=users&limit=' + this.value">
							<?
								foreach ( Array ( 20, 50, 100, 500, 1000, 10000 ) as $a )
								{
									$s = $a == $this->limit ? ' selected="selected"' : '';
									$str .= '<option value="' . $a . '"' . $s . '>' . $a . '</option>';
								}
								return $str;
							?>
						</select>
					</div>
					Brukere <?= $this->groupChoice ?>:
				</h1>
				<div class="Container" style="padding: <?= MarginSize ?>px">
					<table class="List">
						<tr>
							<?
								$str = '';
								foreach ( array (
										'Bilde'=>'',
										'Username'=>'Bruker',
										'I gruppe(r)'=>'',
										'DateCreated'=>'Opprettet',
										'DateLogin'=>'Innlogget'
									) as $k=>$v )
								{
									if ( $v )
									{
										if ( $k == $GLOBALS[ 'Session' ]->UsersSortField )
										{
											$ss = '<em>'; $se = '</em>';
										}
										else if ( $k . 'Inv' == $GLOBALS[ 'Session' ]->UsersSortField )
										{
											$ss = '<u>'; $se = '</u>';
										}
										else $ss = $se = '';
										
										$str .= "<th>$ss<a href=\"admin.php?module=users&sortfield=$k\">$v:</a>$se</th>";
									}
									else $str .= '<th>' . $k . ':</th>';
								}
								return $str;
							?>
							<th style="text-align: center; white-space: nowrap">
								<button onclick="addToGroup()" class="Small" title="Legg til i gruppe">
									<img src="admin/gfx/icons/group_link.png" />
								</button>
								<button type="button" onclick="deleteUsers()" class="Small" title="Slett bruker">
									<img src="admin/gfx/icons/user_delete.png">
								</button>
								<button type="button" onclick="addToCollection()" class="Small" title="Koble til enhet">
									<img src="admin/gfx/icons/building_link.png">
								</button>
								<?if ( $GLOBALS[ 'Session' ]->UsersCollectionID ) { ?>
								<button type="button" onclick="removeFromCollection()" class="Small" title="Fjern fra enheten">
									<img src="admin/gfx/icons/building_error.png">
								</button>
								<?}?>
							</th>
							<th style="text-align: center">
								Verktøy:
							</th>
						</tr>
					<?= $this->userlist ?>
						<tr>
							<td colspan="5">
							</td>
							<td style="text-align: center; white-space: nowrap">
								<button onclick="addToGroup()" class="Small" title="Legg til i gruppe">
									<img src="admin/gfx/icons/group_link.png" />
								</button>
								<button type="button" onclick="deleteUsers()" class="Small" title="Slett bruker">
									<img src="admin/gfx/icons/user_delete.png" />
								</button>
								<button type="button" onclick="addToCollection()" class="Small" title="Koble til enhet">
									<img src="admin/gfx/icons/building_link.png">
								</button>
								<?if ( $GLOBALS[ 'Session' ]->UsersCollectionID ) { ?>
								<button type="button" onclick="removeFromCollection()" class="Small" title="Fjern fra enheten">
									<img src="admin/gfx/icons/building_error.png">
								</button>
								<?}?>
							</td>
							<td>
								
							</td>
						</tr>
					</table>
					<?if ( $this->authGroups > 0 ) { ?>
					<div class="Spacer"><em></em></div>
					<div class="SpacerSmallColored"><em></em></div>
					<button onclick="newUser ( )">
						<img src="admin/gfx/icons/user_add.png" /> Legg til en bruker
					</button>
					<button onclick="importUser ( )">
						<img src="admin/gfx/icons/page_white_excel.png" /> Importer brukere
					</button>
					<button onclick="document.location='admin.php?module=users&export=1';">
						<img src="admin/gfx/icons/table_go.png" /> Exporter brukere
					</button>
					<?}?>
					<div class="Spacer"><em></em></div>
					<?= $this->Navigation ?>
				</div>
			</td>
		</tr>
	</table>
</div>
