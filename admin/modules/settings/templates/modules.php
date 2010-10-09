	
		<h1>
			Sett opp moduler på "<?= $this->site->SiteName ?>":
		</h1>
		<form name="modulelist" method="post">
		
			<input type="hidden" name="action" value="modules"/>
			<input type="hidden" name="SiteID" value="<?= $this->site->ID ?>"/>
			
			<input type="hidden" name="settings" value="<?= $this->modules[ 'settings' ]->ID ? $this->modules[ 'settings' ]->ID : '0' ?>"/>
			<input type="hidden" name="users" value="<?= $this->modules[ 'users' ]->ID ? $this->modules[ 'users' ]->ID : '0' ?>"/>
			<input type="hidden" name="contents" value="<?= $this->modules[ 'contents' ]->ID ? $this->modules[ 'contents' ]->ID : '0' ?>"/>
			<input type="hidden" name="news" value="<?= $this->modules[ 'news' ]->ID ? $this->modules[ 'news' ]->ID : '0' ?>"/>
			<input type="hidden" name="library" value="<?= $this->modules[ 'library' ]->ID ? $this->modules[ 'library' ]->ID : '0' ?>"/>
			<input type="hidden" name="extensions" value="<?= $this->modules[ 'extensions' ]->ID ? $this->modules[ 'extensions' ]->ID : '0' ?>"/>
			
			<div class="SubContainer">
				<table>
					<tr>
						<td><strong>Innstillinger:</strong></td>
						<td><input onchange="seth( 'settings', this )" type="checkbox"<?= $this->modules[ 'settings' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
					<tr>
						<td><strong>Brukere:</strong></td>
						<td><input onchange="seth( 'users', this )" type="checkbox"<?= $this->modules[ 'users' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
					<tr>
						<td><strong>Innhold:</strong></td>
						<td><input onchange="seth( 'contents', this )" type="checkbox"<?= $this->modules[ 'contents' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
					<tr>
						<td><strong>Nyheter:</strong></td>
						<td><input onchange="seth( 'news', this )" type="checkbox"<?= $this->modules[ 'news' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
					<tr>
						<td><strong>Bibliotek:</strong></td>
						<td><input onchange="seth( 'library', this )" type="checkbox"<?= $this->modules[ 'library' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
					<tr>
						<td><strong>Utvidelser:</strong></td>
						<td><input onchange="seth( 'extensions', this )" type="checkbox"<?= $this->modules[ 'extensions' ]->ID ? ' checked="checked"' : '' ?>/></td>
					</tr>
				</table>
			</div>
			<div class="SpacerSmall"></div>
			<button type="button" onclick="submit ( )">
				<img src="admin/gfx/icons/disk.png"/> Lagre listen
			</button>
			<button type="button" onclick="removeModalDialogue ( 'modules' )">
				<img src="admin/gfx/icons/cancel.png"/> Lukk
			</button>
		
		</form>
		
		<script>
			function seth ( key, obj )
			{
				document.modulelist[ key ].value = obj.checked ? 'saveme' : '0';
			}
		</script>
