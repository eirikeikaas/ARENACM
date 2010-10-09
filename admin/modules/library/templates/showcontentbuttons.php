						
					<div>
						<button onclick="addLibraryImage ()"><img src="admin/gfx/icons/image_add.png"> Nytt bilde</button>
						<button onclick="addLibraryFile ()"><img src="admin/gfx/icons/page_add.png"> Ny fil</button>
						<button onclick="createLibraryFile ()"><img src="admin/gfx/icons/star.png"> Lag en fil</button>
						<button onclick="initModalDialogue ( 'optimizesize', 400, 400, 'admin.php?module=library&function=optimizesize&lid=' + document.lid )"><img src="admin/gfx/icons/folder_image.png"> Optimaliser bildene i mappen</button>
						<button onclick="deleteSelected ()"><img src="admin/gfx/icons/image_delete.png"> Slett valgte</button>
					
					</div>
					
					!!!
					
					<button class="Small" onclick="addLibraryImage ()" title="Last opp nytt bilde"><img src="admin/gfx/icons/image_add.png"></button>&nbsp;
					<button class="Small" onclick="addLibraryFile ()" title="Last opp ny fil"><img src="admin/gfx/icons/page_add.png"></button>&nbsp;
					<button class="Small" onclick="createLibraryFile ()" title="Lag en ny fil"><img src="admin/gfx/icons/star.png"></button>
				
