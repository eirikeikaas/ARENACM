
	<form method="post" action="admin.php?module=users&amp;action=savegroup&amp;gid=<?= $this->group->ID ?>">
	
		<input type="hidden" name="GroupID" class="hidden" value="<?= $_REQUEST[ 'pgid' ] ? $_REQUEST[ 'pgid' ] : $this->group->GroupID ?>">
	
		<div class="ModuleContainer">
			<?if ( $this->group->ID && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->group, 'Write', 'admin' ) ) { ?>
			<div id="groupEditTabs" style="margin-bottom: -6px">
				<div class="tab" id="tabGroupEdit">
					<img src="admin/gfx/icons/folder.png" /> <?= i18n ( 'Edit group info' ) ?>
				</div>
				
				<div class="tab" id="tabGroupPermissions">
					<img src="admin/gfx/icons/folder.png" /> <?= i18n ( 'Edit group permissions' ) ?>
				</div>
				<div class="tab" id="tabModulePermissions">
					<img src="admin/gfx/icons/flag_red.png" /> <?= i18n ( 'Edit module access' ) ?>
				</div>
				<div class="tab" id="tabGroupExtraFields">
					<img src="admin/gfx/icons/table_row_insert.png" /> <?= i18n ( 'Extrafields for users' ) ?>
				</div>
				<div class="page" id="pageGroupEdit">
			<?}?>
				<?= ( $GLOBALS[ 'Session' ]->AdminUser->_dataSource != 'core' || !$this->group->ID ) ? ( $this->group->ID ? '<h1>Endre gruppe</h1>' : '<h1>Ny gruppe</h1>' ): '' ?>
			<?if ( !$this->group->ID || !$GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->group, 'Write', 'admin' ) ) { ?>
				<div class="Container">
			<?}?>
					
					<table class="Layout">
						<tr>
							<td width="100%" style="vertical-align: top">				
								<p>
									<strong><?= i18n ( 'Name' ) ?>:</strong>
								</p>
								<p>
									<input type="text" size="30" name="Name" value="<?= $this->group->Name ?>" />
								</p>
								<p>
									<strong><?= i18n ( 'Description' ) ?>:</strong>
								</p>
								<p>
									<textarea cols="55" rows="19" name="Description"><?= $this->group->Description ?></textarea>
								</p>
								<?if ( $GLOBALS[ 'Session' ]->AdminUser->isSuperUser ( ) ) { ?>
								<p>
									<strong><?= i18n ( 'Superuser privileges' ) ?>:</strong>&nbsp;<input type="checkbox"<?= $this->group->SuperAdmin ? ' checked="checked"' : '' ?> onchange="document.getElementById ( 'super_admin' ).value = this.checked ? '1' : '0'" style="position: relative; top: 4px;">
								</p>
								<?}?>
								<input type="hidden" name="SuperAdmin" value="<?= $this->group->SuperAdmin ?>" id="super_admin">
							</td>
						</tr>
					</table>
				</div>
			<?if ( $this->group->ID && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->group, 'Write', 'admin' ) ) { ?>
				<div class="page" id="pageGroupPermissions">
					<?= renderPlugin ( 'permissions', Array ( "ContentTable"=>"Groups", "ContentID"=>$this->group->ID, "PermissionType"=>'admin', 'PluginID'=>'editgroup' ) ) ?>
				</div>
				<div class="page" id="pageModulePermissions">
					<table class="Layout">
						<tr>
							<td width="100%" style="vertical-align: top; padding-left: <?= MarginSize ?>px">
								<?= $this->AdminGui ?>		
							</td>
						</tr>
					</table>
				</div>
				<div class="page" id="pageGroupExtraFields">
					<h2 class="BlockHead">
						<?= i18n ( 'Extrafields for users in this group' ) ?>
					</h2>
					<div class="BlockContainer">
						<?= renderPlugin ( 'extrafields', Array ( 'ContentType'=>'Groups', 'ContentID'=>$this->group->ID ) ) ?>
					</div>
				</div>
			</div>
			<?}?>
			
			<div class="SpacerSmallColored"><em></em></div>
			
			<div class="Container" style="padding: 2px 2px 0 2px">
				
				<?if ( !$this->group->ID || $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->group, 'Write', 'admin' ) ) { ?>
				<button type="submit">
					<img src="admin/gfx/icons/disk.png" /> <?= i18n ( 'Save' ) ?>
				</button>
				<?}?>
				<button type="button" onclick="document.location = 'admin.php?module=users'">
					<img src="admin/gfx/icons/cancel.png" /> <?= i18n ( 'Close' ) ?>
				</button>
				
			</div>
			
		</div>		
	
	</form>
	
	<script type="text/javascript">
		<?if ( $this->group->ID && $GLOBALS[ 'Session' ]->AdminUser->checkPermission ( $this->group, 'Write', 'admin' ) ) { ?>
		initTabSystem ( 'groupEditTabs' );
		document.getElementById ( 'tabGroupEdit' ).onclick ( );
		<?}?>
	</script>
