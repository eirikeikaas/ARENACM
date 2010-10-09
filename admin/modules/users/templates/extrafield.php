<h1>
	Legg til et ekstra felt
</h1>
<div class="Container">
	<p>
		<strong>Felttype:</strong>
	</p>
	<p>
		<select id="extraFieldType">
			<option value="text">Enkelt tekstfelt</option>
			<option value="longtext">Artikkel/større tekst</option>
		</select>
	</p>
	<p>
		<strong>Feltnavn:</strong>
	</p>
	<p>
		<input type="text" value="" id="extraFieldName" size="30"/>
	</p>
</div>
<div class="SpacerSmall"></div>
<button type="button" onclick="executeExtraField ( )">
	<img src="admin/gfx/icons/accept.png" /> Ok
</button>
<button type="button" onclick="removeModalDialogue ( 'extrafield' )">
	<img src="admin/gfx/icons/cancel.png" /> Avbryt
</button>