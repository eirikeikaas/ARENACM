
	<h1>
		Sorter sidene
	</h1>
	<div class="Container" style="padding: 2px">
		<div id="ReorderStructure">
			<?= $this->hierarchyOptions ?>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="reorder ( -1 )">
			<img src="admin/gfx/icons/arrow_up.png">
		</button>
		<button type="button" onclick="reorder ( 1 )">
			<img src="admin/gfx/icons/arrow_down.png">
		</button>
		<button type="button" onclick="saveorder ( )">
			<img src="admin/gfx/icons/disk.png"> Lagre
		</button>
		<button type="button" onclick="removeModalDialogue ( 'reorder' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
	</div>
