<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: livehelp@craftysyntax.com
//         Copyright (C) 2003-2008 Eric Gerdes   (http://www.craftysyntax.com )
// ----------------------------------------------------------------------------
// Please check http://www.craftysyntax.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];
if($isadminsetting != "Y"){
 print $lang['txt41'];
if(!($serversession))
   $mydatabase->close_connect();
 exit;	
}
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action']=""; }
 
if($UNTRUSTED['action'] == "update"){
	
	 if(!(ereg("http",$UNTRUSTED['newwebpath'])))
	   $UNTRUSTED['newwebpath'] = "http://" . $UNTRUSTED['newwebpath'];
	 if(!(ereg("http",$UNTRUSTED['news_webpath'])))
	   $UNTRUSTED['news_webpath'] = "https://" . $UNTRUSTED['news_webpath'];
   if($UNTRUSTED['news_webpath'] == "https://")
      $UNTRUSTED['news_webpath'] = $UNTRUSTED['newwebpath'];
	   	   
   $lastchar = substr($UNTRUSTED['newwebpath'],-1);
   if($lastchar != "/"){ $UNTRUSTED['newwebpath'] .= "/"; }
   $lastchar = substr($UNTRUSTED['news_webpath'],-1);
   if($lastchar != "/"){ $UNTRUSTED['news_webpath'] .= "/"; }    
   if(empty($UNTRUSTED['newmaxexe']))
   	 $UNTRUSTED['newmaxexe'] = 90;   	 
   if(empty($UNTRUSTED['newrefreshrate']))
   	 $UNTRUSTED['newrefreshrate'] = 1;    
   if( ($CSLH_Config['chatmode'] != $UNTRUSTED['new_chatmode']))    
       $newshow_typing = "Y";       
   if($UNTRUSTED['ppbutton'] == "Y")  { $pbutt = "Y"; } else { $pbutt = "N"; }
   if($UNTRUSTED['helpbutton'] == "Y")  { $hbutt = "Y"; } else { $hbutt = "N"; }
   $everythingelse = $pbutt . $hbutt;
   
   $query = "UPDATE livehelp_config 
              SET 
                refreshrate='". filter_sql($UNTRUSTED['newrefreshrate'])."',
                maxexe=".intval($UNTRUSTED['newmaxexe']).",
                speaklanguage='".filter_sql($UNTRUSTED['newspeaklanguage'])."',
                s_webpath='".filter_sql($UNTRUSTED['news_webpath'])."',
                webpath='".filter_sql($UNTRUSTED['newwebpath'])."',
                show_typing='".filter_sql($UNTRUSTED['newshow_typing'])."',
                offset=".intval($UNTRUSTED['newoffset']).",
                chatmode='".filter_sql($UNTRUSTED['new_chatmode'])."',
                ignoreips='".filter_sql($UNTRUSTED['ignoreips'])."',
                tracking='".filter_sql($UNTRUSTED['tracking'])."',
                colorscheme='".filter_sql($UNTRUSTED['colorscheme'])."',
                gethostnames='".filter_sql($UNTRUSTED['gethostnames'])."',
                showgames='".filter_sql($UNTRUSTED['showgames'])."',
                usertracking='".filter_sql($UNTRUSTED['usertracking'])."',
                showdirectory='".filter_sql($UNTRUSTED['showdirectory'])."',  
                resetbutton='".filter_sql($UNTRUSTED['resetbutton'])."',  
                reftracking='".filter_sql($UNTRUSTED['reftracking'])."',
                keywordtrack='".filter_sql($UNTRUSTED['keywordtrack'])."', 
                rememberusers='".filter_sql($UNTRUSTED['rememberusers'])."',   
                smtp_host='".filter_sql($UNTRUSTED['new_smtp_host'])."', 
                smtp_username='".filter_sql($UNTRUSTED['new_smtp_username'])."', 
                smtp_password='".filter_sql($UNTRUSTED['new_smtp_password'])."', 
                owner_email='".filter_sql($UNTRUSTED['new_owner_email'])."', 
                everythingelse='".filter_sql($everythingelse)."',                                                 
                site_title='".filter_sql($UNTRUSTED['newsite_title'])."'";
    $mydatabase->query($query);
    print "<font color=007700 size=+2>" . $lang['txt63'] . "</font>";  
    print "<SCRIPT type=\"text/javascript\"> window.parent.location=\"admin.php?page=mastersettings.php&tab=settings\"; </SCRIPT>";
    print "<a href=admin.php?page=mastersettings.php&tab=settings>".$lang['txt186']."</a>";
    exit;   
 }
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?> ><center>
<?php
$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->query($query);
$data = $data->fetchRow(DB_FETCHMODE_ASSOC);
$offset = $data['offset'];

