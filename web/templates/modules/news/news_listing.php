					
					<?
						global $Session;
						$this->lang = $Session->LanguageCode ? $Session->LanguageCode : $this->data->LanguageCode;
					?>
						
					<div class="Newsitem_Listed<?
						$a = &$GLOBALS[ "newsitem_{$this->data->CategoryID}" ];
						if ( $a <= 0 ) $a = 1;
						else $a++;
						return " Item{$a}";
					?>">
						
						<h2>
							<?if ( $this->data->Article ) { ?>
							<a href="<?= ( $this->Link ? $this->Link : $this->path ) . "?nid={$this->data->ID}" ?>">
							<?}?>
								<?= $this->data->Title ?>
							<?if ( $this->data->Article ) { ?>
							</a>
							<?}?>
						</h2>
						<small><?= ArenaDate ( DATE_FORMAT, $this->data->DateActual ) ?></small>
						<div class="Newsitem_Title_Break"></div>
						<?if ( !strstr ( $this->data->Intro, "<p" ) ) { ?>			
						<p><?= $this->data->Intro ?></p>
						<?}?>
						<?if ( strstr ( $this->data->Intro, "<p" ) ) { ?>
							<?= $this->data->Intro ?>
						<?}?>
						<p class="ReadMore">
						<?if ( $this->data->Article ) { ?>
							<a href="<?=( $this->Link ? $this->Link : $this->path ) . "?nid={$this->data->ID}" ?>"><?= i18n ( 'Read more', $this->lang ) ?></a>
						<?}?>
						<?
							// Extra fields!
							$str = '';
							$this->data->loadExtraFields ( );
							foreach ( $this->data as $k=>$v )
							{
								if ( substr ( $k, 0, 6 ) == '_extra' )
								{
									list ( , , $class ) = explode ( '_', $k );
									$str .= '<p class="' . $class . '">' . stripslashes ( $v ) . '</p>';
								}
							}
							return $str;
						?>
						<?if ( $this->comments > 0 ) { ?>
							<?= ( $this->data->Article ? '| ' : '' ) ?> <a href="<?=( $this->Link ? $this->Link : $this->path ) . "?nid={$this->data->ID}#comments" ?>"><?= $this->replycount ?></a>
						<?}?>
						<?if ( ( $GLOBALS[ "webuser" ]->ID && $this->config->comments == 1 ) || $this->config->comments == 2 ) { ?>
							<?= ( $this->comments > 0 ? '| ' : '' ) ?> <a href="<?= $this->path ?>?nid=<?= $this->data->ID ?>#comment"><?= i18n ( 'Add comment', $this->lang ) ?></a>
						<?}?>
						<?if ( $this->translations ) { ?>
							<?= $this->translations ?>
						<?}?>
						</p>
						<div class="End"></div>
					</div>
					<?if ( $this->Spacer ) { ?>
					<div class="Spacer"></div>
					<?}?>

