<?php
/**
 * Description of WidgetController
 *
 * @author Jens
 */
require_once($path."domein/UltimateBlogrollController.php");
require_once($path."persistentie/dto/LinkpartnerDTO.php");
class WidgetController extends UltimateBlogrollController {
    public function __construct() {
        parent::__construct();
        $this->CheckInlinks();
    }

    private function GetLimit($limit) {
        return (int)$limit;
    }

    private function CheckInlinks() {
        if(!is_admin()) {
            if(isset($_SERVER["HTTP_REFERER"])) {
                //if($_SERVER["HTTP_REFERER"])
                $referer = parse_url($_SERVER["HTTP_REFERER"]);
                $url = parse_url(get_bloginfo("wpurl"));
                if($referer["host"] != $url["host"]) {
                    $links = (PersistentieMapper::Instance()->GetLinkpartnersToCheckAgainstInlinks());
                    if(!empty($links)) {
                        foreach($links as $link) {
                            if(strpos($referer["host"], $link["website_domein"]) !== false) {
                                PersistentieMapper::Instance()->AddTotalLinkin($link["website_id"]);
                                PersistentieMapper::Instance()->Add48Linkin($link["website_id"]);
                                break;
                                return;
                            }//if
                        }//foreach
                    }//if
                }//if
            }//if
        }//if
    }//function

    private function GetOrder($order) {
        switch($order) {
            case "asc":
                $result = "asc";
                break;
            case "desc":
                $result = "desc";
                break;
            default:
                $result = "asc";
                break;
        }
        return $result;
    }

    private function GetOrderBy($orderby) {
        switch($orderby) {
            case "id":
                $result = "website_id";
                break;
            case "name":
                $result = "website_name";
                break;
            case "inlinks":
                $result = "website_total_inlink";
                break;
            case "outlinks":
                $result = "website_total_outlink";
                break;
            default:
                $result = "website_name";
                break;
        }
        return $result;
    }

    private function GetTarget($target) {
        switch($target) {
            case "_blank":
                $result = "_blank";
                break;
            case "_top":
                $result = "_top";
                break;
            case "_none":
                $result = "_none";
                break;
            default:
                $result = "_blank";
                break;
        }

        return " target=\"".$result."\"";
    }

    private function GetFollow($follow){
        if(!is_home() && $follow == "yes") {
            return " rel=\"nofollow\"";
        }
    }

    public function UBWidget($args)
    {
        
        $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
        $general_settings = PersistentieMapper::Instance()->GetGeneralSettings();

        $linkpartners = PersistentieMapper::Instance()->GetLinkpartnersWidget($this->GetLimit($widget_settings->limit), $this->GetOrder($widget_settings->ascending), $this->GetOrderBy($widget_settings->order_by));
        $linkpartners = $this->map_entities($linkpartners);

        extract($args);
        $gui = "";
        $gui .= $before_widget;
        $gui .= $before_title . htmlentities($widget_settings->title, ENT_QUOTES) . $after_title;
        $gui .= "<ul>";
        if(!empty($linkpartners))
        {
            foreach($linkpartners as $linkpartner) {
                $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" title=\"".($linkpartner["website_description"])."\"".$this->GetTarget($general_settings->target).$this->GetFollow($general_settings->nofollow).">".$linkpartner["website_name"]."</a></li>";
            }
        }
        if(!empty($widget_settings->permalink))
            $gui .= "<li><a href=\"".get_permalink($widget_settings->permalink)."\">".__("More", "ultimate-blogroll")."</a></li>";
        $gui .= "</ul>";
        $gui .= $after_widget;

        echo $gui;
    }

