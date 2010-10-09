
	<?
		$this->name = "Extra_{$this->data->ID}_{$this->data->DataTable}_DataText";
	?>
	
	<div class="SpacerSmall"></div>
	<strong><?= $this->data->Name ?>:</strong>
	<div class="SpacerSmall"></div>
	<textarea class="mceSelector" id="<?= $this->name ?>" rows="20" cols="55" name="<?= $this->name ?>"><?=  str_replace ( array ( '<', '>' ), array ( '&lt;', '&gt;' ), encodeArenaHTML ( stripslashes ( $this->data->DataText ) ) )  ?></textarea>
	<div class="SpacerSmall"></div>
	
	
