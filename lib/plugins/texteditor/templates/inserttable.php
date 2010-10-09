
	<h1>
		Sett inn en tabell
	</h1>
	<div class="Container">
		<table>
			<tr>
				<td>
					<p><strong>Kolonner</p></strong>
				</td>
				<td>
					<input type="text" size="6" id="tblCols" value="1">
				</td>
			</tr>
			<tr>
				<td>
					<p><strong>Rader</p></strong>
				</td>
				<td>
					<input type="text" size="6" id="tblRows" value="1">
				</td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="texteditor.get ( texteditor.activeEditorId ).insertTable (); removeModalDialogue('inserttable');"><img src="admin/gfx/icons/table.png"> Sett inn tabellen</button>
	<button type="button" onclick="removeModalDialogue('inserttable')"><img src="admin/gfx/icons/cancel.png"> Lukk</button>