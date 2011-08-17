
	<h1>
		<?= i18n ( 'File upload' ) ?>
	</h1>
	<iframe name="fuploadfr" style="position: absolute; top: -10000px; left: -10000px;"></iframe>
	<form method="post" target="fuploadfr" enctype="multipart/form-data" action="admin.php?plugin=objectconnector&pluginaction=uploadfile&module=extensions&extension=<?= $_REQUEST[ 'extension' ] ?>&pid=<?= $_REQUEST[ 'objectid' ] ?>">
		<div class="Container">
			<p>
				<input type="file" name="uploadfile"/>
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<p>
			<button type="submit"><img src="admin/gfx/icons/disk.png"/> <?= i18n ( 'Start uploading' ) ?></button>
			<button type="button" onclick="removeModalDialogue('upload')">
				<img src="admin/gfx/icons/cancel.png"/> <?= i18n ( 'Close' ) ?>
			</button>
		</p>
	</form>
