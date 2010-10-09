
	<div class="Container" style="padding: <?= MarginSize ?>px">
		<table class="LayoutColumns">
			<tr>
				<td>
					<label>Tips heading:</label>
				</td>
				<td>
					<input type="text" id="mod_blogtip_heading" value="<?= $this->data->Heading ?>">
				</td>
			</tr>
			<tr><td><div class="SpacerSmall"></div></td></tr>
			<tr>
				<td>
					<label>Tips info:</label>
				</td>
				<td>
					<input type="text" id="mod_blogtip_info" value="<?= $this->data->Info ?>">
				</td>
			</tr>
			<tr><td><div class="SpacerSmall"></div></td></tr>
			<tr>
				<td>
					<label>Button text:</label>
				</td>
				<td>
					<input type="text" id="mod_blogtip_buttontext" value="<?= $this->data->ButtonText ?>">
				</td>
			</tr>
			<tr>
				<td>
					<label>Side for skjema:</label>
				</td>
				<td>
					<select id="mod_blogtip_contentelementid">
						<?= getSiteStructureOptions ( $this->data->ContentElementID ) ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label>Innholdsgruppe:</label>
				</td>
				<td>
					<select id="mod_blogtip_contentgroup">
						<?
							$db =& dbObject::globalValue ( 'database' );
							if ( $rows = $db->fetchObjectRows ( '
								SELECT DISTINCT(ContentGroups) AS Uni FROM ContentElement WHERE MainID=ID
							' ) )
							{
								$groups = array ( );
								foreach ( $rows as $row )
								{
									if ( $gr = explode ( ',', $row->Uni ) )
									{
										foreach ( $gr as $g )
										{
											if ( !in_array ( trim ( $g ), $groups ) )
												$groups[] = trim ( $g );
										}
									}
								}
								foreach ( $groups as $g )
								{
									if ( $g == $this->data->ContentGroup )
										$s = ' selected="selected"';
									else $s = '';
									$str .= '<option value="' . $g . '"' . $s . '>' . $g . '</option>';
								}
								return $str;
							}
						?>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="SpacerSmall"></div>
	
	<script type="text/javascript">
		AddSaveFunction ( function ( ) {
			var j = new bajax ( );
			j.openUrl ( ACTION_URL + 'mod=mod_blog_tip&modaction=savecfg', 'post', true );
			j.addVar ( 'configHeading', document.getElementById ( 'mod_blogtip_heading' ).value );
			j.addVar ( 'configInfo', document.getElementById ( 'mod_blogtip_info' ).value );
			j.addVar ( 'configButtonText', document.getElementById ( 'mod_blogtip_buttontext' ).value );
			j.addVar ( 'configContentElementID', document.getElementById ( 'mod_blogtip_contentelementid' ).value );
			j.addVar ( 'configContentGroup', document.getElementById ( 'mod_blogtip_contentgroup' ).value );
			j.onload = function ( ) { };
			j.send ();
		} );
	</script>
	
