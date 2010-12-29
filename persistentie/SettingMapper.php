<?php
/**
 * Description of SettingMapper
 *
 * @author Jens
 */
class SettingMapper {
    private $database;
    
    public function __construct()
    {
        global $wpdb;
        $this->database = $wpdb;
    }
    
    public function SaveGeneralSettings($data) {
        update_option("ultimate_blogroll_general_settings", $data);
    }
    
    public function SaveWidgetSettings($data) {
        update_option("ultimate_blogroll_widget_settings", $data);
    }
    
    public function SaveRecaptchaSettings($data) {
        update_option("ultimate_blogroll_recaptcha_settings", $data);
    }

    public function GetGeneralSettings() {
        return get_option("ultimate_blogroll_general_settings");
    }

    public function GetWidgetSettings() {
        return get_option("ultimate_blogroll_widget_settings");
    }

    public function GetRecaptchaSettings() {
        return get_option("ultimate_blogroll_recaptcha_settings");
    }
}
?>