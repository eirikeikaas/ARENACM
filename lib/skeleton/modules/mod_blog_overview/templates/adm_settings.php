
	<div class="SubContainer">
		<table>
			<tr>
				<td>
					<strong>Antall artikler pr. side:</strong>
				</td>
				<td>
					<input type="text" id="mod_blog_limit" size="10" value="<?= $this->settings->Limit ?>">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Bruker kommentarer:</strong>
				</td>
				<td>
					<input type="checkbox" id="mod_blog_comments"<?= $this->settings->Comments ? ' checked="checked"' : '' ?>>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Viser forfatter:</strong>
				</td>
				<td>
					<input type="checkbox" id="mod_blog_showauthor"<?= $this->settings->ShowAuthor ? ' checked="checked"' : '' ?>>
				</td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="mod_blog_savesettings ( )">
		<img src="admin/gfx/icons/disk.png"> <span id="mod_blog_savetext">Lagre innstillingene</span>
	</button>
	<button type="button" onclick="mod_blog_abortedit ( )">
		<img src="admin/gfx/icons/newspaper.png"> Vis blog arkivet
	</button>

