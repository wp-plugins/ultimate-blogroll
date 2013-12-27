<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 25/11/11
 * Time: 8:58
 * To change this template use File | Settings | File Templates.
 */
 
class UbSettingsRepository {
    /**
     * Checks if the ultimate blogroll settings are already stored
     * @return bool
     */
    public function doesConfigExist() {
        $data = get_option("ultimate_blogroll_settings");
        if($data !== false) {
            return true;
        }
        return false;
    }

    /**
     * @param $key String the identifier of a setting
     * @param $value String the actual value
     * @param string $option_key
     * @return void
     */
    public function setConfig($key, $value, $option_key = "ultimate_blogroll_settings") {
        $data = get_option($option_key);
        $data[$key] = $value;
        update_option($option_key, $data);
    }

    /**
     * @param $key
     * @param string $option_key
     * @return String setting value
     */
    public function getConfig($key, $option_key = "ultimate_blogroll_settings") {
        $data = get_option($option_key);
        if(isset($data[$key]))
            return $data[$key];
        return false;
    }

    /**
     * Get all the widgets in all the widget bars and remove ultimate-blogroll
     * @param $array, array with ultimate-blogroll reference
     * @return array, cleaned array, no more ultimate-blogroll
     */
    public function RemoveExistingWidget($array) {
        if(is_array($array)) {
            $result = array();
            foreach($array as $key => $value) {
                if($value == "ultimate-blogroll") {
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }
        return $array;
    }


    /**
     * @param $id, post_id
     * @param $save, boolean either save it or delete it.
     */
    /*
    public function savePage($id, $save) {
        $pages = $this->getConfig("pages");
        if(empty($pages))
            $pages = array();
        if($save === true) {
            $pages[$id] = $id;
        } else {
            if(in_array($id, $pages)) {
                unset($pages[$id]);
            }
        }
        $this->setConfig("pages", $pages);
    }*/
}