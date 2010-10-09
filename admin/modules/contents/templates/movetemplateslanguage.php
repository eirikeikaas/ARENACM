<table class="LayoutColumns">
	<tr>
		<td>
			<h1>
				Flytt maler fra et språk til et annet
			</h1>
			<div class="SubContainer">
				<?= $this->templateListing ?>
			</div>
			<div class="Spacer"><em></em></div>
			<div class="SubContainer">
				<select id="MoveLanguageID">
					<?= $this->languages ?>
				</select>
			</div>
		</td>
	</tr>
</table>
<div class="SubContainer">
	<button type="button" onclick="removeModalDialogue ( 'MoveToLanguage' )">
		<img src="admin/gfx/icons/cancel.png" /> Lukk
	</button>
	<button type="button" onclick="if ( moveTemplates ( '<?= $this->templates ?>' ) ){ removeModalDialogue ( 'MoveToLanguage' ); }">
		<img src="admin/gfx/icons/bin.png" /> Flytt malene
	</button>
</div>