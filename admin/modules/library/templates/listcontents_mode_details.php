
	<table class="DetailList">
		<tr>
			<th colspan="2"><a href="admin.php?module=library&listmode=sortorder"><?= i18n ( 'Sortorder' ) ?></a>:</th>
			<th><a href="admin.php?module=library&listmode=title"><?= i18n ( 'Title' ) ?></a>:</th>
			<th><a href="admin.php?module=library&listmode=filename"><?= i18n ( 'Filename' ) ?></a>:</th>
			<th><a href="admin.php?module=library&listmode=filesize"><?= i18n ( 'Size' ) ?></a>:</th>
			<th><a href="admin.php?module=library&listmode=date"><?= i18n ( 'Date modified' ) ?></a>:</th>
		</tr>
		<?= $this->contents ?>
	</table>
