				<?if ( $this->ShowHeader ) { ?>
				<h1>
					<div class="HeaderBox">
						<a href="javascript:;" onclick="getHelp ( 'no/funksjonsbokser/biblotek/index.html' )"><img src="admin/gfx/icons/help.png" style="border: 0" /></a>
					</div>
					Bibliotek - innhold og media 
				</h1>
				<?}?>
				<div id="PluginLibrary"><div class="Container">Laster inn...</div></div>
				<link rel="stylesheet" href="<?= BASE_URL ?>lib/plugins/library/css/plugin.css" />
				<script type="text/javascript" src="<?= BASE_URL ?>lib/plugins/library/javascript/plugin.js"></script>
				<script type="text/javascript"> initPluginLibrary ( '<?= $this->ContentType ?>', '<?= $this->ContentID ?>', '<?= $this->Mode ?>' ); </script>