    public function UBWidget_control() {
        if(isset($_POST["ub_submit"])) {
            $data->title        = attribute_escape($_POST["widget_title"]);
            $data->limit        = intval($_POST["limit_linkpartners"]);
            $data->order_by     = attribute_escape($_POST["order_by"]);
            $data->ascending    = attribute_escape($_POST["ascending"]);
            $data->permalink    = intval($_POST["permalink"]);
            update_option("ultimate_blogroll_widget_settings", $data);
        }
        $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
        echo "<style type=\"text/css\">
            .widget_text {
                width: 220px;
            }
        </style>";
        echo "<p><label>".__("Widget title", "ultimate-blogroll").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"widget_title\" value=\"".@$widget_settings->title."\" />
        </p>";
        echo "<p><label>".__("Limit of linkpartners", "ultimate-blogroll").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"limit_linkpartners\" value=\"".@$widget_settings->limit."\" />
        </p>";
        echo "<p><label>".__("Order by", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"order_by\">
                <option ".((@$widget_settings->order_by == "id") ? "selected=\"yes\"" : "")." value=\"id\">".__("ID", "ultimate-blogroll")."</option>
                <option ".((@$widget_settings->order_by == "name") ? "selected=\"yes\"" : "")." value=\"name\">".__("Name", "ultimate-blogroll")."</option>
                <option ".((@$widget_settings->order_by == "inlinks") ? "selected=\"yes\"" : "")." value=\"inlinks\">".__("Inlinks", "ultimate-blogroll")."</option>
                <option ".((@$widget_settings->order_by == "outlinks") ? "selected=\"yes\"" : "")." value=\"outlinks\">".__("Outlinks", "ultimate-blogroll")."</option>
            </select>
        </p>";
        echo "<p><label>".__("Ascending/Descending", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"ascending\">
                <option ".((@$widget_settings->ascending == "asc") ? "selected=\"yes\"" : "")." value=\"asc\">".__("Ascending", "ultimate-blogroll")."</option>
                <option ".((@$widget_settings->ascending == "desc") ? "selected=\"yes\"" : "")." value=\"desc\">".__("Descending", "ultimate-blogroll")."</option>
            </select>
        </p>";
        echo "<p><label>".__("Link exchange page", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"permalink\">";
        $pages = PersistentieMapper::Instance()->GetPagesWithUltimateBlogrollTag();
        if(!empty($pages))
        {
            foreach($pages as $page)
            {
                echo "<option value=\"".$page["id"]."\" ".((@$widget_settings->permalink == $page["id"]) ? "selected=\"yes\"" : "").">".$page["post_title"]."</option>";
            }
        }
        echo "</select></p>";
        echo "<input type=\"hidden\" name=\"ub_submit\" value=\"1\" />";
    }
    
    public function ub_ajax_action_callback() {
        global $wpdb;
        $linkpartner = @$_POST['linkpartner'];
        $id = PersistentieMapper::Instance()->GetIDLinkpartnerFromUrl($linkpartner);
        PersistentieMapper::Instance()->AddTotalLinkout($id);
        PersistentieMapper::Instance()->Add48Linkout($id);
        die();
    }
    
    public function ub_javascript_init() {
        //make sure we have jquery loaded since the lazy motherf*cker won't load all the time
        wp_enqueue_script('jquery');
        $output = '<script type="text/javascript" >
jQuery(document).ready(function($) {
    $("#ultimage-blogroll ul li a").click(function () {
        var data = {
            action: "ub_ajax_action_callback",
            linkpartner: $(this).attr("href")
        };
        jQuery.post("'.get_bloginfo("wpurl").'/wp-admin/admin-ajax.php", data, function(response) {
            //alert("Got this from the server: " + response);
        });
    });
    
    
});
</script>';
        echo $output;
    }
    
    public function widget_init() {
        register_sidebar_widget('Ultimate Blogroll', array($this, 'UBWidget'));
        register_widget_control('Ultimate Blogroll', array($this, 'UBWidget_control'));
    }

    public function create_page($content='') {
        if(! preg_match('|<!--ultimate-blogroll-->|', $content)) {
            return $content;
        } else {
            global $gui;
            $_POST = is_array($_POST) ? array_map('stripslashes_deep', $_POST) : stripslashes($_POST);
            $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
            $general_settings = PersistentieMapper::Instance()->GetGeneralSettings();
            $captcha_settings = PersistentieMapper::Instance()->GetRecaptchaSettings();

            $gui["edit"] = false;
            $gui["fight_spam"] = $general_settings->fight_spam;
            $gui["captcha_settings"] = $captcha_settings;

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

                $gui["value"]["your_name"]           = $linkpartner->name;
                $gui["value"]["your_email"]          = $linkpartner->email;
                $gui["value"]["website_url"]         = $linkpartner->url;
                $gui["value"]["website_title"]       = $linkpartner->title;
                $gui["value"]["website_description"] = $linkpartner->description;
                $gui["value"]["website_domain"]      = $linkpartner->domain;
                $gui["value"]["website_reciprocal"]  = $linkpartner->reciprocal;

                $error = $this->checkFormAddLinkpartner($linkpartner, true, $general_settings->fight_spam, $captcha_settings->recaptcha_private_key, false);
                if($error->ContainsErrors() === false){
                    PersistentieMapper::Instance()->AddLinkpartner($linkpartner);
                    PersistentieMapper::Instance()->SendAnouncementMail($linkpartner, $general_settings->contact);
                    $gui["success"] = true;
                    $gui["value"] = array();
                }
                
                $gui["error"]["messages"]           = $error->GetErrorMessages();
                $gui["error"]["fields"]             = $error->GetErrorFields();
                unset($error);
                unset($linkpartner);
            } else {
                $gui["value"] = array();
            }

            $gui["table_links"] = PersistentieMapper::Instance()->GetLinkpartnersPage($this->GetOrder($widget_settings->ascending), $this->GetOrderBy($widget_settings->order_by));
            
            //secure our output
            $gui["value"] = array_map ( array($this, 'map_entities'), $gui["value"] );
            $gui["table_links_target"]  = $this->GetTarget($general_settings->target);

            $gui["url"]                 = get_bloginfo("wpurl");
            $gui["title"]               = @$general_settings->title;
            $gui["description"]         = @$general_settings->description;

            $gui["support"]             = @$general_settings->support;

            ob_start(); // begin collecting output
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/create_page.php");
            $result = ob_get_clean();
            return "<p>".$result."</p>";
        }
    }
}
?>
