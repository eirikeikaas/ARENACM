	<?= enableTextEditor () ?>

	<h2>Innhold av filen <span id="file_filename"><?= $this->file->Filename ?></span></h2>
	<textarea id="advfileContents" class="<?
		preg_match ( '/^.*?(\.[a-zA-Z0-9]*?)$/', $this->file->Filename, $matches );
		$classes = 'codepress ';
		switch ( strtolower ( str_replace ( '.', '', $matches[ 1 ] ) ) )
		{
			case 'js':
				$classes .= 'javascript';
				break;
			case 'css':
				$classes .= 'css';
				break;
			default:
				$classes = '';
				break;
		}
		return $classes;
	?> autocomplete-off" style="width: 100%; height: 340px; -moz-box-sizing: border-box; box-sizing: border-box;"><?= trim ( $this->contents ) ? $this->contents : "/* Tom fil */" ?></textarea>
	<div class="SpacerSmall"></div>
	<div id="TexteditToolbar">
		<div style="float: right; width: 200px;">
			<div class="Container" id="TxtHud" style="padding: 4px;">
				&nbsp;
			</div>
		</div>
		<button type="button" id="lagre_text_innhold" onclick="saveFileContents ( <?= $this->file->ID ?> )">
			<img src="admin/gfx/icons/page_edit.png"/> Lagre innhold
		</button>
		<button type="button" id="tekstFullscreen" onclick="texteditFullscreen()">
			<img src="admin/gfx/icons/arrow_out.png"/> Fullskjerm
		</button>
		<button type="button" id="tekstFullscreen" onclick="document.title = txdoctitle; removeModalDialogue ( 'EditLevel' )"">
			<img src="admin/gfx/icons/cancel.png"/> Lukk
		</button>
	</div>
	<script>
		if ( navigator.userAgent.toLowerCase ( ).indexOf ( 'ebkit' ) < 0 && typeof ( CodePress ) != 'undefined' )
		{
			CodePress.run ( );
			if ( document.getElementById ( 'pageProperties' ).getElementsByTagName ( 'iframe' ).length )
			{
				CodePressArenaFunctions ( document.getElementById ( 'pageProperties' ).getElementsByTagName ( 'iframe' )[0] );
				document.getElementById ( 'pageProperties' ).getElementsByTagName ( 'iframe' )[0].style.width = '100%';
			}
		}
		else
		{
			if ( '<?= substr ( $this->file->Filename, -5, 5 ) ?>' == '.html' )
			{
				ge ( 'advfileContents' ).className = 'mceSelector';
				texteditor.init ( {classNames : 'mceSelector'} );
			}
			else
			{
				document.getElementById ( 'advfileContents' ).onkeydown = function ( e )
				{
					switch ( e.which )
					{
						case 9:
							var sp = this.selectionStart;
							var ep = this.selectionEnd;
							this.value = this.value.substring ( 0, sp ) +
										"\t" + this.value.substring ( ep, this.value.length );
							this.setSelectionRange ( sp + 1, sp + 1 );
							return false;
							break;
						case 13:
							var sp = this.selectionStart;
							var ep = this.selectionEnd;
							//find tabs
							var tabs = 0;
							var tsp = sp;
							while ( this.value.substr ( tsp, 1 ) == "\n" )
								tsp--;
							for ( var a = tsp; a > 0; a-- )
							{
								if ( this.value.substr ( a, 1 ) == "\t" )
									tabs++;
								else if ( this.value.substr ( a, 1 ) == "\n" || this.value.substr ( a, 1 ) == "\r" )
									break;
								else tabs = 0;
							}
							var n = '\n';
							for ( var a = 0; a < tabs; a++ )
								n += "\t";
							this.value = this.value.substring ( 0, sp ) +
										n + this.value.substring ( ep, this.value.length );
							this.setSelectionRange ( sp + 1 + tabs, sp + 1 + tabs );
							return false;
						case 83:
							if ( e.ctrlKey )
							{
								saveFileContents ( '<?= $this->file->ID ?>' );
								return false;
							}
							break;
					}
				}
			}
		}
	</script>

