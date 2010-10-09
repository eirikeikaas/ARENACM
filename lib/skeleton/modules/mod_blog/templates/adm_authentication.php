
	<div class="SubContainer" style="padding: 2px">
	<?
		if ( $this->blogs )
		{
			$sw = 0;
			foreach ( $this->blogs as $blog )
			{
				$sw = ( $sw + 1 ) % 2;
				$buttons = '';
				$buttons .= '<a href="javascript: void(0)" title="Endre og godkjenn" onclick="mod_blog_edit ( ' . $blog->ID . ' )"><img src="admin/gfx/icons/page_edit.png"></a>';
				$buttons .= '<a href="javascript: void(0)"  class="Small" title="Slett" onclick="mod_blog_delete ( ' . $blog->ID . ' )"><img src="admin/gfx/icons/page_delete.png"></a>';
				$buttons .= '<a href="javascript: void(0)"  class="Small" title="Forhåndsvis" onclick="mod_blog_preview ( ' . $blog->ID . ' )"><img src="admin/gfx/icons/eye.png"></a>';
				$str .= '<tr class="sw' . ( $sw + 1 ) . '"><td>' . $blog->Title . '</td><td>' . $blog->AuthorName . '</td><td>' . ArenaDate ( DATE_FORMAT, strtotime ( $blog->DateUpdated ) ) . '</td><td style="text-align: right">' . $buttons . '</td></tr>';
			}
			return '<table class="Overview LayoutColumns"><tr><th>Tittel:</th><th>Forfatter:</th><th>Dato:</th><th></th></tr>' . $str . '</table>';
		}
		return 'Ingen artikler trenger godkjenning.';
	?>
	</div>
	<div class="SpacerSmallColored"></div>
	<button type="button" onclick="mod_blog_abortedit ( )">
		<img src="admin/gfx/icons/newspaper.png"> Vis blog arkivet
	</button>
