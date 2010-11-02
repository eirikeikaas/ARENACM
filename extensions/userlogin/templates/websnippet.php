		
		
		<?if ( $this->popupdialogs = GetSettingValue ( 'Login_Extension', 'popupdialogs' ) ) { ?>
		<?}?>
		
		<?if ( $GLOBALS[ 'webuser' ]->ID && GetSettingValue ( 'Login_Extension', 'hidewelcometext' ) <= 0 ) { ?>
			<p class="Welcome">
				<strong><?= i18n ( "Welcome" ) ?>, <?= $GLOBALS[ 'webuser' ]->Name ?>!</strong>
			</p>
		<?}?>
		<?if ( $GLOBALS[ 'webuser' ]->ID && GetSettingValue ( 'Login_Extension', 'hidewelcometext' ) > 0 ) { ?>
			<p class="LoggedInAs">
				<?= i18n ( 'You are logged in as' ) ?> <strong><?= $GLOBALS[ 'webuser' ]->Name ?></strong>
			</p>
		<?}?>
		<?if ( $GLOBALS[ 'webuser' ]->ID && GetSettingValue ( 'Login_Extension', 'hidelogintime' ) <= 0 ) { ?>
			<p>
				<?= i18n ( "You have been logged in since" ) ?> <?
					if ( date ( "Y-m-d" ) == date ( "Y-m-d", strtotime ( $GLOBALS[ "webuser" ]->DateLogin ) ) )
						return i18nDate ( date ( "H:i", strtotime ( $GLOBALS[ "webuser" ]->DateLogin ) ) );
					else 
						return i18nDate ( date ( "Y-m-d H:i", strtotime ( $GLOBALS[ "webuser" ]->DateLogin ) ) );
				?>
			</p>
		<?}?>
		<?if ( $GLOBALS[ 'webuser' ]->ID ) { ?>
			<p class="LoginEdit">
				<button id="LoginEdit" class="Edit" type="button" onclick="document.location='<?= BASE_URL . $this->content->getRoute ( ) ?>?ue=userlogin&function=editprofile'">
					<span><?= i18n ( 'Edit your profile' ) ?></span>
				</button>
			</p>
			<?
				global $Session;
				if ( trim ( GetSettingValue ( 'webshop', 'productpage' . $Session->LanguageCode ) ) )
				{
					return '
			<p>
				<button id="LoginProfile" class="ShoppingLog" type="button" onclick="document.location=\\\\\'' . BASE_URL . $this->content->getRoute ( ) . '?ue=userlogin&function=shoppinglog\\\\\'">
					<span>' . i18n ( 'Show shopping log' ) . '</span>
				</button>
			</p>
					';
				}
			?>
			<p class="LogoutParagraph">
				<button class="Logout" type="button" onclick="document.location='<?= $this->content->getUrl ( ) ?>?logout=true'">
					<span><?= i18n ( 'Logout' ) ?></span>
				</button>
			</p>
		<?}?>
		<?if ( $GLOBALS[ 'webuser' ]->ID && $this->popupdialogs ) { ?>
			<script>
				var LoginEdit = document.getElementById ( 'LoginEdit' );
				if ( LoginEdit )
				{
					LoginEdit.onclick = function ( )
					{
					
						this.urlLocation = '<?= getLocalizedBaseUrl ( ) ?>?ue=userlogin&function=editprofile&die=true';
						BlestBoxLaunchElement ( this );
					}
				}
				var LoginLog = document.getElementById ( 'LoginProfile' );
				if ( LoginLog )
				{
					LoginLog.onclick = function ( )
					{
					
						this.urlLocation = '<?= getLocalizedBaseUrl ( ) ?>?ue=userlogin&function=shoppinglog&die=true';
						BlestBoxLaunchElement ( this );
					}
				}
			</script>
		<?}?>
		
		<?if ( $GLOBALS[ 'webuser' ]->IsAdmin ) { ?>
		<p class="ArenaParagraph">
			<a href="admin.php" target="_blank"><?= i18n ( 'Login to Blest ARENA' ) ?> &raquo;</a>
		</p>
		<?}?>
		
		<?if ( !$GLOBALS[ 'webuser' ]->ID ) { ?>
			<form method="post" class="LoginForm" name="LoginForm" action="<?= $this->content->getUrl ( ) ?>">
				<p class="pUser">
					<span class="sUser" id="labelUsername"><?= i18n ( 'Username' ) ?>:</span> <input type="text" name="webUsername" />
				</p>
				<p class="pPass">
					<span class="sPass" id="labelPassword"><?= i18n ( 'Password' ) ?>:</span> <input type="password" name="webPassword" />
				</p>
				<p class="pButton">
					<button class="Login" type="submit">
						<span class="sButton"><?= i18n ( 'Login' ) ?></span>
					</button>
				</p>
			</form>
			<div class="pRegisterForm">
				<?= $this->registerForm ?>
			</div>
		<?}?>
		
		<?
			$GLOBALS[ 'document' ]->addHeadScript ( BASE_URL . 'extensions/userlogin/javascript/login.js' );
		?>
		
		<? i18n ( 'Are you sure?' ); ?>
		<? i18n ( 'A new password has been sent to your e-mail address.' ); ?>
		<? i18n ( 'No user with such an e-mail address exists.' ); ?>
		
