<p><strong>Utvidelser:</strong></p>
<?
	$db =& dbObject::globalValue ( 'database' );
	if ( $dir = opendir ( 'extensions' ) )
	{
		while ( $file = readdir ( $dir ) )
		{
			if ( $file{0} == '.' ) continue;
			if ( !file_exists ( 'extensions/' . $file . '/info.csv' ) )
				continue;
				
			if ( $access = $db->fetchObjectRow ( '
				SELECT * FROM Setting WHERE SettingType="GroupAccess_' . $this->Group->ID . '" AND `Key` LIKE "extension_Access_' . $file . '" ORDER BY `Key` ASC
			' ) )
			{
				$access = $access->Value;
			}
			else $access = true;
			$checked = $access ? ' checked="checked"' : '';
			$str .= '<li>' . $file . ' <input type="checkbox"' . $checked . ' onchange="document.getElementById ( \\'extension_ax_' . $file . '\\' ).value = this.checked ? 1 : 0"/>';
			$str .= '<input type="hidden" name="extension_Access_' . $file . '" value="' . ( $access ? '1' : '0' ) . '" id="extension_ax_' . $file . '"/>';
			$str .= '</li>';
		}
		closedir ( $dir );
	}
	return '<ul>' . $str . '</ul>';
?>
