
		<a name="comment<?= $this->comment->ID ?>"></a>
		<div class="Block">
			<h4>
				<?= $this->comment->Subject ?>
			</h4>
			<p class="Small"><?= i18n ( 'comment by' ) ?> <span class="Bold"><?= $this->comment->Nickname ?></span></p>
			<div class="Block">
				<?= $this->comment->Message ?>
			</div>
			<div class="ClearBoth"></div>
		</div>
		<br/>

