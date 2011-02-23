<?php
/*
Plugin Name: Ultimate Blogroll
Plugin URI: http://www.gheerardyn.be/wordpress/ultimate-blogroll
Description: Ultimate Blogroll is a plugin which enables your visitors to submit a linktrade. Your visitors can add their own website and you can keep track of the in- and outlinks. 
Version: 1.7.6.1
Author: Jens Gheerardyn
Author URI: http://www.gheerardyn.be
*/
/*  Copyright 2010 - 2011 Jens Gheerardyn  (email: wordpress@gheerardyn.be)
**  Use of this application will be at your own risk.
**  No guarantees or warranties are made directly or implied.
**  The creators cannot and will not be liable or held accountable for damages,
**  direct or consequential.
**
**  This program is free software; you can redistribute it and/or modify
**  it under the terms of the GNU General Public License as published by
**  the Free Software Foundation; either version 2 of the License, or
**  (at your option) any later version.
**
**  This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
**  along with this program; if not, write to the Free Software
**  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
//TODO: remove the error_reporting(E_ALL) error level;
//error_reporting(E_ALL);
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/config.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/UltimateBlogrollController.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/WidgetController.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/persistentie/dto/GeneralSettingsDTO.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/persistentie/dto/WidgetSettingsDTO.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/persistentie/dto/RecaptchaSettingsDTO.php");
load_plugin_textdomain( 'ultimate-blogroll', null, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
/*
if (function_exists('load_plugin_textdomain')) {
      load_plugin_textdomain('ultimate-blogroll', WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/languages', dirname(plugin_basename(__FILE__)).'/languages' );
}
 * 
 */



//save on load, this is the admin panel and doesn't need to be loaded outside the wp admin panel.
if (is_admin()) {
    switch(@$_GET["page"])
    {
        case "ultimate-blogroll-overview":
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/LinkpartnerController.php");
            $controller = new LinkpartnerController("overview");
            break;
        case "ultimate-blogroll-add-linkpartner":
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/LinkpartnerController.php");
            $controller = new LinkpartnerController("add-linkpartner");
            break;
        case "ultimate-blogroll-settings":
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/SettingsController.php");
            $controller = new SettingsController();
            break;
        case "ultimate-blogroll-import-export":
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/ImportExportController.php");
            $controller = new ImportExportController();
            break;
        default:
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/LinkpartnerController.php");
            $controller = new LinkpartnerController("overview");
            break;
    }

    function ultimate_blogroll() {
        global $controller;
        $controller->execute();
    }

    register_activation_hook(__file__, array($controller, "activate"));
    register_deactivation_hook(__FILE__, array($controller, "deactivate"));
    add_action("admin_menu", array($controller, "menu"));
    add_action("admin_notices", array($controller, "admin_notices"));
    //TODO: new feature. It's finished but needs a setting option before releasing
    //add_action("admin_head", array($controller, "admin_head"));
}
$widget_controller = new WidgetController();
add_action("plugins_loaded", array($widget_controller, "widget_init"));
//when a user is not logged in
add_action('wp_ajax_nopriv_ub_ajax_action_callback', array($widget_controller, 'ub_ajax_action_callback'));
add_action('wp_ajax_ub_ajax_action_callback', array($widget_controller, 'ub_ajax_action_callback'));
add_action('wp_head', array($widget_controller, 'ub_javascript_init'));
add_action('ub_hourly_event', array($widget_controller, 'ub_hourly_task'));
add_filter('the_content', array($widget_controller, 'create_page'));
wp_enqueue_script('jquery');

/* INJECT WIDGET AT TOP
    $widgets = get_option("sidebars_widgets");
    $widgets = unserialize($widgets);
    array_unshift($widgets["sidebar-1"], "plugin name");
    update_option("sidebars_widgets", $widgets);
*/
//var_dump(wp_get_schedule('ub_hourly_event'));
?>