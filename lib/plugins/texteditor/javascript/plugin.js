
// Setup content changer
var tco = ge ( 'TxContentOptions' );
tco.onchange = function () 
{
	var j = new bajax ();
	j.openUrl ( 'admin.php?plugin=texteditor&pluginaction=getcontentfields', 'post', true );
	j.addVar ( 'cid', this.value );
	j.onload = function () 
	{
		var r = this.getResponseText ().split ( '<!--separate-->' );
		if ( r[0] == 'ok' )
		{
			ge ( 'TxContentFields' ).innerHTML = r[1];
		}
	}
	j.send ();
}
tco.onchange ();

// Insert this content
window.txInsertContentField = function ()
{
	var cid = ge ( 'TxContentOptions' ).value;
	var fnm = ge ( 'TxContentFields' ).getElementsByTagName ( 'select' )[0].value;
	removeModalDialogue ( 'fieldobject' );
	var ed = texteditor.get ( texteditor.activeEditorId );
	ed.insertHTML ( '<span arenatype="fieldobject" style="display: block; width: 400px; height: 100px; background: #c0c0c0 url(admin/gfx/icons/layout.png) no-repeat center center; border: 1px dotted #808080" id="FieldObject__' + cid + '__' + fnm + '">&nbsp;</span>' );
}
