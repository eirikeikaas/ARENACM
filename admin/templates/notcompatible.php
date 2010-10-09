<?
	if ( !strstr ( $_SERVER[ 'HTTP_USER_AGENT' ], 'MSIE 6' ) )
		return '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Blest ARENA v2</title>
		<link rel="stylesheet" href="admin/css/login.css" />
		<meta http-equiv="content-type" contents="text/html; charset=utf-8"/>
		<script type="text/javascript" src="lib/javascript/arena-lib.js"></script>
		<script type="text/javascript" src="lib/javascript/bajax.js"></script>
		<script type="text/javascript" src="admin/javascript/login.js"></script>
	</head>
	<body>

    	<!--[if IE 6]>
		<link rel="stylesheet" href="admin/css/login_ie6.css"/>
		<![endif]-->
		<!--[if IE 7]>
		<link rel="stylesheet" href="admin/css/login_ie7.css"/>
		<![endif]-->

		<div id="UnderContent">
		</div>
		
		<div id="CenterBox">

			<div id="Content">	
				
				<div class="Content">
				
					<h2>
						Ikke kompatibel nettleser
					</h2>
					<p>
						Din nettleser (<?= $_SERVER[ 'HTTP_USER_AGENT' ] ?>) er ikke støttet. Vennligst benytt deg av en av
						følgende nettlesere:
					</p>
					<ul>
						<li>
							<a href="http://www.getfirefox.com" target="_blank">Firefox versjon 3 eller høyere</a>
						</li>
						<li>
							<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" target="_blank">Internet explorer version 7 eller høyere</a>
						</li>
					</ul>
				</div>
				
				<div class="Footer">
					Blest ARENA v<?= ARENA_VERSION ?> (c) 2006-2009 | ARENA er et produkt av Blest AS, <a href="http://www.blest.no" target="_blank">www.blest.no</a> 
				</div>
				
				
			</div>
				
		</div>
		
		
	</body>
</html>
