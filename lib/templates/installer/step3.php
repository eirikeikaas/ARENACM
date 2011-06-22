<!DOCTYPE html><? header ( 'Content-type: text/html; charset=utf-8;' ); ?>
<html>
	<head>
		<title><?= i18n ( 'ARENACM Site Installed' ) ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf8;"/>
		<link rel="stylesheet" href="admin/css/admin.css"/>
	</head>
	<body style="background: #e8e8e8">
		<div class="ModuleContainer">
			<div class="Container">
				<h1>
					<?= i18n ( 'ARENACM Site Installed' ) ?>
				</h1>
				<div class="SubContainer">
					<p>
						Your site is now ready to be used! Please remove the "install.php" file, or
						rename it, to see the website.
					</p>
					<p>
						<a href="index.php"><?= i18n ( 'To your site' ) ?></a>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>
