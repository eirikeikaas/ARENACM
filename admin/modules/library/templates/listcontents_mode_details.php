
	<table class="List">
		<tr>
			<th colspan="2"<?= $GLOBALS['Session']->LibraryListmode=='sortorder'?' class="Active"' : '' ?> width="48"><a href="admin.php?module=library&listmode=sortorder"><?= i18n ( 'Sortorder' ) ?>:</a></th>
			<th<?= $GLOBALS['Session']->LibraryListmode=='title'?' class="Active"' : '' ?>><a href="admin.php?module=library&listmode=title"><?= i18n ( 'Title' ) ?>:</a></th>
			<th<?= $GLOBALS['Session']->LibraryListmode=='filename'?' class="Active"' : '' ?>><a href="admin.php?module=library&listmode=filename"><?= i18n ( 'Filename' ) ?>:</a></th>
			<th<?= $GLOBALS['Session']->LibraryListmode=='filesize'?' class="Active"' : '' ?> width="70"><a href="admin.php?module=library&listmode=filesize"><?= i18n ( 'Size' ) ?>:</a></th>
			<th<?= $GLOBALS['Session']->LibraryListmode=='date'?' class="Active"' : '' ?> width="120"><a href="admin.php?module=library&listmode=date"><?= i18n ( 'Date modified' ) ?>:</a></th>
			<th width="70">Valg:</th>
		</tr>
		<?= $this->contents ?>
	</table>
	<?= $this->nav ?>

