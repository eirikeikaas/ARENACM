

	<h1>
		<?= i18n ( 'Select content field for insertion' ) ?>
	</h1>
	<table>
		<tr>
			<td>
				<strong><?= i18n ( 'Content' ) ?>:</strong>
			</td>
			<td>
				<select type="text/javascript" id="TxContentOptions">
					<?= $this->contentOptions ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong><?= i18n ( 'Content field' ) ?>:</strong>
			</td>
			<td id="TxContentFields">
				
			</td>
		</tr>
	</table>
	<div class="SpacerSmallColored"></div>
	<script>
		<?= file_get_contents ( 'lib/plugins/texteditor/javascript/plugin.js' ) ?>
	</script>
	<button type="button" onclick="window.txInsertContentField ()">
		<img src="admin/gfx/icons/table_row_insert.png"/> <?= i18n ( 'Insert field' ) ?>
	</button>
	<button type="button" onclick="removeModalDialogue ( 'fieldobject' )">
		<img src="admin/gfx/icons/cancel.png"/> <?= i18n ( 'Abort' ) ?>
	</button>

