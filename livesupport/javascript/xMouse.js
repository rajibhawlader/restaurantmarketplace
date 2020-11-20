/******************************************************************* 
* 
* File    : xMouse.js 
* 
* Created : 2000/07/15 
* 
* Author  : Roy Whittle  (Roy@Whittle.com) www.Roy.Whittle.com 
* 
* Purpose : To create a cross browser "Mouse" object.
*		This library will allow scripts to query the current x,y
*		coordinates of the browser
* 
* History 
* Date         Version        Description 
* 2000-06-08	1.0		Initial version
* 2000-07-31	1.1		Some fixing up.
* 2000-10-14	1.2		Now works in IE 5.0+
***********************************************************************/
/*** Create an object able to hold the x,y coordinates ***/
function xMouse() 
{ 	this.X = 0;
	this.Y = 0;

	if(navigator.appName.indexOf("Netscape") != -1)
	{
		this.getMouseXY = function (evnt) 
		{
			document.ml.X=evnt.pageX;
			document.ml.Y=evnt.pageY;
		}

		window.captureEvents(Event.MOUSEMOVE);
		window.onmousemove = this.getMouseXY;

		document.ml = this;
	}
	else if(document.all)
	{
		if(navigator.appVersion.indexOf("MSIE 5.") != -1)
			this.getMouseXY = function ()
			{
				document.ml.X = event.x + document.body.scrollLeft;
				document.ml.Y = event.y + document.body.scrollTop;
			}
		else
			this.getMouseXY = function ()
			{
				document.ml.X = event.x;
				document.ml.Y = event.y;
			}

		document.ml = this;
		document.onmousemove = this.getMouseXY;
	} 
	return(this);
 
}
/*** End  - xMouse a cross browser "Mouse" object by www.Roy.Whittle.com ***/ 