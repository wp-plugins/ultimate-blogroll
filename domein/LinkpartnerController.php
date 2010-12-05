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
        //$gui["title"] = "Wizard";
        switch(@$_GET["step"])
        {
            case "step1":
                $page = "page1";
                break;
            case "step2":
                $page = "page2";

                $widgets = get_option("sidebars_widgets");
                $test = $widgets["wp_inactive_widgets"];
                var_dump($test);
                
                $key_to_delete = array_search("ultimate-blogroll", $widgets["wp_inactive_widgets"]);
                var_dump($key_to_delete);
                unset($widget["wp_inactive_widgets"][$key_to_delete]);
                //array_unshift($widgets["sidebar-1"], "ultimate-blogroll");
                $widgets["sidebar-1"] = array();
                update_option("sidebars_widgets", $widgets);
                $widget_names = $widgets;

                unset($widget_names["wp_inactive_widgets"]);
                unset($widget_names["array_version"]);
                foreach($widget_names as $key => $value) {
                    echo $key."<br />";
                }
                //var_dump($widgets);
                //$widgets = unserialize($widgets);

                echo "<pre>";
                var_dump($widgets);
                echo "</pre>";

                echo "<pre>";
                var_dump($widget_names);
                echo "</pre>";

                break;
            case "step3":
                $page = "page3";
                break;
            default:
                $page = "page1";
                break;
        }
        var_dump($page);
        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/wizard/".$page.".php");
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
            $gui["title"] = __("Overview", "ultimate-blogroll")." <a class=\"button add-new-h2\" href=\"admin.php?page=ultimate-blogroll-add-linkpartner\">".__("Add new", "ultimate-blogroll")."</a>";

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
            $gui["title"] = __("Overview", "ultimate-blogroll")." <a class=\"button add-new-h2\" href=\"admin.php?page=ultimate-blogroll-add-linkpartner\">".__("Add new", "ultimate-blogroll")."</a><span class=\"subtitle\">".__("Search results for", "ultimate-blogroll")." “".htmlentities(@$_GET["s"])."”</span>";
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
        //$gui["edit"] = true;
        $general_settings = PersistentieMapper::Instance()->GetGeneralSettings();
        $captcha_settings = PersistentieMapper::Instance()->GetRecaptchaSettings();
        if(isset($_POST["add_linkpartner"])) {
            $linkpartner = new LinkpartnerDTO(
                    @$_POST["your_name"],
                    @$_POST["your_email"],
                    @$_POST["website_url"],
                    @$_POST["website_title"],
                    @$_POST["website_description"],
                    @$_POST["website_domain"],
                    @$_POST["website_reciprocal"]
            );
            $error = $this->checkFormAddLinkpartner($linkpartner, false, $general_settings["fight_spam"], $captcha_settings["recaptcha_private_key"], true);
            echo "<pre>";
            var_dump($error);
            echo "</pre>";
        }

        
        $this->overview();
        //everything went through the map_entities() in $this->overview(), otherwise it went through it twice
        //$gui = array_map ( array($this, 'map_entities'), $gui );
        $gui["edit"] = true;

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
        $gui["title"] = __("Add linkpartner", "ultimate-blogroll");
        //$general_settings = PersistentieMapper::Instance()->GetGeneralSettings();
        //$captcha_settings = PersistentieMapper::Instance()->GetRecaptchaSettings();
        if(isset($_POST["add_linkpartner"])) {
            $linkpartner = new LinkpartnerDTO(
                    @$_POST["your_name"],
                    @$_POST["your_email"],
                    @$_POST["website_url"],
                    @$_POST["website_title"],
                    @$_POST["website_description"],
                    @$_POST["website_domain"],
                    @$_POST["website_reciprocal"]
            );
            $error = $this->checkFormAddLinkpartner($linkpartner, true, false, false, false);
            //$error = $this->checkFormAddLinkpartner($linkpartner, false, $general_settings["fight_spam"], $captcha_settings["recaptcha_private_key"], true);
            if($error->ContainsErrors() === false){
                PersistentieMapper::Instance()->AddLinkpartner($linkpartner);
                //TODO: verstuur mail
                $gui["success"] = true;
            }
            $gui["value"]["your_name"]           = $linkpartner->name;
            $gui["value"]["your_email"]          = $linkpartner->email;
            $gui["value"]["website_url"]         = $linkpartner->url;
            $gui["value"]["website_title"]       = $linkpartner->title;
            $gui["value"]["website_description"] = $linkpartner->description;
            $gui["value"]["website_domain"]      = $linkpartner->domain;
            $gui["value"]["website_reciprocal"]  = $linkpartner->reciprocal;

            $gui["error"]["messages"]["addlinkpartner"] = $error->GetErrorMessages();
            $gui["error"]["fields"]             = $error->GetErrorFields();
            unset($error);
            unset($linkpartner);
        } else {
            $gui["value"] = array();
        }
        
        //$this->checkFormAddLinkpartner();
        $gui["value"] = array_map ( array($this, 'map_entities'), $gui["value"] );

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/add_linkpartner.php");
        $result = ob_get_clean();
        echo $result;
    }

    private function SendAnouncementMail() {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Wordpress Ultimate Blogroll <'.get_bloginfo('admin_email').'> '."\r\n";

        $subject = __("New link submitted at", "ultimate-blogroll").get_bloginfo('siteurl').''."\r\n";

        $message = __("Hi", "ultimate-blogroll").",<br /><br />".__("Somebody added a new link in", "ultimate-blogroll")." Wordpress Ultimate Blogroll<br />";
        $message .= "<table>";
        $message .= "<tr><td style=\"width: 250px;\">".__("Website owner's name", "ultimate-blogroll").":</td><td>".$gui["value"]["your_name"]."</td></tr>";
        $message .= "<tr><td>".__("Website owner's email", "ultimate-blogroll").":</td><td>".$gui["value"]["your_email"]."</td></tr>";
        $message .= "<tr><td><br /></td></tr>";
        $message .= "<tr><td>".__("Website url", "ultimate-blogroll").":</td><td>".$gui["value"]["website_url"]."</td></tr>";
        $message .= "<tr><td>".__("Website title", "ultimate-blogroll").":</td><td>".$gui["value"]["website_title"]."</td></tr>";
        $message .= "<tr><td>".__("Website description", "ultimate-blogroll").":</td><td>".$gui["value"]["website_description"]."</td></tr>";
        $message .= "<tr><td><br /></td></tr>";
        $message .= "<tr><td>".__("Website domain", "ultimate-blogroll").":</td><td>".$gui["value"]["website_domain"]."</td></tr>";
        $message .= "<tr><td>".__("Website reciprocal", "ultimate-blogroll").":</td><td>".$gui["value"]["website_reciprocal"]."</td></tr>";
    }
}
?>