
		<h1>
			Lag en ny fil
		</h1>
		
		<div class="SubContainer">
			<table class="LayoutColumns">
				<tr>
					<td>
						<strong>Filetype:</strong>
					</td>
					<td>
						<select id="nfFiletype">
							<option value="css">Et stilark</option>
							<option value="txt">En tekst fil</option>
							<option value="js">Et javascript</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Filnavn:</strong>
					</td>
					<td>
						<input type="text" id="nfFilename" size="43">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Innhold:</strong>
					</td>
					<td>
						<textarea id="nfContent" rows="10" cols="45"></textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button type="button" onclick="createLibraryFile ( 1 )">
							<img src="admin/gfx/icons/disk.png"/> Lagre
						</button>
						<button type="button" onclick="removeModalDialogue ( 'newfile' )">
							<img src="admin/gfx/icons/cancel.png"/> Avbryt
						</button>
					</td>
				</tr>
			</table>
		</div>
		
