<?php
error_reporting(-1);
/*
Plugin Name: Ultimate Blogroll
Plugin URI: http://ultimateblogroll.gheerardyn.be
Description: Ultimate Blogroll is a plugin which enables your visitors to submit a linktrade. Your visitors can add their own website and you can keep track of the in- and outlinks. It is the ultimate tool to manage your links.
Version: 2.5.2
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
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define("UB_PLUGIN_DIR", ABSPATH."wp-content".DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."ultimate-blogroll".DIRECTORY_SEPARATOR);
define("UB_PUBLIC_URL", $protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"]."?");
define("UB_ASSETS_URL", plugins_url('assets/', __FILE__));
load_plugin_textdomain( 'ultimate-blogroll', null, dirname( plugin_basename( __FILE__ ) ) . '/assets/languages/' );
require_once("controllers" . DIRECTORY_SEPARATOR . "UbControllerRouter.php");
require_once("persistence" . DIRECTORY_SEPARATOR . "UbPersistenceRouter.php");
require_once("controllers" . DIRECTORY_SEPARATOR . "UbMain.php");
require_once("tools".DIRECTORY_SEPARATOR."UbSortingHelper.php");
require_once("tools".DIRECTORY_SEPARATOR."UbHtmlHelper.php");
require_once("tools".DIRECTORY_SEPARATOR."UbViewHelper.php");
/**
 * Checks if we are in the admin panel
 */
if (is_admin()) {
    //plugin is actived, deactived, uninstalled. What should happen now?
    register_activation_hook(__FILE__, array(UbControllerRouter::getInstance(UbControllerRouter::Install), "ubActivate"));
    register_deactivation_hook(__FILE__, array(UbControllerRouter::getInstance(UbControllerRouter::Install), "ubDeactivate"));
    register_uninstall_hook(__FILE__, array(UbControllerRouter::getInstance(UbControllerRouter::Install), "ubUninstall"));
    //check if the plugin is working properly, if not what user interaction is required to solve the issue?
    add_action("admin_notices", array(UbControllerRouter::getInstance(UbControllerRouter::Install), "admin_notices"));
    //show Ultimate Blogroll in the admin menu (wordpress admin)
    add_action("admin_menu", "ub_admin_menu");
    //check if a page is an Ultimate Blogroll page
    add_action("publish_page", array(UbControllerRouter::getInstance(UbControllerRouter::Settings), "pages"));
    add_action("edit_post", array(UbControllerRouter::getInstance(UbControllerRouter::Settings), "pages"));
    $plugin = plugin_basename(__FILE__);
    add_filter('plugin_action_links_'.$plugin, array(UbControllerRouter::getInstance(UbControllerRouter::Install), 'plugins_link'), 10, 2 );
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
                UbControllerRouter::getInstance(UbControllerRouter::Linkpartner)->index();
                break;
            case "ultimate-blogroll-add-linkpartner":
                UbControllerRouter::getInstance(UbControllerRouter::Linkpartner)->addLinkpartner(true);
                break;
            case "ultimate-blogroll-settings":
                UbControllerRouter::getInstance(UbControllerRouter::Settings)->index();
                break;
            case "ultimate-blogroll-import-export":
                UbControllerRouter::getInstance(UbControllerRouter::ImportExport)->index();
                break;
            default:
                UbControllerRouter::getInstance(UbControllerRouter::Overview)->index();
                break;
        }
    }
}

//here we initiate the Ultimate Blogroll page. This is the page where users can register their website
add_filter('the_content', array(UbControllerRouter::getInstance(UbControllerRouter::Page), 'createPage'));
//initiate the widgets
//add_action("plugins_loaded", array(UbControllerRouter::getInstance(UbControllerRouter::Widget), "widgetInit"));

require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbWidget.php");
function ub_register_widgets() {
    register_widget( 'UbWidget' );
}
add_action( 'widgets_init', 'ub_register_widgets' );

