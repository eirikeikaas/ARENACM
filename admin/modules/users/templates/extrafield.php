<h1>
	<?= i18n ( 'Add extra field' ) ?>
</h1>
<div class="Container">
	<p>
		<strong><?= i18n ( 'Fieldtype' ) ?>:</strong>
	</p>
	<p>
		<select id="extraFieldType">
			<option value="text"><?= i18n ( 'Small textfield' ) ?></option>
			<option value="longtext"><?= i18n ( 'Big textfield' ) ?></option>
		</select>
	</p>
	<p>
		<strong><?= i18n ( 'Fieldname' ) ?>:</strong>
	</p>
	<p>
		<input type="text" value="" id="extraFieldName" size="30"/>
	</p>
</div>
<div class="SpacerSmall"></div>
<button type="button" onclick="executeExtraField ( )">
	<img src="admin/gfx/icons/accept.png" /> <?= i18n ( 'Ok' ) ?>
</button>
<button type="button" onclick="removeModalDialogue ( 'extrafield' )">
	<img src="admin/gfx/icons/cancel.png" /> <?= i18n ( 'Cancel' ) ?>
</button>
