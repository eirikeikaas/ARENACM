	
		<div class="pmGroup<?= $this->active ? 'RowActive' : 'Row' ?>" id="pm_Groups_<?= $this->group->ID ?>" onmousedown="pmCheckItem<?= $this->PluginID ?> ( this )">
			<div class="<?= $this->switch ?>">
				<div class="pmGroupName">
					<?= $this->group->Name ?>
				</div>
			</div>
		</div>

