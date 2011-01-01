
		<h4>
			<div class="Buttons">
				<button title="<?= i18n ( 'Edit field' ) ?>" type="button" onclick="editEditorField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_edit.png"></button>
				<button title="<?= i18n ( 'Remove field' ) ?>" type="button" onclick="removeField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>' )"><img src="admin/gfx/smallbutton_remove.png"></button>
				<button title="<?= i18n ( 'Move up' ) ?>" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', -1 )"><img src="admin/gfx/smallbutton_up.png"></button>
				<button title="<?= i18n ( 'Move down' ) ?>" type="button" onclick="reorderField ( <?= $this->field->ID ?>, '<?= $this->fieldType ?>', 1 )"><img src="admin/gfx/smallbutton_down.png"></button>
			</div>
			<a onclick="javascript: scrollTo ( 0, getElementTop ( this ) );"><?= str_replace ( '_', ' ', $this->field->Name ) ?> (<?= i18n ( 'in' ) ?> <?= $this->fieldGroup ?>):</a>
		</h4>
		<p>
			<input type="text" class="ExtraFieldData" size="40" id="<?= $this->fieldID ?>" value="<?= addslashes ( $this->field->DataString ) ?>"/>
		</p>
