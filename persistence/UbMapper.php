<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 22/11/11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */
 
class UbMapper {
    private static $instance = array();

    const Settings      = "Settings";
    const Install       = "Install";
    const Error         = "Error";
    const Linkpartner   = "Linkpartner";
    const ImportExport  = "ImportExport";
    
    public static function getInstance($class) {
        if(!isset(UbMapper::$instance[$class])) {
            switch($class) {
                case UbMapper::Settings:
                    require_once(UB_PLUGIN_DIR."persistence/UbSettingsMapper.php");
                    UbMapper::$instance[$class] = new UbSettingsMapper();
                    break;
                case UbMapper::Install:
                    require_once(UB_PLUGIN_DIR."persistence/UbInstallMapper.php");
                    UbMapper::$instance[$class] = new UbInstallMapper();
                    break;
                case UbMapper::Error:
                    require_once(UB_PLUGIN_DIR."persistence/UbErrorMapper.php");
                    UbMapper::$instance[$class] = new UbErrorMapper();
                    break;
                case UbMapper::Linkpartner:
                    require_once(UB_PLUGIN_DIR."persistence/UbLinkpartnerMapper.php");
                    UbMapper::$instance[$class] = new UbLinkpartnerMapper();
                    break;
                case UbMapper::ImportExport:
                    require_once(UB_PLUGIN_DIR."persistence/UbImportExportMapper.php");
                    UbMapper::$instance[$class] = new UbImportExportMapper();
                    break;
                default:
                    trigger_error("Mapper does not exists", E_USER_ERROR);
                    break;
            }
        }
        return UbMapper::$instance[$class];
    }
}
