<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/11/11
 * Time: 16:49
 * To change this template use File | Settings | File Templates.
 */
 
class UbLinkpartner extends UbMain {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Dispatcher, all the calls come through here
     * This is a little workaround since we have pages that don't have a link in the menu
     */
    public function index() {
        if(!isset($_GET["action"]))
            $_GET["action"] = null;

        switch($_GET["action"]) {
            case "edit":
                $this->edit(is_admin() ? true : false);
                break;
            case "wizard":
                UbController::getInstance(UbController::Wizard)->show();
                break;
            default:
                $this->overview();
                break;
        }
    }

    /**
     * Edit an existing linkpartner
     * @param bool $admin
     */
    public function edit($admin = false) {
        $gui = array();
        if(isset($_POST["add_linkpartner"])) {
            if(empty($_POST["your_name"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_name", __("Website owner's name", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            if(empty($_POST["your_email"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_email", __("Website owner's email", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif(filter_var($_POST["your_email"], FILTER_VALIDATE_EMAIL) === FALSE) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("your_email", __("Website owner's email", "ultimate-blogroll")." ".__("is not a valid email address", "ultimate-blogroll"));
            }

            if(empty($_POST["website_url"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_url", __("Website url", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif(filter_var($_POST["website_url"], FILTER_VALIDATE_URL) === FALSE) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_url", __("Website url", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
            }

            if(empty($_POST["website_title"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_title", __("Website title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            if(empty($_POST["website_description"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_description", __("Website description", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            }

            if(empty($_POST["website_reciprocal"]) && $admin === false) {
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
            $gui["value"]["your_name"]           = $_POST["your_name"];
            $gui["value"]["your_email"]          = $_POST["your_email"];
            $gui["value"]["website_url"]         = $_POST["website_url"];
            $gui["value"]["website_title"]       = $_POST["website_title"];
            $gui["value"]["website_description"] = $_POST["website_description"];
            $gui["value"]["website_reciprocal"]  = $_POST["website_reciprocal"];
            $gui["value"]["website_image"]       = $_POST["website_image"];
            if( count(UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getError()) == 0 ) {
                $gui["success"]["update"] = true;
                $domain = parse_url($_POST["website_url"]);
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->editLinkpartner(
                    $_POST["your_name"],
                    $_POST["your_email"],
                    $_POST["website_url"],
                    $_POST["website_title"],
                    $_POST["website_description"],
                    $domain["host"],
                    $_POST["website_reciprocal"],
                    $_POST["website_image"],
                    $linkback,
                    $_GET["id"]
                );
            }
        } else {
            $linkpartner = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getLinkpartnerByID($_GET["id"]);
            if($linkpartner !== false) {
                $gui["value"]["your_name"]           = $linkpartner["website_owner_name"];
                $gui["value"]["your_email"]          = $linkpartner["website_owner_email"];
                $gui["value"]["website_url"]         = $linkpartner["website_url"];
                $gui["value"]["website_title"]       = $linkpartner["website_name"];
                $gui["value"]["website_description"] = $linkpartner["website_description"];
                $gui["value"]["website_reciprocal"]  = $linkpartner["website_backlink"];
                $gui["value"]["website_image"]       = $linkpartner["website_image"];
            } else {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("", __("linkpartner does not exist.", "ultimate-blogroll"));
            }
        }
        $gui["edit"] = true;
        require_once(UB_PLUGIN_DIR . "gui" . DIRECTORY_SEPARATOR . "Linkpartner.php");
    }

    /**
     * Bulk approve action of linkpartners
     */
    private function approve() {
        foreach(@$_GET["linkpartner"] as $link) {
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->updateApproveStatus($link, "a");
        }
    }

    /**
     * Bulk unapprove action of linkpartners
     */
    private function unapprove() {
        foreach(@$_GET["linkpartner"] as $link) {
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->updateApproveStatus($link, "u");
        }
    }

    /**
     * Bulk delete action of linkpartners
     */
    private function delete() {
        foreach(@$_GET["linkpartner"] as $link) {
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->DeleteLinkpartner($link);
        }
    }

    /**
     * Wordpress cronjob callback function
     * Here we loop over all the links within Ultimate Blogroll and check if they have proper backlinks
     */
    public function checkLinkpartners() {
        $linkpartners = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getLinkpartners();
        foreach($linkpartners as $linkpartner) {
            $r = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->checkreciprocalLink($linkpartner["website_backlink"]);
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->updateBacklinkStatus($linkpartner["website_id"], (int)$r);
        }
    }



    /**
     * Get an overview of the linkpartners
     */
    public function overview() {
        if(isset($_GET["overview_actions"])) {
            switch($_GET["overview_actions"]) {
                case "approve":
                    $_GET["success"]["action"] = "true";
                    $this->approve();
                    break;
                case "unapprove":
                    $_GET["success"]["action"] = "true";
                    $this->unapprove();
                    break;
                case "delete":
                    $_GET["success"]["action"] = "true";
                    $this->delete();
                    break;
            }

        }
        if(isset($_GET["check_reciprocal_url"])) {
            $gui["success"]["check"] = true;
            wp_schedule_single_event(time(), 'check_linkpartners');
        }
        $gui["status_count"] = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getNumberOfStatus();
        $gui["status_count"]["link_all"]        = UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "all") );
        $gui["status_count"]["link_approved"]   = UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "approved") );
        $gui["status_count"]["link_unapproved"] = UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "unapproved") );

        $gui["linkpartners"] = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getLinkpartners((isset($_GET["status"]) ? $_GET["status"] : ""), (isset($_GET["s"]) ? $_GET["s"] : "") );
        require_once(UB_PLUGIN_DIR."gui".DIRECTORY_SEPARATOR."Overview.php");
    }

    /**
     * Add a new linkpartner
     * @param bool $admin, admins don't need to fill in a reciprocal link
     */
    public function addLinkpartner($admin = false) {
        $gui = array();
        if(isset($_POST["add_linkpartner"])) {
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

            if(empty($_POST["website_reciprocal"]) && $admin === false) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("", __("Website reciprocal", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
            } elseif(filter_var($_POST["website_reciprocal"], FILTER_VALIDATE_URL) === FALSE && !empty($_POST["website_reciprocal"])) {
                UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("website_reciprocal", __("Website reciprocal", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
            }

            /**
             * Er staat geen recaptcha in admin panel, dus dit is niet zichtbaar
             */
//            if(UbMapper::getInstance(UbMapper::Settings)->getConfig("fight_spam") == "yes") {
//                if(!function_exists("recaptcha_get_html")) {
//                    require_once(UB_PLUGIN_DIR."tools".DIRECTORY_SEPARATOR."recaptchalib.php");
//                }
//                $resp = recaptcha_check_answer (UbMapper::getInstance(UbMapper::Settings)->getConfig("recaptcha_public_key"), $_SERVER["REMOTE_ADDR"], @$_POST["recaptcha_challenge_field"], @$_POST["recaptcha_response_field"]);
//                if(!$resp->is_valid) {
//                    UbMapper::getInstance(UbMapper::Error)->addError("captcha", __("Anti-spam", "ultimate-blogroll")." ".__("was wrong", "ultimate-blogroll"));
//                }
//            }
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
            $gui["value"]["your_name"]           = $_POST["your_name"];
            $gui["value"]["your_email"]          = $_POST["your_email"];
            $gui["value"]["website_url"]         = $_POST["website_url"];
            $gui["value"]["website_title"]       = $_POST["website_title"];
            $gui["value"]["website_description"] = $_POST["website_description"];
            $gui["value"]["website_reciprocal"]  = $_POST["website_reciprocal"];
            $gui["value"]["website_image"]       = $_POST["website_image"];

            if( count(UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getError()) == 0 ) {
                $domain = parse_url($_POST["website_url"]);
                $alreadyInDB = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->doWeAlreadyHaveThisSubmission(
                    $_POST["website_url"],
                    $_POST["website_reciprocal"],
                    $domain["host"],
                    $_POST["website_title"]
                );
                if($alreadyInDB === false)
                {
                    $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->addLinkpartner(
                        $_POST["your_name"],
                        $_POST["your_email"],
                        $_POST["website_url"],
                        $_POST["website_title"],
                        $_POST["website_description"],
                        $domain["host"],
                        $_POST["website_reciprocal"],
                        $_POST["website_image"],
                        $linkback
                    );
                    $gui["success"]["insert"] = true;
                    $gui["value"]["your_name"]           = "";
                    $gui["value"]["your_email"]          = "";
                    $gui["value"]["website_url"]         = "";
                    $gui["value"]["website_title"]       = "";
                    $gui["value"]["website_description"] = "";
                    $gui["value"]["website_reciprocal"]  = "";
                    $gui["value"]["website_image"]       = "";
                    if(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("send_mail") == "yes") {
                        $this->sendMail($gui["value"], $id);
                    }
                } else {
                    UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->addError("exists", __("Linkpartner", "ultimate-blogroll")." ".__("is already in our system.", "ultimate-blogroll"));
                }
            }
        }
        require_once(UB_PLUGIN_DIR . "gui" . DIRECTORY_SEPARATOR . "Linkpartner.php");
    }

    /**
     * Ajax outlinks callback function
     * If a user clicks on a link (within the ultimate-blogroll page or widget) it is processed here
     */
    public function ub_ajax_action_callback() {
        $linkpartner = parse_url($_POST['linkpartner']);
        $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getIdByHost($linkpartner["host"]);
        if(is_null($id)) {
            $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getIdByHost(str_replace('www.', '', $linkpartner["host"]));
        }
        echo $id;
        if($id > 0 and UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->checkLink($id, "o") == 0) {
            $this->processHits($id, "o");
        }
    }

    public function processHits($id, $io) {
        UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->addTotalLink($id, $io);
        UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->addLink($id, $io);
    }

    /**
     * Check for inlinks on every page load
     */
    public function checkInlinks() {
        if(!is_admin()) {
            if(isset($_SERVER["HTTP_REFERER"])) {
                $referer = parse_url($_SERVER["HTTP_REFERER"]);
                $url = parse_url(get_bloginfo("wpurl"));
                $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getIdByHost($referer["host"]);
                if(is_null($id)) {
                    $id = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->getIdByHost(str_replace('www.', '', $referer["host"]));
                }
                if($referer["host"] != $url["host"] or true) {
                    if(UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->checkLink($id, "i") == 0) {
                        $this->processHits($id, "i");
                        //UbMapper::getInstance(UbMapper::Linkpartner)->addLink($id, "i");
                    }
                }
            }
        }
    }

    /**
     * wordpress cronjob, runs hourly
     */
    public function ub_hourly_task() {
        //delete all the old hits, 60*60*48 = 172800
        UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->removeOldLinks(172800);
        $hits = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->coundHits();
        foreach($hits as $hit) {
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->addTotaltoSites($hit["id"], $hit["inlink"], $hit["outlink"]);
        }
    }
}
