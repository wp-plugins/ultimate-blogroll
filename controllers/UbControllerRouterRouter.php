<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */
 
class UbControllerRouter {
    private static $instance = array();

    /*
     * Private constructor to make sure this is a singleton class
     */
    private function Controller() {}

    const Linkpartner   = "Linkpartner";
    const Settings      = "Settings";
    const AdminPageWidget = "AdminPageWidget";
    const Install       = "Install";
    const ImportExport  = "ImportExport";
    const Page          = "Page";
    const Widget        = "Widget";
    const Wizard        = "Wizard";
    const Main          = "Main";

    /**
     * Singleton Factory
     * @param $class
     * @return void
     */
    public static function getInstance($class) {
        if(!isset(UbControllerRouter::$instance[$class])) {
            switch($class) {
                case UbControllerRouter::Main:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbMain.php");
                    UbControllerRouter::$instance[$class] = new UbMain();
                    break;
                case UbControllerRouter::Linkpartner:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbLinkpartner.php");
                    UbControllerRouter::$instance[$class] = new UbLinkpartner();
                    break;
                case UbControllerRouter::Settings:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbSettings.php");
                    UbControllerRouter::$instance[$class] = new UbSettings();
                    break;
                case UbControllerRouter::AdminPageWidget:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbAdminPageWidget.php");
                    UbControllerRouter::$instance[$class] = new UbAdminPageWidget();
                    break;
                case UbControllerRouter::Install:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbInstall.php");
                    UbControllerRouter::$instance[$class] = new UbInstall();
                    break;
                case UbControllerRouter::ImportExport:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbImportExport.php");
                    UbControllerRouter::$instance[$class] = new UbImportExport();
                    break;
                case UbControllerRouter::Page:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbPage.php");
                    UbControllerRouter::$instance[$class] = new UbPage();
                    break;
                case UbControllerRouter::Widget:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbWidget.php");
                    UbControllerRouter::$instance[$class] = new UbWidget();
                    break;
                case UbControllerRouter::Wizard:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbWizard.php");
                    UbControllerRouter::$instance[$class] = new UbWizard();
                    break;
                default:
                    trigger_error("Controller does not exists", E_USER_ERROR);
                    break;
            }
        }
        return UbControllerRouter::$instance[$class];
    }
}
