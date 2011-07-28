						
					<div>
						<button onclick="addLibraryImage ()"><img src="admin/gfx/icons/image_add.png"> <?= i18n ( 'Upload image' ) ?></button>
						<button onclick="addLibraryFile ()"><img src="admin/gfx/icons/page_add.png"> <?= i18n ( 'Upload file' ) ?></button>
						<button onclick="createLibraryFile ()"><img src="admin/gfx/icons/star.png"> <?= i18n ( 'Create a file' ) ?></button>
						<button onclick="initModalDialogue ( 'optimizesize', 400, 400, 'admin.php?module=library&function=optimizesize&lid=' + document.lid )"><img src="admin/gfx/icons/folder_image.png"> <?= i18n ( 'Optimize images' ) ?></button>
						<button onclick="deleteSelected ()"><img src="admin/gfx/icons/image_delete.png"> <?= i18n ( 'Delete selected' ) ?></button>
					
					</div>
					
					!!!
					
					<div class="HeaderBox">
						<button onclick="addLibraryImage ()" title="Last opp nytt bilde"><img src="admin/gfx/icons/image_add.png"></button>
						<button onclick="addLibraryFile ()" title="Last opp ny fil"><img src="admin/gfx/icons/page_add.png"></button>
						<button onclick="createLibraryFile ()" title="Lag en ny fil"><img src="admin/gfx/icons/star.png"></button>
					</div>
				
