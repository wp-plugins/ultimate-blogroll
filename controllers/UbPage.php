<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 22:22
 * To change this template use File | Settings | File Templates.
 */
class UbPage extends UbMain{
    public function __construct() {
        parent::__construct();
    }
    /**
     * initiate javascript loading
     */
    public function ub_javascript_init() {
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'ub_placeholder' , UB_ASSETS_URL."js/jquery.placeholder.min.js");
        wp_enqueue_script( 'ub_page' , UB_ASSETS_URL."js/page.js");
        $output = '<script type="text/javascript" >
        var wpurl = "'.get_bloginfo("wpurl").'";
        </script>';
        echo $output;
    }

    /**
     * @param string $content
     * @return string
     */
    public function createPage($content = '') {
        global $post;
        $pages = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages");
        add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );
        if($pages !== false and $post->ID == $pages) {
            if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["add_linkpartner"])) {
                //check if the name is filled in
                if(empty($_POST["your_name"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_name", __("Website owner's name", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                }

                if(empty($_POST["your_email"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_email", __("Website owner's email", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                } elseif(filter_var($_POST["your_email"], FILTER_VALIDATE_EMAIL) === FALSE) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_email", __("Website owner's email", "ultimate-blogroll")." ".__("is not a valid email address", "ultimate-blogroll"));
                }

                if(empty($_POST["website_url"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_url", __("website_url", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                } elseif(filter_var($_POST["website_url"], FILTER_VALIDATE_URL) === FALSE) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_url", __("website_url", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
                }

                if(empty($_POST["website_title"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_title", __("Website title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                }

                if(empty($_POST["website_description"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_description", __("Website description", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                }

                if(empty($_POST["website_reciprocal"]) && UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("reciprocal_link") == "yes") {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("", __("Website reciprocal", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                } elseif(filter_var($_POST["website_reciprocal"], FILTER_VALIDATE_URL) === FALSE && !empty($_POST["website_reciprocal"])) {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_reciprocal", __("Website reciprocal", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
                }

                $linkback = 0;
                if(!empty($_POST["website_url"]) && !empty($_POST["website_domain"])) {
                    if(!UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->isWrong("website_url") && !UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->isWrong("website_reciprocal")) {
                        if(parse_url($_POST["website_url"], PHP_URL_HOST) != parse_url($_POST["website_reciprocal"], PHP_URL_HOST)) {
                            UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_url", __("The", "ultimate-blogroll")." &quot;".__("website reciprocal", "ultimate-blogroll")."&quot; ".__("must be under the same (sub)controllers as the", "ultimate-blogroll")." &quot;".__("Website url", "ultimate-blogroll")."&quot;");
                            UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_reciprocal", __("The", "ultimate-blogroll")." &quot;".__("website reciprocal", "ultimate-blogroll")."&quot; ".__("must be under the same (sub)controllers as the", "ultimate-blogroll")." &quot;".__("Website url", "ultimate-blogroll")."&quot;");
                        } else {
                            $link_back = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->checkReciprocalLink($_POST["website_reciprocal"], UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("website_url"));
                            if($link_back !== true) {
                                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_reciprocal", __("The", "ultimate-blogroll")." &quot;".__("website reciprocal", "ultimate-blogroll")."&quot; ".__("does not have a link back.", "ultimate-blogroll"));
                            }
                            $linkback = (int)$link_back;
                        }
                    }
                    if(strpos($_POST["website_url"], $_POST["website_domain"]) === false) {
                        UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_domain", __("Website controllers", "ultimate-blogroll")." ".__("does not fetch with", "ultimate-blogroll")." ".__("Website url", "ultimate-blogroll"));
                    }
                }

                if(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("fight_spam") == "yes") {
                    if(!function_exists("recaptcha_get_html")) {
                        require_once(UB_PLUGIN_DIR."tools".DIRECTORY_SEPARATOR."recaptchalib.php");
                    }
                    if(!empty($_POST["recaptcha_challenge_field"]) and !empty($_POST["recaptcha_response_field"]))
                    {
                        $resp = recaptcha_check_answer (UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("recaptcha_private_key"), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                        if(!$resp->is_valid) {
                            UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("captcha", __("Anti-spam", "ultimate-blogroll")." ".__("was wrong", "ultimate-blogroll"));
                        }
                    } else {
                        UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("captcha", __("Anti-spam", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
                    }
                }
                $gui["value"]["your_name"]           = $_POST["your_name"];
                $gui["value"]["your_email"]          = $_POST["your_email"];
                $gui["value"]["website_url"]         = $_POST["website_url"];
                $gui["value"]["website_title"]       = $_POST["website_title"];
                $gui["value"]["website_description"] = $_POST["website_description"];
                $gui["value"]["website_reciprocal"]  = empty($_POST["website_reciprocal"]) ? "" : $_POST["website_reciprocal"];
                $gui["value"]["website_image"]       = empty($_POST["website_image"]) ? "" : $_POST["website_image"];

                if( count(UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getError()) == 0 ) {
                    $domain = parse_url($_POST["website_url"]);
                    $alreadyInDB = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->doWeAlreadyHaveThisSubmission(
                        $_POST["website_url"],
                        $_POST["website_reciprocal"],
                        $domain["host"],
                        $_POST["website_title"]
                    );
                    if($alreadyInDB === false) {
                        $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->addLinkpartner(
                            $_POST["your_name"],
                            $_POST["your_email"],
                            $_POST["website_url"],
                            $_POST["website_title"],
                            $_POST["website_description"],
                            $domain["host"],
                            empty($_POST["website_reciprocal"]) ? "" : $_POST["website_reciprocal"],
                            empty($_POST["website_image"]) ? "" : $_POST["website_image"],
                            $linkback
                        );
                        if(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("send_mail") == "yes") {
                            $this->sendMail($gui["value"], $id);
                        }
                        $gui["success"] = true;
                        $gui["value"]["your_name"]           = "";
                        $gui["value"]["your_email"]          = "";
                        $gui["value"]["website_url"]         = "";
                        $gui["value"]["website_title"]       = "";
                        $gui["value"]["website_description"] = "";
                        $gui["value"]["website_reciprocal"]  = "";
                        $gui["value"]["website_image"]       = "";
                    } else {
                        UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("exists", __("Linkpartner", "ultimate-blogroll")." ".__("is already in our system.", "ultimate-blogroll"));
                    }
                }
            }

            $gui["logo_height"]         = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_height");
            $gui["logo_width"]          = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_width");
            $gui["target"]              = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target");
            $gui["nofollow"]            = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow");
            $gui["logo"]                = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo");
            $gui["logo_usage"]          = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_usage");
            $gui["table_links"]         = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getLinkpartnersPage(
                                            UbSortingHelper::GetOrder(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("ascending")),
                                            UbSortingHelper::GetOrderBy(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by"))
                                          );
            $gui["url"]                 = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("website_url");
            $gui["title"]               = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("website_title");
            $gui["description"]         = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("website_description");
            $gui["support"]             = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("support");
            $gui["fight_spam"]          = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("fight_spam");
            $gui["captcha_settings"]    = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("recaptcha_public_key");
            $gui["reciprocal_link"]     = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("reciprocal_link");
            $gui["table_links_target"]  = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target");
            ob_start();
            require (UB_PLUGIN_DIR."gui/Page.php");
            $result = ob_get_clean();
            return "<p>".$result."</p>";
        } else {
            return $content;
        }
    }
}