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
                $this->step1();
                break;
            case "step2":
                /*
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
                */
                break;
            default:
                $this->step1();
                break;
        }
        
        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/wizard/page1.php");
        $result = ob_get_clean();
        echo $result;
    }

    private function step1() {
        global $gui;

        //get the sidebars
        global $wp_registered_sidebars;
        
        $gui["number_of_sidebars"] = count($wp_registered_sidebars);
        $sidebars_names = array();
        if($gui["number_of_sidebars"] > 0) {
            foreach($wp_registered_sidebars as $sidebars) {
                $side["id"] = $sidebars["id"];
                $side["name"] = $sidebars["name"];
                $sidebars_names[] = $side;
            }
        }
        $gui["widget_names"] = $sidebars_names;

        $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
        
        //post and gui values
        if(isset($_POST["wizard"])) {
            $gui["value"]["page_title"]     = @$_POST["page_title"];
            $gui["value"]["sidebar"]        = @$_POST["sidebar"];
            $gui["value"]["public_key"]     = @$_POST["public_key"];
            $gui["value"]["private_key"]    = @$_POST["private_key"];

            $error = new ErrorDTO();
            if(empty($gui["value"]["page_title"])) {
                $error->AddErrorField("page_title");
                $error->AddErrorMessage(__("Page title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }
            if(empty($gui["value"]["sidebar"])) {
                $error->AddErrorField("sidebar");
                $error->AddErrorMessage(__("Select sidebar", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            $gui["error"]["messages"]           = $error->GetErrorMessages();
            $gui["error"]["fields"]             = $error->GetErrorFields();

            if($error->ContainsErrors() === false) {

                //code from: http://wordpress.org/support/topic/how-do-i-create-a-new-page-with-the-plugin-im-building?replies=13
                $the_page = get_page_by_title( $gui["value"]["page_title"] );
                if ( ! $the_page ) {
                    // Create post object
                    $_p = array();
                    $_p['post_title'] = $gui["value"]["page_title"];
                    $_p['post_content'] = "<!--ultimate-blogroll-->";
                    $_p['post_status'] = 'publish';
                    $_p['post_type'] = 'page';
                    $_p['comment_status'] = 'closed';
                    $_p['ping_status'] = 'closed';
                    $_p['post_category'] = array(1); // the default 'Uncategorised'
                    // Insert the post into the database
                    $the_page_id = wp_insert_post( $_p );
                }
                else {
                    // the plugin may have been previously active and the page may just be trashed...
                    $the_page_id = $the_page->ID;
                    //make sure the page is not trashed...
                    $the_page->post_status = 'publish';
                    $the_page->comment_status = 'closed';
                    $the_page->ping_status = 'closed';
                    $the_page->post_content = "<!--ultimate-blogroll-->";
                    $the_page_id = wp_update_post( $the_page );
                }
                $widget_settings->UpdatePermalink($the_page_id);
                PersistentieMapper::Instance()->SaveWidgetSettings($widget_settings);

                //delete Ultimate Blogroll because it can only occur 1 time!
                $widgets = get_option("sidebars_widgets");
                $widgets = array_map(array($this, 'RemoveExistingWidget'), $widgets);
                //put it at the top
                array_unshift($widgets[$gui["value"]["sidebar"]], "ultimate-blogroll");
                update_option("sidebars_widgets", $widgets);

                $settings = new RecaptchaSettingsDTO(
                    @$_POST["public_key"],
                    @$_POST["private_key"]
                );

                PersistentieMapper::Instance()->SaveRecaptchaSettings($settings);
                $gui["succes"]["general"] = true;
            }
            //TODO: input controleren
        } else {
            $t = get_the_title($widget_settings->permalink);
            
            $gui["value"]["page_title"]     = ((empty($t)) ? "Ultimate Blogroll" : $t);
            $sidebars_widgets = wp_get_sidebars_widgets();
            if (empty($sidebars_widgets))
                $sidebars_widgets = wp_get_widget_defaults();
            $t = $this->FindWhichSidebar($sidebars_widgets);
            //if the widget was not set on any sidebar, select the first one. Do this only if a sidebar was set
            if($t === false && !empty($gui["widget_names"])) {
                $t = $gui["widget_names"][0]["id"];
            }
            $gui["value"]["sidebar"]        = $t;
            $data = PersistentieMapper::Instance()->GetRecaptchaSettings();
            $gui["value"]["public_key"]     = $data->recaptcha_public_key;
            $gui["value"]["private_key"]    = $data->recaptcha_private_key;
        }
    }

    private function FindWhichSidebar($array) {
        if(isset($array["wp_inactive_widgets"])) {
            unset($array["wp_inactive_widgets"]);
        }
        foreach($array as $key => $value) {
            foreach($value as $val) {
                if($val == "ultimate-blogroll")
                    return $key;
            }
        }
        return false;
    }

    private function RemoveExistingWidget($array) {
        if(is_array($array)) {
            $result = array();
            foreach($array as $key => $value) {
                if($value == "ultimate-blogroll") {
                    //$result[$key] = "ultimate-blogroll";
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }
        return $array;
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
        if(empty($gui["value"]))
            $gui["value"] = array();
        $gui["value"]           = array_map ( array($this, 'map_entities'), $gui["value"] );
        $gui["linkpartners"]    = array_map ( array($this, 'map_entities'), $gui["linkpartners"] );
        
        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/linkpartner/overview.php");
        $result = ob_get_clean();
        echo $result;
    }

    private function edit() {
        global $gui;
        //$gui = $this->gui;
        //$gui["edit"] = true;
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
                    @$_POST["website_reciprocal"],
                    @$_POST["website_image"]
            );
            
            $gui["value"]["your_name"]           = $linkpartner->name;
            $gui["value"]["your_email"]          = $linkpartner->email;
            $gui["value"]["website_url"]         = $linkpartner->url;
            $gui["value"]["website_title"]       = $linkpartner->title;
            $gui["value"]["website_description"] = $linkpartner->description;
            $gui["value"]["website_domain"]      = $linkpartner->domain;
            $gui["value"]["website_reciprocal"]  = $linkpartner->reciprocal;
            $gui["value"]["website_image"]       = $linkpartner->image_url;
            
            $error = $this->checkFormAddLinkpartner($linkpartner, false, false, false, true);
            if($error->ContainsErrors() === false){
                PersistentieMapper::Instance()->EditLinkpartner($linkpartner, @$_GET["id"]);
                $gui["success"] = true;
            }

            $gui["error"]["messages"]["addlinkpartner"]     = $error->GetErrorMessages();
            $gui["error"]["fields"]                         = $error->GetErrorFields();
            
        } elseif (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"])) {
            if(is_admin()) {
                $linkpartner = PersistentieMapper::Instance()->GetLinkpartnerByID($_GET["id"]);
                if(!empty($linkpartner)) {
                    $linkpartner = $linkpartner[0];

                    $gui["value"]["your_name"]           = $linkpartner["website_owner_name"];
                    $gui["value"]["your_email"]          = $linkpartner["website_owner_email"];
                    $gui["value"]["website_url"]         = $linkpartner["website_url"];
                    $gui["value"]["website_title"]       = $linkpartner["website_name"];
                    $gui["value"]["website_description"] = $linkpartner["website_description"];
                    $gui["value"]["website_domain"]      = $linkpartner["website_domein"];
                    $gui["value"]["website_reciprocal"]  = $linkpartner["website_backlink"];
                    $gui["value"]["website_image"]       = $linkpartner["website_image"];
                }
            }
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
                    @$_POST["website_reciprocal"],
                    @$_POST["website_image"]
            );

            $gui["value"]["your_name"]           = $linkpartner->name;
            $gui["value"]["your_email"]          = $linkpartner->email;
            $gui["value"]["website_url"]         = $linkpartner->url;
            $gui["value"]["website_title"]       = $linkpartner->title;
            $gui["value"]["website_description"] = $linkpartner->description;
            $gui["value"]["website_domain"]      = $linkpartner->domain;
            $gui["value"]["website_reciprocal"]  = $linkpartner->reciprocal;
            $gui["value"]["website_image"]       = $linkpartner->image_url;

            $error = $this->checkFormAddLinkpartner($linkpartner, false, false, false, false);
            //$error = $this->checkFormAddLinkpartner($linkpartner, false, $general_settings["fight_spam"], $captcha_settings["recaptcha_private_key"], true);
            if($error->ContainsErrors() === false){
                PersistentieMapper::Instance()->AddLinkpartner($linkpartner);
                $data = PersistentieMapper::Instance()->GetGeneralSettings();
                PersistentieMapper::Instance()->SendAnouncementMail($linkpartner, $data->contact);
                $gui["success"] = true;
                $gui["value"] = array();
            }
            

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
}
?>