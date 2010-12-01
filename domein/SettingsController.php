<?php
/**
 * Description of SettingsController
 *
 * @author Jens
 */
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/domein/UltimateBlogrollController.php");
class SettingsController extends UltimateBlogrollController {
    //private $persisentie;
    protected $gui = array();
    public function __construct() {
        parent::__construct();
    }
    
    public function execute() {
        global $gui;
        $_POST = is_array($_POST) ? array_map('stripslashes_deep', $_POST) : stripslashes($_POST);
        $this->PreparePage();
        $gui["title"] = "Settings";
        $gui["site_url"] = get_bloginfo('siteurl');
        $gui["blogname"] = get_bloginfo('blogname');
        $gui["description"] = get_bloginfo('description');
        $gui["admin_email"] = get_bloginfo('admin_email');
        $this->checkFormGeneralSettings();
        $this->checkFormWidgetSettings();
        $this->checkFormRecaptchaSettings();

        $gui = array_map ( array($this, 'map_entities'), $gui );

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/settings/settings.php");
        $result = ob_get_clean();
        echo $result;
    }
    
    private function checkFormGeneralSettings() {
        global $gui;
        /*
        if(PersistentieMapper::Instance()->CheckIfUltimateBlogrollTagWasSet() === false)
            $gui["error"]["no_tag"] = true;
        */
        
        
        
        if(isset($_POST["general_settings"])) {
            //set the values
            $gui["value"]["website_url"] = $_POST["website_url"];
            $gui["value"]["website_title"] = $_POST["website_title"];
            $gui["value"]["website_description"] = $_POST["website_description"];
            $gui["value"]["blogroll_contact"] = $_POST["blogroll_contact"];
            $gui["value"]["support"] = $_POST["support"];
            /*
            //depricated since we use <select> instead of checkboxes
            if(isset($_POST["blogroll_email_checkbox"]) && $_POST["blogroll_email_checkbox"] == "on")
                $gui["value"]["blogroll_email_checkbox"] = "on";
            else
                $gui["value"]["blogroll_email_checkbox"] = "off";
            */
            
            //We don't check these values, because in the gui we check for the literal values "yes" "no"
            //If those were not found nothing happens so we don't really need anything fancy to catch these
            //We strip xss and sql injections and save the values without any use, they will simply be ignored.
            $gui["value"]["send_mail"] = $_POST["send_mail"];
            $gui["value"]["reciprocal_link"] = @$_POST["reciprocal_link"];
            $gui["value"]["fight_spam"] = @$_POST["fight_spam"]; //@ because if we have no recaptcha data available we disable this option until data received. If an input element is disabled it simply sends nothing.
            $gui["value"]["target"] = @$_POST["target"];
            $gui["value"]["nofollow"] = @$_POST["nofollow"];

            if(empty($gui["value"]["website_url"])) {
                $gui["error"]["website_url"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Website url is empty</li>";
            } elseif (filter_var($gui["value"]["website_url"], FILTER_VALIDATE_URL) === FALSE) {
                $gui["error"]["website_url"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Website url is not a valid url</li>";
            }
            if(empty($gui["value"]["website_title"])) {
                $gui["error"]["website_title"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Website title is empty</li>";
            }
            if(empty($gui["value"]["website_description"])) {
                $gui["error"]["website_description"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Website description is empty</li>";
            }
            if(empty($gui["value"]["blogroll_contact"])) {
                $gui["error"]["blogroll_contact"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Email address is empty</li>";
            } elseif(!is_email($gui["value"]["blogroll_contact"])) {
                $gui["error"]["blogroll_contact"] = "class=\"red\"";
                $gui["error"]["msg"]["general"][] = "<li>Email address is wrong</li>";
            }
            
            if(!isset($gui["error"]))
            {
                PersistentieMapper::Instance()->SaveGeneralSettings($gui["value"]);
                $gui["succes"]["general"] = true;
            }
        } else {
            $data = PersistentieMapper::Instance()->GetGeneralSettings();
            $gui["value"]["website_url"] = $data["website_url"];
            $gui["value"]["website_title"] = $data["website_title"];
            $gui["value"]["website_description"] = $data["website_description"];
            $gui["value"]["blogroll_contact"] = $data["blogroll_contact"];
            //$gui["value"]["blogroll_email_checkbox"] = $data["blogroll_email_checkbox"];//depricated since we don't use checkboxes anymore, we now use <select>
            $gui["value"]["send_mail"] = $data["send_mail"];
            $gui["value"]["reciprocal_link"] = $data["reciprocal_link"];
            $gui["value"]["fight_spam"] = $data["fight_spam"];
            $gui["value"]["target"] = $data["target"];
            $gui["value"]["nofollow"] = $data["nofollow"];
            $gui["value"]["support"] = $data["support"];
            /*
            if(PersistentieMapper::Instance()->CheckIfUltimateBlogrollTagWasSet() === false)
                $gui["error"]["no_tag"] = true;//if no page with a <--blogroll--> tag was found, then give an error.
            */
        }
        
        
    }
    
    private function checkFormWidgetSettings() {
        global $gui;
        $valid_page_ids = array();
        $pages = PersistentieMapper::Instance()->GetPagesWithUltimateBlogrollTag();
        foreach($pages as $page) {
            $valid_page_ids[] = $page["id"];
        }
        $gui["html"]["permalink"] = $pages;
        
        if(isset($_POST["widget_settings"])) {
            
            $gui["value"]["widget_title"] = @$_POST["widget_title"];
            $gui["value"]["limit_linkpartners"] = @$_POST["limit_linkpartners"];
            $gui["value"]["order_by"] = @$_POST["order_by"];
            $gui["value"]["ascending"] = @$_POST["ascending"];
            $gui["value"]["permalink"] = @$_POST["permalink"];
            
            if(empty($gui["value"]["widget_title"])) {
                $gui["error"]["widget_title"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Widget title is empty</li>";
            }
            if(empty($gui["value"]["limit_linkpartners"])) {
                $gui["error"]["limit_linkpartners"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Limit of linkpartners is empty</li>";
            } elseif(!is_numeric($gui["value"]["limit_linkpartners"])) {
                $gui["error"]["limit_linkpartners"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Limit of linkpartners is not a number</li>";
            } elseif($gui["value"]["limit_linkpartners"] < 0){
                $gui["error"]["limit_linkpartners"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Limit of linkpartners is negative</li>";
            }
            if(!in_array($gui["value"]["order_by"], array("id", "name", "inlinks", "outlinks")))
            {
                $gui["error"]["order_by"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Order by contains an unexpected value</li>";
            }
            if(!in_array($gui["value"]["ascending"], array("asc", "desc")))
            {
                $gui["error"]["ascending"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Ascending/Descending contains an unexpected value</li>";
            }
            
            if(!empty($valid_page_ids) && !in_array($gui["value"]["permalink"], $valid_page_ids))
            {
                $gui["error"]["permalink"] = "class=\"red\"";
                $gui["error"]["msg"]["widget"][] = "<li>Link exchange page contains an unexpected value</li>";
            }
            
            if(!isset($gui["error"]))
            {
                $data["widget_title"]       = $gui["value"]["widget_title"];
                $data["limit_linkpartners"] = $gui["value"]["limit_linkpartners"];
                $data["order_by"]           = $gui["value"]["order_by"];
                $data["ascending"]          = $gui["value"]["ascending"];
                $data["permalink"]          = $gui["value"]["permalink"];
                PersistentieMapper::Instance()->SaveWidgetSettings($data);
                $gui["succes"]["widget"] = true;
            }
        } else {
            $data = PersistentieMapper::Instance()->GetWidgetSettings();
            
            $gui["value"]["widget_title"] = $data["widget_title"];
            $gui["value"]["limit_linkpartners"] = $data["limit_linkpartners"];
            $gui["value"]["order_by"] = $data["order_by"];
            $gui["value"]["ascending"] = $data["ascending"];
            $gui["value"]["permalink"] = $data["permalink"];
        }
    }
    
    private function checkFormRecaptchaSettings() {
        global $gui;
        
        //don't put this anywhere else, 'cause otherwise you need a page refresh to enable the Fight spam <select> element again.
        //check if the recaptcha data is available. If not: do not show Fight spam: Yes. Since it will be disabled and it will be confusing!
        $recaptcha = PersistentieMapper::Instance()->GetRecaptchaSettings();
        if(!empty($recaptcha["recaptcha_public_key"]) && !empty($recaptcha["recaptcha_private_key"])) {
            $gui["html"]["recaptcha"] = true;
        } else {
            //set it here on no
            $gui["value"]["fight_spam"] = "no";
        }
        
        if(isset($_POST["recaptcha_settings"])) {
            $gui["value"]["recaptcha_public_key"] = $_POST["recaptcha_public_key"];
            $gui["value"]["recaptcha_private_key"] = $_POST["recaptcha_private_key"];
            
            if(empty($gui["value"]["recaptcha_public_key"])) {
                $gui["error"]["recaptcha_public_key"] = "class=\"red\"";
                $gui["error"]["msg"]["recaptcha"][] = "<li>Public key is empty</li>";
            }
            if(empty($gui["value"]["recaptcha_private_key"])) {
                $gui["error"]["recaptcha_private_key"] = "class=\"red\"";
                $gui["error"]["msg"]["recaptcha"][] = "<li>Private key is empty</li>";
            }
            
            if(!isset($gui["error"]))
            {
                $data = array();
                $data["recaptcha_public_key"] = attribute_escape($gui["value"]["recaptcha_public_key"]);
                $data["recaptcha_private_key"] = attribute_escape($gui["value"]["recaptcha_private_key"]);
                PersistentieMapper::Instance()->SaveRecaptchaSettings($data);
                $gui["succes"]["recaptcha"] = true;
                //PersistentieMapper::Instance()->SaveWidgetSettings($gui["value"]);
            }
            
        } else {
            $data = PersistentieMapper::Instance()->GetRecaptchaSettings();
            $gui["value"]["recaptcha_public_key"] = $data["recaptcha_public_key"];
            $gui["value"]["recaptcha_private_key"] = $data["recaptcha_private_key"];
        }
    }
}
?>