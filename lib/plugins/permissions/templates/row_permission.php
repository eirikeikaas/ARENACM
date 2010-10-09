
		<div class="pmPermissionRow" id="pm_<?= $this->permission->AuthType ?>Permission_<?= $this->permission->ID ?>" onmousedown="pmCheckItem<?= $this->PluginID ?> ( this )">
			<div class="<?= $this->switch ?>">
				<?
					$q = '"';
					$this->readCheck = " onchange={$q}pmSetPermission{$this->PluginID} ( 'Read', this.checked ? '1' : '0', '{$this->permission->ID}' ){$q}";
					$this->writCheck = " onchange={$q}pmSetPermission{$this->PluginID} ( 'Write', this.checked ? '1' : '0', '{$this->permission->ID}' ){$q}";
					$this->publCheck = " onchange={$q}pmSetPermission{$this->PluginID} ( 'Publish', this.checked ? '1' : '0', '{$this->permission->ID}' ){$q}";
					$this->struCheck = " onchange={$q}pmSetPermission{$this->PluginID} ( 'Structure', this.checked ? '1' : '0', '{$this->permission->ID}' ){$q}";
				?>
				<div class="pmPermissionTypes">
					<input type="checkbox"<?= $this->permission->Read ? ' checked="checked"' : '' ?><?= $this->readCheck ?>>	
					<input type="checkbox"<?= $this->permission->Write ? ' checked="checked"' : '' ?><?= $this->writCheck ?>>	
					<input type="checkbox"<?= $this->permission->Publish ? ' checked="checked"' : '' ?><?= $this->publCheck ?>>	
					<input type="checkbox"<?= $this->permission->Structure ? ' checked="checked"' : '' ?><?= $this->struCheck ?>>
				</div>
				<div class="pmPermissionName">
					<?= $this->Name ?> <?= $this->Info ? ( '(' . $this->Info . ')' ) : '' ?>
				</div>
				<div class="pmBreak">
				</div>
			</div>
		</div>
