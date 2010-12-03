
	<h1>
		<?= i18n ( 'File upload' ) ?>
	</h1>
	<form method="post" enctype="multipart/form-data" action="admin.php?module=extensions&extension=easyeditor&action=uploadfile&pid=<?= $_REQUEST[ 'pid' ] ?>">
		<div class="Container">
			<p>
				<input type="file" name="uploadfile"/>
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<p>
			<button type="submit"><img src="admin/gfx/icons/disk.png"/> <?= i18n ( 'Start uploading' ) ?></button>
			<button type="button" onclick="removeModalDialogue('uploadfile')">
				<img src="admin/gfx/icons/cancel.png"/> <?= i18n ( 'Close' ) ?>
			</button>
		</p>
	</form>
