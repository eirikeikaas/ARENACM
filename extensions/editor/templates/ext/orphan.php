
		<div class="Orphan">
			<h4>
				<div class="Buttons">
					<button title="Endre feltet" type="button" onclick="editEditorField ( <?= $this->field->ID ?>, '<?= $this->field->Table ?>' )"><img src="admin/gfx/smallbutton_edit.png"></button>
					<button title="Fjern feltet" type="button" onclick="removeField ( <?= $this->field->ID ?>, '<?= $this->field->Table ?>' )"><img src="admin/gfx/smallbutton_remove.png"></button>
					<button title="Flytt opp" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->field->Table ?>', -1 )"><img src="admin/gfx/smallbutton_up.png"></button>
					<button title="Flytt ned" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->field->Table ?>', 1 )"><img src="admin/gfx/smallbutton_down.png"></button>
				</div>
				<a onclick="javascript: scrollTo ( 0, getElementTop ( this ) );"><?= str_replace ( '_', ' ', $this->field->Name ) ?> (<?= i18n ( 'in' ) ?> <?= $this->field->ContentGroup ?>) - <?= i18n ( 'wrong content group' ) ?>:</a>
			</h4>
		</div>
		<div class="SpacerSmall"></div>

