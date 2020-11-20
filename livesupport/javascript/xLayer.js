/******************************************************************* 
* 
* File    : xLayer.js 
* 
* Created : 2000/06/08 
* 
* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com 
* 
* Purpose : To create a cross browser dynamic layers. This 
*		library is based on the library defined in the 
*		excellent book. "JavasScript - The Definitive guide" 
*		by David Flanagan. Published by O'Reilly. 
*		ISBN 1-56592-392-8 
* 
* History 
* Date         Version        Description 
* 
* 2000-06-08	1.0		Initial version 
* 2000-06-17	1.1		Changed function name to setzIndex()
* 2000-06-17	1.2		Changed function name to getzIndex()
* 2000-08-07	1.3		Added the event handling functionality
*					from the book.
* 2000-08-15	1.4		Finally! The NS functions are now prototypes.
* 2000-10-14	1.5		Attempting to add NS6 (W3C Standard) functionality
* 2000-11-04	1.6		Added NS6 Event handling
* 2000-11-06	1.7		Added xLayerFromObj - Allows pre existing 
*					layers/divs to gain the functionality of xLayer.
* 2000-11-19	1.8		Changed the event handling to use an event object
*					and make the core properties the same.
*					e.type, e.button
*					e.layerX, e.layerY,  e.clientX, e.clientY, e.pageX, e.pageY
*					e.keyCode, e.altKey, e.ctrlKey, e.shiftKey
***********************************************************************/ 
var xLayerNo=0; 
function xLayer(newLayer, x, y) 
{
	if(x==null)x=0; 
	if(y==null)y=0; 
 	if(document.layers) 
	{
		if(typeof newLayer == "string")
		{
			this.layer=new Layer(100); 
			this.layer.document.open(); 
			this.layer.document.write(newLayer); 
			this.layer.document.close(); 
		}
		else
			this.layer=newLayer;

		this.layer.moveTo(x,y); 
		this.images=this.layer.document.images; 
 	} 
	else 
	if(document.all) 
	{
		var Xname;
		if(typeof newLayer == "string")
		{
			xName="xLayer" + xLayerNo++; 
 			var txt =   "<DIV ID='" + xName 
				+ "' STYLE=\"position:absolute;" 
				+ "left:"  + x + ";" 
				+ "top:"   + y + ";" 
				+ "visibility:hidden\">" 
				+ newLayer 
 				+ "</DIV>"; 

			document.body.insertAdjacentHTML("BeforeEnd",txt); 
		}
		else
			xName=newLayer.id;

		this.content = document.all[xName]; 
		this.layer   = document.all[xName].style; 
		this.images  = document.images; 
	} 
	else 
	if (document.getElementById)
	{
		/*** Add Netscape 6.0 support (NOTE: This may work in I.E. 5+. Will have to test***/

		var newDiv;

		if(typeof newLayer == "string")
		{
			var xName="xLayer" + xLayerNo++;

			var txt = ""
				+ "position:absolute;"
				+ "left:"  + x + "px;"
				+ "top:"   + y + "px;"
				+ "visibility:hidden";

			var newRange   = document.createRange();

			newDiv = document.createElement("DIV");
			newDiv.setAttribute("style",txt);
			newDiv.setAttribute("id", xName);

			document.body.appendChild(newDiv);

			newRange.setStartBefore(newDiv);
			strFrag = newRange.createContextualFragment(newLayer);	
			newDiv.appendChild(strFrag);
		}
		else
			newDiv = newLayer;

		this.content = newDiv;	
		this.layer   = newDiv.style;
		this.images  = document.images;
	}

	return(this); 
} 

function xLayerFromObj(theObj)
{
	if(document.layers)
		return(new xLayer(document.layers[theObj]));
	else 
	if(document.all)
		return(new xLayer(document.all[theObj]));
	else 
	if(document.getElementById)
		return(new xLayer(document.getElementById(theObj)));
}

