<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/12/11
 * Time: 19:23
 * To change this template use File | Settings | File Templates.
 */
 
class UbImportExportRepository {
    /**
     * Get all the linkpartners from wordpress
     * Get the blogroll links from wordpress
     * @return array with all the linkpartners from wordpress
     */
    public function GetBlogrollWordpress() {
        global $wpdb;
        $table_name = $wpdb->prefix . "links";
        return $wpdb->get_results('SELECT link_url, link_name, link_description, link_visible, link_image FROM '.$table_name, ARRAY_A);
    }
    /**
     * @param $data, array with all the links from wordpress; array which is the result of GetBlogrollWordpress()
     * Some things differ in ultimate blogroll. Ex: website_status is called link_visible in Wordpress.
     */
    public function AddLinkpartnerFromWordpress($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . "ubsites";
        $result = array();
        $result["website_name"]             = $data["link_name"];
        $result["website_description"]      = $data["link_description"];
        $result["website_domein"]           = $data["controllers"];
        $result["website_url"]              = $data["link_url"];
        $result["website_change_id"]        = UbPersistenceRouter::getInstance(UbPersistenceRouter::Install)->makeRandom(50);
        $result["website_date_added"]       = time();
        $result["website_ip"]               = $_SERVER['REMOTE_ADDR'];
        if($data["link_visible"] == "Y")
            $result["website_status"] = "a";
        else
            $result["website_status"] = "u";
        $result["website_image"]            = $data["link_image"];

        $sql = $wpdb->prepare("INSERT INTO ".$table_name."
            (website_name, website_description, website_domein, website_url, website_change_id, website_date_added, website_ip, website_status, website_image)
        VALUES
            (%s, %s, %s, %s, %s, %d, %s, %s, %s)", $result
        );
        $wpdb->query($sql);
    }

    /**
     * @param $data, an array from ultimate-blogroll containing the data for the wordpress blogroll
     */
    public function addLinkpartnerToWordpress($data) {
        if($data["link_visible"] == "a")
            $data["link_visible"]     = "Y";
        else
            $data["link_visible"]     = "N";
        wp_insert_link( $data );
    }
}