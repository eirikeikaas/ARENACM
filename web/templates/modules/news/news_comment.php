
<div class="NewsComment">
	<p>
		<strong><?= $this->data->Subject ?></strong> 
	</p>
	<p>
		<small>
			<?= i18n ( 'posted by', $GLOBALS[ "Session" ]->LanguageCode ) ?> <strong><?= $this->poster->Username ? $this->poster->Username : $this->data->Nickname ?></strong>, <?= date ( $this->config->CommentDateFormat ? $this->config->CommentDateFormat : "Y-m-d H:i:s", strtotime ( $this->data->DateModified ) ) ?>&nbsp;
		</small>
	</p>
	<?= dbComment::ProcessMessage ( $this->data->Message ) ?>
</div>
<div class="Spacer"></div>
