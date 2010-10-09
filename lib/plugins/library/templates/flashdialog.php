
	<h1>
		Sett inn flash film
	</h1>
	
	<div class="Container">
		<p>
			<strong>Velg mappe:</strong>
		</p>
		<?= generatePluginFolderstructure ( $GLOBALS[ 'Session' ]->pluginLibraryLevelID ); ?>
	</div>
	<div class="SpacerSmallColored"></div>
	<div class="Container" id="FlashFiles">
		Laster inn...
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="removeModalDialogue ( 'library' )">
		<img src="admin/gfx/icons/cancel.png"/> Lukk
	</button>
