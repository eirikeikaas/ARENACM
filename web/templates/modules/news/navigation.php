
					<?if ( $this->Prev && $this->Next ) { ?>
					<div class="PaginationSeparator"><em></em></div>
					<?}?>

					<?if ( $this->Prev ) { ?>
					<a href="<?= $this->content->getUrl ( ) ?>?newspos=<?= $this->Position - $this->Limit ?>">
						<?= i18n ( 'Previous page' ) ?>
					</a>
					<?}?>
					
					<?if ( $this->Prev && $this->Next ) { ?>
						|
					<?}?>
					
					<?if ( $this->Next ) { ?>
					<a href="<?= $this->content->getUrl ( ) ?>?newspos=<?= $this->Position + $this->Limit ?>">
						<?= i18n ( 'Next page' ) ?>
					</a>
					<?}?>
					
					<?if ( ( $this->Next || $this->Prev ) && $this->PageCount > 1 ) { ?>
						|
					<?}?>
					
					<?if ( $this->PageCount > 1 ) { ?>
					<div style="display: inline">
						<?= i18n ( 'Showing page' ) ?> <?= $this->CurrentPage ?> <?= i18n ( 'of' ) ?> <?= $this->PageCount ?>.
					</div>
					<?}?>
