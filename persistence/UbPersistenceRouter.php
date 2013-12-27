<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 22/11/11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */
 
class UbPersistenceRouter {
    private static $instance = array();

    const Settings      = "Settings";
    const Install       = "Install";
    const Error         = "Error";
    const Linkpartner   = "Linkpartner";
    const ImportExport  = "ImportExport";
    
    public static function getInstance($class) {
        if(!isset(UbPersistenceRouter::$instance[$class])) {
            switch($class) {
                case UbPersistenceRouter::Settings:
                    require_once(UB_PLUGIN_DIR."persistence/UbSettingsRepository.php");
                    UbPersistenceRouter::$instance[$class] = new UbSettingsRepository();
                    break;
                case UbPersistenceRouter::Install:
                    require_once(UB_PLUGIN_DIR."persistence/UbInstallRepository.php");
                    UbPersistenceRouter::$instance[$class] = new UbInstallRepository();
                    break;
                case UbPersistenceRouter::Error:
                    require_once(UB_PLUGIN_DIR."persistence/UbErrorRepository.php");
                    UbPersistenceRouter::$instance[$class] = new UbErrorRepository();
                    break;
                case UbPersistenceRouter::Linkpartner:
                    require_once(UB_PLUGIN_DIR."persistence/UbLinkpartnerRepository.php");
                    UbPersistenceRouter::$instance[$class] = new UbLinkpartnerRepository();
                    break;
                case UbPersistenceRouter::ImportExport:
                    require_once(UB_PLUGIN_DIR."persistence/UbImportExportRepository.php");
                    UbPersistenceRouter::$instance[$class] = new UbImportExportRepository();
                    break;
                default:
                    trigger_error("Mapper does not exists", E_USER_ERROR);
                    break;
            }
        }
        return UbPersistenceRouter::$instance[$class];
    }
}
