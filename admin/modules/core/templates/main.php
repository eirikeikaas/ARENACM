
			<div class="ModuleContainer">
			
				<div class="tabs" id="SettingsTabs">				
			
					<div class="tab" id="tabSettings">
						<img src="admin/gfx/icons/user.png" /> Admin Core Innstillinger
					</div>
				
					<div class="tab" id="tabSites">
						<img src="admin/gfx/icons/sitemap.png" /> ARENA2 Nettsider
					</div>
				
					<div class="page" id="pageSettings">
						<?= $this->pageSettings ?>
					</div>
				
					<div class="page" id="pageSites">
						<h1>
							ARENA2 nettsider i databasen
						</h1>
						<div class="Container">
							<div class="SubContainer" style="padding: 0">
								<table style="border-collapse: collapse; border-spacing: 0px; width: 100%">
									<?= $this->Sites ?>
								</table>
							</div>
						</div>
					
						<div class="SpacerSmall"></div>
						
						<button type="button"  onclick="initModalDialogue ( 'site', 500, 500, 'admin.php?module=core&function=site' )">
							<img src="admin/gfx/icons/world_go.png" /> Legg til en ny ARENA2 nettside
						</button>
					</div>
				
				</div>
				
			</div>
			
			<script type="text/javascript" src="admin/modules/core/javascript/core.js"></script>
			
			<script type="text/javascript">
				initTabSystem ( 'SettingsTabs' );
			</script>
