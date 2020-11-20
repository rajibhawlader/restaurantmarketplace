function makeRequest(url,id) {

var http_request = false;
if (window.XMLHttpRequest) { // Mozilla, Safari, ...

http_request = new XMLHttpRequest();
if (http_request.overrideMimeType) {
http_request.overrideMimeType('text/xml');
// See note below about this line
}
} else if (window.ActiveXObject) { // IE

try {
http_request = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
http_request = new ActiveXObject("Microsoft.XMLHTTP");
} catch (e) {}
}
}

if (!http_request) {
alert('Error creating XMLHttpRequest()');
return false;
}

http_request.onreadystatechange = function() {
var e = document.getElementById(id)

if (http_request.readyState == 4 && http_request.status == 200) { 
e.innerHTML = http_request.responseText; 

}
else {
e.innerHTML = "Loading..."
//e.innerHTML ='<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="middle" align="center" height="350"><img src="images/loading_2.gif" /></td></tr></table>';
}
};
http_request.open('GET', url, true);
http_request.send(null);

}
