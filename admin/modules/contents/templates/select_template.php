<h1>
	<?if ( $this->languages ) { ?>
	<span style="float: right"><select name="Language" style="height: 18px; font-size: 10px; border-top: 1px solid #C9CD8E; border-left: 1px solid #C9CD8E; border-right: 1px slid #fff; border-bottom: 1px solid #fff; background: #6B7D31; color: #ffa;" onchange="var loc = document.location + ''; loc = loc.split ( '#' ); document.location = loc[ 0 ] + '#language' + this.value">
		<?= $this->languages ?>
	</select></span>
	<?}?>
	Ny side
</h1>
<form>
	<?if ( $this->options ) { ?>
	<div class="SubContainer">
		<p>
			<strong>Standard:</strong>
		</p>
		<p>
			<input type="radio" name="template"<?= $this->selectedDefault ?> onchange="document.ModalSelection = '__normal'" /> Tom side (ingen templatespesifisering)
		</p>	
		<?= $this->options ?>
	</div>
	<?}?>
	<?if ( !$this->options ) { ?>
	<input type="hidden" name="template" value="">
	<?}?>
	<div class="SpacerSmall"></div>
	<div class="SubContainer">
		<p>
			<strong>
			Overskrift:
			</strong>
		</p>
		<p>
			<input type="text" id="NewPageTitle" value="Ny side" size="25">
		</p>
		<p>
			<strong>
			Menytittel:
			</strong>
		</p>
		<p>
			<input type="text" id="NewPageMenuTitle" value="Ny side" size="25">
		</p>
	</div>
</form>
<div class="Spacer"></div>
<button type="button" onclick="if ( !document.ModalSelection ){ alert ( 'Du må velge en template' ); } else { makeNewPage ( document.ModalSelection ); }">
	<img src="admin/gfx/icons/accept.png" /> Ok
</button>
<button type="button" onclick="removeModalDialogue ( 'SelectTemplate' )">
	<img src="admin/gfx/icons/cancel.png" /> Lukk
</button>

<?if (  $this->selectedDefault ) { ?>
<script>
	document.ModalSelection = '__normal';
</script>
<?}?>