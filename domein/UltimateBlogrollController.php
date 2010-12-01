<?php
/**
 * Description of UltimateBlogrollController
 *
 * @author Jens
 */
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/persistentie/PersistentieMapper.php");
class UltimateBlogrollController  {
    
    public function __construct() {
        $this->init();
    }
    
    public function init() {
        global $path;
        global $gui;
        $gui["base_url"] = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?";
        $gui["mail_url"] = "http://".$_SERVER["SERVER_NAME"]."/wp-admin".$_SERVER["SCRIPT_NAME"]."?";
        //var_dump($gui["base_url"]);
    }
    
    protected function PreparePage() {
        global $gui;
        global $current_user; get_currentuserinfo();
        $gui["salutation"] = (!empty($current_user->user_firstname) ? $current_user->user_firstname : $current_user->display_name);
        
    }
    
    public function menu(){
        add_menu_page(
            "Ultimate Blogroll", //page title
            "Ultim. Blogroll", //menu title, apearance in the menu
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-overview", //slug, in the url
            "ultimate_blogroll", //the function linked to the slug, without this function your slug is useless
            "" //the favicon for the menu
        );
        
        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            "Ultimate Blogroll Overview", //page title
            "Manage linkpartners", //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-overview", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
        
        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            "Add linkpartner", //page title
            "Add linkpartner", //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-add-linkpartner", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
        
        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            "Settings", //page title
            "Settings", //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-settings", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
    }
    
    public function admin_notices() {
        if(PersistentieMapper::Instance()->CheckIfUltimateBlogrollTagWasSet() === false)
        {
            echo '<div class="updated fade"><p>';
            printf('<b><a href="%s">Ultimate Blogroll</a> Info:</b> The tag <b>%s</b> has not been set on any <a href="edit.php?post_type=page">page</a>.', 
                admin_url('admin.php?page=ultimate-blogroll-overview'),
                htmlentities("<!--ultimate-blogroll-->")
            );
            echo '</p></div>'. "\n";
        }
    }
    
    

    public function map_entities( $str ) {
        if(is_array($str) )
        {
            return array_map ( array($this, 'map_entities'), $str );
        }
        return htmlentities( $str, ENT_QUOTES, 'UTF-8' );
    }

    public function deactivate() {
        wp_clear_scheduled_hook('ub_hourly_event');
    }
    
    public function activate() {
        wp_schedule_event(time(), 'hourly', 'ub_hourly_event');
        
        //default values for the general settings
        if(get_option("ultimate_blogroll_general_settings") === false)
        {
            $default = array();
            $default["website_url"] = get_bloginfo('siteurl');
            $default["website_title"] = get_bloginfo('blogname');
            $default["website_description"] = get_bloginfo('description');
            $default["blogroll_contact"] = get_bloginfo('admin_email');
            $default['send_mail'] = "yes";
            $default['reciprocal_link'] = "yes";
            $default['fight_spam'] = "no";
            $default['target'] = "_blank";
            $default['nofollow'] = "yes";
            $default['support'] = "yes";
            add_option("ultimate_blogroll_general_settings", $default, "", "yes");
        }
        //default settings for the widget settings
        if(get_option("ultimate_blogroll_widget_settings") === false)
        {
            $default = array();
            $default["widget_title"] = "Ultimate Blogroll";
            $default["limit_linkpartners"] = 10;
            $default["order_by"] = "inlinks";
            $default["ascending"] = "desc";
            $default['permalink'] = "none";
            add_option("ultimate_blogroll_widget_settings", $default, "", "yes");
        }
        
        if(get_option("ultimate_blogroll_recaptcha_settings") === false)
        {
            $recaptcha = @get_option("recaptcha");
            $default = array();
            $default["recaptcha_public_key"] = @$recaptcha["pubkey"];
            $default["recaptcha_private_key"] = @$recaptcha["privkey"];
            add_option("ultimate_blogroll_recaptcha_settings", $default, "", "yes");
        }

        /*
        //keep track of the db version, so in case we need to upgrade, we can do it safely
        if(get_option("ultimate_blogroll_db_version") === false)
        {
            add_option("ultimate_blogroll_db_version", $ultimate_blogroll_db_version);
        }
         * 
         */
        //Install the database
        PersistentieMapper::Instance()->InstallDatabase();
    }
    
    protected function checkreciprocalLink($url) {
        $settings = PersistentieMapper::Instance()->GetGeneralSettings();
        $html = @file_get_contents($url);
        if($html === false)
            return false;
        $html = strtolower($html);
        $website_url = strtolower($settings["website_url"]);

        $found = false;
        if (preg_match_all('/<a\s[^>]*href=([\"\']??)([^" >]*?)\\1([^>]*)>/siU', $html, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match)
            {
                if ($match[2] == $website_url || $match[2] == $website_url.'/')
                {
                    $found = true;
                }
            }
        }
        return $found;
    }

