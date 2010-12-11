<?php
/**
 * Description of UltimateBlogrollController
 *
 * @author Jens
 */
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/persistentie/PersistentieMapper.php");
require_once($path."persistentie/dto/ErrorDTO.php");
class UltimateBlogrollController  {
    
    public function __construct() {
        $this->init();
        $this->fix();
    }

    private function fix() {
        $version = get_option("ub_data_version");
        $db_version = get_option("ultimate_blogroll_db_version");
        if(($version === false || $version < 2) && $db_version !== false) {
            $widgets = get_option("sidebars_widgets");
            $widgets = array_map ( array($this, 'FixTypoWidgets'), $widgets );
            update_option("sidebars_widgets", $widgets);

            $data1 = PersistentieMapper::Instance()->GetGeneralSettings();
            if(is_array($data1)) {
                $settings = new GeneralSettingsDTO(
                    @$data1["website_url"],
                    @$data1["website_title"],
                    @$data1["website_description"],
                    @$data1["blogroll_contact"],
                    @$data1["support"],
                    //$gui["value"]["blogroll_email_checkbox"] = $data["blogroll_email_checkbox"];//depricated since we don't use checkboxes anymore, we now use <select>
                    @$data1["send_mail"],
                    @$data1["reciprocal_link"],
                    @$data1["fight_spam"],
                    @$data1["target"],
                    @$data1["nofollow"]
                );
                PersistentieMapper::Instance()->SaveGeneralSettings($settings);
                unset($settings);
            }
            $data2 = PersistentieMapper::Instance()->GetWidgetSettings();
            if(is_array($data2)) {
                $settings = new WidgetSettingsDTO (
                    @$data2["widget_title"],
                    @$data2["limit_linkpartners"],
                    @$data2["order_by"],
                    @$data2["ascending"],
                    @$data2["permalink"]
                );
                PersistentieMapper::Instance()->SaveWidgetSettings($settings);
                unset($settings);
            }
            $data3 = PersistentieMapper::Instance()->GetRecaptchaSettings();
            if(is_array($data3)) {
                $settings = new RecaptchaSettingsDTO(
                    @$data3["recaptcha_public_key"],
                    @$data3["recaptcha_private_key"]
                );
                PersistentieMapper::Instance()->SaveRecaptchaSettings($settings);
                unset($settings);
            }
            if($version === false) {
                add_option("ub_data_version", 2, "", "yes");
            } else {
                update_option("ub_data_version", 2);
            }
        }
    }