if($data['use_flush'] == "YES"){
$continuous = " SELECTED ";
$refresh = "";
} else {
$continuous = "";
$refresh = " SELECTED ";	
}

if($data['show_typing'] == "N"){
  $typing_n = " CHECKED ";
  $typing_y = "";	 
} else {
  $typing_y = " CHECKED ";
  $typing_n = "";
}

if($data['gethostnames'] == "N"){
 $gethostnames_n = " CHECKED ";	
 $gethostnames_y = "  ";
} else {
 $gethostnames_y = " CHECKED ";	
 $gethostnames_n = "  ";
}

if($data['tracking'] == "N"){
 $tracking_n = " CHECKED ";	
 $tracking_y = "  ";
} else {
 $tracking_y = " CHECKED ";	
 $tracking_n = "  ";
}

if($data['showgames'] == "N"){
 $showgames_n = " CHECKED ";	
 $showgames_y = "  ";
} else {
 $showgames_y = " CHECKED ";	
 $showgames_n = "  ";
}

if($data['showdirectory'] == "N"){
 $showdirectory_n = " CHECKED ";	
 $showdirectory_y = "  ";
} else {
 $showdirectory_y = " CHECKED ";	
 $showdirectory_n = "  ";
} 

if($data['usertracking'] == "N"){
 $usertracking_n = " CHECKED ";	
 $usertracking_y = "  ";
} else {
 $usertracking_y = " CHECKED ";	
 $usertracking_n = "  ";
} 

if($data['resetbutton'] == "N"){
 $resetbutton_n = " CHECKED ";	
 $resetbutton_y = "  ";
} else {
 $resetbutton_y = " CHECKED ";	
 $resetbutton_n = "  ";
} 

if($data['keywordtrack'] == "N"){
 $keywordtrack_n = " CHECKED ";	
 $keywordtrack_y = "  ";
} else {
 $keywordtrack_y = " CHECKED ";	
 $keywordtrack_n = "  ";
} 

if($data['reftracking'] == "N"){
 $reftracking_n = " CHECKED ";	
 $reftracking_y = "  ";
} else {
 $reftracking_y = " CHECKED ";	
 $reftracking_n = "  ";
}     

if($data['rememberusers'] == "N"){
 $rememberusers_n = " CHECKED ";	
 $rememberusers_y = "  ";
} else {
 $rememberusers_y = " CHECKED ";	
 $rememberusers_n = "  ";
} 

if(substr($CSLH_Config['everythingelse'],0,1) == "N"){
 $ppbutton_n = " CHECKED ";	
 $ppbutton_y = "  ";
} else {
 $ppbutton_y = " CHECKED ";	
 $ppbutton_n = "  ";
} 

if(substr($CSLH_Config['everythingelse'],1,1) == "N"){
 $helpbutton_n = " CHECKED ";	
 $helpbutton_y = "  ";
} else {
 $helpbutton_y = " CHECKED ";	
 $helpbutton_n = "  ";
} 

?>
<table width=600<tr bgcolor=DDDDDD><td>
<b><?php echo $lang['settings']; ?>:</b></td></tr>
<tr bgcolor=FFFFFF><td>
<form action=mastersettings.php method=post name=configform>
<input type=hidden name=action value=update>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt74']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt75']; ?>
</td></tr></table>
<b>HTTP:</b><input type=text name=newwebpath value="<?php echo $CSLH_Config['webpath']; ?>" size=55><br>
<b>HTTPS:</b><input type=text name=news_webpath value="<?php echo $CSLH_Config['s_webpath']; ?>" size=55><br>


