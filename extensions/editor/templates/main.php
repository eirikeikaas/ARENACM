
	<table class="LayoutColumns">
		<tr>
			<td style="width: 358px; padding-right: 8px" class="Column">
				<h1>
					<img src="admin/gfx/icons/book_open.png" style="float: left; margin: 0 4px 0 0" /> <?= $this->languages ?>
					Nettstedsoversikt
				</h1>
				<div id="StructureChangedButton">
				</div>
				<div class="Container" id="StructureContainer">
					<?= $this->structure ?>
					<script>
						makeCollapsable ( document.getElementById ( 'Structure' ) );
					</script>
				</div>
				<div class="SpacerSmall"></div>
				<div id="StructureButtons" class="Container">
					<?= structureButtons ( $this->page ) ?>
				</div>
				<div class="SpacerSmallColored"></div>
				<div id="EditorAdvancedPages">
					<div id="tabNotes" class="tab">
						<img src="admin/gfx/icons/page_white_edit.png"> Notater
					</div>
					<div id="tabModuleShop" class="tab">
						<img src="admin/gfx/icons/brick_edit.png"> Innholdsmoduler
					</div>
					<div id="pageNotes" class="page" style="padding: 8px">
						<h2>Dine notater angående denne siden:</h2>
						<textarea id="PageNotes"><?= $this->Notes ?></textarea>
					</div>
					<div id="pageModuleShop" class="page" style="padding: 4px">
						<div id="EditorModuleTabs">
							<div class="tab" id="tabModulesConnected">
								<img src="admin/gfx/icons/wand.png"> Dine <?if ( !$GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?> moduler<?}?>
							</div>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
							<div class="tab" id="tabModulesAvailable">
								<img src="admin/gfx/icons/star.png"> Gratismoduler
							</div>
							<div class="tab" id="tabModulesPro">
								<img src="admin/gfx/icons/money.png"> Modulbutikk
							</div>
							<?}?>
							<div class="page" id="pageModulesConnected">
								<?= showAddedModules ( $this->page->ID ) ?>
							</div>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
							<div class="page" id="pageModulesAvailable">
								<?= showFreeModules ( ) ?>
							</div>
							<div class="page" id="pageModulesPro">
								<?= showProModules ( ) ?>
							</div>
							<?}?>
						</div>
					</div>
				</div>
				<script type="text/javascript">
					initTabSystem ( 'EditorAdvancedPages' );
					initTabSystem ( 'EditorModuleTabs' );
				</script>
			</td>
			<td class="Column">
				<h1 id="EditHeadline">
					<div id="SmallButtons">
						<?= contentButtons ( $this->page, 1 ) ?>
					</div>
					<img src="admin/gfx/icons/page_edit.png" style="float: left; margin: 0 4px 0 0" /> Rediger "<strong id="EditHeadlineDiv"><?= $this->page->MenuTitle ? $this->page->MenuTitle : $this->page->_oldTitle ?></strong>"
				</h1>
				<input type="hidden" id="PageID" value="<?= $this->page->ID ?>">
				<input type="hidden" id="PageUrl" value="<?= $this->page->getUrl ( ) ?>">
				<div class="Container" id="ContentForm" style="padding-top: <?= MarginSize ?>px">
					<table class="LayoutColumns">
						<tr>
							<td>
								<h4>Menytittel:</h4>
								<input type="text" value="<?= $this->page->MenuTitle ?>" size="40" id="MenuTitle">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;
							</td>
							<td>
								<h4>Søkbar sidetittel:</h4>
								<input type="text" value="<?= $this->page->Title ?>" size="40" id="Title">
							</td>
						</tr>
					</table>
					<div class="SpacerSmallColored"></div>
					<div id="ContentFields">
						<?= $this->ContentForm ?>
					</div>
					<div class="SpacerSmallColored Bottom"></div>
					<div id="BottomButtons">
						<?= contentButtons ( $this->page ) ?>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	<?= enableTextEditor ( ) ?>
