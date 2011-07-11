
	<div class="Block OverviewNewsitem">
		<?= $this->image ?>
		<h2 class="Block Title">
			<?= $this->blog->Title ?>
		</h2>
		<p class="Block"><span class="Published"><?= i18n ( 'Published' ) ?></span> <span class="Date"><?= ArenaDate ( DATE_FORMAT, $this->blog->DateUpdated ) ?></span></p>
		<div class="Block Leadin"><?= $this->blog->Leadin?></div>
		<?if ( $this->blog->Body ) { ?>
		<p class="Block ReadMore"><a href="<?= $this->link ?>"><span><?= i18n ( 'Read more' ) ?></span></a></p>
		<?}?>
	</div>

