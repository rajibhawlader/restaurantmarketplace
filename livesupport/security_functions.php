<?php
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Adds Slashes to sent value.
  *
  * @param string $buffer the text to add slashes to.
  *
  * @return string $buffer the converted string.
  */
function my_addslashes($what){
  
  if(is_array($what)){
     while (list($key, $val) = each($what)) {
       $what[$key] = my_addslashes($val);
     }
     return $what;
  } else {   	
   if (!(get_magic_quotes_gpc()))
    return addslashes($what);
   else
    return $what;
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Removes Slashes from sent value.
  *
  * @param string $buffer the text to remove slashes from.
  *
  * @return string $buffer the converted string.
  */
function my_stripslashes($what){
	if(is_array($what)){
     while (list($key, $val) = each($what)) {
       $what[$key] = my_stripslashes($val);
     }
     return $what;
  } else {    
    if (!(get_magic_quotes_gpc())) 
       return $what;
    else
       return stripslashes($what);
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Reads all values from the Request Object either adding slashes or 
  * Removing them based on preference.
  *
  * @param string $buffer the text to remove slashes from.
  *
  * @return string $buffer the converted string.
  */
function parse_incoming($addslashes=false){
   global $_REQUEST;

   if($addslashes)
      return my_addslashes($_REQUEST);               
   else 
      return my_stripslashes($_REQUEST);     
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes a SQL insert value "safe" by escaping quotes and optionally  
  * casting as a integer if needed.
  *
  * @param string $buffer the text to make sql safe.
  * @param bool $addslashes add slashes to string if not already done.
  * @param bool $numeric  cast value as int.
  * @param int $maxsize  max size of string 0 is unlimited.
  *
  * @return string $buffer the converted string.
  */
function filter_sql($what,$addslashes=true,$numeric=false,$maxsize=0){	 
	 
	 if($addslashes)
	   $what = addslashes($what);
	 else
	   $what = addslashes(stripslashes($what));
	   
	 if($numeric)
	   $what = intval($what);
	   
	 if($maxsize!=0)
	   $what = substr($what,0,$maxsize);
   
	 $what = str_replace("`","",$what);
	 
   // un-comment the following lines for compatability with Microsoft SQL server:
   // may cause problems with txt-db-api if uncommented...
	  //$what = str_replace("\'", "''", $what);
	  //$what = str_replace("\"", "\"\"", $what);
	 
	 return $what;	   
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Checks to make sure a e-mail is vaild...
  *
  * @param string $email - email address to check.
  *
  * @return bool true if valid false otherwise...
  */
function good_emailaddress($email){		
if (!(preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email)))
	return false;	
 else
  return true;   
}
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes a string insert value "safe" by escaping HTML chars 
  *
  * @param string $buffer the text to make sql safe.
  *
  * @return string $buffer the converted string.
  */
function filter_html($what){      
  
 
   ///.. BASIC ASCII Entities with new Entity Names 
     $what = str_replace( "&#"           , "(^)-(^)"       , $what ); 
     $what = str_replace( "&"            , "&amp;"         , $what ); 
     $what = str_replace( ">"            , "&gt;"          , $what ); 
     $what = str_replace( "<"            , "&lt;"          , $what ); 
     $what = ereg_replace( "\""          , "&quot;"        , $what ); 
     $what = str_replace( "!"            , "!"         , $what ); 
     $what = str_replace( "'"            , "'"         , $what ); 
     $what = str_replace( "(^)-(^)"       , "&#"            , $what ); 
     $what = str_replace("`"             ,""               , $what ); 
     $what = ereg_replace("\n"        , "<br>"          , $what ); 
     $what = ereg_replace("\r"        , ""              , $what );        

     ///.. ISO 8859-1 Symbol Entities 
   $what = str_replace("¡"            , "&iexcl;"      , $what ); 
   $what = str_replace("¤"            , "&curren;"      , $what ); 
   $what = str_replace("¢"            , "&cent;"      , $what );  
   $what = str_replace("£"            , "&pound;"      , $what ); 
   $what = str_replace("€"            , "&euro;"      , $what );    
   $what = str_replace("¥"            , "&yen;"      , $what );    
   $what = str_replace("¦"            , "&brvbar;"      , $what ); 
   $what = str_replace("§"            , "&sect;"      , $what ); 
   $what = str_replace("©"            , "&copy;"      , $what );          
   $what = str_replace("¿"            , "&iquest;"      , $what ); 
   $what = str_replace("¶"            , "&para;"      , $what ); 
        
   ///.. ISO 8859-1 Character Entities 
   $what = str_replace("À"            , "&Agrave;"      , $what );      
   $what = str_replace("Á"            , "&Aacute;"      , $what );    
   $what = str_replace("Â"            , "&Acirc;"      , $what );     
   $what = str_replace("Ä"            , "&Auml;"      , $what );  
   $what = str_replace("Å"            , "&Aring;"      , $what );  
   $what = str_replace("Æ"            , "&AElig;"      , $what );        
   $what = str_replace("Ç"            , "&Ccedil;"      , $what ); 
   $what = str_replace("È"            , "&Egrave;"      , $what );    
   $what = str_replace("É"            , "&Eacute;"      , $what );  
   $what = str_replace("Ê"            , "&Ecirc;"      , $what );  
   $what = str_replace("Ë"            , "&Euml;"      , $what );      
   $what = str_replace("Ì"            , "&Igrave;"      , $what ); 
   $what = str_replace("Î"            , "&Icirc;"      , $what );      
   $what = str_replace("Ï"            , "&Iuml;"      , $what );    
   $what = str_replace("Ð"            , "&ETH;"      , $what ); 
   $what = str_replace("Ñ"            , "&Ntilde;"      , $what ); 
   $what = str_replace("Ò"            , "&Ograve;"      , $what ); 
   $what = str_replace("Ó"            , "&Oacute;"      , $what );    
   $what = str_replace("Ô"            , "&Ocirc;"      , $what );  
   $what = str_replace("Õ"            , "&Otilde;"      , $what );  
   $what = str_replace("Ö"            , "&Ouml;"      , $what );    
   $what = str_replace("Ø"            , "&Oslash;"      , $what );  
   $what = str_replace("Ù"            , "&Ugrave;"      , $what );    
   $what = str_replace("Ú"            , "&Uacute;"      , $what ); 
   $what = str_replace("Û"            , "&Ucirc;"      , $what ); 
   $what = str_replace("Ü"            , "&Uuml;"      , $what );  
   $what = str_replace("Ý"            , "&Yacute;"      , $what ); 
   $what = str_replace("Þ"            , "&THORN;"      , $what ); 
   $what = str_replace("ß"            , "&szlig;"      , $what ); 
   $what = str_replace("à"            , "&agrave;"      , $what );      
   $what = str_replace("á"            , "&aacute;"     , $what );    
   $what = str_replace("â"            , "&acirc;"      , $what );        
   $what = str_replace("à"            , "&aacute;"      , $what );  
   $what = str_replace("ä"            , "&auml;"      , $what ); 
   $what = str_replace("å"            , "&aring;"      , $what ); 
   $what = str_replace("æ"            , "&aelig;"      , $what );  
   $what = str_replace("ç"            , "&ccedil;"      , $what ); 
   $what = str_replace("è"            , "&egrave;"      , $what ); 
   $what = str_replace("é"            , "&eacute;"      , $what );  
   $what = str_replace("ê"            , "&ecirc;"      , $what ); 
   $what = str_replace("ë"            , "&euml;"      , $what );  
   $what = str_replace("ì"            , "&igrave;"      , $what );    
   $what = str_replace("í"            , "&iacute;"      , $what ); 
   $what = str_replace("î"            , "&icirc;"      , $what );      
   $what = str_replace("ï"            , "&iuml;"      , $what );  
   $what = str_replace("ð"            , "&eth;"      , $what ); 
   $what = str_replace("ñ"            , "&ntilde;"      , $what );    
   $what = str_replace("ò"            , "&ograve;"      , $what );    
   $what = str_replace("ó"            , "&oacute;"      , $what ); 
   $what = str_replace("ô"            , "&ocirc;"      , $what ); 
   $what = str_replace("õ"            , "&otilde;"      , $what );    
   $what = str_replace("ö"            , "&ouml;"      , $what ); 
   $what = str_replace("ø"            , "&oslash;"      , $what );  
   $what = str_replace("ù"            , "&ugrave;"      , $what );  
   $what = str_replace("ú"            , "&uacute;"      , $what ); 
   $what = str_replace("û"            , "&ucirc;"      , $what );      
   $what = str_replace("ü"            , "&uuml;"      , $what );      
   $what = str_replace("ý"            , "&yacute;"      , $what ); 
   $what = str_replace("þ"            , "&thorn;"      , $what ); 
   $what = str_replace("ÿ"            , "&yuml;"      , $what );    

   ///.. ISO 8859-1 Other Entities 
   $what = str_replace("Œ"            , "&OElig;"      , $what ); 
   $what = str_replace("œ"            , "&oelig;"      , $what ); 
   $what = str_replace("Š"            , "&Scaron;"      , $what ); 
   $what = str_replace("š"            , "&scaron;"      , $what ); 
   $what = str_replace("˜"            , "&tilde;"      , $what );              
   $what = str_replace("‘"            , "&lsquo;"      , $what ); 
   $what = str_replace("’"            , "&rsquo;"      , $what ); 
   $what = str_replace("‚"            , "&sbquo;"      , $what );  

   // for some really strange reason this is replacing all characters:
   //$what = str_replace("Ã"            , "&Atilde;"      , $what );  
   //$what = str_replace("ã"            , "&atilde;"      , $what );          
  
     return $what;        
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes sure string is only alpha numeric. 
  *
  * @param string $buffer the text to make sql safe.
  *
  * @return string $buffer the converted string.
  */
function alphanumeric($string){
  $string =  ereg_Replace("([^a-zA-Z0-9])*", "", $string);
  return $string;
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes sure string is filterd before sending to system command 
  * no piping, passing possible environment variables ($),
  * seperate commands, nested execution, file redirection,
  * background processing, special commands (backspace, etc.), quotes
  * newlines, or some other special characters
  *
  * @param string $buffer the text to make what safe
  *
  * @return string $buffer the converted string.
  */
function filter_what($string){
	return escapeshellwhat($string);
  //$pattern = '/(;|\||`|>|<|&|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; 
  //$string = preg_replace($pattern, '', $string);
  //$string = '"'.preg_replace('/\$/', '\\\$', $string).'"'; //make sure this is only interpretted as ONE argument
  //return $string;
  
}
?>