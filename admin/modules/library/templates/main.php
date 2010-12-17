<script language="JavaScript" type="text/javascript" src="admin/modules/library/javascript/main.js"></script>
<script language="JavaScript" type="text/javascript" src="admin/modules/library/javascript/imagefunctions.js"></script>
<script language="JavaScript" type="text/javascript" src="admin/modules/library/javascript/filefunction.js"></script>

<script language="JavaScript" type="text/javascript">
	var currentLibraryLevel = '<?= $GLOBALS[ 'Session' ]->LibraryCurrentLevel; ?>';
	document.lid = currentLibraryLevel;
	var Language = new Object ( );
</script>
<?= enableTextEditor ( ) ?>
<script type="text/javascript" src="lib/plugins/library/javascript/plugin.js"></script>
<link rel="stylesheet" href="admin/modules/library/css/main.css" />
<div class="ModuleContainer">
	<table class="LayoutColumns">
		<tr style="height: 0px">
			<th style="width: 240px; padding-right: <?= MarginSize ?>px" id="ColumnLeftTh"></th>
			<th style="width: 4px"></th>
			<th style="padding-right: <?= MarginSize ?>px; padding-left: <?= MarginSize ?>px" id="ColumnMiddleTh"></th>
		</tr>
		<tr>
			<td>
				<div id="ChoicesTabs">
					<div class="tab" id="tabLibStructure">
						<img src="admin/gfx/icons/folder.png"/> <?= i18n ( 'Folders' ) ?>
					</div>
					<div class="tab" id="tabAdvanced">
						<img src="admin/gfx/icons/drive_key.png"> <?= i18n ( 'Advanced' ) ?>
					</div>
					<div class="page" id="pageLibStructure" style="padding: 0px; margin: 0">
						<div id="levels">
							<div>
								<ul id="LibraryLevelTree" class="Collapsable">
									<?= $this->levels; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="page" id="pageAdvanced" style="padding: 3px; margin: 0">
						<div id="newLevel" class="Container"></div>
						<div class="SpacerSmall"></div>
						<div class="Container">
							<button type="button" onclick="cleanCache()">
								<img src="admin/gfx/icons/folder_wrench.png"> <?= i18n ( 'Empty temporary files' ) ?>
							</button>
							<div class="Spacer"></div>
							<button type="button" onclick="document.location='admin.php?module=library&action=checkuploadfolder';">
								<img src="admin/gfx/icons/folder_wrench.png"> <?= i18n ( 'Check for missing files' ) ?>
							</button>
						</div>
					</div>
				</div>
				<?if ( $this->tags ) { ?>
				<h2 class="BlockHead">
					<img src="admin/gfx/icons/tag_green.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/>
					<?= i18n ( 'Tags' ) ?>
				</h2>
				<div class="BlockContainer" id="TagList">
					<?= $this->tags ?>
				</div>
				<?}?>
				<h2 class="BlockHead">
					<img src="admin/gfx/icons/magnifier.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> 
					<?= i18n ( 'Search the library' ) ?>
				</h2>
				<div class="BlockContainer">
					<form id="librarySearch" onsubmit="ModuleLibrarySearch ( ); return false">
						<p>
							<input type="text" style="width: 95%" name="libSearchKeywords" id="libSearchKeywords" value="<?= $_REQUEST[ 'libSearchKeywords' ] ? $_REQUEST[ 'libSearchKeywords' ] : i18n ( 'keywords...' ) ?>" onmouseup="this.select()">
						</p>
					</form>
					<div class="Spacer"></div>
					<button type="button" onclick="ModuleLibrarySearch()">
						<img src="admin/gfx/icons/magnifier.png"> <?= i18n ( 'Search' ) ?>
					</button>
					<button type="button" onclick="ModuleResetLibrarySearch()" id="libNullStillSoek" style="position: absolute; visibility: hidden">
						<img src="admin/gfx/icons/cancel.png"> <?= i18n ( 'Reset search' ) ?>
					</button>
				</div>
				<div id="searchResults"></div>
				<div class="SpacerSmall"></div>
			</td>
			<td>&nbsp;</td>
			<td id="libMainCol">
				<h1>
					<img src="admin/gfx/icons/server.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> 
					<span id="ContentButtonsSmall">
					</span>
					<div style="float: right; margin: -4px 4px 0 0">
						<?= i18n ( 'Show' ) ?>: <select onchange="document.location = 'admin.php?module=library&viewmode=' + this.value">
							<option value="thumbnails"<?= $this->viewmode == 'thumbnails' ? 'selected="selected"' : '' ?>><?= i18n ( 'Thumbnails' ) ?></option>
							<option value="details"<?= $this->viewmode == 'details' ? 'selected="selected"' : '' ?>><?= i18n ( 'Detailed list' ) ?></option>
						</select>
						<?= i18n ( 'Listed by' ) ?>: <select onchange="document.location='admin.php?module=library&listmode=' + this.value">
							<option value="date"<?= $this->listmode == 'date' ? ' selected="selected"' : '' ?>><?= i18n ( 'Date' ) ?></option>
							<option value="filename"<?= $this->listmode == 'filename' ? ' selected="selected"' : '' ?>><?= i18n ( 'Filename' ) ?></option>
							<option value="title"<?= $this->listmode == 'title' ? ' selected="selected"' : '' ?>><?= i18n ( 'File title' ) ?></option>
							<option value="filesize"<?= $this->listmode == 'filesize' ? ' selected="selected"' : '' ?>><?= i18n ( 'Filsize' ) ?></option>
						</select>
					</div>
					<div style="font-size: 13px" id="Innholdsheader"><?= $_REQUEST[ 'tag' ] ? ( i18n ( 'Files and images matching' ) . ' "' . $_REQUEST[ 'tag' ] . '"' ) : ( i18n ( 'The contents of' ) . ' "' . $this->folder->Name . '"' ) ?>:</div>
				</h1>
				<div class="Container">
					
					<div class="SpacerSmall"></div>
					<div id="LibraryMessage"></div>
					<div id="LibraryContentDiv">
						<?= $this->content ?>
					</div>
					<div class="SpacerSmallColored"></div>
					<div id="ContentButtons">
					</div>
				</div>
				<iframe name="LibraryUpload" style="position: absolute; width: 1px; height: 1px; visibility: hidden"></iframe>
				
			</td>
		
		</tr>	
		
	</table>
	<script language="JavaScript" type="text/javascript">
		makeCollapsable ( document.getElementById ( 'LibraryLevelTree' ) );
		showContentButtons ( );
		showNewLevelGUI ( );
		checkLibraryTooltips();
	</script>
  
</div>
