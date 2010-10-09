
	<div class="SpacerSmall"></div>
	<strong><?= $this->data->Name ?>:</strong>
	<div class="SpacerSmall"></div>
	<?
		$this->name = "Extra_{$this->data->ID}_{$this->data->DataTable}_DataString";
	?>
	<p>
		<input type="text" id="<?= $this->name ?>" size="40" name="<?= $this->name ?>" value="<?= $this->data->DataString ?>" />
	</p>
	<div class="SpacerSmall"></div>
	
	