if(navigator.appName.indexOf("Netscape") != -1 && !document.getElementById)
{
var eventmasks = {
      onabort:Event.ABORT, onblur:Event.BLUR, onchange:Event.CHANGE,
      onclick:Event.CLICK, ondblclick:Event.DBLCLICK, 
      ondragdrop:Event.DRAGDROP, onerror:Event.ERROR, 
      onfocus:Event.FOCUS, onkeydown:Event.KEYDOWN,
      onkeypress:Event.KEYPRESS, onkeyup:Event.KEYUP, onload:Event.LOAD,
      onmousedown:Event.MOUSEDOWN, onmousemove:Event.MOUSEMOVE, 
      onmouseout:Event.MOUSEOUT, onmouseover:Event.MOUSEOVER, 
      onmouseup:Event.MOUSEUP, onmove:Event.MOVE, onreset:Event.RESET,
      onresize:Event.RESIZE, onselect:Event.SELECT, onsubmit:Event.SUBMIT,
      onunload:Event.UNLOAD
};

/**** START prototypes for NS ***/ 
xLayer.prototype.moveTo 	= function(x,y) 	{ this.layer.moveTo(x,y); }
xLayer.prototype.moveBy 	= function(x,y) 	{ this.layer.moveBy(x,y); }
xLayer.prototype.show		= function() 	{ this.layer.visibility = "show"; }
xLayer.prototype.hide 		= function() 	{ this.layer.visibility = "hide"; }
xLayer.prototype.setzIndex	= function(z)	{ this.layer.zIndex = z; }
xLayer.prototype.setBgColor 	= function(color) { this.layer.bgColor = color; if(color==null)this.layer.background.src=null;}
xLayer.prototype.setBgImage 	= function(image) { this.layer.background.src = image; }
xLayer.prototype.getX 		= function() 	{ return this.layer.left; }
xLayer.prototype.getY 		= function() 	{ return this.layer.top; }
xLayer.prototype.getWidth 	= function() 	{ return this.layer.width; }
xLayer.prototype.getHeight 	= function() 	{ return this.layer.height; }
xLayer.prototype.getzIndex	= function()	{ return this.layer.zIndex; }
xLayer.prototype.isVisible 	= function() 	{ return this.layer.visibility == "show"; }
xLayer.prototype.setContent   = function(xHtml)
{
	this.layer.document.open();
	this.layer.document.write(xHtml);
	this.layer.document.close();
}

xLayer.prototype.clip = function(x1,y1, x2,y2)
{
	this.layer.clip.top	=y1;
	this.layer.clip.left	=x1;
	this.layer.clip.bottom	=y2;
	this.layer.clip.right	=x2;
}
xLayer.prototype.addEventHandler = function(eventname, handler) 
{
        this.layer.captureEvents(eventmasks[eventname]);
        var xl = this;
        this.layer[eventname] = function(event) { 
		event.clientX	= event.pageX;
		event.clientY	= event.pageY;
		event.button	= event.which;
		event.keyCode	= event.which;
		event.altKey	=((event.modifiers & Event.ALT_MASK) != 0);
		event.ctrlKey	=((event.modifiers & Event.CONTROL_MASK) != 0);
		event.shiftKey	=((event.modifiers & Event.SHIFT_MASK) != 0);
            return handler(xl, event);
        }
}
xLayer.prototype.removeEventHandler = function(eventName) 
{
	this.layer.releaseEvents(eventmasks[eventName]);
	delete this.layer[eventName];
}

/*** END NS ***/ 
} 
else if(document.all) 
{ 
/*** START prototypes for IE ***/ 
xLayer.prototype.moveTo = function(x,y) 
{ 
	this.layer.pixelLeft = x; 
	this.layer.pixelTop = y; 
} 
xLayer.prototype.moveBy = function(x,y) 
{ 
	this.layer.pixelLeft += x; 
	this.layer.pixelTop += y; 
} 
xLayer.prototype.show		= function() 	{ this.layer.visibility = "visible"; } 
xLayer.prototype.hide		= function() 	{ this.layer.visibility = "hidden"; } 
xLayer.prototype.setzIndex	= function(z)	{ this.layer.zIndex = z; } 
xLayer.prototype.setBgColor	= function(color) { this.layer.backgroundColor = color==null?'transparent':color; } 
xLayer.prototype.setBgImage	= function(image) { this.layer.backgroundImage = "url("+image+")"; } 
xLayer.prototype.setContent   = function(xHtml)	{ this.content.innerHTML=xHtml; } 
xLayer.prototype.getX		= function() 	{ return this.layer.pixelLeft; } 
xLayer.prototype.getY		= function() 	{ return this.layer.pixelTop; } 
xLayer.prototype.getWidth	= function() 	{ return this.layer.pixelWidth; } 
xLayer.prototype.getHeight	= function() 	{ return this.layer.pixelHeight; } 
xLayer.prototype.getzIndex	= function()	{ return this.layer.zIndex; } 
xLayer.prototype.isVisible	= function()	{ return this.layer.visibility == "visible"; } 
xLayer.prototype.clip		= function(x1,y1, x2,y2) 
{ 
	this.layer.clip="rect("+y1+" "+x2+" "+y2+" "+x1+")"; 
	this.layer.pixelWidth=x2; 
	this.layer.pixelHeight=y2; 
	this.layer.overflow="hidden"; 
}
xLayer.prototype.addEventHandler = function(eventName, handler) 
{
	var xl = this;
	this.content[eventName] = function() 
	{ 
            var e = window.event;
		e.layerX = e.offsetX;
		e.layerY = e.offsetY;
            e.cancelBubble = true;
            return handler(xl, e); 
	}
}
xLayer.prototype.removeEventHandler = function(eventName) 
{
	this.content[eventName] = null;
}
 /*** END IE ***/ 
} 
else if (document.getElementById) 
{
/*** W3C (NS 6) ***/ 
xLayer.prototype.moveTo = function(x,y)
{
	this.layer.left = x+"px";
	this.layer.top = y+"px";
}
xLayer.prototype.moveBy 	= function(x,y) 	{ this.moveTo(this.getX()+x, this.getY()+y); } 
xLayer.prototype.show		= function() 	{ this.layer.visibility = "visible"; }
xLayer.prototype.hide		= function() 	{ this.layer.visibility = "hidden"; }
xLayer.prototype.setzIndex	= function(z)	{ this.layer.zIndex = z; }
xLayer.prototype.setBgColor	= function(color) { this.layer.backgroundColor = color==null?'transparent':color; }
xLayer.prototype.setBgImage	= function(image) { this.layer.backgroundImage = "url("+image+")"; }
xLayer.prototype.getX		= function() 	{ return parseInt(this.layer.left); }
xLayer.prototype.getY		= function() 	{ return parseInt(this.layer.top); }
xLayer.prototype.getWidth	= function() 	{ return parseInt(this.layer.width); }
xLayer.prototype.getHeight	= function() 	{ return parseInt(this.layer.height); }
xLayer.prototype.getzIndex	= function()	{ return this.layer.zIndex; }
xLayer.prototype.isVisible	= function()	{ return this.layer.visibility == "visible"; }
xLayer.prototype.clip		= function(x1,y1, x2,y2)
{
	this.layer.clip="rect("+y1+" "+x2+" "+y2+" "+x1+")";
	this.layer.width=x2 + "px";
	this.layer.height=y2+ "px";
	this.layer.overflow="hidden";
}
xLayer.prototype.addEventHandler = function(eventName, handler) 
{
	var xl = this;
	this.content[eventName] = function(e) 
	{ 
            e.cancelBubble = true;
            return handler(xl, e);
	}
}
xLayer.prototype.removeEventHandler = function(eventName) 
{
	delete this.content[eventName];
}
xLayer.prototype.setContent   = function(xHtml)
{
	var newRange   = document.createRange();
	newRange.setStartBefore(this.content);

	while (this.content.hasChildNodes())
		this.content.removeChild(this.content.lastChild);

	var strFrag    = newRange.createContextualFragment(xHtml);	
	this.content.appendChild(strFrag);
}

} else
{
xLayer.prototype.moveTo 	= function(x,y) 	{  }
xLayer.prototype.moveBy 	= function(x,y) 	{  }
xLayer.prototype.show 		= function() 	{  }
xLayer.prototype.hide 		= function() 	{  }
xLayer.prototype.setzIndex	= function(z) {  }
xLayer.prototype.setBgColor 	= function(color) {  }
xLayer.prototype.setBgImage 	= function(image) {  }
xLayer.prototype.getX 		= function() 	{ return 0; }
xLayer.prototype.getY 		= function() 	{ return 0; }
xLayer.prototype.getWidth 	= function() 	{ return 0; }
xLayer.prototype.getHeight 	= function() 	{ return 0; }
xLayer.prototype.getzIndex	= function()	{ return 0; }
xLayer.prototype.isVisible 	= function() 	{ return false; }
xlayer.prototype.setContent   = function(xHtml) { }
}

/*** End  - xLayer - a cross browser layer object by www.Roy.Whittle.com ***/ 