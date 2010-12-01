<?php
/**
 * Description of PersistentieMapper
 *
 * @author Jens
 */
require_once("InstallMapper.php");
require_once("SettingMapper.php");
require_once("LinkpartnerMapper.php");
require_once("WidgetMapper.php");
class PersistentieMapper {
    private static $_instance = null;
    
    private $_installMapper;
    private $_settingMapper;
    private $_linkpartnerMapper;
    private $_widgetMapper;
    
    public static function Instance(){
        if (self::$_instance == null) self::$_instance = new PersistentieMapper();
        return self::$_instance;
    }
    
    public function __construct() {
        $this->_installMapper = new InstallMapper();
        $this->_settingMapper = new SettingMapper();
        $this->_linkpartnerMapper = new LinkpartnerMapper();
        $this->_widgetMapper = new WidgetMapper();
    }
    
    public function InstallDatabase() {
        $this->_installMapper->InstallDatabase();
    }

    public function AddLinkpartner($data) {
        $this->_linkpartnerMapper->AddLinkpartner($data);
    }
    
    public function CheckIfUltimateBlogrollTagWasSet() {
        $result = count($this->_installMapper->GetPagesWithUltimateBlogrollTag());
        if($result > 0)
            return true;
        return false;
    }
    
    public function GetPagesWithUltimateBlogrollTag()
    {
        return $this->_installMapper->GetPagesWithUltimateBlogrollTag();
    }
    
    public function SaveGeneralSettings($data)
    {
        $this->_settingMapper->SaveGeneralSettings($data);
    }
    
    public function SaveWidgetSettings($data)
    {
        $this->_settingMapper->SaveWidgetSettings($data);
    }
    
    public function SaveRecaptchaSettings($data)
    {
        $this->_settingMapper->SaveRecaptchaSettings($data);
    }

    public function GetGeneralSettings() {
        return $this->_settingMapper->GetGeneralSettings();
    }

    public function GetWidgetSettings() {
        return $this->_settingMapper->GetWidgetSettings();
    }

    public function GetRecaptchaSettings() {
        return $this->_settingMapper->GetRecaptchaSettings();
    }

    public function GetLinkpartners($where = null)
    {
        return $this->_linkpartnerMapper->GetLinkpartners($where);
    }

    public function SearchLinkpartners($search)
    {
        return $this->_linkpartnerMapper->SearchLinkpartners($search);
    }

    public function UpdateBacklinkStatus($id, $status)
    {
        $this->_linkpartnerMapper->UpdateBacklinkStatus($id, $status);
    }

    public function UpdateApproveStatus($id, $status)
    {
        $this->_linkpartnerMapper->UpdateApproveStatus($id, $status);
    }

    public function DeleteLinkpartner($id) {
        $this->_linkpartnerMapper->DeleteLinkpartner((int)$id);
    }

    public function GetNumberOfStatus() {
        return $this->_linkpartnerMapper->GetNumberOfStatus();
    }

    public function GetLinkpartnerByID($id) {
        return $this->_linkpartnerMapper->GetLinkpartnerByID($id);
    }

    public function EditLinkpartner($linkpartner) {
        $this->_linkpartnerMapper->EditLinkpartner($linkpartner);
    }

    public function GetLastAddedLinkpartner() {
        return $this->_linkpartnerMapper->GetLastAddedLinkpartner();
    }

    public function GetLinkpartnersWidget($amount, $order, $orderby) {
        return $this->_widgetMapper->GetLinkpartnersWidget($amount, $order, $orderby);
    }
    
    public function AddTotalLinkout($id) {
        $this->_widgetMapper->AddTotalLinkout($id);
    }

    public function AddTotalLinkin($id) {
        $this->_widgetMapper->AddTotalLinkin($id);
    }

    public function Add48Linkout($id) {
        $this->_widgetMapper->Add48Linkout($id);
    }

    public function Add48Linkin($id) {
        $this->_widgetMapper->Add48Linkin($id);
    }

    public function GetIDLinkpartnerFromUrl($linkpartner) {
        return $this->_widgetMapper->GetIDLinkpartnerFromUrl($linkpartner);
    }

    public function GetLinkpartnersToCheckAgainstInlinks() {
        return $this->_widgetMapper->GetLinkpartnersToCheckAgainstInlinks();
    }

    public function DeleteOld48($time) {
        $this->_widgetMapper->DeleteOld48($time);
    }

    public function GetOld48In($time) {
        return $this->_widgetMapper->GetOld48In($time);
    }

    public function GetOld48Out($time) {
        return $this->_widgetMapper->GetOld48Out($time);
    }

    public function GetTemp48In($time) {
        return $this->_widgetMapper->GetTemp48In($time);
    }

    public function GetTemp48Out($time) {
        return $this->_widgetMapper->GetTemp48Out($time);
    }

    public function Min48In($id, $total) {
        $this->_widgetMapper->Min48In($id, $total);
    }

    public function Min48Out($id, $total) {
        $this->_widgetMapper->Min48Out($id, $total);
    }

    public function Plus48In($id, $total) {
        $this->_widgetMapper->Plus48In($id, $total);
    }

    public function Plus48Out($id, $total) {
        $this->_widgetMapper->Plus48Out($id, $total);
    }

    public function UpdateCountedLinks() {
        $this->_widgetMapper->UpdateCountedLinks();
    }

    public function GetLinkpartnersPage($order, $orderby) {
        return $this->_widgetMapper->GetLinkpartnersPage($order, $orderby);
    }
}
?>