//register the wordpress cronjob callback function to check backlinks of the linkpartners
add_action('check_linkpartners', array(UbControllerRouter::getInstance(UbControllerRouter::Linkpartner), "checkLinkpartners"));
//make sure jquery is loaded, in some rare cases and basic blogs jquery is not automatically loaded
//use the wordpress build in jquery file
wp_enqueue_script('jquery');
//ajax
add_action('wp_ajax_nopriv_ub_ajax_action_callback', array(UbControllerRouter::getInstance(UbControllerRouter::Linkpartner), 'ub_ajax_action_callback'));
add_action('wp_ajax_ub_ajax_action_callback', array(UbControllerRouter::getInstance(UbControllerRouter::Linkpartner), 'ub_ajax_action_callback'));
//check for inlinks
UbControllerRouter::getInstance(UbControllerRouter::Linkpartner)->checkInlinks();
add_action('ub_hourly_event', array(UbControllerRouter::getInstance(UbControllerRouter::Linkpartner), 'ub_hourly_task'));
add_action('wp_head', array(UbControllerRouter::getInstance(UbControllerRouter::Page), 'ub_javascript_init'));
add_action( 'admin_init', 'ub_admin_init' );
function ub_admin_init() {
    global $pagenow;
    wp_register_style( 'ub_admin_style',  UB_ASSETS_URL."css/admin.css");
    wp_register_style( 'ub_admin_checkbox',  UB_ASSETS_URL."css/checkbox.css");
    wp_register_style( 'ub_admin_apprise',  UB_ASSETS_URL."css/apprise.min.css");
    if($pagenow == "post.php" || $pagenow == "post-new.php")
    {
        wp_enqueue_script( 'ub_checkbox' , UB_ASSETS_URL."js/checkbox.js");
        wp_enqueue_style( 'ub_admin_checkbox' );
    }
}
function ub_admin_style_load() {
    wp_enqueue_style( 'ub_admin_style' );
    wp_enqueue_style( 'ub_admin_checkbox' );
    wp_enqueue_style( 'ub_admin_apprise' );
    wp_enqueue_script( 'ub_checkbox' , UB_ASSETS_URL."js/checkbox.js");
}
function ub_admin_overview() {
    ub_admin_style_load();
    wp_enqueue_script( 'ub_linkpartner' , UB_ASSETS_URL."js/linkpartner.js");
    wp_enqueue_script( 'ub_apprise' , UB_ASSETS_URL."js/apprise-1.5.full.js");
    wp_enqueue_script( 'ub_tablesorter' , UB_ASSETS_URL."js/tablesorter.js");
    wp_enqueue_script( 'ub_tablesorter_filter' , UB_ASSETS_URL."js/tablesorter_filter.js");
}
/**
 * Admin_menu to create a wordpress admin menu
 * for more information see: http://codex.wordpress.org/Administration_Menus
 * @return void
 */
function ub_admin_menu(){
    add_menu_page(
        "Ultimate Blogroll", //page title
        "Ultim. Blogroll", //menu title, apearance in the menu
        "manage_options", //user level, needed before it becomes visible
        "ultimate-blogroll-overview", //slug, in the url
        "ultimate_blogroll", //the function linked to the slug, without this function your slug is useless
        "../wp-content/plugins/ultimate-blogroll/assets/images/icon.png" //the favicon for the menu
    );

    $page = add_submenu_page(
        "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
        "Ultimate Blogroll ".__("Overview", "ultimate-blogroll"), //page title
        __("Manage linkpartners", "ultimate-blogroll"), //menu title
        "manage_options", //user level, needed before it becomes visible
        "ultimate-blogroll-overview", //slug, in the url
        "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
    );
    add_action('admin_print_styles-'.$page, 'ub_admin_overview');

    $page = add_submenu_page(
        "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
        __("Add linkpartner", "ultimate-blogroll"), //page title
        __("Add linkpartner", "ultimate-blogroll"), //menu title
        "manage_options", //user level, needed before it becomes visible
        "ultimate-blogroll-add-linkpartner", //slug, in the url
        "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
    );
    add_action('admin_print_styles-'.$page, 'ub_admin_style_load');

    $page = add_submenu_page(
        "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
        __("Import/Export", "ultimate-blogroll"), //page title
        __("Import/Export", "ultimate-blogroll"), //menu title
        "manage_options", //user level, needed before it becomes visible
        "ultimate-blogroll-import-export", //slug, in the url
        "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
    );
    add_action('admin_print_styles-'.$page, 'ub_admin_style_load');

    $page = add_submenu_page(
        "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
        __("Settings", "ultimate-blogroll"), //page title
        __("Settings", "ultimate-blogroll"), //menu title
        "manage_options", //user level, needed before it becomes visible
        "ultimate-blogroll-settings", //slug, in the url
        "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
    );
    add_action('admin_print_styles-'.$page, 'ub_admin_style_load');
    add_meta_box( "ultimate-blogroll", "Ultimate Blogroll", array(UbControllerRouter::getInstance(UbControllerRouter::Settings), "pagesWidget"), "page", "side", "high");
}