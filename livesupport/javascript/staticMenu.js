var myBrowser;
var staticMenu;

function CreateStaticMenu(theObj, x, y)
{
	myBrowser = new xBrowser();

	staticMenu = new xLayerFromObj(theObj);
	staticMenu.baseX = x;
	staticMenu.baseY = y;
	staticMenu.x = x;
	staticMenu.y = y;
	staticMenu.moveTo(x,y);
	staticMenu.show();
	setInterval("ani()", 20);
}
function ani()
{
	var b = staticMenu;
	var targetX = myBrowser.getMinX() + b.baseX;
	var targetY = myBrowser.getMinY() + b.baseY;
	var dx = (targetX - b.x)/8;
	var dy = (targetY - b.y)/8;
	b.x += dx;
	b.y += dy;

	b.moveTo(b.x, b.y);
}

function CreateStaticMenu2(theObj, x, y)
{
	myBrowser = new xBrowser();

	staticMenu2 = new xLayerFromObj(theObj);
	staticMenu2.baseX = x;
	staticMenu2.baseY = y;
	staticMenu2.x = x;
	staticMenu2.y = y;
	staticMenu2.moveTo(x,y);
	staticMenu2.show();
	setInterval("ani2()", 20);
}
function ani2()
{
	var b = staticMenu2;
	var targetX = myBrowser.getMinX() + b.baseX;
	var targetY = myBrowser.getMinY() + b.baseY;
	var dx = (targetX - b.x)/8;
	var dy = (targetY - b.y)/8;
	b.x += dx;
	b.y += dy;

	b.moveTo(b.x, b.y);
}
