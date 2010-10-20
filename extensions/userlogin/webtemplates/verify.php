	<div class="TextBlock">
		<?if ( $this->verified ) { ?>
			<h2 class="Success"><span><?= i18n ( "Success" ) ?>!</span></h2>
			<div class="Notice">
				<p>
					<?= i18n ( "You are now registered! Please" ) ?>
					<?= i18n ( "log in with your username" ) ?>
					<?= i18n ( "and password in the login form" ) ?>.
				</p>
				<button type="button" onclick="document.location='<?= $this->content->getUrl ( ) ?>'">
					<?= i18n ( "Will do" ) ?>!
				</button>
			</div>
		<?}?>
		<?if ( !$this->verified && !$this->double ) { ?>
			<h2 class="AlreadyExists"><span><?= i18n ( "User already exists" ) ?></span></h2>
			<div class="Notice">
				<p>
					<?= i18n ( "The user you are trying to register" ) ?>
					<?= i18n ( "has already been registered by" ) ?>
					<?= i18n ( "somebody else" ) ?>.
				</p>
				<form method="post">
					<input type="hidden" name="function" value="register" />
					<input type="hidden" name="ue" value="login" />
					<button type="submit">
						<?= i18n ( "Try again with another username" ) ?>
					</button>
				</form>
			</div>
		<?}?>
		<?if ( !$this->verified && $this->double ) { ?>
			<h2 class="DoublePost"><span><?= i18n ( "Double post" ) ?></span></h2>
			<div class="Notice">
				<p>
					<?= i18n ( "You have already been registered" ) ?>
					<?= i18n ( "and have resubmitted a completed" ) ?>
					<?= i18n ( "registration form." ) ?>.
				</p>
				<button type="button" onclick="document.location='<?= $this->content->getUrl ( ) ?>'">
					<?= i18n ( "Ok, I understand" ) ?>
				</button>
			</div>
		<?}?>
	</div>
