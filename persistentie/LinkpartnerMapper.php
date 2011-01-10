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
        $result["website_owner_name"] = $data->name;
        $result["website_owner_email"] = $data->email;
        $result["website_name"] = $data->title;
        $result["website_description"] = $data->description;
        $result["website_domein"] = $data->domain;
        $result["website_url"] = $data->url;
        $result["website_backlink"] = $data->reciprocal;
        $result["website_last_update"] = time();
        $result["website_change_id"] = $this->makeRandom(50);
        $result["website_date_added"] = time();
        $result["website_has_backlink"] = $data->has_backlink;
        $result["website_ip"] = $_SERVER['REMOTE_ADDR'];
        $result["website_image"] = $data->image_url;

        
        $sql = $this->database->prepare("INSERT INTO ".$table_name."
            (website_owner_name, website_owner_email, website_name, website_description, website_domein, website_url, website_backlink, website_last_update, website_change_id, website_date_added, website_has_backlink, website_ip, website_image)
        VALUES
            (%s, %s, %s, %s, %s, %s, %s, %d, %s, %d, %d, %s, %s)", $result
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

    public function EditLinkpartner($linkpartner, $website_id) {
        global $wpdb;
        $table_name = $this->database->prefix . "ubsites";
        $data = array(
            "website_owner_name"    => $linkpartner->name,
            "website_owner_email"   => $linkpartner->email,
            "website_name"          => $linkpartner->title,
            "website_description"   => $linkpartner->description,
            "website_domein"        => $linkpartner->domain,
            "website_url"           => $linkpartner->url,
            "website_backlink"      => $linkpartner->reciprocal,
            "website_image"         => $linkpartner->image_url
        );
        $wpdb->update( $table_name, $data, array("website_id" => $website_id) );
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
        //$table_name = $this->database->prefix . "ubsites";
        $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
        return $id;
    }

    public function SendAnouncementMail($linkpartner, $email) {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Wordpress Ultimate Blogroll <'.get_bloginfo('admin_email').'> '."\r\n";

        $subject = __("New link submitted at", "ultimate-blogroll")." ".get_bloginfo('siteurl').''."\r\n";

        $message = __("Hi", "ultimate-blogroll").",<br /><br />".__("Somebody added a new link in", "ultimate-blogroll")." Wordpress Ultimate Blogroll<br />";
        $message .= "<table>";
        $message .= "<tr><td style=\"width: 250px;\">".__("Website owner's name", "ultimate-blogroll").":</td><td>".$linkpartner->name."</td></tr>";
        $message .= "<tr><td>".__("Website owner's email", "ultimate-blogroll").":</td><td>".$linkpartner->email."</td></tr>";
        $message .= "<tr><td><br /></td></tr>";
        $message .= "<tr><td>".__("Website url", "ultimate-blogroll").":</td><td>".$linkpartner->url."</td></tr>";
        $message .= "<tr><td>".__("Website title", "ultimate-blogroll").":</td><td>".$linkpartner->title."</td></tr>";
        $message .= "<tr><td>".__("Website description", "ultimate-blogroll").":</td><td>".$linkpartner->description."</td></tr>";
        $message .= "<tr><td><br /></td></tr>";
        $message .= "<tr><td>".__("Website domain", "ultimate-blogroll").":</td><td>".$linkpartner->domain."</td></tr>";
        $message .= "<tr><td>".__("Website reciprocal", "ultimate-blogroll").":</td><td>".$linkpartner->reciprocal."</td></tr>";
        $id = PersistentieMapper::Instance()->GetLastAddedLinkpartner();
        $message .= "</table>Do you want to <a href=\""."http://".$_SERVER["SERVER_NAME"]."/wp-admin/admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "action" => "edit", "id" => $id ))."#edit\">View details</a> | <a href=\""."http://".$_SERVER["SERVER_NAME"]."/wp-admin/admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "overview_actions" => "approve", "bulk_action" => "Apply", "linkpartner[]" => $id))."\">Approve</a> | <a href=\""."http://".$_SERVER["SERVER_NAME"]."/wp-admin/admin.php?".http_build_query(array("page" => "ultimate-blogroll-overview", "overview_actions" => "delete", "bulk_action" => "Apply", "linkpartner[]" => $id))."\">Delete</a>";

        //$data = PersistentieMapper::Instance()->GetGeneralSettings();
        $m = @mail($email, $subject, $message, $headers);
    }
}
?>