<?php
/**
 * Description of ImportExportController
 *
 * @author Jens
 */
require_once($path."domein/UltimateBlogrollController.php");
class ImportExportController extends UltimateBlogrollController {
    public function __construct() {
        parent::__construct();
    }

    public function execute() {
        global $gui;
        $this->PreparePage();
        $gui["title"]       = __("Import/Export", "ultimate-blogroll");

        if(isset($_POST["ub_submit"]) && isset($_POST["ub_type"])) {
            if($_POST["ub_type"] == "import") {
                $gui["succes"]["import"] = true;
                $this->GetBlogrollWordpress();
            } elseif($_POST["ub_type"] == "export") {
                $gui["succes"]["export"] = true;
                $this->GetUltimateBlogrollLinks();
            }
        }

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/ImportExport.php");
        $result = ob_get_clean();
        echo $result;
    }

    private function GetBlogrollWordpress() {
        $links      = PersistentieMapper::Instance()->GetBlogrollWordpress();
        $ultimate   = PersistentieMapper::Instance()->GetLinkpartners();
        foreach($links as $link) {
            if($this->CheckIfExists($link["link_url"], $ultimate, "website_url") === false) {
                $url = parse_url($link["link_url"]);
                $link["domain"] = $url["host"];
                
                PersistentieMapper::Instance()->AddLinkpartnerFromWordpress($link);       
            }
        }
    }

    private function CheckIfExists($needle, $haystack, $key) {
        foreach($haystack as $hay) {
            if($needle == $hay[$key])
                return true;
        }
        return false;
    }

    private function GetUltimateBlogrollLinks() {
        global $current_user; get_currentuserinfo();
        $user_id = $current_user->ID;

        $links      = PersistentieMapper::Instance()->GetBlogrollWordpress();
        $ultimate   = PersistentieMapper::Instance()->GetLinkpartners();

        foreach($ultimate as $u) {
            if($this->CheckIfExists($u["website_url"], $links, "link_url") === false) {
                $result = array();
                $result["link_url"]         = $u["website_url"];
                $result["link_name"]        = $u["website_name"];
                $result["link_description"] = $u["website_description"];
                $result["link_visible"]     = $u["website_status"];
                $result["link_owner"]       = $user_id;
                $result["link_image"]       = $u["website_image"];
                $result                     = array_map ( array($this, 'map_entities'), $result );
                PersistentieMapper::Instance()->AddLinkpartnerToWordpress($result);
            }
        }
    }
}
?>