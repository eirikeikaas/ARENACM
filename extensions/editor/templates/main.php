
	<table class="LayoutColumns">
		<tr>
			<td style="width: 358px; padding-right: 8px" class="Column">
				<h1>
					<img src="admin/gfx/icons/book_open.png" style="float: left; margin: 0 4px 0 0" /> <?= $this->languages ?>
					<?= i18n ( 'Website overview' ) ?>
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
						<img src="admin/gfx/icons/page_white_edit.png"> <?= i18n ( 'Notes' ) ?>
					</div>
					<div id="tabModuleShop" class="tab">
						<img src="admin/gfx/icons/brick_edit.png"> <?= i18n ( 'Field modules' ) ?>
					</div>
					<div id="pageNotes" class="page" style="padding: 8px">
						<h2><?= i18n ( 'Your notes on this page' ) ?>:</h2>
						<textarea id="PageNotes"><?= $this->Notes ?></textarea>
					</div>
					<div id="pageModuleShop" class="page" style="padding: 4px">
						<div id="EditorModuleTabs">
							<div class="tab" id="tabModulesConnected">
								<img src="admin/gfx/icons/wand.png"> <?= i18n ( 'Your modules' ) ?>
							</div>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
							<div class="tab" id="tabModulesAvailable">
								<img src="admin/gfx/icons/star.png"> <?= i18n ( 'Free modules' ) ?>
							</div>
							<!--
							<div class="tab" id="tabModulesPro">
								<img src="admin/gfx/icons/money.png"> <?= i18n ( 'Module shop' ) ?>
							</div>-->
							<?}?>
							<div class="page" id="pageModulesConnected">
								<?= showAddedModules ( $this->page->ID ) ?>
							</div>
							<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
							<div class="page" id="pageModulesAvailable">
								<?= showFreeModules ( ) ?>
							</div>
							<!--<div class="page" id="pageModulesPro">
								<?= showProModules ( ) ?>
							</div>-->
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
					<img src="admin/gfx/icons/page_edit.png" style="float: left; margin: 0 4px 0 0" /> <?= i18n ( 'Edit' ) ?> "<strong id="EditHeadlineDiv"><?= $this->page->MenuTitle ? $this->page->MenuTitle : $this->page->_oldTitle ?></strong>"
				</h1>
				<input type="hidden" id="PageID" value="<?= $this->page->ID ?>">
				<input type="hidden" id="PageUrl" value="<?= $this->page->getUrl ( ) ?>">
				<div class="Container" id="ContentForm" style="padding-top: <?= MarginSize ?>px">
					<table class="LayoutColumns">
						<tr>
							<td>
								<h4><?= i18n ( 'Menu title' ) ?>:</h4>
								<input type="text" value="<?= $this->page->MenuTitle ?>" size="40" id="MenuTitle">
							</td>
							<td>
								&nbsp;&nbsp;&nbsp;
							</td>
							<td>
								<h4><?= i18n ( 'Searchable page title' ) ?>:</h4>
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
