<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>
			Arena2 - Installer
		</title>
		<link rel="stylesheet" href="admin/css/admin.css" />
		<style> body, td, th, div, p, input, select { font-size: 12px; } </style>
	</head>
	<body>
		<div style="display: block; padding: 110px 20px 20px 20px">
			<form method="post" action="admin.php?installer=2">
				<h1>
					Installer en ny Site
				</h1>
				<div class="Container">
					<p>
						Fyll inn alle nødvendige felter her for å installere denne siten.
					</p>
					<p>
						<strong>Site ID (navn)</strong>
					</p>
					<p>
						<input type="text" name="SiteName" size="33" value="" />
					</p>
					<p>
						<strong>Database navn:</strong>
					</p>
					<p>
						<input type="text" name="SqlDatabase" value="" />
					</p>
					<p>
						<strong>Database brukernavn:</strong>
					</p>
					<p>
						<input type="text" name="SqlUser" value="" />
					</p>
					<p>
						<strong>Database passord:</strong>
					</p>
					<p>
						<input type="text" name="SqlPass" value="" />
					</p>
					<p>
						<strong>Database host:</strong>
					</p>
					<p>
						<input type="text" name="SqlHost" size="33" value="" />
					</p>
					<p>
						<strong>Base dir (uten / på slutten):</strong>
					</p>
					<p>
						<input type="text" name="BaseDir" size="55" value="" />
					</p>
					<p>
						<strong>Base url (med / på slutten):</strong>
					</p>
					<p>
						<input type="text" name="BaseUrl" size="55" value="" />
					</p>
					<p>
						<button type="submit">
							<img src="admin/gfx/icons/disk.png" /> Installer siten!
						</button>
					</p>
				</div>
			</form>
		</div>
	</body>
</html>