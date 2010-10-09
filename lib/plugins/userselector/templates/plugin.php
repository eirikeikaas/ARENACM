
	<h1><!--<img src="admin/gfx/icons/help.png" style="float: right"/>--><?= i18n ( 'Brukerregister' ) ?></h1>
	
	<div class="Container">
	
		<h2>
			Finn en bruker:
		</h2>
		<p>
			<input type="text" size="20" id="userskeywords"> 
			<script>
				document.getElementById ( 'userskeywords' ).onkeydown = function ( e )
				{
					if (!e) 
						e = window.event;
					if ( document.all && e.keyCode == '13' )
					{
						loadUserList ( document.getElementById ( 'userskeywords' ).value, '' ); return false
						return;
					}
					if ( e.which == '13' )
					{
						loadUserList ( document.getElementById ( 'userskeywords' ).value, '' ); return false
					}
				}
			</script>
			<button type="button" class="Small" onclick="loadUserList ( document.getElementById ( 'userskeywords' ).value, '' )"><img src="admin/gfx/icons/magnifier.png"/></button>
			<button type="button" id="userlistsearchcancel" class="Small" onclick="document.getElementById ( 'userskeywords' ).value = ''; loadUserList ( '', '' )"><img src="admin/gfx/icons/cancel.png"/></button>
		</p>
		<div class="SpacerSmall"></div>
		
		<h2>Brukere:</h2>
		
		<div class="SubContainer" id="PluginUserlist" style="padding: 2px;">
		</div>
		
		<div class="Spacer"></div>
		
		<h2>Grupper:</h2>
		
		<div class="SubContainer" id="PluginGrouplist" style="padding: 2px">
		</div>
		
	</div>
	
	<script>
		function loadUserList ( keywords, varpos )
		{
			if ( !keywords ) 
			{
				keywords = ''; 
				document.getElementById ( 'userlistsearchcancel' ).style.display = 'none';
			}
			else 
			{
				document.getElementById ( 'userlistsearchcancel' ).style.display = '';
			}
			
			if ( !varpos ) varpos = '0';
			
			document.bjax = new bajax ( );
			document.bjax.openUrl ( 'admin.php?plugin=userselector&pluginaction=showusers&pos=' + varpos + '&keywords=' + keywords, 'get', true );
			document.bjax.onload = function ( )
			{
				document.getElementById ( 'PluginUserlist' ).innerHTML = this.getResponseText ( );
				document.bjax = 0;
			}
			document.bjax.send ( );
		}
		document.getElementById ( 'userlistsearchcancel' ).style.display = 'none';
			
		function loadGroupList ( keywords, varpos )
		{
			if ( !varpos ) varpos = '0';
			
			document.gjax = new bajax ( );
			document.gjax.openUrl ( 'admin.php?plugin=userselector&pluginaction=showgroups&pos=' + varpos + '&keywords=' + keywords, 'get', true );
			document.gjax.onload = function ( )
			{
				document.getElementById ( 'PluginGrouplist' ).innerHTML = this.getResponseText ( );
				document.bjax = 0;
			}
			document.gjax.send ( );
		}
			
		loadUserList ( );
		loadGroupList ( );
	</script>


