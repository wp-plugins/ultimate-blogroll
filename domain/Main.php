<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/05/12
 * Time: 11:47
 * To change this template use File | Settings | File Templates.
 */
class Main
{
    /**
    * @param $order
    * @return string
    */
    protected  function GetOrder($order) {
        switch($order) {
            case "asc":
                $result = "asc";
                break;
            case "desc":
                $result = "desc";
                break;
            default:
                $result = "asc";
                break;
        }
        return $result;
    }

    /**
    * @param $orderby
    * @return string
    */
    protected  function GetOrderBy($orderby) {
       switch($orderby) {
           case "id":
               $result = "website_id";
               break;
           case "name":
               $result = "website_name";
               break;
           case "inlinks":
               $result = "website_total_inlink";
               break;
           case "outlinks":
               $result = "website_total_outlink";
               break;
           default:
               $result = "website_name";
               break;
       }
       return $result;
    }

    /**
     * @param $limit
     * @return int
     */
    protected  function GetLimit($limit) {
        return (int)$limit;
    }

    /**
     * @param $target
     * @return string
     */
    protected function GetTarget($target) {
        switch($target) {
            case "_blank":
                $result = "_blank";
                break;
            case "_top":
                $result = "_top";
                break;
            case "_none":
                $result = "_none";
                break;
            default:
                $result = "_blank";
                break;
        }

        return " target=\"".$result."\"";
    }

    /**
     * @param $follow
     * @return string
     */
    protected function GetFollow($follow){
        if(!is_home() && $follow == "yes") {
            return " rel=\"nofollow\"";
        }
    }

    /**
     * Admin_menu to create a wordpress admin menu
     * for more information see: http://codex.wordpress.org/Administration_Menus
     * @return void
     */
    public function adminMenu() {
        add_menu_page(
            "Ultimate Blogroll", //page title
            "Ultim. Blogroll", //menu title, apearance in the menu
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-overview", //slug, in the url
            "ultimate_blogroll", //the function linked to the slug, without this function your slug is useless
            UB_ASSETS_URL."icon.png" //the favicon for the menu
        );

        $page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            "Ultimate Blogroll ".__("Overview", "ultimate-blogroll"), //page title
            __("Manage linkpartners", "ultimate-blogroll"), //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-overview", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
        add_action('admin_print_styles-'.$page, 'ub_admin_style_load');

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
        add_meta_box("ultimate-blogroll", "Ultimate Blogroll", array(Controller::getInstance(Controller::AdminPageWidget), "index"), "page", "side", "high");
        //Admin widget

    }
}
