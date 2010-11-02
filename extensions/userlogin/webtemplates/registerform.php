				
				<form class="RegisterForm" method="post" action="<?= $this->url ?>">
					<div style="visibility: hidden; width: 1px; height: 1px; float: left;">
						<input type="hidden" name="ue" value="login" />
						<input type="hidden" name="function" value="register" />
					</div>
					<p class="pNotRegistered">
						<?= i18n ( 'Not registered yet?' ) ?>
					</p>
					<p class="pRegister">
						<button class="Register" type="submit">
							<span><?= i18n ( 'Register' ) ?></span>
						</button>
					</p>
				</form>
				<p class="pForgotPassword">
					<button class="ForgotPassword" type="button" onclick="forgotPassword ( )">
						<span><?= i18n ( 'Forgot your password?' ) ?></span>
					</button>
				</p>

