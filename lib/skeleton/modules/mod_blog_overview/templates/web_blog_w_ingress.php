
	<h2 class="Block Title">
		<?= $this->blog->Title ?>
	</h2>
	<p class="Block"><span class="Published"><?= i18n ( 'Published' ) ?></span> <span class="Date"><?= ArenaDate ( $this->blog->DateUpdated, DATE_FORMAT ) ?></span></p>
	<div class="Block Leadin"><?= $this->blog->Leadin?></div>
	<?if ( $this->blog->Body ) { ?>
	<p class="Block ReadMore"><a href="<?= $this->link ?>"><?= i18n ( 'Read more' ) ?></a></p>
	<?}?>

