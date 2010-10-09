<div id="libaryPagination">
	
	<button <?= $this->Prev ? '' : ' style="visibility: hidden; position: absolute;" '; ?> type="button" onclick="showLibraryContent( '<?= $this->Position - $this->Limit ?>' )">
		<img src="admin/gfx/icons/arrow_left.png" /> Forrige side
	</button>

	<button <?= $this->Next ? '' : ' style="visibility: hidden; position: absolute;" '; ?> type="button" onclick="showLibraryContent( ' <?= $this->Position + $this->Limit  ?>')">
		Neste side <img src="admin/gfx/icons/arrow_right.png" />
	</button>
	
	<?if ( $this->PageCount > 1 ) { ?>
	<div class="Container" style="display: inline">
		Viser side <?= $this->CurrentPage ?> av <?= $this->PageCount ?>.
	</div>
	<?}?>&nbsp;&nbsp;
</div>
