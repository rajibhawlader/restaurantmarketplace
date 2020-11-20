 
// detect browser 
NS4 = (document.layers) ? 1 : 0; 
IE4 = (document.all) ? 1 : 0; 
// W3C stands for the W3C standard 
W3C = (document.getElementById) ? 1 : 0;     

function makeVisible ( name ) { 
  var ele; 

  if ( W3C ) { 
    ele = document.getElementById(name); 
  } else if ( NS4 ) { 
    ele = document.layers[name]; 
  } else { // IE4 
    ele = document.all[name]; 
  } 

  if ( NS4 ) { 
    ele.visibility = "show"; 
  } else {  // IE4 & W3C & Mozilla 
    ele.style.visibility = "visible"; 
    ele.style.display = "inline"; 
  } 
} 

function makeInvisible ( name ) { 
  if (W3C) { 
    document.getElementById(name).style.visibility = "hidden"; 
    document.getElementById(name).style.display = "none"; 
  } else if (NS4) { 
    document.layers[name].visibility = "hide"; 
  } else { 
    document.all[name].style.visibility = "hidden"; 
    document.all[name].style.style.display = "none"; 
  } 
} 