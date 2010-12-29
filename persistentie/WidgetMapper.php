<?php
/**
 * Description of WidgetMapper
 *
 * @author Jens
 */
class WidgetMapper {
    private $database;

    public function __construct()
    {
        global $wpdb;
        $this->database = $wpdb;
    }

    public function GetLinkpartnersWidget($amount, $order, $orderby) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        //since wpdb breaks the whole thing we have to put our variables directly into the query
        if($amount > 0)
        {
            $q = " limit 0, ".intval($amount);
        } else {
            $q = "";
        }
        $sql = $this->database->prepare("SELECT website_name, website_description, website_url, website_image from ".$table_name." where website_status = 'a' order by ".$orderby." ".$order.$q, array($amount));
        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function AddTotalLinkout($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_total_outlink = website_total_outlink + 1 where website_id = %d", array($id));
        //var_dump($sql);
        $wpdb->query($sql);
    }

    public function AddTotalLinkin($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_total_inlink = website_total_inlink + 1 where website_id = %d", array($id));
        $wpdb->query($sql);
    }

    public function Add48Linkin($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $result = array();
        $result["id"] = $id;
        $result["ip"] = $_SERVER["REMOTE_ADDR"];
        $result["date"] = time();
        $sql = $wpdb->prepare("INSERT INTO ".$table_name." (links_website_id, links_ip, links_date, links_type) VALUES (%d, %s, %d, 'i')", $result);
        $wpdb->query($sql);
    }

    public function Add48Linkout($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $result = array();
        $result["id"] = $id;
        $result["ip"] = $_SERVER["REMOTE_ADDR"];
        $result["date"] = time();
        $sql = $wpdb->prepare("INSERT INTO ".$table_name." (links_website_id, links_ip, links_date, links_type) VALUES (%d, %s, %d, 'o')", $result);
        $wpdb->query($sql);
    }

    public function GetIDLinkpartnerFromUrl($linkpartner) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("select website_id from ".$table_name." where website_url = %s", array($linkpartner));
        return (int)$wpdb->get_var($sql);
    }

    public function GetLinkpartnersToCheckAgainstInlinks() {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("SELECT website_id, website_domein FROM ".$table_name." WHERE website_status = 'a'");
        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function DeleteOld48($time) {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $sql = $wpdb->prepare("delete from ".$table_name." where links_date < %d", array($time)) or die(mysql_error());
        $wpdb->query($sql);
    }

    public function UpdateCountedLinks() {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $sql = $wpdb->prepare("update ".$table_name." set links_counted = 1");
        $wpdb->query($sql);
    }

    public function GetOld48In($time) {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $sql = $wpdb->prepare("select links_website_id, count(links_id) as count from ".$table_name." where links_date < %d and `links_type` = 'i' group by `links_website_id`", array($time));
        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function GetOld48Out($time) {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        $sql = $wpdb->prepare("select links_website_id, count(links_id) as count from ".$table_name." where links_date < %d and `links_type` = 'o' group by `links_website_id`", array($time));
        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function GetTemp48In() {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        return $wpdb->get_results("select links_website_id, count(links_id) as count from ".$table_name." where `links_type` = 'i' and links_counted = 0 group by `links_website_id`", ARRAY_A);
    }

    public function GetTemp48Out() {
        global $wpdb;
        $table_name = $this->database->prefix . "ublinks";
        return $wpdb->get_results("select links_website_id, count(links_id) as count from ".$table_name." where `links_type` = 'o' and links_counted = 0 group by `links_website_id`", ARRAY_A);
    }

    public function Min48In($id, $total) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_last2days_inlink = website_last2days_inlink - %d where website_id = %d", array($id, $total));
        $wpdb->query($sql);
    }

    public function Min48Out($id, $total) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_last2days_outlink = website_last2days_outlink - %d where website_id = %d", array($id, $total));
        $wpdb->query($sql);
    }

    public function Plus48In($id, $total) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_last2days_inlink = website_last2days_inlink + %d where website_id = %d", array($id, $total));
        $wpdb->query($sql);
    }

    public function Plus48Out($id, $total) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $wpdb->prepare("update ".$table_name." set website_last2days_outlink = website_last2days_outlink + %d where website_id = %d", array($id, $total));
        $wpdb->query($sql);
    }

    public function GetLinkpartnersPage($order, $orderby) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = "SELECT website_name, website_description, website_url, website_total_inlink, website_total_outlink, website_last2days_inlink, website_last2days_outlink, website_image from ".$table_name." where website_status = 'a' order by ".$orderby." ".$order."";
        return $wpdb->get_results($sql, ARRAY_A);
    }
}
?>