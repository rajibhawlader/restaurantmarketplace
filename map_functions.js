/* [listing 2-3] */
var centerLatitude = 37.818361;
var centerLongitude = -122.478032;
var startZoom = 13;

var map;

function init()
{
//alert("gmap");
    if (GBrowserIsCompatible()) {	
        map = new GMap2(document.getElementById("googlemap"));
        var location = new GLatLng(centerLatitude, centerLongitude);
        map.setCenter(location, startZoom);
    }
}

window.onload = init;
window.onunload = GUnload;

/* [listing 2-3 end] */