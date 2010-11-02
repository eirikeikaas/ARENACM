
	<div class="ForgotPassword">
		<h2 class="ForgotPassword">
			<span><?= i18n ( 'Forgot your password' ) ?>?</span>
		</h2>
	
		<p>
			<?= i18n ( 'Fill in your e-mail address and click submit' ) ?>
			<?= i18n ( 'and we will send you a new password.' ) ?>
		</p>
	
		<p>
			<strong><?= i18n ( 'Your e-mail address' ) ?>:</strong> <input type="text" id="findEmailAddy" value="" size="20"/>
		</p>
	
		<p>
			<button onclick="receivePassword ( )">
				<?= i18n ( 'Click to receive a new password' ) ?>
			</button>
			<button onclick="closeStyledDialog ( )">
				<?= i18n ( 'Cancel' ) ?>
			</button>
		</p>
	</div>