<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b>SMTP Settings:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
 If the following are set Crafty Syntax will use a socket connection
 for SMTP rather then the php mail() function. Use this if you
 are having trouble sending mail in the leave a message section
 of the site. Leave "SMTP Host" blank to use built in php mail() 
 function.
</td></tr></table>
<table>
	<tr><td><b>SMTP Host:</b></td><td><input type=text name=new_smtp_host value="<?php echo $CSLH_Config['smtp_host']; ?>" size=45></td></tr>
	<tr><td><b>SMTP username:</b></td><td><input type=text name=new_smtp_username value="<?php echo $CSLH_Config['smtp_username']; ?>" size=35></td></tr>
	<tr><td><b>SMTP password:</b></td><td><input type=password name=new_smtp_password value="<?php echo $CSLH_Config['smtp_password']; ?>" size=35></td></tr>
	<tr><td><b>SMTP default e-mail:</b></td><td><input type=text name=new_owner_email value="<?php echo $CSLH_Config['owner_email']; ?>" size=45></td></tr>
</table>
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt187']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt188']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b><input type=radio value=Y name=reftracking <?php echo $reftracking_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=reftracking <?php echo $reftracking_n; ?> > <?php echo $lang['NO']; ?><br>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt189']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php $lang['txt190']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=tracking <?php echo $tracking_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=tracking <?php echo $tracking_n; ?> > <?php echo $lang['NO']; ?><br>
  
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt191']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt192']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=usertracking <?php echo $usertracking_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=usertracking <?php echo $usertracking_n; ?> > <?php echo $lang['NO']; ?><br>
<?php echo $lang['txt210']; ?><br>
<b><?php echo $lang['txt211']; ?>:</b> <input type=radio value=Y name=rememberusers <?php echo $rememberusers_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=rememberusers <?php echo $rememberusers_n; ?> > <?php echo $lang['NO']; ?><br>


<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt193']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt194']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=keywordtrack <?php echo $keywordtrack_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=keywordtrack <?php echo $keywordtrack_n; ?> > <?php echo $lang['NO']; ?><br>
   
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt195']?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt196']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=gethostnames <?php echo $gethostnames_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=gethostnames <?php echo $gethostnames_n; ?> > <?php echo $lang['NO']; ?><br>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<img src=images/games.gif width=25 height=25><b><?php echo $lang['txt197']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt198']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=showgames <?php echo $showgames_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=showgames <?php echo $showgames_n; ?> > <?php echo $lang['NO']; ?><br>
 
 <table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<img src=images/directory.gif width=25 height=25><b><?php echo $lang['txt199']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt200']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=showdirectory <?php echo $showdirectory_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=showdirectory <?php echo $showdirectory_n; ?> > <?php echo $lang['NO']; ?><br>
 
 <table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<img src=images/reset.gif width=25 height=25><b><?php echo $lang['txt201']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt202']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=resetbutton <?php echo $resetbutton_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=resetbutton <?php echo $resetbutton_n; ?> > <?php echo $lang['NO']; ?><br> 

 <table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<img src=images/pp.gif width=25 height=25><b><?php echo $lang['txt225']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt226']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=ppbutton <?php echo $ppbutton_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=ppbutton <?php echo $ppbutton_n; ?> > <?php echo $lang['NO']; ?><br>
 

 <table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<img src=images/help.gif width=25 height=25><b><?php echo $lang['txt227']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt228']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=helpbutton <?php echo $helpbutton_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=helpbutton <?php echo $helpbutton_n; ?> > <?php echo $lang['NO']; ?><br>


