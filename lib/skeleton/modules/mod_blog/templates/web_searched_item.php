
	<div class="Block">
		<p class="Title">
			<a href="<?= $this->data->Url ?>"><?= $this->data->Title ?></a>
		</p>
		<p class="Leadin">
			<?= substr ( strip_tags ( $this->data->Leadin ), 0, 200 ) . '..' ?>
		</p>
		<p class="ReadMore">
			<a href="<?= $this->data->Url ?>"><?= i18n ( 'Read more' ) ?></a>
		</p>
	</div>
