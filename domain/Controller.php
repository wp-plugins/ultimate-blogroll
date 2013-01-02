<?php
namespace UltimateBlogroll;
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */
 
class Controller {
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
        if(!isset(Controller::$instance[$class])) {
            switch($class) {
                case Controller::Main:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Main.php");
                    Controller::$instance[$class] = new Main();
                    break;
                case Controller::Linkpartner:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Linkpartner.php");
                    Controller::$instance[$class] = new Linkpartner();
                    break;
                case Controller::Settings:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Settings.php");
                    Controller::$instance[$class] = new Settings();
                    break;
                case Controller::AdminPageWidget:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."AdminPageWidget.php");
                    Controller::$instance[$class] = new AdminPageWidget();
                    break;
                case Controller::Install:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Install.php");
                    Controller::$instance[$class] = new Install();
                    break;
                case Controller::ImportExport:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."ImportExport.php");
                    Controller::$instance[$class] = new ImportExport();
                    break;
                case Controller::Page:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Page.php");
                    Controller::$instance[$class] = new Page();
                    break;
                case Controller::Widget:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Widget.php");
                    Controller::$instance[$class] = new Widget();
                    break;
                case Controller::Wizard:
                    require_once(UB_PLUGIN_DIR."domain".DIRECTORY_SEPARATOR."Wizard.php");
                    Controller::$instance[$class] = new Wizard();
                    break;
                default:
                    trigger_error("Controller does not exists", E_USER_ERROR);
                    break;
            }
        }
        return Controller::$instance[$class];
    }
}
