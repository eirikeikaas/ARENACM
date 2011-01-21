
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

// Extend DomObject
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

// Setup the image gallery popup system
GalleryPopup.prototype.initialize = function ()
{
	var dom = ge ( this.DomObjectId );
	var links = dom.getElementsByTagName ( 'a' );
	var ar = new Array ();
	for ( var a = 0; a < links.length; a++ )
	{
		var nod = links[a];
		if ( nod.link ) continue;
		ar.push ( nod );
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
		nod.images = ar;
		nod.index = ar.length-1;
		// Make onclick function
		nod.onclick = function ()
		{
			var img = new Image ();
			img.obj = this.obj;
			img.index = this.index;
			img.images = this.images;
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
				image.index = this.index;
				image.images = this.images;
				image.style.position = 'absolute';
				image.style.left = '0px';
				image.style.top = '0px';
				image.style.width = '0px';
				image.style.height = '0px';
				image.style.background = 'url('+this.link+') no-repeat center center';
				image.style.webkitBackgroundSize = '100%';
				image.id = 'GalleryPopupImage';
				image.open = this.obj.Open;
				image.resize = this.obj.Resize;
				image.showControls = this.obj.ShowControls;
				image.close = this.obj.Close;
				image.phase = 0;
				image.tw = this.width;
				image.th = this.height;
				box.appendChild ( image );
				cont.appendChild ( box );
				image.bg = bg;
				bg.currentImage = image;
				image.interval = setInterval ( 'ge(\''+image.id+'\').open()', 30 );
				image.keyfunc = addEvent ( 'onkeydown', function ( e )
				{
					if ( !e ) e = window.event;
					switch ( e.which )
					{
						case 27: if ( _currentGalleryPopupImage.bg ) _currentGalleryPopupImage.close(); break;
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

// Open the image popup
GalleryPopup.prototype.Open = function ()
{
	_currentGalleryPopupImage = this;
	if ( this.phase < 1 )
		this.phase += 0.08;
	else { clearInterval ( this.interval ); this.interval = false; this.showControls(); }
	if ( this.phase > 1 ) this.phase = 1;
	var ph = Math.pow ( Math.sin ( this.phase * 0.5 * Math.PI ), 3 );
	this.style.width = Math.floor ( this.tw * ph ) + 'px';
	this.style.height = Math.floor ( this.th * ph ) + 'px';
	this.style.top = -Math.floor ( this.th * ph * 0.5 ) + 'px';
	this.style.left = -Math.floor ( this.tw * ph * 0.5 ) + 'px';
	setOpacity ( this.bg, this.phase * 0.7 );
}

GalleryPopup.prototype.ShowControls = function ()
{
	//
	var next = document.createElement ( 'div' ); next.className = 'GalleryPopupNext';
	next.innerHTML = '<a href="javascript:void(0)">Next <span>»</span></a>';
	var prev = document.createElement ( 'div' ); prev.className = 'GalleryPopupPrev';
	prev.innerHTML = '<a href="javascript:void(0)"><span>«</span> Previous</a>';
	var close = document.createElement ( 'div' ); close.className = 'GalleryPopupClose';
	close.innerHTML = '<a href="javascript:void(0)"><span>X</span> Close</a>';
	
	// Add them
	this.appendChild ( next );
	this.appendChild ( prev );
	this.appendChild ( close );
	
	// Position
	prev.style.top = Math.floor ( this.th ) + 'px';
	prev.style.left = '0px';
	next.style.right = '0px';
	next.style.top = Math.floor ( this.th ) + 'px';
	close.style.left = Math.floor ( getElementWidth ( this ) * 0.5 ) - 
		Math.floor ( getElementWidth ( close ) * 0.5 ) + 'px';
	close.style.top = Math.floor ( this.th ) + 'px';
	
	// Actions
	close.img = this; next.img = this; prev.img = this;
	close.onclick = function () { this.img.interval = setInterval ( 'ge(\''+this.img.id+'\').close();', 30 ); }
	next.onclick = function () { this.img.resize ( this.img.index + 1 ); }
	prev.onclick = function () { this.img.resize ( this.img.index - 1 ); }
	
	// Record
	this.bnext = next; this.bprev = prev; this.bclose = close;
	this.resize ( this.index );
}

// Close the popup
GalleryPopup.prototype.Close = function ()
{
	if ( !this.closing )
	{
		if ( this.interval )
		{
			clearInterval ( this.interval );
			this.interval = false;
		}
		this.interval = setInterval ( 'ge(\'' + this.id + '\').close()', 30 );
		this.closing = true;
	}
	
	// removes the key checker
	removeEvent ( 'onkeydown', this.keyfunc );
	
	// remove controls
	if ( this.bnext ) 
	{
		this.bnext.parentNode.removeChild ( this.bnext );
		this.bprev.parentNode.removeChild ( this.bprev );
		this.bclose.parentNode.removeChild ( this.bclose );
		this.bnext = false;
	}
		
	
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

// Resize the popup to new image dimensions
GalleryPopup.prototype.Resize = function ( arg )
{
	this.style.backgroundImage = 'url(' + this.images[arg].link+')';
	this.index = arg;
	if ( arg > 0 ) this.bprev.style.display = '';
	else this.bprev.style.display = 'none';
	if ( arg < this.images.length - 1 ) this.bnext.style.display = '';
	else this.bnext.style.display = 'none';
}


// This class is not done yet!