<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt203']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt204']; ?>:
</td></tr></table>
<b><?php echo $lang['color']; ?>:</b>:
<select name=colorscheme>
 <option value="yellow" <?php if($CSLH_Config['colorscheme']=="yellow") print " SELECTED "; ?>><?php echo $lang['Yellow']; ?></option>
 <option value="white" <?php if($CSLH_Config['colorscheme']=="white") print " SELECTED "; ?>><?php echo $lang['White']; ?></option>
 <option value="blue" <?php if($CSLH_Config['colorscheme']=="blue") print " SELECTED "; ?>><?php echo $lang['Blue']; ?></option>
 <option value="brown" <?php if($CSLH_Config['colorscheme']=="brown") print " SELECTED "; ?>><?php echo $lang['Brown']; ?></option> 
</select>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['Language']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt76']; ?>
</td></tr></table>
<b><?php echo $lang['Language']; ?>:</b>
<select name=newspeaklanguage>
<?php
// get list of installed langages..
$dir = "lang" . C_DIR;
if($handle=opendir($dir)){
	$i=0;
	while ( ($i<100) && (false !== ($file = readdir($handle)))) {
	 $i++;
	 if (ereg("lang-",$file))
		{
		if ( (is_file("$dir".C_DIR."$file") && ($file!="lang-.php")))
			{
			$language = str_replace("lang-","",$file);
			$language = str_replace(".php","",$language);
			?><option value=<?php echo $language; ?> <?php if ($CSLH_Config['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language; ?> </option><?php
			}
		}
	}
// no list guess...
} else {
      $languages = array("Dutch","English","English_uk","French","German","Italian","Portuguese_Brazilian","Spanish","Swedish");
			for($i=0;$i<count($languages); $i++){
  			$language = $languages[$i];
  			?><option value=<?php echo $language ?> <?php if ($CSLH_Config['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language ?> </option><?php		
  		}	
}		
?>
</select>
 
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b>Chat Type:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
Chat type is how the chat is pushed to the client. There are 3 different
ways Crafty Syntax can do this. 1) AJAX, 2) flushing output buffers to
the client, and 3) refreshing the page. AJAX is by far the most prefered
way to do this and will be the ONLY way in version 3.x of Crafty Syntax.
Using flush and refresh methods of chat have been deprecated in 2.14.x because
AJAX is supported by most browsers.. If ajax fails crafty still falls to flush
as backup and will still work... However, If you would like to enable flush or refresh as default methods of chat you will have to EDIT the 
mastersettings.php PHP file and uncomment out lines #347 TO  #350 to allow
you to select that type of chat mode. Again flush and refresh will be taken out of version 3.x
</td></tr></table>
<b>Chat Type:</b><select name=new_chatmode>
<option value="xmlhttp-flush-refresh" <?php if ($CSLH_Config['chatmode'] == "xmlhttp-flush-refresh") print " SELECTED "; ?> > AJAX -&gt; Flush() -&gt; Refresh </option>
<!-- it is HIGHLY RECOMMENDED THAT YOU ONLY USE xmlhttp REQUESTS (known as AJAX.. but if you must you can uncomment and select one of the methods below -->
<!-- <option value="flush-xmlhttp-refresh" <?php if ($CSLH_Config['chatmode'] == "flush-xmlhttp-refresh") print " SELECTED "; ?> > Flush() -&gt; AJAX -&gt; Refresh </option> -->
<!-- <option value="xmlhttp-refresh" <?php if ($CSLH_Config['chatmode'] == "xmlhttp-refresh") print " SELECTED "; ?> > AJAX -&gt; Refresh </option> -->
<!-- <option value="flush-refresh" <?php if ($CSLH_Config['chatmode'] == "flush-refresh") print " SELECTED "; ?> > Flush() -&gt; Refresh </option> -->
<!-- <option value="refresh" <?php if ($CSLH_Config['chatmode'] == "refresh") print " SELECTED "; ?> > Refresh</option> -->
</select><br>

<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt159']; ?>
</td></tr></table>
<b>Flush() chatmode Refresh:</b>: <select name=newmaxexe>
<option value=14 <?php if ($CSLH_Config['maxexe'] == 14){ print " SELECTED "; } ?> > 15 Seconds </option>
<option value=30 <?php if ($CSLH_Config['maxexe'] == 30){ print " SELECTED "; } ?>> 30 Seconds </option>
<option value=60 <?php if ($CSLH_Config['maxexe'] == 60){ print " SELECTED "; } ?>> 60 Seconds </option>
<option value=90 <?php if ($CSLH_Config['maxexe'] == 90){ print " SELECTED "; } ?>> 90 Seconds </option>
<option value=180 <?php if ($CSLH_Config['maxexe'] == 180){ print " SELECTED "; } ?>> 180 Seconds </option>
<option value=300 <?php if ($CSLH_Config['maxexe'] == 300){ print " SELECTED "; } ?>> 300 Seconds </option>
<option value=600 <?php if ($CSLH_Config['maxexe'] == 600){ print " SELECTED "; } ?>> 600 Seconds </option>
<option value=900 <?php if ($CSLH_Config['maxexe'] == 900){ print " SELECTED "; } ?>> 900 Seconds </option>
<option value=1500 <?php if ($CSLH_Config['maxexe'] == 1500){ print " SELECTED "; } ?>> 1500 Seconds </option>
<option value=1000 <?php if ($CSLH_Config['maxexe'] == 2000){ print " SELECTED "; } ?>> 2000 Seconds </option>
<option value=4000 <?php if ($CSLH_Config['maxexe'] == 4000){ print " SELECTED "; } ?>> 4000 Seconds </option>
<option value=8000 <?php if ($CSLH_Config['maxexe'] == 8000){ print " SELECTED "; } ?>> 8000 Seconds </option>
</select>

<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt160']; ?>
</td></tr></table>
<b>Refresh chatmode Refresh</b>: <select name=newrefreshrate>
<option value=1 <?php if ($CSLH_Config['refreshrate'] == 1){ print " SELECTED "; } ?> > Auto Detect </option>
<option value=4 <?php if ($CSLH_Config['refreshrate'] == 3){ print " SELECTED "; } ?>> 3 Seconds </option>
<option value=5 <?php if ($CSLH_Config['refreshrate'] == 5){ print " SELECTED "; } ?>> 5 Seconds </option>
<option value=7 <?php if ($CSLH_Config['refreshrate'] == 7){ print " SELECTED "; } ?>> 7 Seconds </option>
<option value=10 <?php if ($CSLH_Config['refreshrate'] == 10){ print " SELECTED "; } ?>> 10 Seconds </option>
<option value=15 <?php if ($CSLH_Config['refreshrate'] == 15){ print " SELECTED "; } ?>> 15 Seconds </option>
</select>
 
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt205']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt78']; ?>
</td></tr></table>
<b><?php echo $lang['txt205']; ?>:</b><select name=newoffset>
<option value=<?php echo $offset; ?>><?php echo $offset ?></option>
<option value=<?php echo $offset; ?>>---</option>
<?php 
for($i=-20;$i<20; $i++){ 
  print "<option value=$i>$i</option>\n";
} 
?>
</select><br>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['title']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt79']; ?>
</td></tr></table>
<b><?php echo $lang['title']; ?>:</b><input type=text name=newsite_title value="<?php echo $CSLH_Config['site_title']; ?>"><br>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt206']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt172']; ?>
</td></tr></table>
<textarea cols=45 rows=5 name=ignoreips><?php echo htmlspecialchars($CSLH_Config['ignoreips']); ?></textarea><br>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['TYPINGPREVIEW']; ?>:</b>
</td></tr></table>
<table width=100% bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt80']; ?>
</td></tr></table>
<b><?php echo $lang['Enable']; ?>:</b> <input type=radio value=Y name=newshow_typing <?php echo $typing_y; ?> > <?php echo $lang['YES']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=newshow_typing <?php echo $typing_n; ?> > <?php echo $lang['NO']; ?><br>

<input type=submit value="<?php echo $lang['UPDATE']; ?>">
</body>
<?php
if(!($serversession))
$mydatabase->close_connect();
?></td></tr></table>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>  
</font>