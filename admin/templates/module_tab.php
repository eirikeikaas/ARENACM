
	<div class="ModuleTab<?= ( $this->active ? "Active" : "" ) ?>" onclick="document.location='<?= $this->link ? ( 'admin.php?' . $this->link ) : ( BASE_URL . 'admin.php?module=' . $this->module ) ?>'">
		<span>
			<img src="admin/gfx/icons/<?= $this->image ?>" alt="module" width="16" height="16">
			<a href="javascript:void(0)"><?= $this->moduleName ?></a>
		</span>
	</div>

