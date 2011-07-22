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
					<form method="post">
						<h2>
							<?= SITE_ID ?> admin: logg inn
						</h2>
						<p>
							Logg inn med ditt brukernavn og passord under. Vennligst ta kontakt med
							<a href="http://www.ideverket.no" target="_blank">Idéverket</a> hvis du
							ønsker support.
						</p>
						
						<div id="LoginForm">
							<p>
								For support, send e-post til: <a href="mailto:post@ideverket.no">post@ideverket.no</a>
							</p>
							<div class="Box" class="Username">
								<p>
									<strong>
										Brukernavn:
									</strong>
								</p>
								<p>
									<input id="inputUsername" type="text" name="loginUsername" class="Username" />
								</p>
							</div>
							<div class="Box" class="Password">
								<p>
									<strong>
										Passord:
									</strong>
								</p>
								<p>
									<input id="inputPassword" type="password" name="loginPassword" class="Password" />
								</p>
							</div>
						</div>
						<div id="ForgotEmail">
							<p>
								Skriv inn din e-post adresse. Denne må være registrert i ARENA systemet ditt.
								Når du klikker på motta passord, vil det sendes en e-post adresse til deg.
							</p>
							<p>
								<strong>Din e-post adresse:</strong> &nbsp; <input id="forgot_password_email" type="text" size="25" value=""/>
							</p>
						</div>
						<hr/>
						<button type="button" style="float: right" class="NewPassword">
							<img src="admin/gfx/icons/email_attach.png" alt="receive" /> <span id="receive_text">Motta nytt passord</span>
						</button>
						<button type="button" onclick="this.form.submit()" class="Login">
							<img src="admin/gfx/icons/key_go.png" alt="login" /> <span id="login_text">Logg deg inn</span>
						</button>
						
					
					</form>
					<script language="JavaScript" type="text/javascript">
						document.getElementById( 'inputUsername' ).focus();
					</script>
  
				</div>
				
				<div class="Footer">
					AARENA CM v<?= ARENA_VERSION ?> | <?= i18n ( 'ARENA CM is available under the' ) ?> <a href="http://www.mozilla.org/MPL/MPL-1.1.html" target="_bløank"><?= i18n ( 'MPL License' ) ?></a>
				</div>
				
				
			</div>
				
		</div>
		
		<script type="text/javascript">
			function checkKeyDown ( e )
			{
				if ( !e ) e = window.event;
				if ( e.keyCode == 13 )
					this.form.submit ();
			}
			document.getElementById ( 'inputPassword' ).onkeydown = checkKeyDown;
			document.getElementById ( 'inputUsername' ).onkeydown = checkKeyDown;
			if ( document.getElementById ( 'Workbench' ) )
			{
				document.location = 'admin.php';
			}
		</script>
		
	</body>
</html>
