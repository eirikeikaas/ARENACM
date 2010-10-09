
	<div class="ModuleContainer">
	
		<?if ( !$GLOBALS[ 'document' ]->extensionsOnTop ) { ?>
		<?= $this->extensionList ?>	
		<br style="clear: both">
	
		<div class="pageActive">
		<?}?>
			<?= $this->content ?>
		<?if ( !$GLOBALS[ 'document' ]->extensionsOnTop ) { ?>
		</div>
		<?}?>
	
	</div>
