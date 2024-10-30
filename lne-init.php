<?php
/*
Plugin Name: Local Navigation Extended
Plugin URI: http://wordpress.org/extend/plugins/local-navigation-extended/
Description: Local Navigation using wp_list_pages.  CMS type widget useful for sites with a large amount of pages.
Version: 0.1
Author: Chris Carvache
Author URI: http://chriscarvache.com
License:  GPL2

Copyright 2012  Chris Carvache  (email : chriscarvache@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('NLWS_LNM_VERSION', 01);
define('NLWS_LNM_URL', rtrim(plugin_dir_url(__FILE__)));
define('NLWS_LNM_DIR', rtrim(plugin_dir_path(__FILE__)));

require_once('lne-functions.php');

add_action("plugins_loaded", "nlws_lnm_init");
register_activation_hook( __FILE__, 'nlws_lnm_activate' );
?>