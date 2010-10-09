
	<h1>
		Flytt "<?= $this->page->MenuTitle ?>"
	</h1>
	<div class="Container" style="padding: 2px">
		<div id="StructureMove">
			<?= $this->hierarchyOptions ?>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="executeMove ( )">
			<img src="admin/gfx/icons/disk.png"> Flytt til under valgte side
		</button>
		<button type="button" onclick="removeModalDialogue ( 'move' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
	</div>
