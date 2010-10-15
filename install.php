<?php

// Check for errors ----------------------------------------------------------->

$errors = array ();

// Check that the config is writable
if ( !( $fp = fopen ( 'config.php', 'w' ) ) )
{
	$errors[] = 'Can not open config.php for writing! Please change the permissions on it to "777".';
}
else fclose ( $fp );

// Check upload folder, that it exists
if ( !file_exists ( 'upload' ) )
{
	$errors[] = 'No "upload" directory exists!';
}
else
{
	// Check upload folder
	if ( !( $fp = fopen ( 'upload/test', 'w' ) ) )
	{
		$errors[] = 'The "upload" folder is not writable. Please change the permissions on it to "777".';
	} else fclose ( $fp );
	
	// Check images-master
	if ( !file_exists ( 'upload/images-master' ) )
	{
		$errors[] = 'No "upload/images-master" directory exists!';	
	}
	else if ( !( $fp = fopen ( 'upload/images-master/test', 'w' ) ) )
	{
		$errors[] = 'The "upload/images-master" folder is not writable. Please change the permissions on it to "777".';
	}
	if ( $fp ) fclose ( $fp );
	
	// Check images-cache
	if ( !file_exists ( 'upload/images-cache' ) )
	{
		$errors[] = 'No "upload/images-cache" directory exists!';	
	}
	else if ( !( $fp = fopen ( 'upload/images-cache/test', 'w' ) ) )
	{
		$errors[] = 'The "upload/images-cache" folder is not writable. Please change the permissions on it to "777".';
	}
	if ( $fp ) fclose ( $fp );
}
// Check extensions folder
if ( !file_exists ( 'extensions' ) )
{
	$errors[] = 'No "extensions" folder exist!';
}
else if ( !file_exists ( 'extensions/editor' ) )
{
	$errors[] = 'You need to add the "editor" extension to the extensions folder. Copy the "editor" extension folder into "extensions/" to adress this.';
}

// Done checking for errors ---------------------------------------------------<

if ( !$errors )
{
	switch ( $_REQUEST[ 'step' ] )
	{
		case '3':
			$tpl = new cPTemplate ( 'lib/templates/installer/step3.php' );
			break;
		case '2':
			
			// Set basedir and baseurl
			$basedir = getcwd ();
			list ( $baseurl, ) = explode ( 'index.php', $_SERVER[ 'REQUEST_URI' ] );
			$baseurl = 'http://' . $_SERVER[ 'SERVER_NAME' ] . $baseurl;
			
			// Define objects
			$cdb = new cDatabase ( );
			$cdb->setUsername ( $_POST[ 'coreUsername' ] );
			$cdb->setPassword ( $_POST[ 'corePassword' ] );
			$cdb->setHostname ( $_POST[ 'coreHostname' ] );
			$sdb = new cDatabase ( );
			$sdb->setUsername ( $_POST[ 'siteUsername' ] );
			$sdb->setPassword ( $_POST[ 'sitePassword' ] );
			$sdb->setHostname ( $_POST[ 'siteHostname' ] );
			
			// Connect to core database
			if ( !$cdb->open () )
				die ( 'Failed to connect to core database!' );
			$cdb->setDb ( $_POST[ 'coreDatabase' ] );
			
			// Check for core database, create if it doesn't exist
			$result = $cdb->fetchRow ( 'DESCRIBE Sites' );
			if ( $result[0] != 'ID' )
			{
				// Create
				$cdb->query ( 'CREATE DATABASE `' . $_POST[ 'coreDatabase' ] . '`' );
				$cdb->query ( 'USE `' . $_POST[ 'coreDatabase' ] . '`' );
				
				// Import structure
				$sql = file_get_contents ( $basedir . '/lib/skeleton/arenacore.sql' ); 
				$sql = split ( ';', $sql );
				foreach ( $sql as $s )
				{
					if ( $s{0} == '-' ) continue;
					if ( !trim ( $s ) ) continue;
					$cdb->query ( trim ( $s ) );
				}
				
				// Add root user
				$cdb->query ( '
					INSERT INTO Users ( `Username`, `Password`, `Name`, `Email` ) 
					VALUES ( "arenauser", md5("arenapassword"), "ArenaCM Admin", "admin@'.$_SERVER['SERVER_NAME'].'" )
				' );
				
				$result = $cdb->fetchRow ( 'DESCRIBE Sites' );
			}
			if ( $result[0] != 'ID' )
			{
				die ( 'Failed to find and/or create core database!' );
			}
			
			// Create site
			$cdb->query ( 'CREATE DATABASE `' . $_POST[ 'siteDatabase' ] . '`' );
			$cdb->query ( 'USE `' . $_POST[ 'siteDatabase' ] . '`' );
			if ( $sdb->open () )
			{
				$sql = file_get_contents ( $basedir . '/lib/skeleton/arenadb.sql' ); 
				$sql = split ( ';', $sql );
				foreach ( $sql as $s )
				{
					if ( $s{0} == '-' ) continue;
					if ( !trim ( $s ) ) continue;
					$cdb->query ( trim ( $s ) );
				}
			}
			else die ( 'Failed to create database `' . $_POST[ 'siteDatabase' ] . '`.' );
			
			// Insert site into core db
			$cdb->query ( 'USE `' . $_POST[ 'coreDatabase' ] . '`' );
			$site = new dbObject ( 'Sites', &$cdb );
			$site->SiteName = $_POST[ 'siteID' ];
			$site->SqlUser = $_POST[ 'siteUsername' ];
			$site->SqlPass = $_POST[ 'sitePassword' ];
			$site->SqlHost = $_POST[ 'siteHostname' ];
			$site->SqlDatabase = $_POST[ 'siteDatabase' ];
			$site->BaseUrl = $baseurl;
			$site->BaseDir = $basedir;
			$site->save ();
			
			// Insert standard modules
			$a = 0; $mods = array ( 'extensions', 'library', 'users', 'settings' );
			foreach ( $mods as $m )
			{
				$mod = new dbObject ( 'ModulesEnabled', &$cdb );
				$mod->SiteID = $site->ID;
				$mod->Module = $m;
				$mod->SortOrder = $a++;
				$mod->save ();
			}
			
			// Set config file
			$fp = fopen ( 'config.php', 'w+' );
			$str = '<?
	define ( \'SITE_ID\', "' . $site->SiteName . '" );
	define ( \'NEWEDITOR\', "true" );
?>';
			fwrite ( $fp, $str );
			fclose ( $fp );
			
			// Redirect to step 3
			header ( 'Location: admin.php' );
			die ( );
			
			break;
		case '1':
		default:
			$tpl = new cPTemplate ( 'lib/templates/installer/step1.php' );
			break;
	}
}
else
{
	$tpl = new cPTemplate ( 'lib/templates/installer/error.php' );
	foreach ( $errors as $er )
	{
		$tpl->error .= '<p>' . $er . '</p>';
	}
}
die ( $tpl->render () );

?>
