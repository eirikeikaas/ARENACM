
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
						$str .= $i->getImageUrl ( $this->settings->Width, $this->settings->Height - 27, 'framed' );
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
		gal.galleryWidth = <?= $this->settings->Width ?>;
		gal.galleryHeight = <?= $this->settings->Height - 27 ?>;
		gal.galleryAnimated = <?= $this->settings->Animated == 1 ? '1' : '0' ?>;
		gal.galleryPause = <?= $this->settings->Pause >= 1 ? $this->settings->Pause : '2' ?>;
		gal.init ( '<?= $this->field->Name ?>' );
	</script>