    public function ub_hourly_task() {
        //we run this every hour so we just want to add the results from now until 1 hour ago
        $time   = time()-(60*60);
        $tIn    = PersistentieMapper::Instance()->GetTemp48In($time);
        $tOut   = PersistentieMapper::Instance()->GetTemp48Out($time);
        $time   = time()-(60*60*48);
        $oIn    = PersistentieMapper::Instance()->GetOld48In($time);
        $oOut   = PersistentieMapper::Instance()->GetOld48Out($time);
        PersistentieMapper::Instance()->DeleteOld48($time);
        PersistentieMapper::Instance()->UpdateCountedLinks();

        if(!empty($oIn)) {
            foreach($oIn as $link) {
                PersistentieMapper::Instance()->Min48In($link["count"], $link["links_website_id"]);
            }
        }

        if(!empty($oOut)) {
            foreach($oOut as $link) {
                PersistentieMapper::Instance()->Min48Out($link["count"], $link["links_website_id"]);
            }
        }

        if(!empty($tIn)) {
            foreach($tIn as $link) {
                PersistentieMapper::Instance()->Plus48In($link["count"], $link["links_website_id"]);
            }
        }

        if(!empty($tOut)) {
            foreach($tOut as $link) {
                PersistentieMapper::Instance()->Plus48Out($link["count"], $link["links_website_id"]);
            }
        }
    }
    protected function checkFormAddLinkpartner()
    {
        global $gui;
        if(isset($_POST["add_linkpartner"])) {
            $gui["value"]["your_name"] = $_POST["your_name"];
            $gui["value"]["your_email"] = $_POST["your_email"];
            $gui["value"]["website_url"] = $_POST["website_url"];
            $gui["value"]["website_title"] = $_POST["website_title"];
            $gui["value"]["website_description"] = $_POST["website_description"];
            $gui["value"]["website_domain"] = $_POST["website_domain"];
            $gui["value"]["website_reciprocal"] = $_POST["website_reciprocal"];
            

            if(empty($gui["value"]["your_name"])) {
                $gui["error"]["your_name"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website owner's name is empty</li>";
            }

            if(empty($gui["value"]["your_email"])) {
                $gui["error"]["your_email"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website owner's email is empty</li>";
            } elseif (filter_var($gui["value"]["your_email"], FILTER_VALIDATE_EMAIL) === FALSE) {
                $gui["error"]["your_email"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website owner's email is not a valid email address</li>";
            }

            if(empty($gui["value"]["website_url"])) {
                $gui["error"]["website_url"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website url is empty</li>";
            } elseif (filter_var($gui["value"]["website_url"], FILTER_VALIDATE_URL) === FALSE) {
                $gui["error"]["website_url"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website url is not a valid url</li>";
            }

            if(empty($gui["value"]["website_title"])) {
                $gui["error"]["website_title"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website title is empty</li>";
            }

            if(empty($gui["value"]["website_description"])) {
                $gui["error"]["website_description"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website description is empty</li>";
            }

            if(empty($gui["value"]["website_domain"])) {
                $gui["error"]["website_domain"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website domain is empty</li>";
            }

            if(empty($gui["value"]["website_reciprocal"]) && isset($gui["public_add"])) {
                $gui["error"]["website_reciprocal"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website reciprocal is empty</li>";
            }

            if (!empty($gui["value"]["website_reciprocal"]) && filter_var($gui["value"]["website_reciprocal"], FILTER_VALIDATE_URL) === FALSE) {
                $gui["error"]["website_reciprocal"] = "class=\"red\"";
                $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website reciprocal is not a valid url</li>";
            }

            if(isset($gui["fight_spam"]) && $gui["fight_spam"] == "yes")
            {
                global $path;
                require_once($path."gui/recaptchalib.php");
                $captcha_settings = PersistentieMapper::Instance()->GetRecaptchaSettings();
                $privatekey = $captcha_settings["recaptcha_private_key"];
                $resp = recaptcha_check_answer ($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    @$_POST["recaptcha_challenge_field"],
                                    @$_POST["recaptcha_response_field"]);
                if(!$resp->is_valid) {
                    $gui["error"]["captcha"] = "class=\"red\"";
                    $gui["error"]["msg"]["addlinkpartner"][] = "<li>Captcha was wrong</li>";
                }
            }

            if(!isset($gui["error"]["website_url"]) && !isset($gui["error"]["website_reciprocal"]) && !empty($gui["value"]["website_reciprocal"])) {
                if(parse_url($gui["value"]["website_url"], PHP_URL_HOST) != parse_url($gui["value"]["website_reciprocal"], PHP_URL_HOST)) {
                    $gui["error"]["website_url"] = "class=\"red\"";
                    $gui["error"]["website_reciprocal"] = "class=\"red\"";
                    $gui["error"]["msg"]["addlinkpartner"][] = "<li>The \"website reciprocal\" must be under the same (sub)domain as the \"Website url\"</li>";
                } else {
                    $link_back = $this->checkreciprocalLink($gui["value"]["website_reciprocal"]);
                    if($link_back !== true)
                    {
                        $gui["error"]["website_reciprocal"] = "class=\"red\"";
                        $gui["error"]["msg"]["addlinkpartner"][] = "<li>The \"website reciprocal\" does not have a link back.</li>";
                    }
                    $gui["value"]["website_has_backlink"] = (int)$link_back;
                }
            }
            if(!empty($gui["value"]["website_url"]) && !empty($gui["value"]["website_domain"])) {
                if(strpos($gui["value"]["website_url"], $gui["value"]["website_domain"]) === false) {
                    $gui["error"]["website_domain"] = "class=\"red\"";
                    $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website domain does not fetch with Website url.</li>";
                }
            }
            if($gui["edit"] === false) {
                $links = PersistentieMapper::Instance()->GetLinkpartners();
                foreach($links as $link)
                {
                    ///////////////////////////////////
                    $db = @$link["website_url"];
                    $form = @$gui["value"]["website_url"];

                    $db = parse_url($db);
                    $form = parse_url($form);

                    if(isset($db["host"]) && isset($form["host"]) && $db["host"] == $form["host"]) {
                        $gui["error"]["website_url"] = "class=\"red\"";
                        $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website url is already in our system.</li>";
                    }
                    ///////////////////////////////////
                    $db = @$link["website_reciprocal"];
                    $form = @$gui["value"]["website_backlink"];

                    if(!empty($db) && !empty($form))
                    {
                        $db = parse_url($db);
                        $form = parse_url($form);

                        if(isset($db["host"]) && isset($form["host"]) && $db["host"] == $form["host"]) {
                            $gui["error"]["website_backlink"] = "class=\"red\"";
                            $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website reciprocal is already in our system.</li>";
                        }
                    }
                    /////////////////////////////////////
                    $db = $link["website_domein"];
                    $form = $gui["value"]["website_domain"];

                    if($db == $form) {
                        $gui["error"]["website_domain"] = "class=\"red\"";
                        $gui["error"]["msg"]["addlinkpartner"][] = "<li>Website domain is already in our system.</li>";
                    }
                }
            }


            if(!isset($gui["error"]))
            {
                if($gui["edit"] === false) {
                    //var_dump("mail");
                    PersistentieMapper::Instance()->AddLinkpartner($gui["value"]);
                    $gui["success"] = "OK";
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: Wordpress Ultimate Blogroll <'.get_bloginfo('admin_email').'> '."\r\n";

                    $subject = 'New link submitted at '.get_bloginfo('siteurl').''."\r\n";

                    $message = "Hi,<br /><br />Somebody added a new link in Wordpress Ultimate Blogroll.<br />";
                    $message .= "<table>";
                    $message .= "<tr><td style=\"width: 250px;\">Website owner's name:</td><td>".$gui["value"]["your_name"]."</td></tr>";
                    $message .= "<tr><td>Website owner's email:</td><td>".$gui["value"]["your_email"]."</td></tr>";
                    $message .= "<tr><td><br /></td></tr>";
                    $message .= "<tr><td>Website url:</td><td>".$gui["value"]["website_url"]."</td></tr>";
                    $message .= "<tr><td>Website title:</td><td>".$gui["value"]["website_title"]."</td></tr>";
                    $message .= "<tr><td>Website description:</td><td>".$gui["value"]["website_description"]."</td></tr>";
                    $message .= "<tr><td><br /></td></tr>";
                    $message .= "<tr><td>Website domain:</td><td>".$gui["value"]["website_domain"]."</td></tr>";
                    $message .= "<tr><td>Website reciprocal:</td><td>".$gui["value"]["website_reciprocal"]."</td></tr>";
                    $id = PersistentieMapper::Instance()->GetLastAddedLinkpartner();
                    $message .= "</table>Do you want to <a href=\"".$gui["mail_url"].http_build_query(array("page" => "ultimate-blogroll-overview", "action" => "edit", "id" => $id ))."#edit\">View details</a> | <a href=\"".$gui["mail_url"].http_build_query(array("page" => "ultimate-blogroll-overview", "overview_actions" => "approve", "bulk_action" => "Apply", "linkpartner[]" => $id))."\">Approve</a> | <a href=\"".$gui["mail_url"].http_build_query(array("page" => "ultimate-blogroll-overview", "overview_actions" => "delete", "bulk_action" => "Apply", "linkpartner[]" => $id))."\">Delete</a>";

                    $data = PersistentieMapper::Instance()->GetGeneralSettings();
                    $m = @mail($data["blogroll_contact"], $subject, $message, $headers);
                    
                    unset($gui["value"]);
                } else {
                    if(is_admin()) {
                        $gui["value"]["website_id"] = $_GET["id"];
                        $gui["success"] = "OK";
                        PersistentieMapper::Instance()->EditLinkpartner($gui["value"]);
                    } else {
                        //TODO: updaten voor de linkpartner
                    }
                }

            }
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
                }
            } else {
                //TODO: wijzigen voor de linkpartner
            }

        }
    }
}
?>