    private function FixTypoWidgets($array) {
        if(is_array($array)) {
            $result = array();
            foreach($array as $key => $value) {
                if($value == "ultimage-blogroll") {
                    $result[$key] = "ultimate-blogroll";
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }
        return $array;
    }
    
    public function init() {
        global $path;
        global $gui;
        $gui["base_url"] = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?";
        $gui["mail_url"] = "http://".$_SERVER["SERVER_NAME"]."/wp-admin".$_SERVER["SCRIPT_NAME"]."?";
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
            "Ultimate Blogroll ".__("Overview", "ultimate-blogroll"), //page title
            __("Manage linkpartners", "ultimate-blogroll"), //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-overview", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
        
        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            __("Add linkpartner", "ultimate-blogroll"), //page title
            __("Add linkpartner", "ultimate-blogroll"), //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-add-linkpartner", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );

        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            __("Import/Export", "ultimate-blogroll"), //page title
            __("Import/Export", "ultimate-blogroll"), //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-import-export", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
        
        $sub_page = add_submenu_page(
            "ultimate-blogroll-overview", //parent slug, because the slug will be the same unlike the menu text, we are not sure of, we link the submenu to the parent slug
            __("Settings", "ultimate-blogroll"), //page title
            __("Settings", "ultimate-blogroll"), //menu title
            "manage_options", //user level, needed before it becomes visible
            "ultimate-blogroll-settings", //slug, in the url
            "ultimate_blogroll" //the function linked to the slug, without this function your slug is useless
        );
    }
    
    public function admin_notices() {
        if(PersistentieMapper::Instance()->CheckIfUltimateBlogrollTagWasSet() === false)
        {
            echo '<div class="updated fade"><p>';
            printf('<b><a href="%s">Ultimate Blogroll</a> '.__("Info", "ultimate-blogroll").':</b> '.__("The tag", "ultimate-blogroll").' <b>%s</b> '.__("has not been set on any", "ultimate-blogroll").' <a href="edit.php?post_type=page">'.__("page", "ultimate-blogroll").'</a>. '.__('Use the <a href="%s">wizard</a> if you are unsure what this means.', 'ultimate-blogroll').'',
                admin_url('admin.php?page=ultimate-blogroll-overview'),
                htmlentities("<!--ultimate-blogroll-->"),
                admin_url('admin.php?page=ultimate-blogroll-overview&action=wizard')
            );
            echo '</p></div>'. "\n";

            if(PersistentieMapper::Instance()->CheckIfTablesExists() === false) {
                echo '<div class="error fade"><p>';
                echo "<b>Ultimate Blogroll:</b> ".__("Could not find the required MySQL tables", "ultimate-blogroll");
                echo '</p></div>'. "\n";
            }
            if($this->checkreciprocalLink(get_bloginfo("wpurl")) != true) {
                echo '<div class="error fade"><p>';
                echo "<b>Ultimate Blogroll:</b> ".__("Could not check for reciprocal website. Check if ports are open.", "ultimate-blogroll");
                echo '</p></div>'. "\n";
            }
        } else {
            $pages = PersistentieMapper::Instance()->GetPagesWithUltimateBlogrollTag();
            $data = PersistentieMapper::Instance()->GetWidgetSettings();
            if(empty($data->permalink) || !isset($data->permalink))
            {
                $data->permalink = $pages[0]["id"];
                update_option("ultimate_blogroll_widget_settings", $data);
            }
        }
    }
    
    

    public function map_entities( $str ) {
        if(is_array($str) )
        {
            return array_map ( array($this, 'map_entities'), $str );
        }
        //objects were given to the $gui appearantly
        //TODO: investigate! NEVER send objects to gui!
        if(is_object($str)) {
            return $str;
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
            $default["website_url"]         = get_bloginfo('siteurl');
            $default["website_title"]       = get_bloginfo('blogname');
            $default["website_description"] = get_bloginfo('description');
            $default["blogroll_contact"]    = get_bloginfo('admin_email');
            $default['send_mail']           = "yes";
            $default['reciprocal_link']     = "yes";
            $default['fight_spam']          = "no";
            $default['target']              = "_blank";
            $default['nofollow']            = "yes";
            $default['support']             = "yes";
            add_option("ultimate_blogroll_general_settings", $default, "", "yes");
        }
        //default settings for the widget settings
        if(get_option("ultimate_blogroll_widget_settings") === false)
        {
            $default = array();
            $default["widget_title"]        = "Ultimate Blogroll";
            $default["limit_linkpartners"]  = 10;
            $default["order_by"]            = "inlinks";
            $default["ascending"]           = "desc";
            $default['permalink']           = "none";
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
        $website_url = strtolower($settings->url);

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
        //$time   = time()-(60*60);
        $tIn    = PersistentieMapper::Instance()->GetTemp48In();
        $tOut   = PersistentieMapper::Instance()->GetTemp48Out();
        PersistentieMapper::Instance()->UpdateCountedLinks();
        
        $time   = time()-(60*60*48);
        $oIn    = PersistentieMapper::Instance()->GetOld48In($time);
        $oOut   = PersistentieMapper::Instance()->GetOld48Out($time);
        PersistentieMapper::Instance()->DeleteOld48($time);
        

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

    
    protected function checkFormAddLinkpartner($linkpartner, $public, $fight_spam, $private_spam_key, $edit = false)
    {   
        //tijdens het herschrijven van checkFormAddLinkpartner() dit aangetroffen: $gui["value"]["website_has_backlink"] = (int)$link_back;
        //als het niet goed werkt, kijken om terug te implementeren
        global $path;
        $error = new ErrorDTO();

        //check if a name was given.
        if(empty($linkpartner->name)) {
            $error->AddErrorField("your_name");
            $error->AddErrorMessage(__("Website owner's name", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }
        
        if(empty($linkpartner->email)) {
            $error->AddErrorField("your_email");
            $error->AddErrorMessage(__("Website owner's email", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        } elseif(filter_var($linkpartner->email, FILTER_VALIDATE_EMAIL) === FALSE) {
            $error->AddErrorField("your_email");
            $error->AddErrorMessage(__("Website owner's email", "ultimate-blogroll")." ".__("is not a valid email address", "ultimate-blogroll"));
        }
        
        if(empty($linkpartner->url)) {
            $error->AddErrorField("website_url");
            $error->AddErrorMessage(__("Website url", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        } elseif(filter_var($linkpartner->url, FILTER_VALIDATE_URL) === FALSE) {
            $error->AddErrorField("website_url");
            $error->AddErrorMessage(__("Website url", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
        }
            
        if(empty($linkpartner->title)) {
            $error->AddErrorField("website_title");
            $error->AddErrorMessage(__("Website title", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(empty($linkpartner->description)) {
            $error->AddErrorField("website_description");
            $error->AddErrorMessage(__("Website description", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(empty($linkpartner->domain)) {
            $error->AddErrorField("website_domain");
            $error->AddErrorMessage(__("Website domain", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        //if on public page then it is required
        if(empty($linkpartner->reciprocal) && $public === true) {
            $error->AddErrorField("website_reciprocal");
            $error->AddErrorMessage(__("Website reciprocal", "ultimate-blogroll")." ".__("is empty", "ultimate-blogroll"));
        }

        if(!empty($linkpartner->reciprocal) && filter_var($linkpartner->reciprocal, FILTER_VALIDATE_URL) === FALSE) {
            $error->AddErrorField("website_reciprocal");
            $error->AddErrorMessage(__("Website reciprocal", "ultimate-blogroll")." ".__("is not a valid url", "ultimate-blogroll"));
        }
            
        if($fight_spam == "yes") {
            require_once($path."gui/recaptchalib.php");
            $resp = recaptcha_check_answer ($private_spam_key, $_SERVER["REMOTE_ADDR"], @$_POST["recaptcha_challenge_field"], @$_POST["recaptcha_response_field"]);
            if(!$resp->is_valid) {
                $error->AddErrorField("captcha");
                $error->AddErrorMessage(__("Anti-spam", "ultimate-blogroll")." ".__("was wrong", "ultimate-blogroll"));
            }
        }
               
        if(!$error->IsError("website_url") && !$error->IsError("website_reciprocal") && !empty($linkpartner->reciprocal)) {
            if(parse_url($linkpartner->url, PHP_URL_HOST) != parse_url($linkpartner->reciprocal, PHP_URL_HOST)) {
                $error->AddErrorField("website_url");
                $error->AddErrorField("website_reciprocal");
                $error->AddErrorMessage(__("The", "ultimate-blogroll")." &quot;".__("website reciprocal", "ultimate-blogroll")."&quot; ".__("must be under the same (sub)domain as the", "ultimate-blogroll")." &quot;".__("Website url", "ultimate-blogroll")."&quot;");
            } else {
                $link_back = $this->checkreciprocalLink($linkpartner->reciprocal);
                if($link_back !== true) {
                    $error->AddErrorField("website_reciprocal");
                    $error->AddErrorMessage(__("The", "ultimate-blogroll")." &quot;".__("website reciprocal", "ultimate-blogroll")."&quot; ".__("does not have a link back.", "ultimate-blogroll"));
                }
                $linkpartner->SetLinkBack((int)$link_back);
            }
        }

        if(!empty($linkpartner->url) && !empty($linkpartner->domain)) {
            if(strpos($linkpartner->url, $linkpartner->domain) === false) {
                $error->AddErrorField("website_domain");
                $error->AddErrorMessage(__("Website domain", "ultimate-blogroll")." ".__("does not fetch with", "ultimate-blogroll")." ".__("Website url", "ultimate-blogroll"));
            }
        }

        if($edit === false) {
            $links = PersistentieMapper::Instance()->GetLinkpartners();
            foreach($links as $link) {
                ////////////////////////////////////////
                $db     = @$link["website_url"];
                $form   = @$linkpartner->url;

                if(!empty($db) && !empty($form)) {
                    $db     = parse_url($db);
                    $form   = parse_url($form);

                    if(isset($db["host"]) && isset($form["host"]) && $db["host"] == $form["host"]) {
                        $error->AddErrorField("website_url");
                        $error->AddErrorMessage(__("Website url", "ultimate-blogroll")." ".__("is already in our system.", "ultimate-blogroll"));
                    }
                }
                ////////////////////////////////////////
                $db     = @$link["website_reciprocal"];
                $form   = @$linkpartner->reciprocal;

                if(!empty($db) && !empty($form)) {
                    $db     = parse_url($db);
                    $form   = parse_url($form);

                    if(isset($db["host"]) && isset($form["host"]) && $db["host"] == $form["host"]) {
                        $error->AddErrorField("website_backlink");
                        $error->AddErrorMessage(__("Website reciprocal", "ultimate-blogroll")." ".__("is already in our system.", "ultimate-blogroll"));
                    }
                }
                ////////////////////////////////////////
                $db     = @$link["website_domein"];
                $form   = @$linkpartner->domain;

                if($db == $form) {
                    $error->AddErrorField("website_domain");
                    $error->AddErrorMessage(__("Website domain", "ultimate-blogroll")." ".__("is already in our system.", "ultimate-blogroll"));
                }//if
            }//foreach
        }//if
        return $error;
    }//function
}//class
?>