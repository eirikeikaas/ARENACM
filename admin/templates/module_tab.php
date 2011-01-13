
	<div class="ModuleTab<?= ( $this->active ? "Active" : "" ) ?>">
		<span>
			<a href="<?= ( $this->link ? ( 'admin.php?' . $this->link ) : ( BASE_URL . 'admin.php?module=' . $this->module ) ) ?>">
				<img src="admin/gfx/icons/<?= $this->image ?>" alt="module" width="16" height="16"> <?= $this->moduleName ?>
			</a>
		</span>
	</div>

