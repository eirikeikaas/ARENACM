
	<?
		i18n ( 'You have to enter your name.' );
		i18n ( 'You have to write a comment.' );
		i18n ( 'Wrong spam control text, please try again.' );
	?>
	<div class="Block">
		<h2 class="Heading"><?= $this->blog->Title ?></h2>
		<?if ( $this->cfgShowAuthor ) { ?>
		<p class="Block Bold"><?= i18n ( 'written by' ) . ' ' . $this->blog->AuthorName ?></p>
		<?}?>
		<p class="Block Small"><?= i18n ( 'posted date' ) . ' ' . ArenaDate ( DATE_FORMAT, $this->blog->DateUpdated ) ?></p>
		<div class="ClearBoth"></div>
		<?if ( list ( $this->image, ) = $this->blog->getObjects ( 'ObjectType = Image' ) ) { ?>
		<div class="Image DetailImage" style="background-image: url(<?= $this->image->getImageUrl ( $this->sizeX, $this->sizeY, 'proximity' ) ?>)"></div>
		<?}?>
		<div class="Block DetailLeadin">
			<?= $this->blog->Leadin ?>
		</div>
		<div class="Block Body">
			<?= $this->blog->Body ?>
		</div>
	</div>
	<hr class="BackUrl"/>
	<p class="Block BackUrl">
		<a href="<?= $this->content->getUrl ( ) ?>"><?= i18n ( 'Back to' ) ?> <?= strtolower ( $this->content->MenuTitle ) ?></a>
	</p>
	<div class="ClearBoth"></div>
	<hr class="BackUrl"/>
	<?if ( $this->comments ) { ?>
	<h3><?= i18n ( 'Comments' ) ?>:</h3>
	<hr/>
	<?= $this->comments ?>
	<hr/>
	<?}?>
	<?if ( $this->cfgComments && $this->canComment ) { ?>
	<div class="Block">
		<h3>
			<?= i18n ( 'Add your comment' ) ?>:
		</h3>
		<hr/>
		<form method="post" id="mod_blog_commentform" action="<?= $this->content->getRoute ( ) . '/blogitem/' . $this->blog->ID . '_' . texttourl ( $this->blog->Title ) ?>.html">
			<table class="TableColumns">
				<tr>
					<th>
						<p class="Bold Small"><?= i18n ( 'Your name' ) ?>:</p>
					</th>
					<td>
						<input type="text" size="40" name="Name" value="<?= $GLOBALS[ 'webuser' ] ? $GLOBALS[ 'webuser' ]->Username : i18n('Anonymous') ?>"/>
					</td>
				</tr>
				<tr>
					<th>
						<p class="Bold Small"><?= i18n ( 'Subject' ) ?>:</p>
					</th>
					<td>
						<input type="text" size="40" name="Subject" value="Re: <?= $this->blog->Title ?>"/>
					</td>
				</tr>
				<tr>
					<th>
						<p class="Bold Small"><?= i18n ( 'Your comment' ) ?>:</p>
					</th>
					<td>
						<textarea cols="40" rows="6" name="Message"></textarea>
					</td>
				</tr>
				<tr>
					<th>
						<p class="Bold Small"><?= i18n ( 'Spam control image' ) ?>:</p>
					</th>
					<td>
						<a name="comment"></a>
					
						<table>
							<tr>
								<td id="mod_blog_captcha_image">
									<?
										$css = file_get_contents ( 'upload/main.css' );
										$background = 0x5599ff;
										$color = 0x220066;
										if ( preg_match ( '/background\:.*?(\#[0-9a-fA-Z]*)/', $css, $matches ) )
										{
											// Create a background and an inverted background for captcha text color
											$background = $matches[ 1 ];
											$background = string2hex ( $background );
											$r = 0xff & ( $background >> 16 );
											$g = 0xff & ( ( $background << 8 ) >> 16 );
											$b = 0xff & ( ( $background << 16 ) >> 16 );
											$color = 0xffffff & ( ( ( 255 - $r ) << 16 ) | ( ( 255 - $g ) << 8 ) | ( ( 255 - $b ) ) );
											$background = 0xffffff & ( ( ( $r ) << 16 ) | ( ( $g ) << 8 ) | ( ( $b ) ) );
										}
										$img = new dbImage ( );
										list ( $img, $result ) = $img->renderCaptcha ( 180, 48, $background, $color );
										$_SESSION[ 'captcha_value' ] = $result;
										if ( $_REQUEST[ 'captcha' ] )
										{
											preg_match ( '/src\=\"(.*?)\"/', $img, $matches );
											die ( $matches[ 1 ] );
										}
										return $img;
									?>
								</td>
								<td>
									<button type="button" onclick="mod_blog_reloadCaptcha ( )"><?= i18n ( 'Try another image' ) ?></button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th><p class="Bold Small"><?= i18n ( 'What is written in the image?' ) ?></p></th>
					<td><input type="text" size="9" name="Captcha" value=""/></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button type="button" onclick="mod_blog_submitComment ( )">
							<?= i18n ( 'Save comment' ) ?>
						</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?}?>
