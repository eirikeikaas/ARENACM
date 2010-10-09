
					<strong>Ny mappe:</strong>
					<p>
						Opprett en ny mappe på valgte mappeplassering.
					</p>
					<div class="SpacerSmall"></div>
					<form id="newlevelform" onsubmit="addLibraryLevel (); return false">
						<input type="hidden" id="libraryParent" name="Parent" value="<?= $_REQUEST[ 'lid' ] ?>">
						<input type="text" style="width: 95%" id="libraryName" name="Name" value="">
					</form>
					<div class="Spacer"></div>
					<button onclick="addLibraryLevel ()"><img src="admin/gfx/icons/folder_add.png"> Legg til</button>

