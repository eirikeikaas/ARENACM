
	<script type="text/javascript">
	
		var ACTION_URL = "<?= BASE_URL ?>admin.php?module=extensions&extension=easyeditor&";
		
		var savefuncs = new Array ( );
		function AddSaveFunction ( func )
		{
			savefuncs.push ( func );
		}
		
		function savePage ()
		{
			for ( var a = 0; a < savefuncs.length; a++ )
				savefuncs[a]();
			
			var j = new bajax ();
			j.openUrl ( 'admin.php?module=extensions&extension=easyeditor&action=savepage', 'post', true );
			j.addVar ( 'pageTitle', document.getElementById ( 'pageTitle' ).value );
			if ( document.getElementsByTagName ( 'textarea' )[0] )
			{
				j.addVar ( 'fieldData', document.getElementsByTagName ( 'textarea' )[0].value );
				j.addVar ( 'bodyField', document.getElementsByTagName ( 'textarea' )[0].id );
			}
			j.addVar ( 'ID', document.getElementById ( 'pageID' ).value );
			j.onload = function ()
			{
				var result = this.getResponseText ();
				if ( result == 'fail' )
				{
					alert ( 'Kunne ikke lagre siden.' );
					return false;
				}
				document.location.reload();
			}
			j.send ();
		}
		
	</script>
	
	
	<?= enableTextEditor ( array ( "mode"=>"easy" ) ) ?>
	<input type="hidden" id="pageID" value="<?= $this->page->ID ?>"/>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td valign="top" width="240px">
				<h1>
					<img src="admin/gfx/icons/book_open.png" style="float: left; margin: 0 4px 0 0" /> Sider
				</h1>
				<div class="Container" id="Structure" style="padding: 3px">
					<div class="Container" style="padding: 8px 4px 8px 18px">
					<?
						function __listLevels ( $p = '0' )
						{
							global $Session;
							$db =& dbObject::globalValue ( 'database' );
							if ( $rows = $db->fetchObjectRows ( '
								SELECT * FROM ContentElement 
								WHERE 
									ID != MainID AND 
									`Parent` = ' . $p . ' AND 
									!IsTemplate AND 
									!IsDeleted AND 
									`ContentType` != "Link"
									ORDER BY SortOrder ASC, ID ASC
							' ) )
							{
								$str .= '<ul>';
								foreach ( $rows as $row )
								{
									$row->_tableName = 'ContentElement';
									$row->_isLoaded = true;
									if ( $Session->AdminUser->checkPermission ( $row, 'Read', 'admin' ) )
									{
										$c = ($Session->EditorContentID == $row->ID) ? ' class="current"' : '';
										$str .= '<li class="' . texttourl ( $row->MenuTitle ) . '">';
										$str .= '<a' . $c . ' href="admin.php?module=extensions&extension=easyeditor&cid=' . $row->ID . '">';
										if ( !trim ( $row->MenuTitle ) ) $row->MenuTitle = 'Uten navn';
										$str .= $row->MenuTitle . '</a>';
										$str .= __listLevels ( $row->MainID );
										$str .= '</li>';
									}
								}
								$str .= '</ul>';
							}
							return $str;
						}
						return __listLevels ( );
					?>
					</div>
				</div>
				<?if ( $this->note ) { ?>
				<div class="SpacerSmallColored"></div>
				<h1>
					<img src="admin/gfx/icons/help.png" style="float: left; margin: 0 4px 0 0" /> Hjelpetekst
				</h1>
				<div class="Container" style="padding: 8px">
					<?= $this->note ?>
				</div>
				<?}?>
				<div class="SpacerSmallColored"></div>
				<?if ( !defined ( 'EASYEDITOR_HIDEMODULES' ) ) { ?>
				<h1>
					<img src="admin/gfx/icons/brick.png" style="float: left; margin: 0 4px 0 0" /> Moduler
				</h1>
				<div class="Container" style="padding: 2px">
					<div class="SubContainer" style="padding: 0">
						<div style="padding: 8px 8px 0 8px">
							<p>Velg den modulen du ønsker ved å huke av i listen. Modulen vil aktiveres umiddelbart og vil deaktivere innholdsfeltet.</p>
						</div>
					<?
						if ( $dir = opendir ( 'skeleton/modules' ) )
						{
							$script = '';
							$selected = false;
							while ( $file = readdir ( $dir ) )
							{
								if ( $file{0} == '.' ) continue;
								if ( substr ( $file, 0, 4 ) == 'mod_' )
								{
									$info = file_get_contents ( 'skeleton/modules/' . $file . '/info.txt' );
									$info = explode ( '|', $info );
									if ( trim( $info[3] ) == 'simple' )
									{
										$i = '<img src="skeleton/modules/' . $file . '/module.png" width="16px" height="16px"/>';
										$s = GetSettingValue ( $file, $this->page->ID ) == 1 ? ' checked="checked"' : '';
										if ( trim ( $s ) )
										{
											$selected = true;
											$script = '<script type="text/javascript">document.currentActiveModule=\\\\'' . $file . '\\\\';</script>';
										}
										$str .= '<tr class="sw' . ( $sw = ( $sw == 1 ? 2 : 1 ) ) . '">';
										$str .= '<td width="16">' . $i . '</td><td>' . $info[0] . '</td>';
										$str .= '<td width="20">';
										$str .= '<input type="radio"' . $s . ' name="module" onmouseup="ActivateModule(\\\\'' . $file . '\\\\', event)"/>';
										$str .= '</td></tr>';
									}
								}
							}
							closedir ( $dir );
							$str = '<tr class="sw' . ( $sw = ( $sw == 1 ? 2 : 1 ) ) . '"><td width="16"><img src="admin/gfx/icons/page.png"/></td><td>Standardmodul</td><td width="20"><input type="radio" name="module" onmouseup="NoModule()"' . ( $selected == true ? '' : ' checked="checked"' ) . '/></td></tr>' . $str;
							return '<table class="List">' . $str . '</table>' . $script;
						}
					?>
					</div>
				</div>
				<div class="SpacerSmallColored"></div>
				<?}?>
				<h1>
					<img src="admin/gfx/icons/thumb_up.png" style="float: left; margin: 0 4px 0 0" /> Oppgradere
				</h1>
				<div class="Container" style="padding: 8px">
					<p>
						Ønsker du å oppgradere til pro versjonen? Klikk på knappen under for å 
						bli kontaktet av en selger.
					</p>
					<p>
						<button type="button" onclick="GetSupport()">
							<img src="admin/gfx/icons/phone.png"/> Kontakt meg
						</button>
					</p>
				</div>
			</td>
			<td width="8px"></td>
			<td valign="top">
				<h1>
					<img src="admin/gfx/icons/page_edit.png" style="float: left; margin: 0 4px 0 0" /> Rediger: "<?= $this->page->Title ? $this->page->Title : 'Uten navn' ?>"
				</h1>
				<div class="Container">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td>
								<p>
									<strong>Sidetittel:</strong> <input type="text" size="50" value="<?= $this->page->Title ? $this->page->Title : 'Uten navn' ?>" id="pageTitle"/>
								</p>
							</td>
						</tr>
					</table>
					<p>
						<strong>Innhold:</strong>
					</p>
					<?if ( !$this->editableField ) { ?>
					<textarea id="pageBody" class="mceSelector"><?= $this->pageBody ?></textarea>
					<?}?>
					<?if ( $this->editableField ) { ?>
					<?= $this->editableField ?>
					<?}?>
				</div>
				<?if ( $this->obs = $this->page->getObjects ( 'ObjectType = File' ) ) { ?>
				<div class="SpacerSmall"></div>
				<h1>
					<img src="admin/gfx/icons/attach.png" valign="middle"/> Fil Vedlegg
				</h1>
				<div class="Container" style="padding: 4px">
				<?
					$str = '';
					foreach ( $this->obs as $o )
					{
						switch ( strtolower ( substr ( $o->Filename, -4, 4 ) ) )
						{
							case '.pdf':
								$img = '<img src="admin/gfx/icons/page_white_acrobat.png" valign="middle" />&nbsp;';
								break;
							default:
								$img = '<img src="admin/gfx/icons/page_white.png" valign="middle" />&nbsp;';
						}
						$d = '<img src="admin/gfx/icons/bin.png" style="cursor: hand; cursor: pointer" onclick="RemovePageAttachment(' . $o->ID . ')"/>';
						$str .= '<tr class="sw' . ($sw=($sw==1?2:1)) . '"><td>' . $img . ' ' . $o->Title . ' (' . floor( filesize ( BASE_DIR . '/upload/' . $o->Filename )/1000) . 'kb)</td><td align="right">' . $d . '</td></tr>';
					}
					return '<table class="List">' . $str . '</table>';
				?>
				</div>
				<?}?>
				<div class="SpacerSmallColored"></div>
				<div class="Container">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td>
								<button type="button" onclick="savePage()">
									<img src="admin/gfx/icons/disk.png"/> Lagre
								</button>
							</td>
							<td align="right">
								<button type="button" onclick="UploadFile()">
									<img src="admin/gfx/icons/attach.png"/> Last opp en fil
								</button>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	
	
	
