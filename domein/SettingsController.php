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
                PersistentieMapper::Instance()->SaveGeneralSettings($settings);
                $gui["succes"]["general"] = true;
            }
            $gui["value"]["website_url"]            = $settings->url;
            $gui["value"]["website_title"]          = $settings->title;
            $gui["value"]["website_description"]    = $settings->description;
            $gui["value"]["blogroll_contact"]       = $settings->contact;
            //$gui["value"]["blogroll_email_checkbox"] = $data["blogroll_email_checkbox"];//depricated since we don't use checkboxes anymore, we now use <select>
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
            $data = PersistentieMapper::Instance()->GetGeneralSettings();
            $gui["value"]["website_url"]            = $data->url;
            $gui["value"]["website_title"]          = $data->title;
            $gui["value"]["website_description"]    = $data->description;
            $gui["value"]["blogroll_contact"]       = $data->contact;
            //$gui["value"]["blogroll_email_checkbox"] = $data["blogroll_email_checkbox"];//depricated since we don't use checkboxes anymore, we now use <select>
            $gui["value"]["send_mail"]              = $data->send_mail;
            $gui["value"]["reciprocal_link"]        = $data->reciprocal;
            $gui["value"]["fight_spam"]             = $data->fight_spam;
            $gui["value"]["target"]                 = $data->target;
            $gui["value"]["nofollow"]               = $data->nofollow;
            $gui["value"]["support"]                = $data->support;
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
                PersistentieMapper::Instance()->SaveWidgetSettings($settings);
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
            $data = PersistentieMapper::Instance()->GetWidgetSettings();

            $gui["value"]["widget_title"]       = $data->title;
            $gui["value"]["limit_linkpartners"] = $data->limit;
            $gui["value"]["order_by"]           = $data->order_by;
            $gui["value"]["ascending"]          = $data->ascending;
            $gui["value"]["permalink"]          = $data->permalink;
        }
        if(isset($_POST["recaptcha_settings"])) {
            $settings = new RecaptchaSettingsDTO(
                @$_POST["recaptcha_public_key"],
                @$_POST["recaptcha_private_key"]
            );
            $error = $this->checkFormRecaptchaSettings($settings);
            if($error->ContainsErrors() === false) {
                PersistentieMapper::Instance()->SaveRecaptchaSettings($settings);
                $gui["succes"]["recaptcha"] = true;
            }
            $gui["value"]["recaptcha_public_key"]       = $settings->recaptcha_public_key;
            $gui["value"]["recaptcha_private_key"]      = $settings->recaptcha_private_key;

            $gui["error"]["messages"]           = $error->GetErrorMessages();
            $gui["error"]["fields"]             = $error->GetErrorFields();

            unset($error);
            unset($settings);
        } else {
            $data = PersistentieMapper::Instance()->GetRecaptchaSettings();
            $gui["value"]["recaptcha_public_key"]       = $data->recaptcha_public_key;
            $gui["value"]["recaptcha_private_key"]      = $data->recaptcha_private_key;
        }
        $recaptcha = PersistentieMapper::Instance()->GetRecaptchaSettings();
        if(!empty($recaptcha->recaptcha_public_key) && !empty($recaptcha->recaptcha_private_key)) {
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