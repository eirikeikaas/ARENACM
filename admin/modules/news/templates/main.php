<script src="admin/modules/news/javascript/main.js"></script>
<link rel="stylesheet" href="admin/modules/news/css/main.css" />

<div class="ModuleContainer">
	<table class="LayoutColumns">
		<tr>
			<td style="width: 25%; padding-right: <?= MarginSize ?>px">
				<h1><span style="float: right">
					<select name="Language" onchange="document.location='admin.php?module=news&amp;language=' + this.value">
						<?= $this->languages ?>
					</select>
				</span>Kategorier:</h1>
					
				<div id="newsLevelListing" class="Container">
					
					<ul id="newsLevelTree" class="Collapsable">
					<?= $this->levels; ?>
					</ul>

					<?if ( $GLOBALS[ 'Session' ]->news_currentCategory && $GLOBALS[ 'Session' ]->news_currentCategory != 'all' ) { ?>
					<script type="text/javascript">
						setCookie ( 'newsCurrentCategory', '<?= $GLOBALS[ 'Session' ]->news_currentCategory ?>' );
					</script>
					<?}?>
					
				</div>
					
				<div class="SpacerSmall"></div>
				
				<div class="Container">	
					<div style="white-space: nowrap;">
					
						<button id="showfromall" type="button" onclick="document.location.href = 'admin.php?module=news&cid=all'">
							<img src="admin/gfx/icons/folder_explore.png" />
						</button>
						<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->CurrentCategory, 'Read', 'admin' ) ) { ?>
						<button id="addcatbutton" type="button" onclick="editCategory ( )">
							<img src="admin/gfx/icons/folder.png" />
						</button>
						<button id="addsubcatbutton" type="button" onclick="editCategory ( '0', getCookie ( 'newsCurrentCategory' ) )">
							<img src="admin/gfx/icons/folder_add.png" />
						</button>
						<?}?>
					</div>
				</div>
				
				<div class="SpacerSmall"></div>
				
				<h1>Søk i nyhetene:</h1>
				
				<div class="Container">
				
					<p>
						<strong>Søkeord:</strong>
					</p>
					
					<form method="get">
					
						<p>
							<input type="text" size="25" name="keywords" value="<?= $_REQUEST[ "keywords" ] ?>" />
						</p>
						<div class="Spacer"></div>
						<button type="submit">
							<img src="admin/gfx/icons/magnifier.png" /> Søk
						</button>
						<?if ( $_REQUEST[ "keywords" ] ) { ?>
						<button type="button" onclick="document.location = 'admin.php?module=news'">
							<img src="admin/gfx/icons/cancel.png" /> Nullstill søk
						</button>
						<?}?>
						
					</form>
					
				</div>
				
			</td>
			<td style="width: 75%">
				<h1>
					<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->CurrentCategory, 'Write', 'admin' ) ) { ?>
					<span>
						<button type="button" onclick="editNews ( )" class="Small">
							<img src="admin/gfx/icons/page_add.png" /> Ny nyhet&nbsp;
						</button>
						&nbsp;
						<button type="button" onclick="editEvent ( )" class="Small">
							<img src="admin/gfx/icons/page_white_add.png" /> Ny hendelse&nbsp;
						</button>
					</span>
					<?}?>
					Nyheter og hendelser i: <?= $this->Category->ID > 0 ? $this->Category->Name : "Alle kategorier" ?>
				</h1>
				<div class="Container">
					
					<div class="Spacer"></div>
					
					<div class="SubContainer">
					<?= $this->NewsItems ?>
					</div>
					
					<div class="Spacer"></div>
					<?if ( $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->CurrentCategory, 'Write', 'admin' ) ) { ?>
					<button type="button" onclick="editNews ( )">
						<img src="admin/gfx/icons/page_add.png" /> Ny nyhet
					</button>
					
					<button type="button" onclick="editEvent ( )">
						<img src="admin/gfx/icons/page_white_add.png" /> Ny hendelse
					</button>
					<?}?>
					
					<div class="Spacer"></div>
					
					<?= $this->Navigation ?>
					
				</div>
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	
	var currentCategoryID = '<?= $this->Category->ID ?>';
	
	makeCollapsable ( document.getElementById ( 'newsLevelTree' ) );
	addOnload ( function ( )
	{
		if( document.getElementById( 'showfromall' )  ) 		addToolTip( 'Alle kategorier', 'Vis innhold fra alle kategorier.', 'showfromall' );
		if( document.getElementById( 'addcatbutton' )  ) 		addToolTip( 'Ny kategori', 'Legg til kategori i hovednivået.', 'addcatbutton' );
		if( document.getElementById( 'addsubcatbutton' )  ) addToolTip( 'Ny subkategori', 'Legg til kategori under valgt kategori.', 'addsubcatbutton' );
		if( document.getElementById( 'editcatbutton' )  ) 	addToolTip( 'Endre kategori', 'Redigere kategori.', 'editcatbutton' );
		if( document.getElementById( 'deletecatbutton' )  ) addToolTip( 'Slett kategori', 'Sletting må bekreftes og det må bestemmes hva som skal skjer med innhold i kategoriet.', 'deletecatbutton' );
		if( document.getElementById( 'cattowbbutton' )  ) 	addToolTip( 'Legg ned på arbeidsbenken', 'Klikk fo rå legge ned kategori til arbeidsbenken for å bruke den derfra.', 'cattowbbutton' );
	} );
</script>
    
