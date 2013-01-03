<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/05/12
 * Time: 12:49
 * To change this template use File | Settings | File Templates.
 */
class UbAdminPageWidget
{
    /**
     * Show a widget when creating/editing a page
     */
    public function index() {
        if(isset($_GET["post"]) and $_GET["post"] == UbMapper::getInstance(UbMapper::Settings)->getConfig("pages")) {
            $ub_page = "checked";
        } else {
            $ub_page = "";
        }
        require_once(UB_PLUGIN_DIR."gui/AdminPageWidget.php");
    }
}