
	<h1>
		<?= $this->language->ID ? 'Endre ' . $language->Name : 'Nytt språk' ?>
	</h1>

	<div class="SubContainer">
		
		<form name="languageform" id="languageform">
		
			<input type="hidden" value="<?= $this->language->ID ?>" name="ID">
			
			<h2>
				Kort navn:
			</h2>
			<p>
				<input type="text" name="Name" value="<?= $this->language->Name ?>" size="35">
			</p>
		
			<table class="LayoutColumns">
				<tr>
					<td>
						<h2>
							Navn (orginalspråk):
						</h2>
						<p>
							<input name="NativeName" type="text" value="<?= $this->language->NativeName ?>" size="7">
						</p>
					</td>
					<td>
						<h2>
							Er hovedspråk?
						</h2>
						<p>
							<input name="IsDefault" id="isdefaultlang" type="hidden" value="<?= $this->language->IsDefault ?>">
							<input type="checkbox"<?= $this->language->IsDefault ? ' checked="checked"' : '' ?> onchange="document.getElementById ( 'isdefaultlang' ).value = this.checked ? '1' : '0'">
						</p>
					</td>
				</tr>
			</table>
		
			<h2>
				Url aktivator:
			</h2>
			<p>
				<input type="text" name="UrlActivator" value="<?= $this->language->UrlActivator ?>" size="35">
			</p>
		
			<h2>
				BaseUrl:
			</h2>
			<p>
				<input type="text" name="BaseUrl" value="<?= $this->language->BaseUrl ?>" size="35">
			</p>
		
		</form>
		
	</div>

	<div class="SpacerSmall"></div>

	<div class="SubContainer">
		<button onclick="cfgSaveLanguage ( )">
			<img src="admin/gfx/icons/disk.png"> Lagre
		</button>
		<button onclick="removeModalDialogue ( 'language' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
	</div>
