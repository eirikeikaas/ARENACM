

	<h1 id="userselectorh1">
		<?= $this->user->ID ? ( 'Endre ' . ( $this->user->Name ? $this->user->Name : $this->user->Email ) ) : 'Ny bruker' ?>
	</h1>
	<iframe class="Hidden" name="useriframe"></iframe>
	<form method="post" id="userselectorform" action="admin.php?plugin=userselector&pluginaction=saveuser" target="useriframe">
		<input type="hidden" id="userselectorID" value="<?= $this->user->ID ? $this->user->ID : '0' ?>" name="ID">
		<div class="Container">
			<table class="Form">
				<tr>
					<td><strong>Navn:</strong></td>
					<td><input type="text" name="Name" value="<?= $this->user->Name ?>" size="30"></td>
				</tr>
				<tr>
					<td><strong>E-mail:</strong></td>
					<td><input type="text" name="Email" value="<?= $this->user->Email ?>" size="30"></td>
				</tr>
				<tr>
					<td><strong>Grupper:</strong></td>
					<td><?= $this->groups ?></td>
				</tr>
				<tr>
					<td><strong>Beskrivelse:</strong></td>
					<td><textarea name="Description" cols="32" rows="5"><?= $this->user->Description ?></textarea></td>
				</tr>
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="document.getElementById ( 'userselectorform' ).submit ( )">
			<img src="admin/gfx/icons/disk.png"> Lagre
		</button>
		<button type="button" onclick="var str = document.location+''; document.location = str.split('?')[0] + '?' + ( Math.floor ( Math.random () * 100 ) )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
	</form>

