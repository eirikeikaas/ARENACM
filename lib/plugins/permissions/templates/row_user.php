
		<div class="pmUserRow" id="pm_Users_<?= $this->user->ID ?>" onmousedown="pmCheckItem<?= $this->PluginID ?> ( this )">
			<div class="<?= $this->switch ?>">
				<div class="pmGroupName">
					<?= $this->user->Name ?> (<?= $this->user->Username ?>)
				</div>
			</div>
		</div>

