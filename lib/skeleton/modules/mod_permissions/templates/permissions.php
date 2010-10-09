
		<?= renderPlugin ( 'permissions', Array ( 'ContentID' => $_REQUEST[ 'cid' ], 'ContentTable' => $_REQUEST[ 'type' ], 'PermissionType' => $_REQUEST[ 'mode' ] ) ) ?>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="if ( confirm ( 'Er du sikker på at du ønsker å bruke disse\nrettighetene på alle undersidene?' ) ) { document.copyPermissionsToSubs ( ); }">
			<img src="admin/gfx/icons/wand.png"> Bruk på alle undersider
		</button>
		<button type="button" onclick="updateStructure ( ); removeModalDialogue ( 'permissions' )">
			<img src="admin/gfx/icons/cancel.png"> Lukk
		</button>
		
		<script>
			document.copyPermissionsToSubs = function ( )
			{
				var pjax = new bajax ( );
				pjax.openUrl ( 'admin.php?module=mod_permissions', 'post', true );
				pjax.addVar ( 'action', 'copypermissionstosubs' );
				pjax.addVar ( 'type', '<?= $_REQUEST[ 'mode' ] ?>' );
				pjax.addVar ( 'cid', document.getElementById ( 'PageID' ).value );
				pjax.onload = function ( )
				{
					if ( this.getResponseText ( ) == 'ok' )
					{
						document.location = 'admin.php?module=extensions&extension=editor';
					}
					else
					{
						alert ( this.getResponseText ( ) );
					}
				}
				pjax.send ( );
			}
		</script>
