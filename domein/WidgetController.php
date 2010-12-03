<?php
/**
 * Description of WidgetController
 *
 * @author Jens
 */
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
                            }
                        }
                    }
                }
            }
        }
    }

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

        $linkpartners = PersistentieMapper::Instance()->GetLinkpartnersWidget($this->GetLimit($widget_settings["limit_linkpartners"]), $this->GetOrder($widget_settings["ascending"]), $this->GetOrderBy($widget_settings["order_by"]));
        $linkpartners = $this->map_entities($linkpartners);

        extract($args);
        $gui = "";
        $gui .= $before_widget;
        $gui .= $before_title . htmlentities($widget_settings["widget_title"], ENT_QUOTES) . $after_title;
        $gui .= "<ul>";
        if(!empty($linkpartners))
        {
            foreach($linkpartners as $linkpartner) {
                $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" title=\"".($linkpartner["website_description"])."\"".$this->GetTarget($general_settings["target"]).$this->GetFollow($general_settings["nofollow"]).">".$linkpartner["website_name"]."</a></li>";
            }
        }
        if(!empty($widget_settings["permalink"]))
            $gui .= "<li><a href=\"".get_permalink($widget_settings["permalink"])."\">".__("More")."</a></li>";
        $gui .= "</ul>";
        $gui .= $after_widget;

        echo $gui;
    }

    public function UBWidget_control() {
        if(isset($_POST["ub_submit"])) {
            $data["widget_title"]       = attribute_escape($_POST["widget_title"]);
            $data["limit_linkpartners"] = intval($_POST["limit_linkpartners"]);
            $data["order_by"]           = attribute_escape($_POST["order_by"]);
            $data["ascending"]          = attribute_escape($_POST["ascending"]);
            $data["permalink"]          = intval($_POST["permalink"]);
            update_option("ultimate_blogroll_widget_settings", $data);
        }
        $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
        echo "<style type=\"text/css\">
            .widget_text {
                width: 220px;
            }
        </style>";
        echo "<p><label>".__("Widget title").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"widget_title\" value=\"".@$widget_settings["widget_title"]."\" />
        </p>";
        echo "<p><label>".__("Limit of linkpartners").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"limit_linkpartners\" value=\"".@$widget_settings["limit_linkpartners"]."\" />
        </p>";
        echo "<p><label>".__("Order by").":</label><br />
            <select class=\"widget_text\" name=\"order_by\">
                <option ".((@$widget_settings["order_by"] == "id") ? "selected=\"yes\"" : "")." value=\"id\">".__("ID")."</option>
                <option ".((@$widget_settings["order_by"] == "name") ? "selected=\"yes\"" : "")." value=\"name\">".__("Name")."</option>
                <option ".((@$widget_settings["order_by"] == "inlinks") ? "selected=\"yes\"" : "")." value=\"inlinks\">".__("Inlinks")."</option>
                <option ".((@$widget_settings["order_by"] == "outlinks") ? "selected=\"yes\"" : "")." value=\"outlinks\">".__("Outlinks")."</option>
            </select>
        </p>";
        echo "<p><label>".__("Ascending/Descending").":</label><br />
            <select class=\"widget_text\" name=\"ascending\">
                <option ".((@$widget_settings["ascending"] == "asc") ? "selected=\"yes\"" : "")." value=\"asc\">".__("Ascending")."</option>
                <option ".((@$widget_settings["ascending"] == "desc") ? "selected=\"yes\"" : "")." value=\"desc\">".__("Descending")."</option>
            </select>
        </p>";
        echo "<p><label>".__("Link exchange page").":</label><br />
            <select class=\"widget_text\" name=\"permalink\">";
        $pages = PersistentieMapper::Instance()->GetPagesWithUltimateBlogrollTag();
        if(!empty($pages))
        {
            foreach($pages as $page)
            {
                echo "<option value=\"".$page["id"]."\" ".((@$widget_settings["permalink"] == $page["id"]) ? "selected=\"yes\"" : "").">".$page["post_title"]."</option>";
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
        register_sidebar_widget('Ultimage Blogroll', array($this, 'UBWidget'));
        register_widget_control('Ultimage Blogroll', array($this, 'UBWidget_control'));
    }

    public function create_page($content='') {
        if(! preg_match('|<!--ultimate-blogroll-->|', $content)) {
            return $content;
        } else {
            global $gui;
            $widget_settings = PersistentieMapper::Instance()->GetWidgetSettings();
            $general_settings = PersistentieMapper::Instance()->GetGeneralSettings();
            $captcha_settings = PersistentieMapper::Instance()->GetRecaptchaSettings();
            //var_dump($general_settings);

            $gui["edit"] = false;
            $gui["public_add"] = true;
            $gui["fight_spam"] = $general_settings["fight_spam"];
            $gui["captcha_settings"] = $captcha_settings;

            $this->checkFormAddLinkpartner();

            $gui["table_links"] = PersistentieMapper::Instance()->GetLinkpartnersPage($this->GetOrder($widget_settings["ascending"]), $this->GetOrderBy($widget_settings["order_by"]));
            
            //secure our output
            $gui = array_map ( array($this, 'map_entities'), $gui );
            $gui["table_links_target"]  = $this->GetTarget($general_settings["target"]);
            $gui["url"]                 = get_bloginfo("wpurl");
            //var_dump($general_settings);
            $gui["title"]               = $general_settings["website_title"];
            $gui["description"]         = $general_settings["website_description"];
            $gui["support"]             = @$general_settings["support"];

            ob_start(); // begin collecting output
            require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/create_page.php");
            $result = ob_get_clean();
            return "<p>".$result."</p>";
        }
    }
}
?>
