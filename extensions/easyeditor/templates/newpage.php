
	<h1>
		<?= i18n ( 'New subpage' ) ?> 
	</h1>
	<div class="SubContainer">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td><strong>Søkbar tittel:</strong></td>
				<td><input type="text" value="" id="npTitle" size="40"/></td>
			</tr>
			<tr>
				<td><strong>Menytittel:</strong></td>
				<td><input type="text" value="" id="npMenuTitle" size="40"/></td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmallColored"></div>
	<p>
		<button type="button" onclick="_addPage()">
			<img src="admin/gfx/icons/disk.png"/> <?= i18n ( 'Save page' ) ?>
		</button>
		<button type="button" onclick="removeModalDialogue('newpage')">
			<img src="admin/gfx/icons/cancel.png"/> <?= i18n ( 'Cancel' ) ?>
		</button>
	</p>
