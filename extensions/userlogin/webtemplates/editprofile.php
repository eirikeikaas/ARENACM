

	<div class="EditProfile">
	
		<h1 class="YourProfile">
			<span><?= i18n ( 'Your profile' ) ?></span>
		</h1>
		
		<hr/>
		
		<form method="post" action="<?= $this->content->getUrl ( ) ?>?ue=login&function=saveinfo<?= ( $_REQUEST[ 'die' ] ? '&gohome=true' : '' ) ?>" name="infoform">
		
			<table>
				<tr>
					<td>
						<?= i18n ( 'Full name' ) ?>:
					</td>
					<td>
						<input type="text" name="Name" value="<?= $GLOBALS[ 'webuser' ]->Name ?>" size="35"/>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'E-mail address' ) ?>:
					</td>
					<td>
						<input type="text" name="Email" value="<?= $GLOBALS[ 'webuser' ]->Email ?>" size="35"/>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'Username' ) ?>:
					</td>
					<td>
						<strong><?= $GLOBALS[ 'webuser' ]->Username ?></strong>
					</td>
				</tr>
				<?if ( GetSettingValue ( 'Login_Extension', 'usenickname' ) ) { ?> 
				<tr>
					<td>
						<?= i18n ( 'Nickname' ) ?>:
					</td>
					<td>
						<input type="text" name="Nickname" size="20" value="<?= $GLOBALS[ 'webuser' ]->Nickname ?>" />
					</td>
				</tr>
				<?}?>
				<tr>
					<td>
						<?= i18n ( 'Telephone' ) ?>:
					</td>
					<td>
						<input type="text" name="Telephone" value="<?= $GLOBALS[ 'webuser' ]->Telephone ?>" size="15"/>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'Address' ) ?>:
					</td>
					<td>
						<input type="text" name="Address" value="<?= $GLOBALS[ 'webuser' ]->Address ?>" size="35"/>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'Postcode' ) ?>/<?= i18n ( 'City' ) ?>:
					</td>
					<td>
						<input type="text" name="Postcode" value="<?= $GLOBALS[ 'webuser' ]->Postcode ?>" size="5"/>
						<input type="text" name="City" value="<?= $GLOBALS[ 'webuser' ]->City ?>" size="24"/>
					</td>
				</tr>
				<?if ( !GetSettingValue ( 'Login_Extension', 'hidecountry' ) ) { ?>
				<tr>
					<td>
						<?= i18n ( 'Country' ) ?>:
					</td>
					<td>
						<input type="text" name="Country" value="<?= $GLOBALS[ 'webuser' ]->Country ?>" size="30"/>
					</td>
				</tr>
				<?}?>
				<tr>
					<td colspan="2">
						<h2><?= i18n ( 'Alter your password' ) ?></h2>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'Password' ) ?>:
					</td>
					<td>
						<input type="password" name="Password" value="********" size="20"/>
					</td>
				</tr>
				<tr>
					<td>
						<?= i18n ( 'Confirm password' ) ?>:
					</td>
					<td>
						<input type="password" name="Password_Confirm" value="********" size="20"/>
					</td>
				</tr>
			</table>
			
		</form>
		
		<hr/>
		
		<p>
			<button type="button" onclick="verifyInformationForm ( )">
				<?= i18n ( 'Save information' ) ?>
			</button>
			<button type="button" onclick="document.location='<?= getLocalizedBaseUrl ( ) ?>'">
				<?= i18n ( 'Abort' ) ?>
			</button>
		</p>
		
		
	
	</div>
