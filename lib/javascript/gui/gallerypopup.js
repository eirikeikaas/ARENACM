
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

var _currentGalleryPopupImage = false;

var GalleryPopup = function ( domobj, url )
{
	this.Url = url;
	if ( typeof ( domobj ) != 'object' )
		this.DomObjectId = domobj;
	else this.DomObjectId = domobj.id;
	this.initialize ( );
	var css = document.createElement ( 'link' );
	css.rel = 'stylesheet'; css.href = 'lib/javascript/css/gallerypopup.css';
	document.getElementsByTagName ( 'head' )[0].appendChild ( css );
}

GalleryPopup.prototype = GuiObject.prototype;

GalleryPopup.prototype.initialize = function ()
{
	var dom = ge ( this.DomObjectId );
	var links = dom.getElementsByTagName ( 'a' );
	for ( var a = 0; a < links.length; a++ )
	{
		var nod = links[a];
		var img;
		if ( !( img = nod.getElementsByTagName ( 'img' ) ) )
			continue;
		img = img[0];
		nod.className = 'GalleryPopupImage';
		nod.onmouseover = function ()
		{ this.className = 'GalleryPopupImageOver'; }
		nod.onmouseout = function ()
		{ this.className = 'GalleryPopupImage'; }
		nod.link = ( nod.href + '' );
		nod.obj = this;
		// Make onclick function
		nod.onclick = function ()
		{
			var img = new Image ();
			img.obj = this.obj;
			img.link = ( this.link + '' );
			img.onload = function ()
			{
				var cont = ge ( 'Empty__' ) ? ge ( 'Empty__' ) : ge ( 'Javascript' );
				var bg = document.createElement ( 'div' );
				bg.className = 'GalleryPopupBackground';
				cont.appendChild ( bg );
				setOpacity ( bg, 0.0 );
				var box = document.createElement ( 'div' );
				box.className = 'GalleryPopupBox';
				var image = document.createElement ( 'div' );
				image.style.position = 'absolute';
				image.style.left = '0px';
				image.style.top = '0px';
				image.style.width = '0px';
				image.style.height = '0px';
				image.style.background = 'url('+this.link+') no-repeat center center';
				image.style.webkitBackgroundSize = '100%';
				image.id = 'GalleryPopupImage';
				image.open = this.obj.Open;
				image.close = this.obj.Close;
				image.phase = 0;
				image.tw = this.width;
				image.th = this.height;
				box.appendChild ( image );
				cont.appendChild ( box );
				image.bg = bg;
				bg.currentImage = image;
				image.bg.onclick = function ()
				{
					if ( this.currentImage.interval )
					{
						clearInterval ( this.currentImage.interval );
						this.currentImage.interval = false;
					}
					this.currentImage.interval = setInterval ( 'ge(\'' + this.currentImage.id + '\').close()', 30 );
				}
				image.interval = setInterval ( 'ge(\''+image.id+'\').open()', 30 );
				image.keyfunc = addEvent ( 'onkeydown', function ( e )
				{
					if ( !e ) e = window.event;
					switch ( e.which )
					{
						case 27: if ( _currentGalleryPopupImage.bg ) _currentGalleryPopupImage.bg.onclick(); break;
						default: break;
					}
				}
				);
			}
			img.src = this.link;
		}
		nod.href = 'javascript:void(0)';
	}
}

// For image div
GalleryPopup.prototype.Open = function ()
{
	_currentGalleryPopupImage = this;
	if ( this.phase < 1 )
		this.phase += 0.08;
	else { clearInterval ( this.interval ); this.interval = false }
	if ( this.phase > 1 ) this.phase = 1;
	var ph = Math.pow ( Math.sin ( this.phase * 0.5 * Math.PI ), 3 );
	this.style.width = Math.floor ( this.tw * ph ) + 'px';
	this.style.height = Math.floor ( this.th * ph ) + 'px';
	this.style.top = -Math.floor ( this.th * ph * 0.5 ) + 'px';
	this.style.left = -Math.floor ( this.tw * ph * 0.5 ) + 'px';
	setOpacity ( this.bg, this.phase * 0.7 );
}

GalleryPopup.prototype.Close = function ()
{
	// removes the key checker
	delEvent ( this.keyfunc );
	
	if ( this.phase > 0 )
		this.phase -= 0.08;
	else 
	{
		clearInterval ( this.interval ); this.interval = false;
		this.bg.parentNode.removeChild ( this.bg );
		this.parentNode.parentNode.removeChild ( this.parentNode );
		_currentGalleryPopupImage = false;
		return;
	}
	if ( this.phase < 0 ) this.phase = 0;
	var ph = Math.pow ( Math.sin ( this.phase * 0.5 * Math.PI ), 3 );
	this.style.width = Math.floor ( this.tw * ph ) + 'px';
	this.style.height = Math.floor ( this.th * ph ) + 'px';
	this.style.top = -Math.floor ( this.th * ph * 0.5 ) + 'px';
	this.style.left = -Math.floor ( this.tw * ph * 0.5 ) + 'px';
	setOpacity ( this.bg, this.phase * 0.7 );
}


// This class is not done yet!
