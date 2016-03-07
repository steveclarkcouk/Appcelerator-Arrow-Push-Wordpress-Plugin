<?php
/*
Plugin Name:  Appcelerator Arrow DB Push
Description: 
Version: 1
Author: Steve Clark
*/

include('lib/base-class.php');
include('lib/admin.class.php');
include('vendor/Appcelerator-REST-SDK/Cloud.class.php');

if(is_admin()) {
    $arrowdb_pugin = new ArrowDB_Push_Admin();
    $arrowdb_pugin->plugin_url = plugin_dir_url( __FILE__ );
}

?>