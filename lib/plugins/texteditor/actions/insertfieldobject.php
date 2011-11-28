<?php
global $pluginTplDir, $Session, $database;

function _showContentOptions ( $current, $parent = '0', $r = '' )
{
	global $database;
	if ( $rows = $database->fetchObjectRows ( '
		SELECT * FROM ContentElement WHERE MainID=ID AND !IsTemplate AND !IsDeleted
		AND Parent="' . $parent . '"
		ORDER BY SortOrder ASC, ID DESC
	' ) )
	{
		$r2 = $r . '&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach ( $rows as $row )
		{
			$s = $row->ID == $current ? ' selected="selected"' : '';
			$str .= '<option value="' . $row->MainID . '"' . $s . '>' . $r . $row->MenuTitle . '</option>';
			$str .= _showContentOptions ( $current, $row->ID, $r2 );
		}
		return $str;
	}
	return '';
}
$opts = _showContentOptions ( $Session->EditorContentID );
$t = new cPTemplate ( $pluginTplDir . '/insertfieldobject.php' );
$t->contentOptions = $opts;
die ( $t->render () );

?>
