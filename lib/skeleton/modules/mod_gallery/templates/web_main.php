
	<?if ( trim ( $this->settings->Heading ) ) { ?>
	<h1 class="Heading"><?= trim ( $this->settings->Heading ) ?></h1>
	<?}?>
	<?
		if ( $folders = explode ( ':', $this->settings->Folders ) )
		{
			$str = '';
			foreach ( $folders as $fid )
			{
				$img = new dbImage ();
				if ( $this->settings->SortMode == 'listmode_sortorder' )
				{
					$img->addClause ( 'ORDER BY', 'SortOrder ASC' );
				}
				else if ( $this->settings->SortMode == 'listmode_fromto' )
				{
					$img->addClause ( 'WHERE', 'DateFrom <= NOW() AND DateTo >= NOW()' );
					$img->addClause ( 'ORDER BY', 'SortOrder ASC' );
				}
				else
				{
					$img->addClause ( 'ORDER BY', 'DateModified DESC' );
				}
				$img->addClause ( 'WHERE', 'ImageFolder=' . $fid );
				if ( $images = $img->find ( ) )
				{
					foreach ( $images as $i )
					{
						$str .= '<a href="' . ( BASE_URL . 'upload/images-master/' . $i->Filename ) . '">';
						$str .= '<img src="';
						$str .= $i->getImageUrl ( $this->settings->Width, $this->settings->Height, 'framed' );
						$str .= '" alt="' . str_replace ( array ( "\\n", "\\r" ), array ( "<br/>", "" ), $i->Description ) . '" title="' . $i->Title . '"/></a>';
					}
				}
			}
			return $str;
		}
		return '';
	?>
	<script type="text/javascript">
		var gal = new arenaGallery ();
		gal.galleryWidth = <?= $this->settings->Width > 0 ? $this->settings->Width : 'false' ?>;
		gal.galleryHeight = <?= $this->settings->Height > 0 ? $this->settings->Height : 'false' ?>;
		gal.galleryAnimated = <?= $this->settings->Animated == 1 ? '1' : '0' ?>;
		gal.galleryShowStyle = '<?= $this->settings->ShowStyle ?>';
		gal.galleryPause = <?= $this->settings->Pause >= 1 ? $this->settings->Pause : '2' ?>;
		gal.gallerySpeed = <?= $this->settings->Speed >= 1 ? ($this->settings->Speed*100) : '200' ?>;
		gal.init ( '<?= $this->field->Name ?>' );
	</script>

