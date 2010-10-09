
<div class="ModuleContainer">
	<table class="LayoutColumns">
		<tr>
			<td width="25%" style="padding-right: <?= MarginSize ?>px">
				
				<form method="post" enctype="multipart/form-data" name="importform" action="admin.php?module=users&amp;action=importusers">
					
					<h1>
						<div style="float: right">
							<button type="submit" title="Start import">
								<img src="admin/gfx/icons/script_go.png"/>
							</button>
							<button type="button" onclick="document.location='admin.php?module=users'" title="Avbryt">
								<img src="admin/gfx/icons/cancel.png"/>
							</button>
						</div>
						Importer brukere
					</h1>
				
					<div class="Container">
						
						<h2>Felter som skal importeres, med sorteringsindeks:</h2>
						<p>
							<table>
								<tr>
									<th>Navn:</th>
									<th>Indeks:</th>
									<th>&#x2713;</th>
								</tr>
							<?= $this->Fields ?>
							</table>
						</p>
						<h2>
							Fil for import
						</h2>
						<p>
							<input type="file" name="filestream"/>
						</p>
						<h2>
							Legge til i gruppe?
						</h2>
						<p>
							<select name="groupid[]" multiple="multiple" size="4">
								<option value="0">Ingen gruppe</option>
								<?
									$obj = new dbObject ( 'Groups' );
									$obj->addClause ( 'ORDER BY', 'Name ASC' );
									if ( $objs = $obj->find ( ) ) 
									{
										foreach ( $objs as $obj )
										{
											$ostr .= '<option value="' . $obj->ID . '">' . $obj->Name . '</option>';
										}
										return $ostr;
									}
								?>
							</select>
						</p>
						<h2>
							Generere passord?
						</h2>
						<p>
							<input type="hidden" name="GeneratePassword" value="0"/>
							<input type="checkbox" onchange="document.importform.GeneratePassword.value = this.checked ? '1' : '0'"/>
						</p>
						<h2>
							Generere loggfil?
						</h2>
						<p>
							<input type="hidden" name="UseLogfile" value="0"/>
							<input type="checkbox" onchange="document.importform.UseLogfile.value = this.checked ? '1' : '0'"/>
						</p>
						<h2>
							Hvor skal loggfilen sendes (epost adresse)?
						</h2>
						<p>
							<input type="text" value="" name="Email"/>
						</p>
						<h2>
							Felter separeres med:
						</h2>
						<p>
							<input type="text" value="," name="Separator"/>
						</p>
						<p>
							<button type="submit">
								<img src="admin/gfx/icons/script_go.png"/> Start importeringsprosessen!
							</button>
							<button type="button" onclick="document.location='admin.php?module=users'">
								<img src="admin/gfx/icons/cancel.png"/> Avbryt
							</button>
						</p>
					</div>
				
				</form>
				
			</td>
		</tr>
	</table>
</div>
