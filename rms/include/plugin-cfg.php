<?php
/*** Plugins ***/
if ( !defined('IN_XRMS') )    {        die('Hacking attempt');        exit;    }
/**
 * To install plugins, just add elements to this array that have
 * the plugin directory name relative to the /plugins/ directory.
 * For instance, for the 'clock' plugin, you'd put a line like
 * the following.
 *    $plugins[0] = "clock";
 *    $plugins[1] = "inventory";
 *
 * Normally, this file is generated by admin/plugin/plugin-admin.php
 */

// Add list of enabled plugins here 
$plugins = array();
$plugins[0] = 'phone';
$plugins[1] = 'useradmin';
$plugins[2] = 'vcard';
$plugins[3] = 'webcalendar';
?>