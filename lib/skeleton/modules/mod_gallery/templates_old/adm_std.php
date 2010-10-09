	<div>
		<div class="SubContainer" style="padding: 2px">
			<?
				$str = '';
				$str .= '<div class="galleryheader"><div class="galleryheaderitem1"></div><div class="galleryheaderitem2">Bildenavn</div><div class="galleryheaderitem3">Biblioteksfolder</div></div>';

				$db =& dbObject::globalValue('database');
				$q = 'SELECT i.*, f.Name AS foldername FROM GalleryItem g, Image i, Folder f WHERE g.ImageID = i.ID AND g.ContentElementID = ' . $this->MainID . ' AND i.ImageFolder = f.ID';
				if($results = $db->fetchObjectRows($q))
				{
					$switch = 0;
					foreach($results as $result)
					{
						$icon = new dbImage($result->ID);
						$iconstring = '<img src="' . $icon->getImageUrl(20, 20, 'centered', '', 0xffffff) . '"/>';
						$str .= '<div class="ImageRow' . $switch . '"><div class="ImageIcon">' . $iconstring . '</div><div class="ImageTitle"> ' . $result->Title . '</div><div class="FolderTitle"> ' . $result->foldername . '</div></div>';
						$switch = 1 - $switch;
					}
				}
				return $str;
			?>
		</div>
		<br/>
		<div id="GallerySettings">
			<?
				$str = '';
				$db =& dbObject::globalValue('database');
				$q = 'SELECT DataMixed FROM ContentDataSmall WHERE DataString = "mod_gallery" AND ContentID = ' . $this->ID;
				if ($result = $db->fetchObjectRow($q))
				{
				    list ($gallerytype, $mode, $popup, $showtitles) = explode('#', $result->DataMixed);
					if ($gallerytype == 'thumbs')
					{
						list($cols, $rows) = explode('_', $mode);
						$str .= '<div><strong>Galleritype: </strong>Thumbnails med ' . $cols . ' kolonner og ' . $rows . ' rekker, ';
						if ($popup == 'ja') $str .='pop-up, ';
						if ($showtitles == 'ja') $str .='vis bildetitler.</div>';
					}
					else if ($gallerytype == 'slides')
					{
						$str .= '<div><strong>Galleritype: </strong> Slideshow, ';
						$str .= $mode . ', ';
						if ($popup == 'ja') $str .='pop-up, ';
						if ($showtitles == 'ja') $str .='vis bildetitler.</div>';
					}
				}
				return $str;
			?>
		<br/>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="mod_gallery_edit()">
			<img src="admin/gfx/icons/wrench.png"/> Endre galleri
		</button>
	</div>

