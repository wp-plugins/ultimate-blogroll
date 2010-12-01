<?php
/**
 * Description of LinkpartnerController
 *
 * @author Jens
 */
require_once($path."domein/UltimateBlogrollController.php");
class LinkpartnerController extends UltimateBlogrollController {
    private $_type;
    public function __construct($type) {
        parent::__construct();
        $this->_type = $type;
        global $gui;
        $gui["base_url"] = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?";
    }
    
    private function CheckWizard() {
        switch(@$_GET["action"])
        {
            case "wizard":
                $this->wizard();
                break;
            case "edit":
                $this->edit();
                break;
            default:
                $this->overview();
                break;
        }
    }
    
    public function execute()
    {
        $_POST = is_array($_POST) ? array_map('stripslashes_deep', $_POST) : stripslashes($_POST);
        switch($this->_type)
        {
            case "overview":
                $this->CheckWizard();
                break;
            case "add-linkpartner":
                $this->add_linkpartner();
                break;
            default:
                $this->CheckWizard();
                break;
        }
    }
    
    private function wizard() {
        global $gui;
        $this->PreparePage();
        $gui["title"] = "Wizard";

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/wizard.php");
        $result = ob_get_clean();
        echo $result;
    }
    
    private function overview() {
        global $gui;
        $this->PreparePage();
        $this->ub_hourly_task();

        //create the links (example): all (3) approved (2) unapproved (1)
        $gui["status_count"] = PersistentieMapper::Instance()->GetNumberOfStatus();
        
        $gui["status_count"]["link_all"]        = $gui["base_url"].http_build_query( array("page" => @$_GET["page"], "status" => "all") );
        $gui["status_count"]["link_approved"]   = $gui["base_url"].http_build_query( array("page" => @$_GET["page"], "status" => "approved") );
        $gui["status_count"]["link_unapproved"] = $gui["base_url"].http_build_query( array("page" => @$_GET["page"], "status" => "unapproved") );

        //if the search button was hit an alternative mapper and subtitle was called.
        if(!isset($_GET["search_button"])) {
            $gui["linkpartners"] = PersistentieMapper::Instance()->GetLinkpartners((isset($_GET["status"]) ? $_GET["status"] : ""));
            $gui["title"] = "Overview <a class=\"button add-new-h2\" href=\"admin.php?page=ultimate-blogroll-add-linkpartner\">Add new</a>";

            if(isset($_GET["bulk_action"])) {
                if(!isset($_GET["linkpartner"]))
                    return false;
                foreach(@$_GET["linkpartner"] as $link)
                {
                    //var_dump($link);
                    switch(@$_GET["overview_actions"]) {
                        case "approve":
                            PersistentieMapper::Instance()->UpdateApproveStatus($link, "a");
                            break;
                        case "unapprove":
                            PersistentieMapper::Instance()->UpdateApproveStatus($link, "u");
                            break;
                        case "delete":
                            PersistentieMapper::Instance()->DeleteLinkpartner($link);
                            break;
                    }
                    $gui["status_count"] = PersistentieMapper::Instance()->GetNumberOfStatus();
                    $gui["status_count"]["link_all"]        = $gui["base_url"].http_build_query(array_merge( (array) array("page" => @$_GET["page"]), (array) array("status" => "all")));
                    $gui["status_count"]["link_approved"]   = $gui["base_url"].http_build_query(array_merge( (array) array("page" => @$_GET["page"]), (array) array("status" => "approved") ));
                    $gui["status_count"]["link_unapproved"] = $gui["base_url"].http_build_query(array_merge( (array) array("page" => @$_GET["page"]), (array) array("status" => "unapproved")));
                    $gui["linkpartners"] = PersistentieMapper::Instance()->GetLinkpartners((isset($_GET["status"]) ? $_GET["status"] : ""));
                }
            }

            //check reciprocal url
            if(isset($_GET["check_reciprocal_url"])) {
                //loop through each element
                foreach($gui["linkpartners"] as $linkpartner) {
                    //if a backlink is set, then check for a link back, there has to one if is set.
                    if(!empty($linkpartner["website_backlink"])) {
                        //return 1 if true; 0 if false;
                        $backlink = (int)$this->checkreciprocalLink($linkpartner["website_backlink"]);
                        //set in array, so that gui changes with the check

                        //save the values, showing them in the gui is not enough
                        PersistentieMapper::Instance()->UpdateBacklinkStatus($linkpartner["website_id"], $backlink);
                    }
                }
                $gui["linkpartners"] = PersistentieMapper::Instance()->GetLinkpartners((isset($_GET["status"]) ? $_GET["status"] : ""));
            }
        } else {
            //if the user hit the search the button we have a different query and a new subtitle was added
            $gui["linkpartners"] = PersistentieMapper::Instance()->SearchLinkpartners(@$_GET["s"]);
            $gui["title"] = "Overview <a class=\"button add-new-h2\" href=\"admin.php?page=ultimate-blogroll-add-linkpartner\">Add new</a><span class=\"subtitle\">Search results for “".htmlentities(@$_GET["s"])."”</span>";
        }
        $gui = array_map ( array($this, 'map_entities'), $gui );
        
        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/overview.php");
        $result = ob_get_clean();
        echo $result;
    }

    private function edit() {
        global $gui;
        //$gui = $this->gui;
        $gui["edit"] = true;
        $this->checkFormAddLinkpartner();
        $this->overview();
        //everything went through the map_entities() in $this->overview(), otherwise it went through it twice
        //$gui = array_map ( array($this, 'map_entities'), $gui );
        $gui["edit"] = (bool)$gui["edit"];

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/add_linkpartner.php");
        $result = ob_get_clean();
        echo '<div class="wrap"><div id="edit"></div><div id="poststuff" class="metabox-holder" style="margin-top: 20px;">';
        echo $result;
        echo '</div></div>';
    }
    
    private function add_linkpartner() {
        global $gui;
        $this->PreparePage();
        $gui["edit"] = false;
        $gui["title"] = "Add linkpartner";
        $this->checkFormAddLinkpartner();

        $gui = array_map ( array($this, 'map_entities'), $gui );

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/add_linkpartner.php");
        $result = ob_get_clean();
        echo $result;
    }
    
    

    
}
?>