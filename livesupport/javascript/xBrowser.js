/*******************************************************************

*

* File    : xBrowser.js

*

* Created : 2000/07/15

*

* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com

*

* Purpose : To create a cross browser "Browser" object.
*		This library will allow scripts to query parameters
*		about the current browser window.
*

* History

* Date         Version        Description

*

* 2000-06-08	1.0		Initial version
* 2000-08-31	1.1		Made all parameters function calls
* 2000-10-14	1.2		Made compatible with NS6
***********************************************************************/



function xBrowser()

{

	if(navigator.appName.indexOf("Netscape") != -1)
	{
		this.getCanvasWidth	= function() {return innerWidth;}
		this.getCanvasHeight	= function() {return innerHeight;}
		this.getWindowWidth 	= function() {return outerWidth;}
		this.getWindowHeight	= function() {return outerHeight;}
		this.getScreenWidth 	= function() {return screen.width;}
		this.getScreenHeight	= function() {return screen.height;}
		this.getMinX		= function() {return(pageXOffset);}
		this.getMinY		= function() {return(pageYOffset);}
		this.getMaxX		= function() {return(pageXOffset+innerWidth);}
		this.getMaxY		= function() {return(pageYOffset+innerHeight);}

	}

	else

	if(document.all)

	{
		this.getCanvasWidth	= function() {return document.body.clientWidth;}
		this.getCanvasHeight	= function() {return document.body.clientHeight;}
		this.getScreenWidth	= function() {return screen.width;}
		this.getScreenHeight	= function() {return screen.height;}
		this.getMinX		= function() {return(document.body.scrollLeft);}
		this.getMinY		= function() {return(document.body.scrollTop);}
		this.getMaxX		= function() {
			return(document.body.scrollLeft
				+document.body.clientWidth);
		}
		this.getMaxY		= function() {
				return(document.body.scrollTop
					+document.body.clientHeight);
		}
	}

	return(this);

}

/*** End  - xBrowser a cross browser "Browser" object by www.Roy.Whittle.com ***/

