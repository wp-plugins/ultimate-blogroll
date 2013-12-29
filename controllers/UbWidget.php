<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/12/11
 * Time: 22:21
 * To change this template use File | Settings | File Templates.
 */

class MyNewWidget extends WP_Widget {

    function MyNewWidget() {
        // Instantiate the parent object
        parent::__construct( false, 'ub2' );
    }

    function widget( $args, $instance ) {
        extract($args);
        $linkpartners = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->
            getLinkpartnersWidget(
                $this->GetLimit(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("limit_linkpartners")),
                $this->GetOrder(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("ascending")),
                $this->GetOrderBy(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by"))
            );
        $gui = "";
        $gui .= $before_widget;
        $gui .= $before_title . htmlentities(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("widget_title"), ENT_QUOTES) . $after_title;
        $gui .= "<ul>";
        if(!empty($linkpartners))
        {
            foreach($linkpartners as $linkpartner) {
                if(!empty($linkpartner["website_image"]) && UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo") == "yes") {
                    $logo_usage = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_usage");
                    if($logo_usage == "text") {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."</a></li>";
                    }
                    elseif($logo_usage == "image") {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow"))."><img style=\"width: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_width")."px; max-height: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_height")."px;\" src=\"".$linkpartner["website_image"]."\" alt=\"".($linkpartner["website_description"])."\" /></a></li>";
                    }
                    else {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."<img style=\"width: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_width")."px; max-height: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_height")."px;\" src=\"".$linkpartner["website_image"]."\" alt=\"".($linkpartner["website_description"])."\" /></a></li>";
                    }
                } else {
                    $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" title=\"".($linkpartner["website_description"])."\"".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."</a></li>";
                }
            }
        }
        $permalink = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages");
        if(!empty($permalink) && $permalink != "")
        {
            //$gui .= "<li><a href=\"".get_permalink(UbMapper::getInstance(UbMapper::Settings)->getConfig("permalink"))."\">".__("More", "ultimate-blogroll")."</a></li>";
            $gui .= "<li><a href=\"".$this->AddWebsiteURL(get_permalink(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages")))."#wp-add-your-site\">".__("Add link", "ultimate-blogroll")."</a></li>";
        }
        $gui .= "</ul>";
        $gui .= $after_widget;
        var_dump("bla");
        echo $gui;
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
    }

    function form( $instance ) {
        // Output admin widget options form
    }
}





class UbWidget extends UbMain {
    public function __construct() {
        parent::__construct();
    }
    /**
     * Initiate and register the widgets
     */
    public function widgetInit() {
        register_sidebar_widget('Ultimate Blogroll', array($this, 'widgetCreator'));
        register_widget_control('Ultimate Blogroll', array($this, 'widgetControl'));
    }

