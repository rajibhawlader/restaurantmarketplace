var xmlhttp = false; 
var XMLHTTP_supported = false;

 
function gettHTTPreqobj(){
	try { 
	xmlhttp = new XMLHttpRequest(); 
 } catch (e1) { 
 	 try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
   } catch (e2) { 
     try { 
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
     } catch (e3) { 
    xmlhttp = false; 
   } 
  } 
 }
 return xmlhttp;
}
 
function loadXMLHTTP() { 
     // account for cache..
     randu=Math.round(Math.random()*99);
     // load a test page page:
     loadOK('xmlhttp.php?whattodo=ping&rand='+ randu); 
} 

function loadOK(fragment_url) { 
	  xmlhttp = gettHTTPreqobj();
    xmlhttp.open("GET", fragment_url, true); 
    xmlhttp.onreadystatechange = function() { 
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
       isok = xmlhttp.responseText;
       if(isok == "OK")
          XMLHTTP_supported = true;   
          checkXMLHTTP();
     } 
    } 
    try { xmlhttp.send(null); } catch(whocares){}
}


 // XMLHTTP ----------------------------------------------------------------- 
 function oXMLHTTPStateHandler() { 
     // only if req shows "loaded" 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) {         // 4="completed" 
          if( oXMLHTTP.status==200 ) {         // 'OK Operation successful                
               try { 
                 resultingtext = oXMLHTTP.responseText;
               } catch(e) { 
                 resultingtext ="error=1;";
               }
               ExecRes(unescape(resultingtext)); 
               delete oXMLHTTP; 
               oXMLHTTP=false;
               //DEBUG:SetStatus('Response received... Now Processing',0); 
            } else { 
            	return false;
               //DEBUG:alert( "There was a problem receiving the data.\n" 
               //         +"Please wait a few moments and try again.\n" 
               //         +"If the problem persists, please contact us.\n" 
               //  +oXMLHTTP.getAllResponseHeaders() 
               //       ); 
             }   
           }
         } 
      } 
      
      // Submit POST data to server and retrieve results 
      function PostForm(sURL, sPostData) { 
         oXMLHTTP = gettHTTPreqobj(); 
         if( typeof(oXMLHTTP)!="object" ) return false; 
         
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { 
            oXMLHTTP.open("POST", sURL, true); 
         } catch(er) { 
            //DEBUG: alert( "Error opening XML channel\n"+er.description ); 
            return false; 
         }    
         oXMLHTTP.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
         try { oXMLHTTP.send(sPostData); } catch(whocares){}
         return true; 
      }  
      
      // Submit GET data to server and retrieve results 
      function GETForm(sURL) { 
         oXMLHTTP = gettHTTPreqobj();          
         if( typeof(oXMLHTTP)!="object" ) return false;          
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { 
            oXMLHTTP.open("GET", sURL, true); 
         } catch(er) { 
            //DEBUG: alert( "Error opening XML channel\n"+er.description ); 
            return false; 
         }    
         try { oXMLHTTP.send(null); } catch(whocares){}
         return true; 
      }

// getting started:
xmlhttp = gettHTTPreqobj();
          