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
			<th style="width: 23%; padding-right: <?= MarginSize ?>px" id="ColumnLeftTh"></th>
			<th style="padding: 4px;"></th>
			<th style="width: 77%; padding-right: <?= MarginSize ?>px; padding-left: <?= MarginSize ?>px" id="ColumnMiddleTh"></th>
		</tr>
		<tr>
			<td>
				<div id="ChoicesTabs">
					<div class="tab" id="tabLibStructure">
						<img src="admin/gfx/icons/folder.png"/> Mapper
					</div>
					<div class="tab" id="tabChoices">
						<img src="admin/gfx/icons/wrench.png"> Valg
					</div>
					<div class="tab" id="tabAdvanced">
						<img src="admin/gfx/icons/drive_key.png"> Avansert
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
					<div class="page" id="pageChoices" style="padding: 3px; margin: 0">
						<div id="newLevel" class="Container"></div>
					</div>
					<div class="page" id="pageAdvanced" style="padding: 3px; margin: 0">
						<div class="Container">
							<button type="button" onclick="cleanCache()">
								<img src="admin/gfx/icons/folder_wrench.png"> Tøm midlertidige filer
							</button>
							<div class="Spacer"></div>
							<button type="button" onclick="document.location='admin.php?module=library&action=checkuploadfolder';">
								<img src="admin/gfx/icons/folder_wrench.png"> Skjekk for manglende filer
							</button>
						</div>
					</div>
				</div>
				<div class="SpacerSmallColored"></div>		
				<?if ( $this->tags ) { ?>
				<h1>
					<img src="admin/gfx/icons/tag_green.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/>
					Tags
				</h1>
				<div class="Container">
					<?= $this->tags ?>
				</div>
				<div class="SpacerSmallColored"></div>
				<?}?>
				<h1>
					<img src="admin/gfx/icons/magnifier.png" style="float: left; margin: 0pt 4px 0pt 0pt;"/> 
					Søk i bibliotek
				</h1>
				<div class="Container">
					<p><strong>Søkeord:</strong></p>
					<form id="librarySearch" onsubmit="ModuleLibrarySearch ( ); return false">
						<p>
							<input type="text" style="width: 95%" name="libSearchKeywords" id="libSearchKeywords" value="<?= $_REQUEST[ 'libSearchKeywords' ] ?>">
						</p>
					</form>
					<div class="Spacer"></div>
					<button type="button" onclick="ModuleLibrarySearch()">
						<img src="admin/gfx/icons/magnifier.png"> Søk
					</button>
					<button type="button" onclick="ModuleResetLibrarySearch()" id="libNullStillSoek" style="position: absolute; visibility: hidden">
						<img src="admin/gfx/icons/cancel.png"> Nullstill søk
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
						Vis: <select onchange="document.location = 'admin.php?module=library&viewmode=' + this.value">
							<option value="thumbnails"<?= $this->viewmode == 'thumbnails' ? 'selected="selected"' : '' ?>>Thumbnails</option>
							<option value="details"<?= $this->viewmode == 'details' ? 'selected="selected"' : '' ?>>Detaljert liste</option>
						</select>
						Listet etter: <select onchange="document.location='admin.php?module=library&listmode=' + this.value">
							<option value="date"<?= $this->listmode == 'date' ? ' selected="selected"' : '' ?>>Dato</option>
							<option value="filename"<?= $this->listmode == 'filename' ? ' selected="selected"' : '' ?>>Filnavn</option>
							<option value="title"<?= $this->listmode == 'title' ? ' selected="selected"' : '' ?>>Tittel</option>
							<option value="filesize"<?= $this->listmode == 'filesize' ? ' selected="selected"' : '' ?>>Filstørrelse</option>
						</select>
					</div>
					<div style="font-size: 13px" id="Innholdsheader">Innhold i "<?= $this->folder->Name ?>":</div>
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
