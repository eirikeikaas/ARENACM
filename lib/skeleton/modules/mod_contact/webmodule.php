<?php

/*******************************************************************************
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/

Software distributed under the License is distributed on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations
under the License.

The Original Code is (C) 2004-2010 Blest AS.

The Initial Developer of the Original Code is Blest AS.
Portions created by Blest AS are Copyright (C) 2004-2010
Blest AS. All Rights Reserved.

Contributor(s): Hogne Titlestad, Thomas Wollburg, Inge Jørgensen, Ola Jensen, 
Rune Nilssen
*******************************************************************************/

global $page;

i18nAddLocalePath ( 'skeleton/modules/mod_contact/locale/' );
$settings = CreateObjectFromString ( $field->DataMixed );

if ( $_POST[ 'action' ] && $_SESSION[ 'last_contact_mailkey' ] != $_POST[ 'action' ] )
{
	if ( $_POST[ 'spamcontrol' ] != '4' )
	{
		$module .= '<h1>'.i18n('Spam').'</h1><p>'.i18n('Your message was identified as spam').'.</p><p><a href="' . $page->getUrl () . '">' . i18n ( 'Back' ) . '</a></p>';
	} 
	else if ( $settings->Receivers )
	{
		$str = '';
		foreach ( $_POST as $k=>$v )
		{
			if ( $k == 'action' )continue;
			$str .= "$k: $v\n";
		}
		if ( !@mail_ ( $settings->Receivers, i18n ( 'Contact form' ), $str, 'Content-type: text/plain' ) )
		{
			mail ( $settings->Receivers, i18n ( 'Contact form' ), $str, 'Content-type: text/plain' );
		}
		$_SESSION[ 'last_contact_mailkey' ] = $_POST[ 'action' ];
		$module .= $settings->SendMessage;
	}
	else
	{
		$module .= '<p>System error. No e-mail receiver set up. Please contact support.</p>';
	}
}
else if ( $_POST[ 'action' ] )
{
	$module .= '<h2>' . i18n ( 'Already posted' ) . '</h2>';
	$module .= '<p>' . i18n ( 'Your message was already posted' ) . '</p>';
}
else
{
	if ( trim ( $settings->SortOrder ) )
	{
		$order = explode ( ':', $settings->SortOrder );
		foreach ( $order as $k )
		{
			if ( !$settings->$k )
				continue;
				
			switch ( $k )
			{
				case 'LeadinMessage':
				case 'SendMessage':
				case 'Receivers':
				case 'undefined':
					continue;
				case 'Message':
					$str .= '<tr class="tr' . texttourl ( $k ) . '"><td class="' . texttourl ( $k ) . '">' . i18n ( $k ) . ':</td>';
					$str .= '<td class="' . texttourl ( $k ) . '"><textarea name="' . texttourl(i18n($k)) . '" cols="50" rows="10"></textarea></td></tr>';
					break;
				default:
					$str .= '<tr class="tr' . texttourl ( $k ) . '"><td class="' . texttourl ( $k ) . '">' . i18n ( $k ) . ':</td>';
					$str .= '<td class="' . texttourl ( $k ) . '"><input type="text" name="' . texttourl(i18n($k)) . '" value="" size="50"/></td></tr>';
					break;
			}
		}
	}
	else
	{
		foreach ( $settings as $k=>$v )
		{
			switch ( $k )
			{
				case 'LeadinMessage':
				case 'SendMessage':
				case 'Receivers':
				case 'undefined':
					continue;
				case 'Message':
					if ( $v == 1 )
					{
						$str .= '<tr><td class="' . texttourl ( $k ) . '">' . i18n ( $k ) . ':</td>';
						$str .= '<td class="' . texttourl ( $k ) . '"><textarea name="' . texttourl(i18n($k)) . '" cols="50" rows="10"></textarea></td></tr>';
					}
					break;
				default:
					if ( $v == 1 )
					{
						$str .= '<tr><td class="' . texttourl ( $k ) . '">' . i18n ( $k ) . ':</td>';
						$str .= '<td class="' . texttourl ( $k ) . '"><input type="text" name="' . texttourl(i18n($k)) . '" value="" size="50"/></td></tr>';
					}
					break;
			}
		}
	}
	$str .= '</table>';
	$str .= '<table><tr><td class="spam_control">' .i18n ( 'Spam control' ) . ' - ' . i18n ( 'What is sum of one plus three' ) . '?</td>';
	$str .= '<td class="spam_control"><input type="text" name="spamcontrol" value=""/></td></tr>';
	
	$module .= $settings->LeadinMessage;
	$module .= '<form method="post" name="' . $field->Name . '" action="' . $page->getUrl () . '">';
	$module .= '<input type="hidden" name="action" value="mail' . ( microtime() . rand(0,99999) ) . '"/>';
	$module .= '<table>' . $str . '</table>';
	$module .= '<p class="submit"><button type="submit">' . i18n ( 'Send form' ) . '</button></p>';
	$module .= '</form>';
}	

?>
