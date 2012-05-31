<?php
/*
http://www.zdnet.be/news/140006/israel-flame-malware-komt-niet-van-ons//*
Plugin Name: Ultimate Blogroll
Plugin URI: http://ultimateblogroll.gheerardyn.be
Description: Ultimate Blogroll is a plugin which enables your visitors to submit a linktrade. Your visitors can add their own website and you can keep track of the in- and outlinks. 
Version: 2.1
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
//error_reporting(-1);
define("UB_PLUGIN_DIR", ABSPATH."wp-content".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."ultimate-blogroll".DIRECTORY_SEPARATOR);
define("UB_PUBLIC_URL", "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?");
define("UB_ASSETS_URL", plugins_url('gui/assets/', __FILE__));
define("UB_ADMIN_URL", "http://".$_SERVER["SERVER_NAME"]."/wp-admin".$_SERVER["SCRIPT_NAME"]."?");
load_plugin_textdomain( 'ultimate-blogroll', null, dirname( plugin_basename( __FILE__ ) ) . '/gui/languages/' );
require_once("domain/Controller.php");
require_once("persistence/Mapper.php");
require_once("domain/Main.php");

/**
 * Checks if we are in the admin panel
 */
if (is_admin()) {
    //plugin is actived, deactived, uninstalled. What should happen now?
    register_activation_hook(__FILE__, array(Controller::getInstance(Controller::Install), "ubActivate"));
    register_deactivation_hook(__FILE__, array(Controller::getInstance(Controller::Install), "ubDeactivate"));
    register_uninstall_hook(__FILE__, array(Controller::getInstance(Controller::Install), "ubUninstall"));
    //check if the plugin is working properly, if not what user interaction is required to solve the issue?
    add_action("admin_notices", array(Controller::getInstance(Controller::Install), "admin_notices"));
    //show Ultimate Blogroll in the admin menu (wordpress admin)
    add_action("admin_menu", array(Controller::getInstance(Controller::Main), 'adminMenu'));

    //check if a page is an Ultimate Blogroll page
    add_action("publish_page", array(Controller::getInstance(Controller::Settings), "pages"));
    add_action("edit_post", array(Controller::getInstance(Controller::Settings), "pages"));
    $plugin = plugin_basename(__FILE__);
    add_filter('plugin_action_links_'.$plugin, array(Controller::getInstance(Controller::Install), 'plugins_link'), 10, 2 );
    /**
     * This function is called from admin_menu()
     * It checks which page/function should be called
     * Router
     * @return void
     */
    function ultimate_blogroll() {
        switch(@$_GET["page"])
        {
            case "ultimate-blogroll-overview":
                Controller::getInstance(Controller::Linkpartner)->index();
                break;
            case "ultimate-blogroll-add-linkpartner":
                Controller::getInstance(Controller::Linkpartner)->addLinkpartner(true);
                break;
            case "ultimate-blogroll-settings":
                Controller::getInstance(Controller::Settings)->index();
                break;
            case "ultimate-blogroll-import-export":
                Controller::getInstance(Controller::ImportExport)->index();
                break;
            default:
                Controller::getInstance(Controller::Overview)->index();
                break;
        }
    }
}
//here we initiate the Ultimate Blogroll page. This is the page where users can register their website
add_filter('the_content', array(Controller::getInstance(Controller::Page), 'createPage'));
//initiate the widgets
add_action('plugins_loaded', array(Controller::getInstance(Controller::Widget), "widgetInit"));
//register the wordpress cronjob callback function to check backlinks of the linkpartners
add_action('check_linkpartners', array(Controller::getInstance(Controller::Linkpartner), "checkLinkpartners"));
//make sure jquery is loaded, in some rare cases and basic blogs jquery is not automatically loaded
//use the wordpress build in jquery file
wp_enqueue_script('jquery');
//ajax
add_action('wp_ajax_nopriv_ub_ajax_action_callback', array(Controller::getInstance(Controller::Linkpartner), 'ub_ajax_action_callback'));
add_action('wp_ajax_ub_ajax_action_callback', array(Controller::getInstance(Controller::Linkpartner), 'ub_ajax_action_callback'));
//check for inlinks
Controller::getInstance(Controller::Linkpartner)->checkInlinks();
add_action('ub_hourly_event', array(Controller::getInstance(Controller::Linkpartner), 'ub_hourly_task'));
add_action('wp_head', array(Controller::getInstance(Controller::Page), 'ub_javascript_init'));
?>