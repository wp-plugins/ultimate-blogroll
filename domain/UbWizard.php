<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 14/04/12
 * Time: 22:02
 * To change this template use File | Settings | File Templates.
 */
class UbWizard {
    /**
     * Show the Wizard
     */
    public function show() {
        global $wp_registered_sidebars;
        //All the pages published for step 1
        $allPages = UbMapper::getInstance(UbMapper::Install)->getPublishedPages();
        if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["save"])) {
            if(isset($_POST["frmCreate"])) {//create a new page
                $post = array(
                    'post_title' => $_POST["frmPageName"],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                    'post_content' => __('This is now an ultimate blogroll page, check out the page "Ultimate blogroll" details at your right if you want to change this', "ultimate-blogroll"),
                );
                $post_id = wp_insert_post($post);
                UbMapper::getInstance(UbMapper::Settings)->setConfig("pages", $post_id );
            } else {//save the selected pages
                UbMapper::getInstance(UbMapper::Settings)->setConfig("pages", @$_POST["pages"] );
            }
            if(empty($_POST["sides"])) {
                $frmSelectedWidgetBars = array();
            } else {
                $frmSelectedWidgetBars = $_POST["sides"];
            }
            $frmCreate = "";//checkbox, select a page or create a page
            $frmPageName = @$_POST["frmPageName"];
            $frmSelectedPage = @$_POST["pages"];
            if(isset($_POST["frmCreate"])) {
                $frmCreate = "checked";
            }
            $frmImport = "";
            if(isset($_POST["frmImport"])) {
                UbController::getInstance(UbController::ImportExport)->importToUltimateBlogroll();
                $frmImport = "checked";
            }
            //get all the sidebars
            $widgets = get_option("sidebars_widgets");
            //delete all the ultimate-blogroll references
            $widgets = array_map(array(UbMapper::getInstance(UbMapper::Settings), 'RemoveExistingWidget'), $widgets);
            foreach($frmSelectedWidgetBars as $bars) {
                array_unshift($widgets[$bars], "ultimate-blogroll");
            }
            update_option("sidebars_widgets", $widgets);
        } else {
            $frmImport = "checked";
            $frmSides = array();
            $frmCreate = "";
            $frmPageName = "Ultimate Blogroll";
            $frmSelectedPage = UbMapper::getInstance(UbMapper::Settings)->getConfig("pages");
            $frmImport = "checked";

            //get all the sidebars with the ultimate-blogroll plugin in.
            $widget_bars = get_option("sidebars_widgets");
            unset($widget_bars["wp_inactive_widgets"]);
            unset($widget_bars["array_version"]);
            $frmSelectedWidgetBars = array();
            foreach($widget_bars as $key => $bars) {
                //var_dump($key);
                foreach($bars as $bar) {
                    if($bar == "ultimate-blogroll") {
                        $frmSelectedWidgetBars[] = $key;
                    }
                }
            }
        }
        //Get all the widget bars available
        $sidebars_names = array();
        if(count($wp_registered_sidebars) > 0) {
            foreach($wp_registered_sidebars as $sidebars) {
                $side["id"] = $sidebars["id"];
                $side["name"] = $sidebars["name"];
                $sidebars_names[] = $side;
            }
        }
        require_once(UB_PLUGIN_DIR . "gui" . DIRECTORY_SEPARATOR . "Wizard.php");
    }
}