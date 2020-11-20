<?php

/**
* Tabbed box abstract class
*/
class CTabBox {
/** @var array */
	var $tabs=NULL;
/** @var int The active tab */
	var $active=NULL;
/** @var string The base URL query string to prefix tab links */
	var $baseHRef=NULL;
/** @var string The base path to prefix the include file */
	var $baseInc;

/**
* Constructor
* @param string The base URL query string to prefix tab links
* @param string The base path to prefix the include file
* @param int The active tab
*/
	function CTabBox( $baseHRef='', $baseInc='', $active=0 ) {
		$this->tabs = array();
		$this->active = $active;
		$this->baseHRef = ($baseHRef ? "$baseHRef&" : "?");
		$this->baseInc = $baseInc;
	}
/**
* Gets the name of a tab
* @return string
*/
	function getTabName( $idx ) {
		return $this->tabs[$idx][1];
	}
/**
* Adds a tab to the object
* @param string File to include
* @param The display title/name of the tab
*/
	function add( $file, $title ) {
		$this->tabs[] = array( $file, $title );
	}
/**
* Displays the tabbed box
*
* This function may be overridden
*
* @param string Can't remember whether this was useful
*/
	function show( $extra='' ) {		
		$uistyle = 'blue';
		reset( $this->tabs );
		$s = '';

		// tabbed view
	  $s = '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
		$s .= '<tr><td><table border="0" cellpadding="0" cellspacing="0">';
			
		if ( count($this->tabs)-1 < $this->active ) {
				//Last selected tab is not available in this view. eg. Child tasks
				$this->active = 0;
		}
		foreach( $this->tabs as $k => $v ) {
				$class = ($k == $this->active) ? 'tabon' : 'taboff';
				$sel = ($k == $this->active) ? 'Selected' : '';
				$s .= '<td height="28" valign="middle" width="3" class="tabbed"><img src="./images/tab'.$sel.'Left.png" width="3" height="28" border="0" alt="" /></td>';
				$s .= '<td valign="middle" nowrap="nowrap"  class="tabbed"  background="./images/tab'.$sel.'Bg.png">&nbsp;<a href="'.$this->baseHRef.'tab='.$k.'"  class="tabbed">'.$v[1].'</a>&nbsp;</td>';
				$s .= '<td valign="middle" width="3"  class="tabbed"><img src="./images/tab'.$sel.'Right.png" width="3" height="28" border="0" alt="" /></td>';
				$s .= '<td width="3" class="tabsp"><img src="./images/blank.gif" height="1" width="3" /></td>';
			}
			$s .= '</table></td></tr>';
			$s .= '<tr><td width="100%" colspan="'.(count($this->tabs)*4 + 1).'" class="tabox">';
			echo $s;
			//Will be null if the previous selection tab is not available in the new window eg. Children tasks
			if ( $this->baseInc.$this->tabs[$this->active][0] != "" )
				require $this->baseInc.$this->tabs[$this->active][0].'.php';
			echo '</td></tr></table>';
		} // show
} //class
?>