<h1>
	<div style="float: right">
		<button type="button" onclick="removeModalDialogue ( 'library' )" title="Lukk vinduet">
			<img src="admin/gfx/icons/cancel.png" />
		</button>
	</div>
	Velg mappe
</h1>
<div id="juba" class="SubContainer">
	<h2 class="PluginLibrary">Mapper:</h2>
	<div class="SpacerSmall"></div>
	<?= generatePluginFolderstructure ( $GLOBALS[ 'Session' ]->pluginLibraryLevelID ); ?>
</div>
<div class="SpacerSmall"></div>
<h1>
	Velg bilde
</h1>
<div class="SubContainer" id="DiaLibraryImages" style="height: 287px; overflow: auto">
	Laster inn...
</div>
<div class="SpacerSmallColored"></div>
<div class="SubContainer" style="padding: <?= MarginSize ?>px">
	<iframe style="visibility: hidden; position: absolute; top: -1000px; left: -1000px" name="upfr"></iframe>
	<form name="upfrm" action="admin.php?plugin=library&pluginaction=uploadimage" method="post" enctype="multipart/form-data" target="upfr">
	<strong>Last opp bilde:</strong>&nbsp;<input type="file" name="ImageStream"> <button type="submit"><img src="admin/gfx/icons/attach.png"> Last opp</button>
	</form>
</div>
<div class="SpacerSmall"></div>
<button type="button" onclick="removeModalDialogue ( 'library' )">
	<img src="admin/gfx/icons/cancel.png" /> Lukk vinduet
</button>
