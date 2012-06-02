<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/11/11
 * Time: 16:46
 * To change this template use File | Settings | File Templates.
 */
 
class Settings {
    /**
     * Show a widget when creating/editing a page
     */
    public function pagesWidget() {
        if(isset($_GET["post"]) and $_GET["post"] == Mapper::getInstance(Mapper::Settings)->getConfig("pages")) {
            $ub_page = "checked";
        } else {
            $ub_page = "";
        }
        require_once(UB_PLUGIN_DIR."gui/AdminPageWidget.php");
    }
    
    /**
     * @param $post_id, when you save/edit a page this function is called
     * We save every page that has the Ultimate Blogroll checkbox enabled
     */
    public function pages($post_id) {

        if(isset($_POST["ub_page"]) and $_POST["ub_page"] == "true") {
            Mapper::getInstance(Mapper::Settings)->setConfig("pages", $post_id);
        } else {
            if(Mapper::getInstance(Mapper::Settings)->getConfig("pages") == $post_id) {
                Mapper::getInstance(Mapper::Settings)->setConfig("pages", "");
            }
        }
            /*else {//no page was set, the user will get a notice
            Mapper::getInstance(Mapper::Settings)->setConfig("pages", "");
        }*/
    }



    /**
     * Process the settings
     */
    public function index(){
        $pages = Mapper::getInstance(Mapper::Install)->getPublishedPages();
        $gui = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["save"])) {

            //the iphone style on/off buttons are in fact checkboxes, if set to off nothing was send to the server
            //to prevent unpredictable behaviour we set the values to "no";
            if(!isset($_POST["send_mail"]))
              $_POST["send_mail"] = "no";

            if(!isset($_POST["reciprocal_link"]))
              $_POST["reciprocal_link"] = "no";

            if(!isset($_POST["nofollow"]))
              $_POST["nofollow"] = "no";

            if(!isset($_POST["support"]))
              $_POST["support"] = "no";

            if(!isset($_POST["logo"]))
              $_POST["logo"] = "no";

            if(!isset($_POST["fight_spam"]))
              $_POST["fight_spam"] = "no";

            /* start general settings */
            $gui["value"]["website_url"]            = @$_POST["website_url"];
            $gui["value"]["website_title"]          = @$_POST["website_title"];
            $gui["value"]["website_description"]    = @$_POST["website_description"];

            $gui["value"]["blogroll_contact"]       = @$_POST["blogroll_contact"];
            $gui["value"]["send_mail"]              = @$_POST["send_mail"];

            $gui["value"]["reciprocal_link"]        = @$_POST["reciprocal_link"];

            $gui["value"]["target"]                 = @$_POST["target"];
            $gui["value"]["nofollow"]               = @$_POST["nofollow"];
            $gui["value"]["support"]                = @$_POST["support"];
            /* end general settings */

            /* start widget settings */
            $gui["value"]["widget_title"]           = @$_POST["widget_title"];
            $gui["value"]["limit_linkpartners"]     = @$_POST["limit_linkpartners"];
            $gui["value"]["order_by"]               = @$_POST["order_by"];
            $gui["value"]["ascending"]              = @$_POST["ascending"];
            //$gui["value"]["permalink"]              = @$_POST["permalink"];
            $gui["value"]["logo"]                   = @$_POST["logo"];
            $gui["value"]["selected_page"]          = @$_POST["pages"];
            if(@$_POST["logo"] == "yes") {
                $gui["value"]["logo_width"]             = @$_POST["logo_width"];
                $gui["value"]["logo_height"]            = @$_POST["logo_height"];
                $gui["value"]["logo_usage"]             = @$_POST["logo_usage"];
            } else {
                $gui["value"]["logo_width"]             = Mapper::getInstance(Mapper::Settings)->getConfig("logo_width");
                $gui["value"]["logo_height"]            = Mapper::getInstance(Mapper::Settings)->getConfig("logo_height");
                $gui["value"]["logo_usage"]             = Mapper::getInstance(Mapper::Settings)->getConfig("logo_usage");
            }
            /* end widget settings */

            /* start recaptcha settings */
            $gui["value"]["fight_spam"]             = @$_POST["fight_spam"];
            if(@$_POST["fight_spam"] == "yes") {
                $gui["value"]["recaptcha_public_key"]   = @$_POST["recaptcha_public_key"];
                $gui["value"]["recaptcha_private_key"]  = @$_POST["recaptcha_private_key"];
            } else {
                $gui["value"]["recaptcha_public_key"]   = Mapper::getInstance(Mapper::Settings)->getConfig("recaptcha_public_key");
                $gui["value"]["recaptcha_private_key"]  = Mapper::getInstance(Mapper::Settings)->getConfig("recaptcha_private_key");
            }
            /* end recaptcha settings */
            //END GIVING THE GUI VALUES!
            
            if(empty($_POST["website_url"])) {
                Mapper::getInstance(Mapper::Error)->addError("website_url", __("Website url", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif(filter_var($_POST["website_url"], FILTER_VALIDATE_URL) === FALSE) {
                Mapper::getInstance(Mapper::Error)->addError("website_url", __("Website url", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
            }
            
            if(empty($_POST["website_title"])) {
                Mapper::getInstance(Mapper::Error)->addError("website_title", __("Website title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            if(empty($_POST["website_description"])) {
                Mapper::getInstance(Mapper::Error)->addError("website_description", __("Website description", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            if(empty($_POST["blogroll_contact"])) {
                Mapper::getInstance(Mapper::Error)->addError("blogroll_contact", __("Email address", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif(!is_email( $_POST["blogroll_contact"] )) {
                Mapper::getInstance(Mapper::Error)->addError("blogroll_contact", __("Email address", "ultimate-blogroll")." ".__("is wrong", "ultimate-blogroll"));
            }

            /**
             * check if widget title is not empty
             */
            if(empty($_POST["widget_title"])) {
                Mapper::getInstance(Mapper::Error)->addError("widget_title", __("Widget title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }
            /**
             * check if limit of linkpartners is not empty, is a number and is not negative
             */
            if(empty($_POST["limit_linkpartners"])) {
                Mapper::getInstance(Mapper::Error)->addError("limit_linkpartners", __("Limit of linkpartners", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif (!is_numeric( $_POST["limit_linkpartners"] )) {
                Mapper::getInstance(Mapper::Error)->addError("limit_linkpartners", __("Limit of linkpartners", "ultimate-blogroll")." ".__("is not a number", "ultimate-blogroll"));
            } elseif($_POST["limit_linkpartners"] < 0){
                Mapper::getInstance(Mapper::Error)->addError("limit_linkpartners", __("Limit of linkpartners", "ultimate-blogroll")." ".__("is negative", "ultimate-blogroll"));
            }
            /**
             * Check if Order by is still a predefined value
             */
            if(!in_array(@$_POST["order_by"], array("id", "name", "inlinks", "outlinks"))) {
                Mapper::getInstance(Mapper::Error)->addError("order_by", __("Order by", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
            }

            if(!in_array($_POST["ascending"], array("asc", "desc"))) {
                Mapper::getInstance(Mapper::Error)->addError("ascending", __("Ascending/Descending", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
            }
            
            foreach($pages as $page) {
                $valid_page_ids[] = $page["id"];
            }
            if(isset($_POST["pages"]) and !empty($_POST["pages"]) and is_array($_POST["pages"])) {
                foreach($_POST["pages"] as $page) {
                    if(!in_array($page, $valid_page_ids)) {
                        Mapper::getInstance(Mapper::Error)->addError("pages", __("Link exchange page", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
                    }
                }
            }

            if(!in_array($_POST["logo"], array("yes", "no"))) {
                Mapper::getInstance(Mapper::Error)->addError("logo", __("Logo", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
            }

            if(@$_POST["logo"] == "yes"){
                if(empty( $_POST["logo_width"] )) {
                    Mapper::getInstance(Mapper::Error)->addError("logo_width", __("Width of logo", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                } elseif (!is_numeric( $_POST["logo_width"] )) {
                    Mapper::getInstance(Mapper::Error)->addError("logo_width", __("Width of logo", "ultimate-blogroll")." ".__("is not a number", "ultimate-blogroll"));
                } elseif($_POST["logo_width"] < 0){
                    Mapper::getInstance(Mapper::Error)->addError("logo_width", __("Width of logo", "ultimate-blogroll")." ".__("is negative", "ultimate-blogroll"));
                }

                if(empty( $_POST["logo_height"] )) {
                    Mapper::getInstance(Mapper::Error)->addError("logo_height", __("Height of logo", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                } elseif (!is_numeric( $_POST["logo_height"] )) {
                    Mapper::getInstance(Mapper::Error)->addError("logo_height", __("Height of logo", "ultimate-blogroll")." ".__("is not a number", "ultimate-blogroll"));
                } elseif($_POST["logo_height"] < 0){
                    Mapper::getInstance(Mapper::Error)->addError("logo_height", __("Height of logo", "ultimate-blogroll")." ".__("is negative", "ultimate-blogroll"));
                }

                if(!in_array($_POST["logo_usage"], array("both", "image", "text"))) {
                    Mapper::getInstance(Mapper::Error)->addError("logo_usage", __("Logo usage", "ultimate-blogroll")." ".__("contains an unexpected value", "ultimate-blogroll"));
                }
            }

            /**
             * Only validate and save the data if recaptcha is enabled, otherwise: ignore
             */
            if(@$_POST["fight_spam"] == "yes") {
                if(empty($_POST["recaptcha_public_key"])) {
                    Mapper::getInstance(Mapper::Error)->addError("recaptcha_public_key", __("Public key", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                }
                if(empty($_POST["recaptcha_private_key"])) {
                    Mapper::getInstance(Mapper::Error)->addError("recaptcha_private_key", __("Private key", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                }
            }
            
            /**
             * Save the submitted form if there are no errors!
             */
            if( count(Mapper::getInstance(Mapper::Error)->getError()) == 0 ) {
                Mapper::getInstance(Mapper::Settings)->setConfig("website_url", @$_POST["website_url"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("website_title", @$_POST["website_title"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("website_description", @$_POST["website_description"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("blogroll_contact", @$_POST["blogroll_contact"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("send_mail", @$_POST["send_mail"] );
                Mapper::getInstance(Mapper::Settings)->setConfig("reciprocal_link", @$_POST["reciprocal_link"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("target", @$_POST["target"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("nofollow", @$_POST["nofollow"] );
                Mapper::getInstance(Mapper::Settings)->setConfig("support", @$_POST["support"] );

                Mapper::getInstance(Mapper::Settings)->setConfig("widget_title", @$_POST["widget_title"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("limit_linkpartners", @$_POST["limit_linkpartners"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("order_by", @$_POST["order_by"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("ascending", @$_POST["ascending"]);
                Mapper::getInstance(Mapper::Settings)->setConfig("pages", @$_POST["pages"] );
                Mapper::getInstance(Mapper::Settings)->setConfig("logo", @$_POST["logo"] );
                //if(@$_POST["logo"] == "yes") {
                    Mapper::getInstance(Mapper::Settings)->setConfig("logo_width", @$_POST["logo_width"]);
                    Mapper::getInstance(Mapper::Settings)->setConfig("logo_height", @$_POST["logo_height"]);
                    Mapper::getInstance(Mapper::Settings)->setConfig("logo_usage", @$_POST["logo_usage"]);
                //}
                Mapper::getInstance(Mapper::Settings)->setConfig("fight_spam", @$_POST["fight_spam"] );
                if(@$_POST["fight_spam"] == "yes") {
                    Mapper::getInstance(Mapper::Settings)->setConfig("recaptcha_public_key", @$_POST["recaptcha_public_key"]);
                    Mapper::getInstance(Mapper::Settings)->setConfig("recaptcha_private_key", @$_POST["recaptcha_private_key"]);
                }
                $gui["success"]["save"] = true;
            }
        } else {
            /* start general settings */
            $gui["value"]["website_url"]            = Mapper::getInstance(Mapper::Settings)->getConfig("website_url");
            $gui["value"]["website_title"]          = Mapper::getInstance(Mapper::Settings)->getConfig("website_title");
            $gui["value"]["website_description"]    = Mapper::getInstance(Mapper::Settings)->getConfig("website_description");

            $gui["value"]["blogroll_contact"]       = Mapper::getInstance(Mapper::Settings)->getConfig("blogroll_contact");
            $gui["value"]["send_mail"]              = Mapper::getInstance(Mapper::Settings)->getConfig("send_mail");
            
            $gui["value"]["reciprocal_link"]        = Mapper::getInstance(Mapper::Settings)->getConfig("reciprocal_link");
            
            $gui["value"]["target"]                 = Mapper::getInstance(Mapper::Settings)->getConfig("target");
            $gui["value"]["nofollow"]               = Mapper::getInstance(Mapper::Settings)->getConfig("nofollow");
            $gui["value"]["support"]                = Mapper::getInstance(Mapper::Settings)->getConfig("support");
            /* end general settings */

            /* start widget settings */
            $gui["value"]["widget_title"]           = Mapper::getInstance(Mapper::Settings)->getConfig("widget_title");
            $gui["value"]["limit_linkpartners"]     = Mapper::getInstance(Mapper::Settings)->getConfig("limit_linkpartners");
            $gui["value"]["order_by"]               = Mapper::getInstance(Mapper::Settings)->getConfig("order_by");
            $gui["value"]["ascending"]              = Mapper::getInstance(Mapper::Settings)->getConfig("ascending");
            //$gui["value"]["permalink"]              = Mapper::getInstance(Mapper::Settings)->getConfig("permalink");
            $gui["value"]["logo"]                   = Mapper::getInstance(Mapper::Settings)->getConfig("logo");
            $gui["value"]["logo_width"]             = Mapper::getInstance(Mapper::Settings)->getConfig("logo_width");
            $gui["value"]["logo_height"]            = Mapper::getInstance(Mapper::Settings)->getConfig("logo_height");
            $gui["value"]["logo_usage"]             = Mapper::getInstance(Mapper::Settings)->getConfig("logo_usage");
            /* end widget settings */

            /* start recaptcha settings */
            $gui["value"]["fight_spam"]             = Mapper::getInstance(Mapper::Settings)->getConfig("fight_spam");
            $gui["value"]["recaptcha_public_key"]   = Mapper::getInstance(Mapper::Settings)->getConfig("recaptcha_public_key");
            $gui["value"]["recaptcha_private_key"]  = Mapper::getInstance(Mapper::Settings)->getConfig("recaptcha_private_key");
            $gui["value"]["selected_page"]          = Mapper::getInstance(Mapper::Settings)->getConfig("pages");
            /* end recaptcha settings */
        }
        $gui["value"]["pages"]                          = $pages;
        $gui["value"]["default_website_url"]            = get_bloginfo('siteurl');//Mapper::getInstance(Mapper::Settings)->getConfig("website_url");
        $gui["value"]["default_website_title"]          = get_bloginfo('blogname');//Mapper::getInstance(Mapper::Settings)->getConfig("website_title");
        $gui["value"]["default_website_description"]    = get_bloginfo('description');//Mapper::getInstance(Mapper::Settings)->getConfig("website_description");
        $gui["value"]["default_blogroll_contact"]       = get_bloginfo('admin_email');//Mapper::getInstance(Mapper::Settings)->getConfig("blogroll_contact");
        require_once(UB_PLUGIN_DIR."gui".DIRECTORY_SEPARATOR."Settings.php");
    }
}