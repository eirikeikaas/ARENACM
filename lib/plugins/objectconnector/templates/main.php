
		<?if ( $this->Heading ) { ?>
		<h1>
			<?= $this->Heading ?>
		</h1>
		<?}?>
		
		<script type="text/javascript">
			var ObjectConnectionType = '<?= $this->objectConnectionType ?>';
			var ObjectConnectionId = '<?= $this->objectConnectionId ?>';
		</script>
		
		<?if ( $this->Container ) { ?>
		<div class="Container">
		<?}?>
			<div id="ObjectDropArea" class="Dropzone">
			
				Slipp objekter her
			
			</div>
			<div class="SpacerSmall"><em></em></div>
			<div id="Objects" class="Container">
			</div>
			
			<div class="SpacerSmall"><em></em></div>
			<div class="Upload">
				<button type="button" onclick="poc_doUploadObject()">
					<img src="admin/gfx/icons/attach.png"/> Last opp tilkobling
				</button>
				<button type="button" onclick="poc_emptyConnectedObjects()">
					<img src="admin/gfx/icons/cancel.png"/> Fjern tilkoblinger
				</button>
			</div>
			
		<?if ( $this->Container ) { ?>
		</div>
		<?}?>
		
		<script src="<?= $this->scriptDir ?>/main.js"></script>
