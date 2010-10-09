
	<h1>
		Last opp fil
	</h1>
	<form method="post" enctype="multipart/form-data" action="admin.php?module=extensions&extension=easyeditor&action=uploadfile&pid=<?= $_REQUEST[ 'pid' ] ?>">
		<div class="Container">
			<p>
				<input type="file" name="uploadfile"/>
			</p>
		</div>
		<div class="SpacerSmall"></div>
		<p>
			<button type="submit"><img src="admin/gfx/icons/disk.png"/> Lagre filen</button>
			<button type="button" onclick="removeModalDialogue('uploadfile')">
				<img src="admin/gfx/icons/cancel.png"/> Lukk
			</button>
		</p>
	</form>