    /**
     * Show the widget
     * @param $args
     */
    public function widgetCreator($args) {
        extract($args);
        $linkpartners = UbPersistenceRouter::getInstance(UbPersistenceRouter::Linkpartner)->
                getLinkpartnersWidget(
            $this->GetLimit(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("limit_linkpartners")),
            $this->GetOrder(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("ascending")),
            $this->GetOrderBy(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by"))
        );
        $gui = "";
        $gui .= $before_widget;
        $gui .= $before_title . htmlentities(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("widget_title"), ENT_QUOTES) . $after_title;
        $gui .= "<ul>";
        if(!empty($linkpartners))
        {
            foreach($linkpartners as $linkpartner) {
                if(!empty($linkpartner["website_image"]) && UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo") == "yes") {
                    $logo_usage = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_usage");
                    if($logo_usage == "text") {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."</a></li>";
                    }
                    elseif($logo_usage == "image") {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow"))."><img style=\"width: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_width")."px; max-height: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_height")."px;\" src=\"".$linkpartner["website_image"]."\" alt=\"".($linkpartner["website_description"])."\" /></a></li>";
                    }
                    else {
                        $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" ".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."<img style=\"width: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_width")."px; max-height: ".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("logo_height")."px;\" src=\"".$linkpartner["website_image"]."\" alt=\"".($linkpartner["website_description"])."\" /></a></li>";
                    }
                } else {
                    $gui .= "<li><a href=\"".$linkpartner["website_url"]."\" title=\"".($linkpartner["website_description"])."\"".$this->GetTarget(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("target")).$this->GetFollow(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("nofollow")).">".$linkpartner["website_name"]."</a></li>";
                }
            }
        }
        $permalink = UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages");
        if(!empty($permalink) && $permalink != "")
        {
            //$gui .= "<li><a href=\"".get_permalink(UbMapper::getInstance(UbMapper::Settings)->getConfig("permalink"))."\">".__("More", "ultimate-blogroll")."</a></li>";
            $gui .= "<li><a href=\"".$this->AddWebsiteURL(get_permalink(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages")))."#wp-add-your-site\">".__("Add link", "ultimate-blogroll")."</a></li>";
        }
        $gui .= "</ul>";
        $gui .= $after_widget;
        echo $gui;
    }

    /**
     * @param $url
     * @return string
     */
    private function AddWebsiteURL($url)
    {
        return rtrim($url, '/');
    }

    /**
     * The widget control, here you have the widget options.
     * The control options can be found in the widget section of the wordpress admin section.
     */
    public function widgetControl() {
        if(isset($_POST["ub_submit"])) {
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->setConfig("widget_title",       attribute_escape($_POST["widget_title"]));
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->setConfig("limit_linkpartners", intval($_POST["limit_linkpartners"]));
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->setConfig("order_by",           attribute_escape($_POST["order_by"]));
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->setConfig("ascending",          attribute_escape($_POST["ascending"]));
            UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->setConfig("pages",          intval($_POST["permalink"]));
        }
        echo "<style type=\"text/css\">
            .widget_text {
                width: 220px;
            }
        </style>";
        echo "<p><label>".__("Widget title", "ultimate-blogroll").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"widget_title\" value=\"".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("widget_title")."\" />
        </p>";
        echo "<p><label>".__("Limit of linkpartners", "ultimate-blogroll").":</label><br />
            <input type=\"text\" class=\"widget_text\" name=\"limit_linkpartners\" value=\"".UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("limit_linkpartners")."\" />
        </p>";
        echo "<p><label>".__("Order by", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"order_by\">
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by") == "id") ? "selected=\"yes\"" : "")." value=\"id\">".__("ID", "ultimate-blogroll")."</option>
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by") == "name") ? "selected=\"yes\"" : "")." value=\"name\">".__("Name", "ultimate-blogroll")."</option>
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by") == "inlinks") ? "selected=\"yes\"" : "")." value=\"inlinks\">".__("Inlinks", "ultimate-blogroll")."</option>
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("order_by") == "outlinks") ? "selected=\"yes\"" : "")." value=\"outlinks\">".__("Outlinks", "ultimate-blogroll")."</option>
            </select>
        </p>";
        echo "<p><label>".__("Ascending/Descending", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"ascending\">
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("ascending") == "asc") ? "selected=\"yes\"" : "")." value=\"asc\">".__("Ascending", "ultimate-blogroll")."</option>
                <option ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("ascending") == "desc") ? "selected=\"yes\"" : "")." value=\"desc\">".__("Descending", "ultimate-blogroll")."</option>
            </select>
        </p>";
        $pages = UbPersistenceRouter::getInstance(UbPersistenceRouter::Install)->getPublishedPages();
        echo "<p><label>".__("Link exchange page", "ultimate-blogroll").":</label><br />
            <select class=\"widget_text\" name=\"permalink\">";
        if(!empty($pages))
        {
            foreach($pages as $page)
            {
                echo "<option value=\"".$page["id"]."\" ".((UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("pages") == $page["id"]) ? "selected=\"yes\"" : "").">".$page["post_title"]."</option>";
            }
        }
        echo "</select></p>";
        echo "<input type=\"hidden\" name=\"ub_submit\" value=\"1\" />";
    }
    
    /**
     * @param $target
     * @return string
     */
    /*
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
    }*/

    /**
     * @param $follow
     * @return string
     */
    /*
    private function GetFollow($follow){
        if(!is_home() && $follow == "yes") {
            return " rel=\"nofollow\"";
        }
    }
    */
}
