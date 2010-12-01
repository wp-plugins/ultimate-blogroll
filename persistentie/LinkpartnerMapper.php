<?php
/**
 * Description of LinkpartnerMapper
 *
 * @author Jens
 */
class LinkpartnerMapper {
    private $database;

    public function __construct() {
        global $wpdb;
        $this->database = $wpdb;
    }
    
    public function AddLinkpartner($data) {
        $table_name = $this->database->prefix . "ubsites";
        $result = array();
        $result["website_owner_name"] = $data["your_name"];
        $result["website_owner_email"] = $data["your_email"];
        $result["website_name"] = $data["website_title"];
        $result["website_description"] = $data["website_description"];
        $result["website_domein"] = $data["website_domain"];
        $result["website_url"] = $data["website_url"];
        $result["website_backlink"] = $data["website_reciprocal"];
        $result["website_last_update"] = time();
        $result["website_change_id"] = $this->makeRandom(50);
        $result["website_date_added"] = time();
        $result["website_has_backlink"] = @$data["website_has_backlink"];
        $result["website_ip"] = $_SERVER['REMOTE_ADDR'];

        
        $sql = $this->database->prepare("INSERT INTO ".$table_name."
            (website_owner_name, website_owner_email, website_name, website_description, website_domein, website_url, website_backlink, website_last_update, website_change_id, website_date_added, website_has_backlink, website_ip)
        VALUES
            (%s, %s, %s, %s, %s, %s, %s, %d, %s, %d, %d, %s)", $result
        );
        $this->database->query($sql);
    }

    public function GetLinkpartners($where = null) {
        global $wpdb;
        $query = "";
        if(!empty($where))
        {
            switch($where){
                case "approved":
                    $query = " where website_status = 'a' ";
                    break;
                case "unapproved":
                    $query = " where website_status = 'u' ";
                    break;
            }
        }
        $table_name = $this->database->prefix . "ubsites";
        return $wpdb->get_results('SELECT * FROM '.$table_name.' '.$query, ARRAY_A);
    }

    public function SearchLinkpartners($search) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $query = $this->database->prepare("SELECT * FROM ".$table_name." where website_name like '%s'", array("%".$search."%"));
        return $wpdb->get_results($query, ARRAY_A);
    }

    public function GetNumberOfStatus() {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";

        $result["approved"] = 0;
        $result["unapproved"] = 0;

        $status_approved = $wpdb->get_var('SELECT count(*) FROM `'.$table_name.'` where `website_status` = \'a\'');
        $status_unapproved = $wpdb->get_var('SELECT count(*) FROM `'.$table_name.'` where `website_status` = \'u\'');

        $result["approved"] = (int)$status_approved;
        $result["unapproved"] = (int)$status_unapproved;
        
        $result["total"] = $result["approved"] + $result["unapproved"];
        return $result;
    }

    public function UpdateApproveStatus($id, $status) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $wpdb->update( $table_name, array("website_status" => $status), array("website_id" => $id) );
    }

    public function UpdateBacklinkStatus($id, $status) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        //$sql = $this->database->prepare("update '".$table_name."' set website_has_backlink = ", array($id, $status));
        $wpdb->update( $table_name, array("website_has_backlink" => $status), array("website_id" => $id) );
    }

    public function DeleteLinkpartner($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $this->database->prepare("DELETE FROM ".$table_name." WHERE website_id = %d", array($id));
        $this->database->query($sql);
    }

    public function GetLinkpartnerByID($id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $sql = $this->database->prepare("SELECT * FROM ".$table_name." WHERE website_id = %d", array($id));
        return $this->database->get_results($sql, ARRAY_A);
    }

    public function EditLinkpartner($linkpartner) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $data = array(
            "website_owner_name"    => $linkpartner["your_name"],
            "website_owner_email"   => $linkpartner["your_email"],
            "website_name"          => $linkpartner["website_title"],
            "website_description"   => $linkpartner["website_description"],
            "website_domein"        => $linkpartner["website_domain"],
            "website_url"           => $linkpartner["website_url"],
            "website_backlink"      => $linkpartner["website_reciprocal"]
        );
        $wpdb->update( $table_name, $data, array("website_id" => $linkpartner["website_id"]) );
    }

    private function makeRandom($length)
    {
        $availableChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $generatedID = null;
        $lengthOfAvailableChars = count($availableChars) - 1;

        for($i = 0; $i < $length; $i++)
        {
            $randomChar = mt_rand(0, $lengthOfAvailableChars);
            $generatedID .= $availableChars[$randomChar];
        }
        return $generatedID;
    }

    public function GetLastAddedLinkpartner() {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
        return $id;
    }
}
?>
