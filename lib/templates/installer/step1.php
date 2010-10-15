<!DOCTYPE html><? header ( 'Content-type: text/html; charset=utf-8;' ); ?>
<html>
	<head>
		<title><?= i18n ( 'Install ARENACM Site' ) ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf8;"/>
		<link rel="stylesheet" href="admin/css/admin.css"/>
	</head>
	<body style="background: #e8e8e8">
		<div class="ModuleContainer">
			<div class="Container">
				<h1>
					<?= i18n ( 'Install ARENACM Site' ) ?>
				</h1>
				<form method="post" action="index.php?step=2" name="installform">
					<div class="Container">
						<h2>
							Login information to ARENACM core database:
						</h2>
						<div class="Spacer"></div>
						<p>
							The first step in installing this ARENACM site is to 
							enter in the ARENACM core database information, so that
							we can register the site with the core. All important 
							settings are stored there.
						</p>
						<p>
							NB: Make sure that the ARENACM core database user has 
							the privileges to create a new database for the site. 
							If you do not have these privileges, then you will have 
							to set up the site database manually.
						</p>
						<table cellspacing="0" cellpadding="4" width="390px">
							<tr>
								<td><strong>MySQL username:</strong></td>
								<td><input type="text" value="" name="coreUsername"/></td>
							</tr>
							<tr>
								<td><strong>MySQL password:</strong></td>
								<td><input type="text" value="" name="corePassword"/></td>
							</tr>
							<tr>
								<td><strong>MySQL database:</strong></td>
								<td><input type="text" value="" name="coreDatabase"/></td>
							</tr>
							<tr>
								<td><strong>MySQL hostname:</strong></td>
								<td><input type="text" value="" name="coreHostname"/></td>
							</tr>
						</table>
						<div class="SpacerSmallColored"></div>
						<div class="Spacer"></div>
						<h2>
							Setup your site info
						</h2>
						<p>
							Now enter the site information that will be used to initialize the site.
							As stated above, you need to create the site database manually if the ARENACM core
							user has no "CREATE DATABASE" privileges.
						</p>
						<table cellspacing="0" cellpadding="4" width="390px">
							<tr>
								<td><strong>Site ID (literal):</strong></td>
								<td><input type="text" value="" name="siteID"/> (ex: my_site)</td>
							</tr>
							<tr>
								<td><strong>Site MySQL username:</strong></td>
								<td><input type="text" value="" name="siteUsername"/></td>
							</tr>
							<tr>
								<td><strong>Site MySQL password:</strong></td>
								<td><input type="text" value="" name="sitePassword"/></td>
							</tr>
							<tr>
								<td><strong>Site MySQL database:</strong></td>
								<td><input type="text" value="" name="siteDatabase"/></td>
							</tr>
							<tr>
								<td><strong>Site MySQL hostname:</strong></td>
								<td><input type="text" value="" name="siteHostname"/></td>
							</tr>
						</table>
						<div class="SpacerSmallColored"></div>
						<div class="Spacer"></div>
						<button type="button" onclick="checkform()">
							Finish <img src="admin/gfx/icons/arrow_right.png"/>
						</button>
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			function checkform ()
			{
				var eles = document.getElementsByTagName ( 'input' );
				for ( var a = 0; a < eles.length; a++ )
				{
					if ( eles[a].value <= 1 )
					{
						alert ( 'Du må fylle inn ' + eles[a].name.toLowerCase () + '.' );
						eles[a].focus();
						return false;
					}
				}
				document.installform.submit();
			}
		</script>
	</body>
</html>
