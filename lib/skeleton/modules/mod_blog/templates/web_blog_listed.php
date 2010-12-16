		<div class="Block Newsitem">
			<h2>
				<?= ( $this->titleLength > 0 && strlen ( strip_tags ( $this->blog->Title ) ) > $this->titleLength ) ? ( substr ( strip_tags ( $this->blog->Title ), 0, $this->titleLength - 3 ) . '...' ) : $this->blog->Title ?>
			</h2>
			<?if ( list ( $this->image, ) = $this->blog->getObjects ( 'ObjectType = Image' ) ) { ?>
			<div class="Image" style="background-image: url(<?= $this->image->getImageUrl ( $this->sizeX, $this->sizeY, 'proximity' ) ?>)"></div>
			<?}?>
			<?if ( $this->cfgShowAuthor ) { ?>
			<p class="Bold"><?= i18n ( 'written by' ) . ' ' . $this->blog->AuthorName ?></p>
			<?}?>
			<p class="Small"><?= i18n ( 'posted date' ) . ' ' . ArenaDate ( DATE_FORMAT, $this->blog->DateUpdated ) ?></p>
			<div class="Block Leadin">
				<?= ( $this->leadinLength > 0 && strlen ( strip_tags ( $this->blog->Leadin ) ) > $this->leadinLength ) ? ( substr ( strip_tags ( $this->blog->Leadin ), 0, $this->leadinLength - 3 ) . '...' ) : $this->blog->Leadin ?>
			</div>
			<p class="Block ReadMore">
				<?if ( trim ( $this->blog->Body ) ) { ?>
				<a class="FloatLeft Small" href="<?= ( $this->detailpage ? $this->detailpage->getRoute ( ) : $this->content->getRoute ( ) ) . '/blogitem/' . $this->blog->ID . '_' . texttourl ( $this->blog->Title ) ?>.html">
				<?= i18n ( 'Read more' ) ?>
				</a>
				<?}?>
				<?if ( $this->cfgComments ) { ?>
				<div class="FloatLeft">
					&nbsp;|&nbsp;
				</div>
				<a class="FloatLeft Small" href="<?= ( $this->detailpage ? $this->detailpage->getRoute ( ) : $this->content->getRoute ( ) ) . '/blogitem/' . $this->blog->ID . '_' . texttourl ( $this->blog->Title ) ?>.html#comment">
				<?= i18n ( 'Add comment' ) ?> (<?= 
					$this->commentcount ? 
						( $this->commentcount . ' ' .
							( $this->commentcount == 1 ? i18n ( 'comment' ) : i18n ( 'comments' ) )
						)
						: i18n('no comments') ?>)
				</a>
				<?}?>
				<?if ( $this->facebookLike ) { ?>
				<div class="FacebookLike"><iframe src="http://www.facebook.com/plugins/like.php?href=<?= $this->facebookLikeUrl ?>&amp;layout=standard&amp;show_faces=false&amp;width=<?= $this->facebookLikeWidth ?>&amp;action=like&amp;font=tahoma&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?= $this->facebookLikeWidth ?>px; height:<?= $this->facebookLikeHeight ?>px;" allowTransparency="true"></iframe></div>
				<?}?>
				<div class="ClearBoth"></div>
			</p>
		</div>
