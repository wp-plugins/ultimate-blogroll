<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */
 
class UbController {
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
        if(!isset(UbController::$instance[$class])) {
            switch($class) {
                case UbController::Main:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbMain.php");
                    UbController::$instance[$class] = new UbMain();
                    break;
                case UbController::Linkpartner:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbLinkpartner.php");
                    UbController::$instance[$class] = new UbLinkpartner();
                    break;
                case UbController::Settings:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbSettings.php");
                    UbController::$instance[$class] = new UbSettings();
                    break;
                case UbController::AdminPageWidget:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbAdminPageWidget.php");
                    UbController::$instance[$class] = new UbAdminPageWidget();
                    break;
                case UbController::Install:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbInstall.php");
                    UbController::$instance[$class] = new UbInstall();
                    break;
                case UbController::ImportExport:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbImportExport.php");
                    UbController::$instance[$class] = new UbImportExport();
                    break;
                case UbController::Page:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbPage.php");
                    UbController::$instance[$class] = new UbPage();
                    break;
                case UbController::Widget:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbWidget.php");
                    UbController::$instance[$class] = new UbWidget();
                    break;
                case UbController::Wizard:
                    require_once(UB_PLUGIN_DIR . "controllers" . DIRECTORY_SEPARATOR . "UbWizard.php");
                    UbController::$instance[$class] = new UbWizard();
                    break;
                default:
                    trigger_error("Controller does not exists", E_USER_ERROR);
                    break;
            }
        }
        return UbController::$instance[$class];
    }
}
