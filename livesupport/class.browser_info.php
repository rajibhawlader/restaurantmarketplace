<?php
	/****************************************************************************************
	*                       HTTP_USER_AGENT identification class                            *
	*            (c)2003-2005 TOTH Richard, riso.toth@seznam.cz, Slovakia                   *
	*                               rev 1.9 (04.02.2005)                                    *
	*****************************************************************************************
	*   Operating systems: Win3.1, Win3.11, Win95, Win98, WinME, WinNT, Win2000, WinXP,     *
	*                      Win.NET, Windows codename: Longhorn, WinCE,                      *
	*                      MacOSX, MacPPC, Mac68K, LindowsOS,                               *
	*                      Linux, FreeBSD, NetBSD, Unix, HP-UX, SunOS, IRIX, OSF1,          *
	*                      QNX Photon, OS/2, Amiga, Symbian, Palm,                          *
	*                      Liberate, Sega Dreamcast, WebTV, PowerTV, Prodigy,               *
	*                      Siemes CX35                                                      *
	*   Browsers: Amaya, AOL, AWeb, Beonex, Camino, Cyberdog, Dillo, Doris, ELinks, Emacs,  *
	*             Firebird, Firefox, FrontPage, Galeon, Chimera, iCab, IBM Web Browser,     *
	*             Internet Explorer, Konqueror, Liberate, Links, Lycoris Desktop/LX, Lynx,  *
	*             Netbox, Netcaptor, Netpliance, Netscape, Mozzila, OffByOne, Opera,        *
	*             Pocket Internet Explorer, PowerBrowser, Phoenix, PlanetWeb, PowerTV,      *
	*             Prodigy, Voyager, QuickTime, Safari, Sharp Reader, Tango, WebExplorer,    *
	*             WebTV, WGet, Yandex, UP Browser                                           *
	*   Bots: GoogleBot, MSNBot, SurveyBot, IBM Almaden Crawler, Alexa, ZyBorg,             *
	*         W3C Checklink, LinkWalker, Fast WebCrawler, Yahoo! Slurp, NaverBot,           *
	*         Link checker pro, BecomeBot, Convera Crawler, W3C Validator, Innerprise,      *
	*         TopicSpy, Poodle Predictort, Ichiro, Link Checker Pro, Grub Client,           *
	*         Gigabot, PSBot, MJ12Bot, NextGenSearchBot, TutorGigBot, Exabot NG, GaisBot    *
	*         Xenu Link Sleuth, TurnnitinBot, Iconsurf, Zoe Indexer                         *
	****************************************************************************************/

	class BrowserInfo
	{
		var $USER_AGENT = ""; // STRING - USER_AGENT_STRING
		var $OS = ""; // STRING - operating system
		var $OS_Version = ""; // STRING - operating system version
		var $Browser = "" ;// STRING - Browser name
		var $Browser_Version = ""; // STRING - Browser version
		var $NET_CLR = false; // BOOL - .NET Common Language Runtime
		var $Resolved = false; // BOOL - resolving proceeded
		var $Type = ""; // STRING - Browser/Robot
		
		
		
		// CONSTRUCTOR - Main function to resolving user agents
		function BrowserInfo($UA) // PUBLIC - BrowserInfo((string) USER_AGENT_STRING)
		{
			$this->USER_AGENT = $UA;
			$this->Resolve();
			$this->Resolved = true;
		}
		
		
		// FUNCTION - Resolving user agents
		function Resolve() // PUBLIC - Resolve()
		{
			$this->Resolved = false;
			$this->OS = "";
			$this->OS_Version = "";
			$this->NET_CLR = false;
			
			$this->_GetOperatingSystem();
			$this->_GetBrowser();
			$this->_GetNET_CLR();
		}
		
		/***********************************************************************************/
		
		// PROTECTED - _GetNET_CLR()
		function _GetNET_CLR()
		{
			if (eregi("NET CLR",$this->USER_AGENT)) {$this->NET_CLR = true;}
		}
		
		
		// PROTECTED - _GetOperatingSystem()
		function _GetOperatingSystem()
		{
			if (eregi("win",$this->USER_AGENT))
			{
				$this->OS = "Windows";
				if ((eregi("Windows 95",$this->USER_AGENT)) || (eregi("Win95",$this->USER_AGENT))) {$this->OS_Version = "95";}
				elseif (eregi("Windows ME",$this->USER_AGENT) || (eregi("Win 9x 4.90",$this->USER_AGENT))) {$this->OS_Version = "ME";}
				elseif ((eregi("Windows 98",$this->USER_AGENT)) || (eregi("Win98",$this->USER_AGENT))) {$this->OS_Version = "98";}
				elseif ((eregi("Windows NT 5.0",$this->USER_AGENT)) || (eregi("WinNT5.0",$this->USER_AGENT)) || (eregi("Windows 2000",$this->USER_AGENT)) || (eregi("Win2000",$this->USER_AGENT))) {$this->OS_Version = "2000";}
				elseif ((eregi("Windows NT 5.1",$this->USER_AGENT)) || (eregi("WinNT5.1",$this->USER_AGENT)) || (eregi("Windows XP",$this->USER_AGENT))) {$this->OS_Version = "XP";}
				elseif ((eregi("Windows NT 5.2",$this->USER_AGENT)) || (eregi("WinNT5.2",$this->USER_AGENT))) {$this->OS_Version = ".NET 2003";}
				elseif ((eregi("Windows NT 6.0",$this->USER_AGENT)) || (eregi("WinNT6.0",$this->USER_AGENT))) {$this->OS_Version = "Codename: Longhorn";}
				elseif (eregi("Windows CE",$this->USER_AGENT)) {$this->OS_Version = "CE";}
				elseif (eregi("Win3.11",$this->USER_AGENT)) {$this->OS_Version = "3.11";}
				elseif (eregi("Win3.1",$this->USER_AGENT)) {$this->OS_Version = "3.1";}
				elseif ((eregi("Windows NT",$this->USER_AGENT)) || (eregi("WinNT",$this->USER_AGENT))) {$this->OS_Version = "NT";}
			}
			elseif (eregi("lindows",$this->USER_AGENT))
			{
				$this->OS = "LindowsOS";
			}
			elseif (eregi("mac",$this->USER_AGENT))
			{
				$this->OS = "MacIntosh";
				if ((eregi("Mac OS X",$this->USER_AGENT)) || (eregi("Mac 10",$this->USER_AGENT))) {$this->OS_Version = "OS X";}
				elseif ((eregi("PowerPC",$this->USER_AGENT)) || (eregi("PPC",$this->USER_AGENT))) {$this->OS_Version = "PPC";}
				elseif ((eregi("68000",$this->USER_AGENT)) || (eregi("68k",$this->USER_AGENT))) {$this->OS_Version = "68K";}
			}
			elseif (eregi("linux",$this->USER_AGENT))
			{
				$this->OS = "Linux";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
				elseif (eregi("ppc",$this->USER_AGENT)) {$this->OS_Version = "ppc";}
			}
			elseif (eregi("sunos",$this->USER_AGENT))
			{
				$this->OS = "SunOS";
			}
			elseif (eregi("hp-ux",$this->USER_AGENT))
			{
				$this->OS = "HP-UX";
			}
			elseif (eregi("osf1",$this->USER_AGENT))
			{
				$this->OS = "OSF1";
			}
			elseif (eregi("freebsd",$this->USER_AGENT))
			{
				$this->OS = "FreeBSD";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
			}
			elseif (eregi("netbsd",$this->USER_AGENT))
			{
				$this->OS = "NetBSD";
				if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
				elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
				elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
				elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
			}
			elseif (eregi("irix",$this->USER_AGENT))
			{
				$this->OS = "IRIX";
			}
			elseif (eregi("os/2",$this->USER_AGENT))
			{
				$this->OS = "OS/2";
				if (eregi("Warp 4.5",$this->USER_AGENT)) {$this->OS_Version = "Warp 4.5";}
				elseif (eregi("Warp 4",$this->USER_AGENT)) {$this->OS_Version = "Warp 4";}
			}
			elseif (eregi("amiga",$this->USER_AGENT))
			{
				$this->OS = "Amiga";
			}
			elseif (eregi("liberate",$this->USER_AGENT))
			{
				$this->OS = "Liberate";
			}
			elseif (eregi("qnx",$this->USER_AGENT))
			{
				$this->OS = "QNX";
				if (eregi("photon",$this->USER_AGENT)) {$this->OS_Version = "Photon";}
			}
			elseif (eregi("dreamcast",$this->USER_AGENT))
			{
				$this->OS = "Sega Dreamcast";
			}
			elseif (eregi("palm",$this->USER_AGENT))
			{
				$this->OS = "Palm";
			}
			elseif (eregi("powertv",$this->USER_AGENT))
			{
				$this->OS = "PowerTV";
			}
			elseif (eregi("prodigy",$this->USER_AGENT))
			{
				$this->OS = "Prodigy";
			}
			elseif (eregi("symbian",$this->USER_AGENT))
			{
				$this->OS = "Symbian";
				if (eregi("symbianos/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
			}
			elseif (eregi("unix",$this->USER_AGENT))
			{
				$this->OS = "Unix";
			}
			elseif (eregi("webtv",$this->USER_AGENT))
			{
				$this->OS = "WebTV";
			}
			elseif (eregi("sie-cx35",$this->USER_AGENT))
			{
				$this->OS = "Siemens CX35";
			}
		}
		
		
		// PROTECTED - _GetBrowser()
		function _GetBrowser()
		{
			// boti
			if (eregi("msnbot",$this->USER_AGENT))
			{
				$this->Browser = "MSN Bot";
				$this->Type = "robot";
				if (eregi("msnbot/0.11",$this->USER_AGENT)) {$this->Browser_Version = "0.11";}
				elseif (eregi("msnbot/0.30",$this->USER_AGENT)) {$this->Browser_Version = "0.3";}
				elseif (eregi("msnbot/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("almaden",$this->USER_AGENT))
			{
				$this->Browser = "IBM Almaden Crawler";
				$this->Type = "robot";
			}
			elseif (eregi("BecomeBot",$this->USER_AGENT))
			{
				$this->Browser = "BecomeBot";
				if (eregi("becomebot/1.23",$this->USER_AGENT)) {$this->Browser_Version = "1.23";}
				$this->Type = "robot";
			}
			elseif (eregi("Link-Checker-Pro",$this->USER_AGENT))
			{
				$this->Browser = "Link Checker Pro";
				$this->Type = "robot";
			}
			elseif (eregi("ia_archiver",$this->USER_AGENT))
			{
				$this->Browser = "Alexa";
				$this->Type = "robot";
			}
			elseif ((eregi("googlebot",$this->USER_AGENT)) || (eregi("google",$this->USER_AGENT)))
			{
				$this->Browser = "Google Bot";
				$this->Type = "robot";
				if ((eregi("googlebot/2.1",$this->USER_AGENT)) || (eregi("google/2.1",$this->USER_AGENT))) {$this->Browser_Version = "2.1";}
			}
			elseif (eregi("surveybot",$this->USER_AGENT))
			{
				$this->Browser = "Survey Bot";
				$this->Type = "robot";
				if (eregi("surveybot/2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
			}
			elseif (eregi("zyborg",$this->USER_AGENT))
			{
				$this->Browser = "ZyBorg";
				$this->Type = "robot";
				if (eregi("zyborg/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("w3c-checklink",$this->USER_AGENT))
			{
				$this->Browser = "W3C Checklink";
				$this->Type = "robot";
				if (eregi("checklink/3.6",$this->USER_AGENT)) {$this->Browser_Version = "3.6";}
			}
			elseif (eregi("linkwalker",$this->USER_AGENT))
			{
				$this->Browser = "LinkWalker";
				$this->Type = "robot";
			}
			elseif (eregi("fast-webcrawler",$this->USER_AGENT))
			{
				$this->Browser = "Fast WebCrawler";
				$this->Type = "robot";
				if (eregi("webcrawler/3.8",$this->USER_AGENT)) {$this->Browser_Version = "3.8";}
			}
			elseif ((eregi("yahoo",$this->USER_AGENT)) && (eregi("slurp",$this->USER_AGENT)))
			{
				$this->Browser = "Yahoo! Slurp";
				$this->Type = "robot";
			}
			elseif (eregi("naverbot",$this->USER_AGENT))
			{
				$this->Browser = "NaverBot";
				$this->Type = "robot";
				if (eregi("dloader/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("converacrawler",$this->USER_AGENT))
			{
				$this->Browser = "ConveraCrawler";
				$this->Type = "robot";
				if (eregi("converacrawler/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
			}
			elseif (eregi("w3c_validator",$this->USER_AGENT))
			{
				$this->Browser = "W3C Validator";
				$this->Type = "robot";
				if (eregi("w3c_validator/1.305",$this->USER_AGENT)) {$this->Browser_Version = "1.305";}
			}
			elseif (eregi("innerprisebot",$this->USER_AGENT))
			{
				$this->Browser = "Innerprise";
				$this->Type = "robot";
				if (eregi("innerprise/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("topicspy",$this->USER_AGENT))
			{
				$this->Browser = "Topicspy Checkbot";
				$this->Type = "robot";
			}
			elseif (eregi("poodle predictor",$this->USER_AGENT))
			{
				$this->Browser = "Poodle Predictor";
				$this->Type = "robot";
				if (eregi("poodle predictor 1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("ichiro",$this->USER_AGENT))
			{
				$this->Browser = "Ichiro";
				$this->Type = "robot";
				if (eregi("ichiro/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("link checker pro",$this->USER_AGENT))
			{
				$this->Browser = "Link Checker Pro";
				$this->Type = "robot";
				if (eregi("link checker pro 3.2.16",$this->USER_AGENT)) {$this->Browser_Version = "3.2.16";}
			}
			elseif (eregi("grub-client",$this->USER_AGENT))
			{
				$this->Browser = "Grub client";
				$this->Type = "robot";
				if (eregi("grub-client-2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
			}
			elseif (eregi("gigabot",$this->USER_AGENT))
			{
				$this->Browser = "Gigabot";
				$this->Type = "robot";
				if (eregi("gigabot/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("psbot",$this->USER_AGENT))
			{
				$this->Browser = "PSBot";
				$this->Type = "robot";
				if (eregi("psbot/0.1",$this->USER_AGENT)) {$this->Browser_Version = "0.1";}
			}
			elseif (eregi("mj12bot",$this->USER_AGENT))
			{
				$this->Browser = "MJ12Bot";
				$this->Type = "robot";
				if (eregi("mj12bot/v0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
			}
			elseif (eregi("nextgensearchbot",$this->USER_AGENT))
			{
				$this->Browser = "NextGenSearchBot";
				$this->Type = "robot";
				if (eregi("nextgensearchbot 1",$this->USER_AGENT)) {$this->Browser_Version = "1";}
			}
			elseif (eregi("tutorgigbot",$this->USER_AGENT))
			{
				$this->Browser = "TutorGigBot";
				$this->Type = "robot";
				if (eregi("bot/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (ereg("NG",$this->USER_AGENT))
			{
				$this->Browser = "Exabot NG";
				$this->Type = "robot";
				if (eregi("ng/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("gaisbot",$this->USER_AGENT))
			{
				$this->Browser = "Gaisbot";
				$this->Type = "robot";
				if (eregi("gaisbot/3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
			}
			elseif (eregi("xenu link sleuth",$this->USER_AGENT))
			{
				$this->Browser = "Xenu Link Sleuth";
				$this->Type = "robot";
				if (eregi("xenu link sleuth 1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
			}
			elseif (eregi("turnitinbot",$this->USER_AGENT))
			{
				$this->Browser = "TurnitinBot";
				$this->Type = "robot";
				if (eregi("turnitinbot/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("iconsurf",$this->USER_AGENT))
			{
				$this->Browser = "IconSurf";
				$this->Type = "robot";
				if (eregi("iconsurf/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("zoe indexer",$this->USER_AGENT))
			{
				$this->Browser = "Zoe Indexer";
				$this->Type = "robot";
				if (eregi("v1.x",$this->USER_AGENT)) {$this->Browser_Version = "1";}
			}
			// prehliadace
			elseif (eregi("amaya",$this->USER_AGENT))
			{
				$this->Browser = "amaya";
				$this->Type = "browser";
				if (eregi("amaya/5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("amaya/5.1",$this->USER_AGENT)) {$this->Browser_Version = "5.1";}
				elseif (eregi("amaya/5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
				elseif (eregi("amaya/5.3",$this->USER_AGENT)) {$this->Browser_Version = "5.3";}
				elseif (eregi("amaya/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("amaya/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("amaya/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("amaya/6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
				elseif (eregi("amaya/6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
				elseif (eregi("amaya/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("amaya/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("amaya/7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
				elseif (eregi("amaya/8.0",$this->USER_AGENT)) {$this->Browser_Version = "8.0";}
			}
			elseif ((eregi("aol",$this->USER_AGENT)) && !(eregi("msie",$this->USER_AGENT)))
			{
				$this->Browser = "AOL";
				$this->Type = "browser";
				if ((eregi("aol 7.0",$this->USER_AGENT)) || (eregi("aol/7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
			}
			elseif ((eregi("aweb",$this->USER_AGENT)) || (eregi("amigavoyager",$this->USER_AGENT)))
			{
				$this->Browser = "AWeb";
				$this->Type = "browser";
				if (eregi("voyager/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("voyager/2.95",$this->USER_AGENT)) {$this->Browser_Version = "2.95";}
				elseif ((eregi("voyager/3",$this->USER_AGENT)) || (eregi("aweb/3.0",$this->USER_AGENT))) {$this->Browser_Version = "3.0";}
				elseif (eregi("aweb/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
				elseif (eregi("aweb/3.2",$this->USER_AGENT)) {$this->Browser_Version = "3.2";}
				elseif (eregi("aweb/3.3",$this->USER_AGENT)) {$this->Browser_Version = "3.3";}
				elseif (eregi("aweb/3.4",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
				elseif (eregi("aweb/3.9",$this->USER_AGENT)) {$this->Browser_Version = "3.9";}
			}
			elseif (eregi("beonex",$this->USER_AGENT))
			{
				$this->Browser = "Beonex";
				$this->Type = "browser";
				if (eregi("beonex/0.8.2",$this->USER_AGENT)) {$this->Browser_Version = "0.8.2";}
				elseif (eregi("beonex/0.8.1",$this->USER_AGENT)) {$this->Browser_Version = "0.8.1";}
				elseif (eregi("beonex/0.8",$this->USER_AGENT)) {$this->Browser_Version = "0.8";}
			}
			elseif (eregi("camino",$this->USER_AGENT))
			{
				$this->Browser = "Camino";
				$this->Type = "browser";
				if (eregi("camino/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
			}
			elseif (eregi("cyberdog",$this->USER_AGENT))
			{
				$this->Browser = "Cyberdog";
				$this->Type = "browser";
				if (eregi("cybergog/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("cyberdog/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("cyberdog/2.0b1",$this->USER_AGENT)) {$this->Browser_Version = "2.0b1";}
			}
			elseif (eregi("dillo",$this->USER_AGENT))
			{
				$this->Browser = "Dillo";
				$this->Type = "browser";
				if (eregi("dillo/0.6.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6.6";}
				elseif (eregi("dillo/0.7.2",$this->USER_AGENT)) {$this->Browser_Version = "0.7.2";}
				elseif (eregi("dillo/0.7.3",$this->USER_AGENT)) {$this->Browser_Version = "0.7.3";}
				elseif (eregi("dillo/0.8",$this->USER_AGENT)) {$this->Browser_Version = "0.8";}
			}
			elseif (eregi("doris",$this->USER_AGENT))
			{
				$this->Browser = "Doris";
				$this->Type = "browser";
				if (eregi("doris/1.10",$this->USER_AGENT)) {$this->Browser_Version = "1.10";}
			}
			elseif (eregi("emacs",$this->USER_AGENT))
			{
				$this->Browser = "Emacs";
				$this->Type = "browser";
				if (eregi("emacs/w3/2",$this->USER_AGENT)) {$this->Browser_Version = "2";}
				elseif (eregi("emacs/w3/3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
				elseif (eregi("emacs/w3/4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
			}
			elseif (eregi("firebird",$this->USER_AGENT))
			{
				$this->Browser = "Firebird";
				$this->Type = "browser";
				if ((eregi("firebird/0.6",$this->USER_AGENT)) || (eregi("browser/0.6",$this->USER_AGENT))) {$this->Browser_Version = "0.6";}
				elseif (eregi("firebird/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
			}
			elseif (eregi("firefox",$this->USER_AGENT))
			{
				$this->Browser = "Firefox";
				$this->Type = "browser";
				if (eregi("firefox/0.9.1",$this->USER_AGENT)) {$this->Browser_Version = "0.9.1";}
				elseif (eregi("firefox/0.10",$this->USER_AGENT)) {$this->Browser_Version = "0.10";}
				elseif (eregi("firefox/0.9",$this->USER_AGENT)) {$this->Browser_Version = "0.9";}
				elseif (eregi("firefox/0.8",$this->USER_AGENT)) {$this->Browser_Version = "0.8";}
				elseif (eregi("firefox/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("frontpage",$this->USER_AGENT))
			{
				$this->Browser = "FrontPage";
				$this->Type = "browser";
				if ((eregi("express 2",$this->USER_AGENT)) || (eregi("frontpage 2",$this->USER_AGENT))) {$this->Browser_Version = "2";}
				elseif (eregi("frontpage 3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
				elseif (eregi("frontpage 4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
				elseif (eregi("frontpage 5",$this->USER_AGENT)) {$this->Browser_Version = "5";}
				elseif (eregi("frontpage 6",$this->USER_AGENT)) {$this->Browser_Version = "6";}
			}
			elseif (eregi("galeon",$this->USER_AGENT))
			{
				$this->Browser = "Galeon";
				$this->Type = "browser";
				if (eregi("galeon 0.1",$this->USER_AGENT)) {$this->Browser_Version = "0.1";}
				elseif (eregi("galeon/0.11.1",$this->USER_AGENT)) {$this->Browser_Version = "0.11.1";}
				elseif (eregi("galeon/0.11.2",$this->USER_AGENT)) {$this->Browser_Version = "0.11.2";}
				elseif (eregi("galeon/0.11.3",$this->USER_AGENT)) {$this->Browser_Version = "0.11.3";}
				elseif (eregi("galeon/0.11.5",$this->USER_AGENT)) {$this->Browser_Version = "0.11.5";}
				elseif (eregi("galeon/0.12.8",$this->USER_AGENT)) {$this->Browser_Version = "0.12.8";}
				elseif (eregi("galeon/0.12.7",$this->USER_AGENT)) {$this->Browser_Version = "0.12.7";}
				elseif (eregi("galeon/0.12.6",$this->USER_AGENT)) {$this->Browser_Version = "0.12.6";}
				elseif (eregi("galeon/0.12.5",$this->USER_AGENT)) {$this->Browser_Version = "0.12.5";}
				elseif (eregi("galeon/0.12.4",$this->USER_AGENT)) {$this->Browser_Version = "0.12.4";}
				elseif (eregi("galeon/0.12.3",$this->USER_AGENT)) {$this->Browser_Version = "0.12.3";}
				elseif (eregi("galeon/0.12.2",$this->USER_AGENT)) {$this->Browser_Version = "0.12.2";}
				elseif (eregi("galeon/0.12.1",$this->USER_AGENT)) {$this->Browser_Version = "0.12.1";}
				elseif (eregi("galeon/0.12",$this->USER_AGENT)) {$this->Browser_Version = "0.12";}
				elseif ((eregi("galeon/1",$this->USER_AGENT)) || (eregi("galeon 1.0",$this->USER_AGENT))) {$this->Browser_Version = "1.0";}
			}
			elseif (eregi("ibm web browser",$this->USER_AGENT))
			{
				$this->Browser = "IBM Web Browser";
				$this->Type = "browser";
				if (eregi("rv:1.0.1",$this->USER_AGENT)) {$this->Browser_Version = "1.0.1";}
			}
			elseif (eregi("chimera",$this->USER_AGENT))
			{
				$this->Browser = "Chimera";
				$this->Type = "browser";
				if (eregi("chimera/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
				elseif (eregi("chimera/0.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6";}
				elseif (eregi("chimera/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
				elseif (eregi("chimera/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
			}
			elseif (eregi("icab",$this->USER_AGENT))
			{
				$this->Browser = "iCab";
        		$this->Type = "browser";
				if (eregi("icab/2.7.1",$this->USER_AGENT)) {$this->Browser_Version = "2.7.1";}
				elseif (eregi("icab/2.8.1",$this->USER_AGENT)) {$this->Browser_Version = "2.8.1";}
				elseif (eregi("icab/2.8.2",$this->USER_AGENT)) {$this->Browser_Version = "2.8.2";}
				elseif (eregi("icab 2.9",$this->USER_AGENT)) {$this->Browser_Version = "2.9";}
				elseif (eregi("icab 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
			elseif (eregi("konqueror",$this->USER_AGENT))
			{
				$this->Browser = "Konqueror";
				$this->Type = "browser";
				if (eregi("konqueror/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
				elseif (eregi("konqueror/3.3",$this->USER_AGENT)) {$this->Browser_Version = "3.3";}
				elseif (eregi("konqueror/3.2",$this->USER_AGENT)) {$this->Browser_Version = "3.2";}
				elseif (eregi("konqueror/3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("konqueror/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
				elseif (eregi("konqueror/2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
				elseif (eregi("konqueror/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("liberate",$this->USER_AGENT))
			{
				$this->Browser = "Liberate";
				$this->Type = "browser";
				if (eregi("dtv 1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("dtv 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("desktop/lx",$this->USER_AGENT))
			{
				$this->Browser = "Lycoris Desktop/LX";
				$this->Type = "browser";
			}
			elseif (eregi("netbox",$this->USER_AGENT))
			{
				$this->Browser = "NetBox";
				$this->Type = "browser";
				if (eregi("netbox/3.5",$this->USER_AGENT)) {$this->Browser_Version = "3.5";}
			}
			elseif (eregi("netcaptor",$this->USER_AGENT))
			{
				$this->Browser = "Netcaptor";
				$this->Type = "browser";
				if (eregi("netcaptor 7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("netcaptor 7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("netcaptor 7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
				elseif (eregi("netcaptor 7.5",$this->USER_AGENT)) {$this->Browser_Version = "7.5";}
				elseif (eregi("netcaptor 6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
			}
			elseif (eregi("netpliance",$this->USER_AGENT))
			{
				$this->Browser = "Netpliance";
				$this->Type = "browser";
			}
			elseif (eregi("netscape",$this->USER_AGENT)) // (1) netscape nie je prilis detekovatelny....
			{
				$this->Browser = "Netscape";
				$this->Type = "browser";
				if (eregi("netscape/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
				elseif (eregi("netscape/7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
				elseif (eregi("netscape/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
				elseif (eregi("netscape6/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("netscape6/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("netscape6/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
			}
			elseif ((eregi("mozilla/5.0",$this->USER_AGENT)) && (eregi("rv:",$this->USER_AGENT)) && (eregi("gecko/",$this->USER_AGENT))) // mozilla je troschu zlozitejsia na detekciu
			{
				$this->Browser = "Mozilla";
				$this->Type = "browser";
				if (eregi("rv:1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("rv:1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("rv:1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("rv:1.3",$this->USER_AGENT)) {$this->Browser_Version = "1.3";}
				elseif (eregi("rv:1.4",$this->USER_AGENT)) {$this->Browser_Version = "1.4";}
				elseif (eregi("rv:1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
				elseif (eregi("rv:1.6",$this->USER_AGENT)) {$this->Browser_Version = "1.6";}
				elseif (eregi("rv:1.7",$this->USER_AGENT)) {$this->Browser_Version = "1.7";}
				elseif (eregi("rv:1.8",$this->USER_AGENT)) {$this->Browser_Version = "1.8";}
			}
			elseif (eregi("offbyone",$this->USER_AGENT))
			{
				$this->Browser = "OffByOne";
				$this->Type = "browser";
				if (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
			}
			elseif (eregi("omniweb",$this->USER_AGENT))
			{
				$this->Browser = "OmniWeb";
				$this->Type = "browser";
				if (eregi("omniweb/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("omniweb/4.4",$this->USER_AGENT)) {$this->Browser_Version = "4.4";}
				elseif (eregi("omniweb/4.3",$this->USER_AGENT)) {$this->Browser_Version = "4.3";}
				elseif (eregi("omniweb/4.2",$this->USER_AGENT)) {$this->Browser_Version = "4.2";}
				elseif (eregi("omniweb/4.1",$this->USER_AGENT)) {$this->Browser_Version = "4.1";}
			}
			elseif (eregi("opera",$this->USER_AGENT))
			{
				$this->Browser = "Opera";
				$this->Type = "browser";
				if ((eregi("opera/7.21",$this->USER_AGENT)) || (eregi("opera 7.21",$this->USER_AGENT))) {$this->Browser_Version = "7.21";}
				elseif ((eregi("opera/8.0",$this->USER_AGENT)) || (eregi("opera 8.0",$this->USER_AGENT))) {$this->Browser_Version = "8.0";}
				elseif ((eregi("opera/7.60",$this->USER_AGENT)) || (eregi("opera 7.60",$this->USER_AGENT))) {$this->Browser_Version = "7.60";}
				elseif ((eregi("opera/7.54",$this->USER_AGENT)) || (eregi("opera 7.54",$this->USER_AGENT))) {$this->Browser_Version = "7.54";}
				elseif ((eregi("opera/7.53",$this->USER_AGENT)) || (eregi("opera 7.53",$this->USER_AGENT))) {$this->Browser_Version = "7.53";}
				elseif ((eregi("opera/7.52",$this->USER_AGENT)) || (eregi("opera 7.52",$this->USER_AGENT))) {$this->Browser_Version = "7.52";}
				elseif ((eregi("opera/7.51",$this->USER_AGENT)) || (eregi("opera 7.51",$this->USER_AGENT))) {$this->Browser_Version = "7.51";}
				elseif ((eregi("opera/7.50",$this->USER_AGENT)) || (eregi("opera 7.50",$this->USER_AGENT))) {$this->Browser_Version = "7.50";}
				elseif ((eregi("opera/7.23",$this->USER_AGENT)) || (eregi("opera 7.23",$this->USER_AGENT))) {$this->Browser_Version = "7.23";}
				elseif ((eregi("opera/7.22",$this->USER_AGENT)) || (eregi("opera 7.22",$this->USER_AGENT))) {$this->Browser_Version = "7.22";}
				elseif ((eregi("opera/7.20",$this->USER_AGENT)) || (eregi("opera 7.20",$this->USER_AGENT))) {$this->Browser_Version = "7.20";}
				elseif ((eregi("opera/7.11",$this->USER_AGENT)) || (eregi("opera 7.11",$this->USER_AGENT))) {$this->Browser_Version = "7.11";}
				elseif ((eregi("opera/7.10",$this->USER_AGENT)) || (eregi("opera 7.10",$this->USER_AGENT))) {$this->Browser_Version = "7.10";}
				elseif ((eregi("opera/7.03",$this->USER_AGENT)) || (eregi("opera 7.03",$this->USER_AGENT))) {$this->Browser_Version = "7.03";}
				elseif ((eregi("opera/7.02",$this->USER_AGENT)) || (eregi("opera 7.02",$this->USER_AGENT))) {$this->Browser_Version = "7.02";}
				elseif ((eregi("opera/7.01",$this->USER_AGENT)) || (eregi("opera 7.01",$this->USER_AGENT))) {$this->Browser_Version = "7.01";}
				elseif ((eregi("opera/7.0",$this->USER_AGENT)) || (eregi("opera 7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
				elseif ((eregi("opera/6.12",$this->USER_AGENT)) || (eregi("opera 6.12",$this->USER_AGENT))) {$this->Browser_Version = "6.12";}
				elseif ((eregi("opera/6.11",$this->USER_AGENT)) || (eregi("opera 6.11",$this->USER_AGENT))) {$this->Browser_Version = "6.11";}
				elseif ((eregi("opera/6.1",$this->USER_AGENT)) || (eregi("opera 6.1",$this->USER_AGENT))) {$this->Browser_Version = "6.1";}
				elseif ((eregi("opera/6.	0",$this->USER_AGENT)) || (eregi("opera 6.0",$this->USER_AGENT))) {$this->Browser_Version = "6.0";}
				elseif ((eregi("opera/5.12",$this->USER_AGENT)) || (eregi("opera 5.12",$this->USER_AGENT))) {$this->Browser_Version = "5.12";}
				elseif ((eregi("opera/5.0",$this->USER_AGENT)) || (eregi("opera 5.0",$this->USER_AGENT))) {$this->Browser_Version = "5.0";}
				elseif ((eregi("opera/4",$this->USER_AGENT)) || (eregi("opera 4",$this->USER_AGENT))) {$this->Browser_Version = "4";}
			}
			elseif (eregi("oracle",$this->USER_AGENT))
			{
				$this->Browser = "Oracle PowerBrowser";
				$this->Type = "browser";
				if (eregi("(tm)/1.0a",$this->USER_AGENT)) {$this->Browser_Version = "1.0a";}
				elseif (eregi("oracle 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("phoenix",$this->USER_AGENT))
			{
				$this->Browser = "Phoenix";
				$this->Type = "browser";
				if (eregi("phoenix/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
				elseif (eregi("phoenix/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
			}
			elseif (eregi("planetweb",$this->USER_AGENT))
			{
				$this->Browser = "PlanetWeb";
				$this->Type = "browser";
				if (eregi("planetweb/2.606",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("planetweb/1.125",$this->USER_AGENT)) {$this->Browser_Version = "3";}
			}
			elseif (eregi("powertv",$this->USER_AGENT))
			{
				$this->Browser = "PowerTV";
				$this->Type = "browser";
				if (eregi("powertv/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("prodigy",$this->USER_AGENT))
			{
				$this->Browser = "Prodigy";
				$this->Type = "browser";
				if (eregi("wb/3.2e",$this->USER_AGENT)) {$this->Browser_Version = "3.2e";}
				elseif (eregi("rv: 1.",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
			}
			elseif ((eregi("voyager",$this->USER_AGENT)) || ((eregi("qnx",$this->USER_AGENT))) && (eregi("rv: 1.",$this->USER_AGENT))) // aj voyager je trosku zlozitejsi na detekciu
			{
				$this->Browser = "Voyager";
        $this->Type = "browser";
				if (eregi("2.03b",$this->USER_AGENT)) {$this->Browser_Version = "2.03b";}
				elseif (eregi("wb/win32/3.4g",$this->USER_AGENT)) {$this->Browser_Version = "3.4g";}
			}
			elseif (eregi("quicktime",$this->USER_AGENT))
			{
				$this->Browser = "QuickTime";
				$this->Type = "browser";
				if (eregi("qtver=5",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("qtver=6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("qtver=6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
				elseif (eregi("qtver=6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
				elseif (eregi("qtver=6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
				elseif (eregi("qtver=6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
				elseif (eregi("qtver=6.5",$this->USER_AGENT)) {$this->Browser_Version = "6.5";}
			}
			elseif (eregi("safari",$this->USER_AGENT))
			{
				$this->Browser = "Safari";
				$this->Type = "browser";
				if (eregi("safari/48",$this->USER_AGENT)) {$this->Browser_Version = "0.48";}
				elseif (eregi("safari/49",$this->USER_AGENT)) {$this->Browser_Version = "0.49";}
				elseif (eregi("safari/51",$this->USER_AGENT)) {$this->Browser_Version = "0.51";}
				elseif (eregi("safari/60",$this->USER_AGENT)) {$this->Browser_Version = "0.60";}
				elseif (eregi("safari/61",$this->USER_AGENT)) {$this->Browser_Version = "0.61";}
				elseif (eregi("safari/62",$this->USER_AGENT)) {$this->Browser_Version = "0.62";}
				elseif (eregi("safari/63",$this->USER_AGENT)) {$this->Browser_Version = "0.63";}
				elseif (eregi("safari/64",$this->USER_AGENT)) {$this->Browser_Version = "0.64";}
				elseif (eregi("safari/65",$this->USER_AGENT)) {$this->Browser_Version = "0.65";}
				elseif (eregi("safari/66",$this->USER_AGENT)) {$this->Browser_Version = "0.66";}
				elseif (eregi("safari/67",$this->USER_AGENT)) {$this->Browser_Version = "0.67";}
				elseif (eregi("safari/68",$this->USER_AGENT)) {$this->Browser_Version = "0.68";}
				elseif (eregi("safari/69",$this->USER_AGENT)) {$this->Browser_Version = "0.69";}
				elseif (eregi("safari/70",$this->USER_AGENT)) {$this->Browser_Version = "0.70";}
				elseif (eregi("safari/71",$this->USER_AGENT)) {$this->Browser_Version = "0.71";}
				elseif (eregi("safari/72",$this->USER_AGENT)) {$this->Browser_Version = "0.72";}
				elseif (eregi("safari/73",$this->USER_AGENT)) {$this->Browser_Version = "0.73";}
				elseif (eregi("safari/74",$this->USER_AGENT)) {$this->Browser_Version = "0.74";}
				elseif (eregi("safari/80",$this->USER_AGENT)) {$this->Browser_Version = "0.80";}
				elseif (eregi("safari/83",$this->USER_AGENT)) {$this->Browser_Version = "0.83";}
				elseif (eregi("safari/84",$this->USER_AGENT)) {$this->Browser_Version = "0.84";}
				elseif (eregi("safari/85",$this->USER_AGENT)) {$this->Browser_Version = "0.85";}
				elseif (eregi("safari/90",$this->USER_AGENT)) {$this->Browser_Version = "0.90";}
				elseif (eregi("safari/92",$this->USER_AGENT)) {$this->Browser_Version = "0.92";}
				elseif (eregi("safari/93",$this->USER_AGENT)) {$this->Browser_Version = "0.93";}
				elseif (eregi("safari/94",$this->USER_AGENT)) {$this->Browser_Version = "0.94";}
				elseif (eregi("safari/95",$this->USER_AGENT)) {$this->Browser_Version = "0.95";}
				elseif (eregi("safari/96",$this->USER_AGENT)) {$this->Browser_Version = "0.96";}
				elseif (eregi("safari/97",$this->USER_AGENT)) {$this->Browser_Version = "0.97";}
				elseif (eregi("safari/125",$this->USER_AGENT)) {$this->Browser_Version = "1.25";}
			}
			elseif (eregi("sextatnt",$this->USER_AGENT))
			{
				$this->Browser = "Tango";
				$this->Type = "browser";
				if (eregi("sextant v3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
			}
			elseif (eregi("sharpreader",$this->USER_AGENT))
			{
				$this->Browser = "SharpReader";
				$this->Type = "browser";
				if (eregi("sharpreader/0.9.5",$this->USER_AGENT)) {$this->Browser_Version = "0.9.5";}
			}
			elseif (eregi("elinks",$this->USER_AGENT))
			{
				$this->Browser = "ELinks";
				$this->Type = "browser";
				if (eregi("0.3",$this->USER_AGENT)) {$this->Browser_Version = "0.3";}
				elseif (eregi("0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
				elseif (eregi("0.9",$this->USER_AGENT)) {$this->Browser_Version = "0.9";}
			}
			elseif (eregi("links",$this->USER_AGENT))
			{
				$this->Browser = "Links";
				$this->Type = "browser";
				if (eregi("0.9",$this->USER_AGENT)) {$this->Browser_Version = "0.9";}
				elseif (eregi("2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
			}
			elseif (eregi("lynx",$this->USER_AGENT))
			{
				$this->Browser = "Lynx";
				$this->Type = "browser";
				if (eregi("lynx/2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
				elseif (eregi("lynx/2.4",$this->USER_AGENT)) {$this->Browser_Version = "2.4";}
				elseif ((eregi("lynx/2.5",$this->USER_AGENT)) || (eregi("lynx 2.5",$this->USER_AGENT))) {$this->Browser_Version = "2.5";}
				elseif (eregi("lynx/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("lynx/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
				elseif (eregi("lynx/2.8",$this->USER_AGENT)) {$this->Browser_Version = "2.8";}
			}
			elseif (eregi("webexplorer",$this->USER_AGENT))
			{
				$this->Browser = "WebExplorer";
				$this->Type = "browser";
				if (eregi("dll/v1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
			}
			elseif (eregi("wget",$this->USER_AGENT))
			{
				$this->Browser = "WGet";
				$this->Type = "browser";
				if (eregi("Wget/1.9",$this->USER_AGENT)) {$this->Browser_Version = "1.9";}
				if (eregi("Wget/1.8",$this->USER_AGENT)) {$this->Browser_Version = "1.8";}
			}
			elseif (eregi("webtv",$this->USER_AGENT))
			{
				$this->Browser = "WebTV";
				$this->Type = "browser";
				if (eregi("webtv/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
				elseif (eregi("webtv/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("webtv/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
				elseif (eregi("webtv/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
				elseif (eregi("webtv/2.5",$this->USER_AGENT)) {$this->Browser_Version = "2.5";}
				elseif (eregi("webtv/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
				elseif (eregi("webtv/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
			}
			elseif (eregi("yandex",$this->USER_AGENT))
			{
				$this->Browser = "Yandex";
				$this->Type = "browser";
				if (eregi("/1.01",$this->USER_AGENT)) {$this->Browser_Version = "1.01";}
				elseif (eregi("/1.03",$this->USER_AGENT)) {$this->Browser_Version = "1.03";}
			}
			elseif ((eregi("mspie",$this->USER_AGENT)) || ((eregi("msie",$this->USER_AGENT))) && (eregi("windows ce",$this->USER_AGENT)))
			{
				$this->Browser = "Pocket Internet Explorer";
				$this->Type = "browser";
				if (eregi("mspie 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
				elseif (eregi("mspie 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("msie 3.02",$this->USER_AGENT)) {$this->Browser_Version = "3.02";}
			}
			elseif (eregi("UP.Browser/",$this->USER_AGENT))
			{
				$this->Browser = "UP Browser";
				$this->Type = "browser";
				if (eregi("Browser/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
			}
			elseif (eregi("msie",$this->USER_AGENT))
			{
				$this->Browser = "Internet Explorer";
				$this->Type = "browser";
				if (eregi("msie 6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
				elseif (eregi("msie 5.5",$this->USER_AGENT)) {$this->Browser_Version = "5.5";}
				elseif (eregi("msie 5.01",$this->USER_AGENT)) {$this->Browser_Version = "5.01";}
				elseif (eregi("msie 5.23",$this->USER_AGENT)) {$this->Browser_Version = "5.23";}
				elseif (eregi("msie 5.22",$this->USER_AGENT)) {$this->Browser_Version = "5.22";}
				elseif (eregi("msie 5.2.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2.2";}
				elseif (eregi("msie 5.1b1",$this->USER_AGENT)) {$this->Browser_Version = "5.1b1";}
				elseif (eregi("msie 5.17",$this->USER_AGENT)) {$this->Browser_Version = "5.17";}
				elseif (eregi("msie 5.16",$this->USER_AGENT)) {$this->Browser_Version = "5.16";}
				elseif (eregi("msie 5.12",$this->USER_AGENT)) {$this->Browser_Version = "5.12";}
				elseif (eregi("msie 5.0b1",$this->USER_AGENT)) {$this->Browser_Version = "5.0b1";}
				elseif (eregi("msie 5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
				elseif (eregi("msie 5.21",$this->USER_AGENT)) {$this->Browser_Version = "5.21";}
				elseif (eregi("msie 5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
				elseif (eregi("msie 5.15",$this->USER_AGENT)) {$this->Browser_Version = "5.15";}
				elseif (eregi("msie 5.14",$this->USER_AGENT)) {$this->Browser_Version = "5.14";}
				elseif (eregi("msie 5.13",$this->USER_AGENT)) {$this->Browser_Version = "5.13";}
				elseif (eregi("msie 4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("msie 4.01",$this->USER_AGENT)) {$this->Browser_Version = "4.01";}
				elseif (eregi("msie 4.0b2",$this->USER_AGENT)) {$this->Browser_Version = "4.0b2";}
				elseif (eregi("msie 4.0b1",$this->USER_AGENT)) {$this->Browser_Version = "4.0b1";}
				elseif (eregi("msie 4",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
				elseif (eregi("msie 3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("msie 2",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
				elseif (eregi("msie 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
			}
			elseif (eregi("iexplore",$this->USER_AGENT))
			{
				$this->Browser = "Internet Explorer";
				$this->Type = "browser";
			}
			elseif (eregi("mozilla",$this->USER_AGENT)) // (2) netscape nie je prilis detekovatelny....
			{
				$this->Browser = "Netscape";
				$this->Type = "browser";
				if (eregi("mozilla/4.8",$this->USER_AGENT)) {$this->Browser_Version = "4.8";}
				elseif (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "4.7";}
				elseif (eregi("mozilla/4.6",$this->USER_AGENT)) {$this->Browser_Version = "4.6";}
				elseif (eregi("mozilla/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
				elseif (eregi("mozilla/4.0",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
				elseif (eregi("mozilla/3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
				elseif (eregi("mozilla/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
			}
		}
	}
?>
