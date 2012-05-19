<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 22/11/11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */
 
class Mapper {
    private static $instance = array();

    const Settings      = "Settings";
    const Install       = "Install";
    const Error         = "Error";
    const Linkpartner   = "Linkpartner";
    const ImportExport  = "ImportExport";
    
    public static function getInstance($class) {
        if(!isset(Mapper::$instance[$class])) {
            switch($class) {
                case Mapper::Settings:
                    require_once(UB_PLUGIN_DIR."persistence/SettingsMapper.php");
                    Mapper::$instance[$class] = new SettingsMapper();
                    break;
                case Mapper::Install:
                    require_once(UB_PLUGIN_DIR."persistence/InstallMapper.php");
                    Mapper::$instance[$class] = new InstallMapper();
                    break;
                case Mapper::Error:
                    require_once(UB_PLUGIN_DIR."persistence/ErrorMapper.php");
                    Mapper::$instance[$class] = new ErrorMapper();
                    break;
                case Mapper::Linkpartner:
                    require_once(UB_PLUGIN_DIR."persistence/LinkpartnerMapper.php");
                    Mapper::$instance[$class] = new LinkpartnerMapper();
                    break;
                case Mapper::ImportExport:
                    require_once(UB_PLUGIN_DIR."persistence/ImportExportMapper.php");
                    Mapper::$instance[$class] = new ImportExportMapper();
                    break;
                default:
                    trigger_error("Mapper does not exists", E_USER_ERROR);
                    break;
            }
        }
        return Mapper::$instance[$class];
    }
}
