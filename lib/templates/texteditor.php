<!doctype html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>
		</title>
		<link rel="stylesheet" href="lib/templates/texteditor.css"/>
		<?= stripslashes ( $_REQUEST['extra'] ) ?>
		<meta http-equiv="content-type" content="text/xhtml; charset=UTF-8"/>
	</head>
	<body onload="if ( navigator.userAgent.indexOf ( 'MSIE' ) >= 0 ) document.body.contentEditable='true';" onmouseup="if ( navigator.userAgent.indexOf ( 'MSIE' ) >= 0 ) return false" onselectstart="if ( navigator.userAgent.indexOf ( 'MSIE' ) >= 0 ) return false"  onclick="if ( navigator.userAgent.indexOf ( 'MSIE' ) >= 0 ) return false" oncontextmenu="return false">
		Laster inn...
	</body>
</html>
