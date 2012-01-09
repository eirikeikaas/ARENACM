			
			<form method="post" action="<?= $this->content->getUrl ( ) ?>">
				<div class="Header">
					<h2><?= $this->header ? $this->header : i18n ( $this->search_heading ) ?></h2>
				</div>
				<div class="SearchBox Block">
					<p class="Searching">
						<label class="Keywords"><?= i18n ( $this->search_keywords ) ?>:</label>
						<input type="text" class="SearchInput" name="keywords" value="<?= $_REQUEST[ 'keywords' ] ?>"/>
					</p>
					<p class="Buttons">
						<button type="submit" onclick="">
							<span><?= i18n ( $this->search_webpage ) ?></span>
						</button>
						<?if ( $this->search_extensions ) { ?>
						<select name="search_extension">
							<option value="">Søk i alt</option>
							<?
								if ( $keys = explode ( '|', $this->search_extensions ) )
								{
									$options = '<option value="texts">i tekstsider</option>';
									foreach ( $keys as $key )
									{
										if ( file_exists ( 'lib/skeleton/modules/' . $key . '/info.txt' ) )
											$info = explode ( '|', file_get_contents ( 'lib/skeleton/modules/' . $key . '/info.txt' ) );
										else if ( file_exists ( 'extensions/' . $key . '/info.csv' ) )
											$info = explode ( '|', file_get_contents ( 'extensions/' . $key . '/info.csv' ) );
										else continue;
										$options .= '<option value="' . $key . '">' . i18n ( 'search in ' . $info[0] ) . '</option>';
									}
									return $options;
								}
							?>
						</select>
						<?}?>
					</p>
				</div>
			</form>
