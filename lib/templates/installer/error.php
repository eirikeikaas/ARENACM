<!DOCTYPE html><? header ( 'Content-type: text/html; charset=utf-8;' ); ?>
<html>
	<head>
		<title><?= i18n ( 'Install ARENACM Site - Error' ) ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf8;"/>
		<link rel="stylesheet" href="admin/css/admin.css"/>
	</head>
	<body style="background: #e8e8e8">
		<div class="ModuleContainer">
			<div class="Container">
				<h1>
					<?= i18n ( 'Install ARENACM Site - Error' ) ?>
				</h1>
				<div class="Container">
					<div style="border: 2px solid #a00; background: #ffc; padding: 15px"><?= $this->error ?></div>
					<div class="SpacerSmallColored"></div>
					<button type="button" onclick="document.location.reload()">
						<img src="admin/gfx/icons/arrow_refresh.png"/> Reload
					</button>
				</div>
			</div>
		</div>
	</body>
</html>
