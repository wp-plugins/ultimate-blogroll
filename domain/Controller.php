<?php
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
    //const Overview      = "Overview";
    const Install       = "Install";
    const ImportExport  = "ImportExport";
    const Page          = "Page";
    const Widget        = "Widget";
    const Wizard        = "Wizard";

    /**
     * Singleton Factory
     * @param $class
     * @return void
     */
    public static function getInstance($class) {
        if(!isset(Controller::$instance[$class])) {
            switch($class) {
                case Controller::Linkpartner:
                    require_once(UB_PLUGIN_DIR."domain/Linkpartner.php");
                    Controller::$instance[$class] = new Linkpartner();
                    break;
                case Controller::Settings:
                    require_once(UB_PLUGIN_DIR."domain/Settings.php");
                    Controller::$instance[$class] = new Settings();
                    break;
                /*case Controller::Overview:
                    require_once(UB_PLUGIN_DIR."domain/Overview.php");
                    Controller::$instance[$class] = new Overview();
                    break;*/
                case Controller::Install:
                    require_once(UB_PLUGIN_DIR."domain/Install.php");
                    Controller::$instance[$class] = new Install();
                    break;
                case Controller::ImportExport:
                    require_once(UB_PLUGIN_DIR."domain/ImportExport.php");
                    Controller::$instance[$class] = new ImportExport();
                    break;
                case Controller::Page:
                    require_once(UB_PLUGIN_DIR."domain/Page.php");
                    Controller::$instance[$class] = new Page();
                    break;
                case Controller::Widget:
                    require_once(UB_PLUGIN_DIR."domain/Widget.php");
                    Controller::$instance[$class] = new Widget();
                    break;
                case Controller::Wizard:
                    require_once(UB_PLUGIN_DIR."domain/Wizard.php");
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
