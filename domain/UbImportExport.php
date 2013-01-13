<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 21:49
 */
class UbImportExport {
    /**
     * Show the possibilities to the user, let him choose between importing or exporting the links
     */
    public function index() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["ub_submit"]) and isset($_POST["ub_type"])) {
            if($_POST["ub_type"] == "import") {
                $gui["succes"]["import"] = true;
                $this->importToUltimateBlogroll();
            } elseif($_POST["ub_type"] == "export") {
                $gui["succes"]["export"] = true;
                $this->exportToWordpress();
            }
        }

        require_once(UB_PLUGIN_DIR . "gui" . DIRECTORY_SEPARATOR . "ImportExport.php");
    }

    /**
     * Export the links from Ultimate Blogroll into Wordpress
     * If the user is not satisfied, he needs to get out nicely
     */
    public function exportToWordpress() {
        $linksFromWordpress         = UbMapper::getInstance(UbMapper::ImportExport)->GetBlogrollWordpress();
        $linksFromUltimateBlogroll  = UbMapper::getInstance(UbMapper::Linkpartner)->getLinkpartners();
        foreach($linksFromUltimateBlogroll as $u) {
            if($this->CheckIfExists($u["website_url"], $linksFromWordpress, "link_url") === false) {
                $result = array();
                $result["link_url"]         = $u["website_url"];
                $result["link_name"]        = $u["website_name"];
                $result["link_description"] = $u["website_description"];
                $result["link_visible"]     = $u["website_status"];
                //$result["link_owner"]       = $user_id;
                $result["link_image"]       = $u["website_image"];
                //$result                     = array_map ( array($this, 'map_entities'), $result );
                UbMapper::getInstance(UbMapper::ImportExport)->addLinkpartnerToWordpress($result);
            }
        }
    }
    /**
     * Import the links from Wordpress into Ultimate Blogroll
     */
    public function importToUltimateBlogroll() {
        $linksFromWordpress         = UbMapper::getInstance(UbMapper::ImportExport)->GetBlogrollWordpress();
        $linksFromUltimateBlogroll  = UbMapper::getInstance(UbMapper::Linkpartner)->getLinkpartners();
        foreach($linksFromWordpress as $link) {
            if($this->CheckIfExists($link["link_url"], $linksFromUltimateBlogroll, "website_url") === false) {
                $url = parse_url($link["link_url"]);
                $link["domain"] = $url["host"];
                UbMapper::getInstance(UbMapper::ImportExport)->AddLinkpartnerFromWordpress($link);
            }
        }
    }
    /**
     * @param $needle, what are we looking for
     * @param $haystack, the array we need to loop through
     * @param $key, the haystack is a 2 dimensional array, we need a key
     * @return bool, found the needle (true) or not (false)
     */
    private function CheckIfExists($needle, $haystack, $key) {
        foreach($haystack as $hay) {
            if($needle == $hay[$key])
                return true;
        }
        return false;
    }
}