<?php
/**
 * Description of ImportExportMapper
 *
 * @author Jens
 */
class ImportExportMapper {
    public function GetBlogrollWordpress() {
        global $wpdb;
        $table_name = $wpdb->prefix . "links";
        return $wpdb->get_results('SELECT link_url, link_name, link_description, link_visible, link_image FROM '.$table_name, ARRAY_A);
    }

    public function AddLinkpartnerFromWordpress($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . "ubsites";
        $result = array();
        $result["website_name"]             = $data["link_name"];
        $result["website_description"]      = $data["link_description"];
        $result["website_domein"]           = $data["domain"];
        $result["website_url"]              = $data["link_url"];
        $result["website_change_id"]        = PersistentieMapper::Instance()->makeRandom(50);
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

    public function AddLinkpartnerToWordpress($data) {
        //global $wpdb;

        if($data["link_visible"] == "a")
            $data["link_visible"]     = "Y";
        else
            $data["link_visible"]     = "N";
        $link_id = wp_insert_link( $data );
        /*
        $table_name = $wpdb->prefix . "links";
        if($data["link_visible"] == "a")
            $data["link_visible"]     = "Y";
        else
            $data["link_visible"]     = "N";
        $sql = $wpdb->prepare("INSERT INTO ".$table_name."
            (link_url, link_name, link_category, link_description, link_visible, link_owner, link_rating, link_image)
        VALUES
            (%s, %s, 0, %s, %s, %d, 0, %s)", $data
        );
        $wpdb->query($sql);

        $id = PersistentieMapper::Instance()->GetLastAddedLinkpartner();
        var_dump($id);
        */
    }
}
?>