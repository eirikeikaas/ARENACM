<?php
/*
    Admin CORE - CMS and API
    Copyright (C) 2006-2010 Hogne Titlestad
    CopyRight (c) 2006-2010 eAdmin
    
    Email: hogga@sub-ether.org
    Telephone: (+0047) 51 69 09 17
*/
class cTemplate
{
	var $data;
	var $replacements;
	var $Filename;
			
	// Initialize the template object
	// Can also be initialized with a filename 
	// and will use that filename 
	function cTemplate ( $filename=false )
	{
		$this->data = "";
		
		if ( !empty ( $filename ) )
		{
			$this->load ( $filename );
		}						
		$this->replacements = Array ( );
	}
	
	// Works statically - loads and shows a template
	function show ( $filename )
	{
		$ct = new cTemplate ();
		$ct->load ( $filename );
		$ct->Page =& $GLOBALS[ 'Page' ];
		return $ct->render ();
	}
	
	// Load in a template file
	function load ( $filename )
	{
		if ( file_exists ( $filename ) && !is_dir ( $filename ) )
		{
			$fp = fopen ( $filename, "r" );
			$this->data = fread ( $fp, filesize ( $filename ) );
			fclose ( $fp );
			return true;
		}	
		return false;
	}
	
	// Set a variable on self
	function setVariable ( $field, $data )
	{
		// Todo check overwrite of preexisting actual fields!
		$this->$field = $data;
	}
	
	// Carry out replacements on a temporary string and return it
	function makeReplacements ( )
	{
		$oStr = $this->data;				
		if ( count ( $this->replacements ) )
		{
			foreach ( $this->replacements as $replacement )
			{
				$oStr = str_replace ( $replacement[ 0 ], $replacement[ 1 ], $oStr );
			}						                  				
		}
		// Condition blocks
		while ( preg_match ( '/\<\?if[ ]*\(([^)]*)\)[ ]*\{[ ]*\?\>([\w\W]*?)\<\?\}\?\>/i', $oStr, $matches ) )
		{
			$GLOBALS[ 'tmpObject' ] =& $this;
			$string = str_replace ( '$this', '$GLOBALS[\'tmpObject\']', $matches[1] );
			if ( eval ( 'return ' . $string . ';' ) )
				$replacement = $matches[2];
			else $replacement = '';
			unset ( $GLOBALS[ 'tmpObject' ] );
			$oStr = str_replace ( $matches[0], $replacement, $oStr );
		}
		// Return with final evaluations
		return preg_replace ( "/<\?([\w\W]*?)\?>/e", '$this->evaluatePHP (\'$1\')', $oStr );
	}
	
	
	
	// Add a replacement to the replacement array
	// Each replacement must either be string/object & string/object or
	// two arrays with the same amount of elements (all string/object)
	function addReplacement ( $source, $destination )
	{
		if ( is_object ( $source ) ) 			
			$source = $source->render ( );
			
		if ( is_object ( $destination ) ) 
			$destination = $destination->render ( );
		
		if ( is_string ( $source ) && is_string ( $destination ) )
		{
			$this->replacements[] = Array ( $source, $destination );
			return true;
		}
		else if ( 
			is_array ( $source ) && is_array ( $destination ) &&
			count ( $source ) == count ( $destination ) 
		)
		{
			for ( $a = 0; $a < count ( $source ); $a++ )
			{
				if ( is_object ( $source[ $a ] ) ) $source[ $a ] = $source[ $a ]->render ( );
				if ( is_object ( $destination[ $a ] ) ) $destination[ $a ] = $destination[ $a ]->render ( );
				$this->replacements[] = Array ( $source[ $a ], $destination[ $a ] );
			}				
			return true;
		}			
		return false;
	}
	
	// Add data to the data variable
	function addData ( $data )
	{
		if ( is_object ( $data ) )
		{
			$this->data .= $data->render ( );
		}
		else if ( is_array ( $data ) )
		{
			foreach ( $data as $key=>$value )
			{
				$this->data .= $value;
			}
		}
		else
		{
			$this->data .= $data;
		}
	}
	
	function evaluatePHP ( $code )
	{		
		/* Allow <?="Hello!"?> */
		if ( $code{0} == "=" ) $code = "return " . trim ( substr ( $code, 1, strlen ( $code ) - 1 ) ) . ";";
		
		// Fix doublequotes
        if ( strpos ( $code, '\"' ) )
            $code = str_replace ( array ( '\"', '"' ), '"', $code );
		
		/* Return eval'ed code */
		return eval ( $code );
	}
	
	function evaluatePHPCondition ( $condition, $code )
	{
		return 'lala';
	}
	
	// Make special replacements
	function parseSpecial ( $string )
	{
		// Headers
		$string = str_replace ( array ( "###\n", "###\r" ), "</h3>\n", $string );
		$string = str_replace ( "###", "<h3>", $string );
		$string = str_replace ( array ( "##\n", "##\r" ), "</h2>\n", $string );
		$string = str_replace ( "##", "<h2>", $string );
		$string = str_replace ( array ( "#\n", "#\r" ), "</h1>\n", $string );
		$string = str_replace ( "#", "<h1>", $string );
		$string = str_replace ( array ( "** ", "**\r", "**\n" ), "</strong>\n", $string );
		$string = str_replace ( "**", "<strong>", $string );
	
		return $string;
	}
	
	// Make replacement on temporary string and return it
	function render ( )
	{
		$oStr = $this->makeReplacements ( );
		return $oStr;		
	}
}		
?>
