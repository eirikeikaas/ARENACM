
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
			
			<div class="Spacer"><em></em></div>
			
			<div id="Objects" class="SubContainer">
			</div>
			
		<?if ( $this->Container ) { ?>
		</div>
		<?}?>
		
		<script src="<?= $this->scriptDir ?>/main.js"></script>
