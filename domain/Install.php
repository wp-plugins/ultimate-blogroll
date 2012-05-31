<?php
/**
* Created by JetBrains PhpStorm.
* User: Jens
* Date: 9/11/11
* Time: 15:16
* To change this template use File | Settings | File Templates.
*/
class Install {
    /**
     * This is related to the admin_notices in the ultimate-blogroll.php file.
     * It checks if everything is ok and otherwise informs the blog owner
     *
     * add_action("admin_notices", array(Controller::getInstance("Install"), "admin_notices"));
     * @return void
     */
    public function admin_notices() {
        if(Mapper::getInstance("Install")->isGetExternalSiteWorking() !== true && Mapper::getInstance(Mapper::Settings)->getConfig("reciprocal_link") == "yes") {
            require_once(UB_PLUGIN_DIR."gui/notices/SocketError.php");
        }
        if(Mapper::getInstance("Install")->doRequiredTablesExist() !== true) {
            require_once(UB_PLUGIN_DIR."gui/notices/TablesDoNotExist.php");
        }
        $pages = Mapper::getInstance(Mapper::Settings)->getConfig("pages");
        if( empty( $pages ) ) {
            require_once(UB_PLUGIN_DIR."gui/notices/noPageWasSet.php");
        }
    }

    public function plugins_link($links) {
        $link = "<a href='admin.php?page=ultimate-blogroll-overview&amp;action=wizard'>".__("Wizard", "ultimate-blogroll")."</a>";
        array_unshift($links, $link);
        $link = "<a href='admin.php?page=ultimate-blogroll-settings'>".__("Settings", "ultimate-blogroll")."</a>";
        array_unshift($links, $link);
        return $links;
    }

    /**
     * Plugin is activated, put the default values into the database
     * @return void
     */
    function ubActivate() {
        wp_schedule_event(time(), 'hourly', 'ub_hourly_event');
        if(Mapper::getInstance("Settings")->doesConfigExist() !== true){
            $default = array();
            $default["website_url"]             = get_bloginfo('siteurl');
            $default["website_title"]           = get_bloginfo('blogname');
            $default["website_description"]     = get_bloginfo('description');

            $default["blogroll_contact"]        = get_bloginfo('admin_email');
            $default['send_mail']               = "yes";

            $default['reciprocal_link']         = "yes";

            $default['target']                  = "_blank";
            $default['nofollow']                = "yes";
            $default['support']                 = "yes";

            $default["widget_title"]            = "Ultimate Blogroll";
            $default["limit_linkpartners"]      = 10;
            $default["order_by"]                = "inlinks";
            $default["ascending"]               = "desc";
            $default['permalink']               = "none";
            $default['logo']                    = "no";
            $default['logo_width']              = "125";
            $default['logo_height']             = "125";
            $default['logo_usage']              = "image";

            $recaptcha = @get_option("recaptcha");
            if($recaptcha !== false) {
                $default['fight_spam']              = "yes";
                $default["recaptcha_public_key"]    = @$recaptcha["pubkey"];
                $default["recaptcha_private_key"]   = @$recaptcha["privkey"];
            } else {
                $default['fight_spam']              = "no";
                $default["recaptcha_public_key"]    = "";
                $default["recaptcha_private_key"]   = "";
            }

            $default["db_version"]              = 2;
            $default["data_version"]            = 4;
            add_option("ultimate_blogroll_settings", $default, "", "yes");
        }
        if(Mapper::getInstance("Install")->doRequiredTablesExist() !== true) {
            Mapper::getInstance("Install")->installDatabase();
        }
        //import all the pages with the <!--ultimate-blogroll--> tag
        $pages = Mapper::getInstance(Mapper::Install)->getPagesWithUltimateBlogrollTag();
        foreach($pages as $page) {
            Mapper::getInstance(Mapper::Settings)->setConfig("pages", $page["id"]);
        }
    }

    /**
     * Plugin is deactivated
     * @return void
     */
    function ubDeactivate() {
        wp_clear_scheduled_hook('ub_hourly_event');
    }

    /**
     * Plugin is removed
     * Now we can remove everything related to ultimate blogroll
     * @return void
     */
    function ubUninstall() {
        //todo: delete the tables and the data
    }
}
