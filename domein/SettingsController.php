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
        $gui["title"]       = __("Settings", "ultimate-blogroll");
        $gui["site_url"]    = get_bloginfo('siteurl');
        $gui["blogname"]    = get_bloginfo('blogname');
        $gui["description"] = get_bloginfo('description');
        $gui["admin_email"] = get_bloginfo('admin_email');

        if(isset($_POST["general_settings"])) {
            $settings = new GeneralSettingsDTO(
                @$_POST["website_url"],
                @$_POST["website_title"],
                @$_POST["website_description"],
                @$_POST["blogroll_contact"],
                @$_POST["support"],
                @$_POST["send_mail"],
                @$_POST["reciprocal_link"],
                @$_POST["fight_spam"],
                @$_POST["target"],
                @$_POST["nofollow"]
            );
            $error = $this->checkFormGeneralSettings($settings);
            if($error->ContainsErrors() === false) {
                PersistentieMapper::Instance()->SetConfig("website_url",            $settings->url);
                PersistentieMapper::Instance()->SetConfig("website_title",          $settings->title);
                PersistentieMapper::Instance()->SetConfig("website_description",    $settings->description);
                PersistentieMapper::Instance()->SetConfig("blogroll_contact",       $settings->contact);
                PersistentieMapper::Instance()->SetConfig("support",                $settings->support);
                PersistentieMapper::Instance()->SetConfig("send_mail",              $settings->send_mail);
                PersistentieMapper::Instance()->SetConfig("reciprocal_link",        $settings->reciprocal);
                PersistentieMapper::Instance()->SetConfig("fight_spam",             $settings->fight_spam);
                PersistentieMapper::Instance()->SetConfig("target",                 $settings->target);
                PersistentieMapper::Instance()->SetConfig("nofollow",               $settings->nofollow);
                //PersistentieMapper::Instance()->SaveGeneralSettings($settings);
                $gui["succes"]["general"] = true;
            }
            $gui["value"]["website_url"]            = $settings->url;
            $gui["value"]["website_title"]          = $settings->title;
            $gui["value"]["website_description"]    = $settings->description;
            $gui["value"]["blogroll_contact"]       = $settings->contact;
            $gui["value"]["send_mail"]              = $settings->send_mail;
            $gui["value"]["reciprocal_link"]        = $settings->reciprocal;
            $gui["value"]["fight_spam"]             = $settings->fight_spam;
            $gui["value"]["target"]                 = $settings->target;
            $gui["value"]["nofollow"]               = $settings->nofollow;
            $gui["value"]["support"]                = $settings->support;

            $gui["error"]["messages"]               = $error->GetErrorMessages();
            $gui["error"]["fields"]                 = $error->GetErrorFields();
            unset($error);
            unset($settings);
        } else {
            //$data = PersistentieMapper::Instance()->GetGeneralSettings();
            $gui["value"]["website_url"]            = PersistentieMapper::Instance()->GetConfig("website_url");
            $gui["value"]["website_title"]          = PersistentieMapper::Instance()->GetConfig("website_title");
            $gui["value"]["website_description"]    = PersistentieMapper::Instance()->GetConfig("website_description");
            $gui["value"]["blogroll_contact"]       = PersistentieMapper::Instance()->GetConfig("blogroll_contact");
            //$gui["value"]["blogroll_email_checkbox"] = $data["blogroll_email_checkbox"];//depricated since we don't use checkboxes anymore, we now use <select>
            $gui["value"]["send_mail"]              = PersistentieMapper::Instance()->GetConfig("send_mail");
            $gui["value"]["reciprocal_link"]        = PersistentieMapper::Instance()->GetConfig("reciprocal_link");
            $gui["value"]["fight_spam"]             = PersistentieMapper::Instance()->GetConfig("fight_spam");
            $gui["value"]["target"]                 = PersistentieMapper::Instance()->GetConfig("target");
            $gui["value"]["nofollow"]               = PersistentieMapper::Instance()->GetConfig("nofollow");
            $gui["value"]["support"]                = PersistentieMapper::Instance()->GetConfig("support");
        }
        $valid_page_ids = array();
        $pages = PersistentieMapper::Instance()->GetPagesWithUltimateBlogrollTag();
        $gui["html"]["permalink"] = $pages;
        if(isset($_POST["widget_settings"])) {
            foreach($pages as $page) {
                $valid_page_ids[] = $page["id"];
            }

            $settings = new WidgetSettingsDTO(
                @$_POST["widget_title"],
                @$_POST["limit_linkpartners"],
                @$_POST["order_by"],
                @$_POST["ascending"],
                @$_POST["permalink"]
            );
            $error = $this->checkFormWidgetSettings($settings, $valid_page_ids);
            if($error->ContainsErrors() === false) {
                PersistentieMapper::Instance()->SetConfig("widget_title",       $settings->title);
                PersistentieMapper::Instance()->SetConfig("limit_linkpartners", $settings->limit);
                PersistentieMapper::Instance()->SetConfig("order_by",           $settings->order_by);
                PersistentieMapper::Instance()->SetConfig("ascending",          $settings->ascending);
                PersistentieMapper::Instance()->SetConfig("permalink",          $settings->permalink);
                //PersistentieMapper::Instance()->SaveWidgetSettings($settings);
                $gui["succes"]["widget"] = true;
            }
            $gui["value"]["widget_title"]       = $settings->title;
            $gui["value"]["limit_linkpartners"] = $settings->limit;
            $gui["value"]["order_by"]           = $settings->order_by;
            $gui["value"]["ascending"]          = $settings->ascending;
            $gui["value"]["permalink"]          = $settings->permalink;

            $gui["error"]["messages"]           = $error->GetErrorMessages();
            $gui["error"]["fields"]             = $error->GetErrorFields();
            
            unset($error);
            unset($settings);
        } else {
            //$data = PersistentieMapper::Instance()->GetWidgetSettings();

            $gui["value"]["widget_title"]       = PersistentieMapper::Instance()->GetConfig("widget_title");
            $gui["value"]["limit_linkpartners"] = PersistentieMapper::Instance()->GetConfig("limit_linkpartners");
            $gui["value"]["order_by"]           = PersistentieMapper::Instance()->GetConfig("order_by");
            $gui["value"]["ascending"]          = PersistentieMapper::Instance()->GetConfig("ascending");
            $gui["value"]["permalink"]          = PersistentieMapper::Instance()->GetConfig("permalink");
        }
        if(isset($_POST["recaptcha_settings"])) {
            $settings = new RecaptchaSettingsDTO(
                @$_POST["recaptcha_public_key"],
                @$_POST["recaptcha_private_key"]
            );
            $error = $this->checkFormRecaptchaSettings($settings);
            if($error->ContainsErrors() === false) {
                //PersistentieMapper::Instance()->SaveRecaptchaSettings($settings);
                PersistentieMapper::Instance()->SetConfig("recaptcha_public_key",   $settings->recaptcha_public_key);
                PersistentieMapper::Instance()->SetConfig("recaptcha_private_key",  $settings->recaptcha_private_key);
                $gui["succes"]["recaptcha"] = true;
            }
            $gui["value"]["recaptcha_public_key"]       = $settings->recaptcha_public_key;
            $gui["value"]["recaptcha_private_key"]      = $settings->recaptcha_private_key;

            $gui["error"]["messages"]           = $error->GetErrorMessages();
            $gui["error"]["fields"]             = $error->GetErrorFields();

            unset($error);
            unset($settings);
        } else {
            //$data = PersistentieMapper::Instance()->GetRecaptchaSettings();
            $gui["value"]["recaptcha_public_key"]       = PersistentieMapper::Instance()->GetConfig("recaptcha_public_key");
            $gui["value"]["recaptcha_private_key"]      = PersistentieMapper::Instance()->GetConfig("recaptcha_private_key");
        }
        //$recaptcha = PersistentieMapper::Instance()->GetRecaptchaSettings();
        $public     = PersistentieMapper::Instance()->GetConfig("recaptcha_public_key");
        $private    = PersistentieMapper::Instance()->GetConfig("recaptcha_private_key");
        if(!empty($public) && !empty($private)) {
            $gui["html"]["recaptcha"] = true;
        } else {
            $gui["value"]["fight_spam"] = "no";
        }
        

        $gui = array_map ( array($this, 'map_entities'), $gui );

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/settings/settings.php");
        $result = ob_get_clean();
        echo $result;
    }
    
    private function checkFormGeneralSettings($settings) {
        $error = new ErrorDTO();
        
        if(empty($settings->url)) {
            $error->AddErrorField("website_url");
            $error->AddErrorMessage(__("Website url", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        } elseif(filter_var($settings->url, FILTER_VALIDATE_URL) === FALSE) {
            $error->AddErrorField("website_url");
            $error->AddErrorMessage(__("Website url", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
        }
        
        if(empty($settings->title)) {
            $error->AddErrorField("website_title");
            $error->AddErrorMessage(__("Website title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(empty($settings->description)) {
            $error->AddErrorField("website_description");
            $error->AddErrorMessage(__("Website description", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(empty($settings->contact)) {
            $error->AddErrorField("blogroll_contact");
            $error->AddErrorMessage(__("Email address", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        } elseif(!is_email($settings->contact)) {
            $error->AddErrorField("blogroll_contact");
            $error->AddErrorMessage(__("Email address", "ultimate-blogroll")." ".__("is wrong", "ultimate-blogroll"));
        }
        return $error;
    }
    
    private function checkFormWidgetSettings($settings, $valid_pages_ids) {
        $error = new ErrorDTO();
        
        if(empty($settings->title)) {
            $error->AddErrorField("widget_title");
            $error->AddErrorMessage(__("Widget title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }
        
        if(empty($settings->limit)) {
            $error->AddErrorField("limit_linkpartners");
            $error->AddErrorMessage(__("Limit of linkpartners", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        } elseif (!is_numeric($settings->limit)) {
            $error->AddErrorField("limit_linkpartners");
            $error->AddErrorMessage(__("Limit of linkpartners", "ultimate-blogroll")." ".__("is not a number", "ultimate-blogroll"));
        } elseif($settings->limit < 0){
            $error->AddErrorField("limit_linkpartners");
            $error->AddErrorMessage(__("Limit of linkpartners", "ultimate-blogroll")." ".__("is negative", "ultimate-blogroll"));
        }
            
        if(!in_array($settings->order_by, array("id", "name", "inlinks", "outlinks"))) {
            $error->AddErrorField("order_by");
            $error->AddErrorMessage(__("Order by", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
        }
           
        if(!in_array($settings->ascending, array("asc", "desc"))) {
            $error->AddErrorField("ascending");
            $error->AddErrorMessage(__("Ascending/Descending", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
        }

        if(!empty($valid_page_ids) && !in_array($settings->permalink, $valid_page_ids)) {
            $error->AddErrorField("permalink");
            $error->AddErrorMessage(__("Link exchange page", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
        }
        return $error;
    }
    
    private function checkFormRecaptchaSettings($settings) {
        $error = new ErrorDTO();
        
        if(empty($settings->recaptcha_public_key)) {
            $error->AddErrorField("recaptcha_public_key");
            $error->AddErrorMessage(__("Public key", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(empty($settings->recaptcha_private_key)) {
            $error->AddErrorField("recaptcha_private_key");
            $error->AddErrorMessage(__("Private key", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }
        return $error;        
    }
}
?>