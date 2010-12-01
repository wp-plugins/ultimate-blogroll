<?php
/**
 * Description of InstallMapper
 *
 * @author Jens
 */
class InstallMapper {
    private $database;
    
    public function __construct()
    {
        global $wpdb;
        $this->database = $wpdb;
    }
    
    public function InstallDatabase()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        if(!$wpdb->get_var("show tables like '".$wpdb->prefix."ubsites'"))
        {
            $sql =
            "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ubsites` (
              `website_id` int(11) NOT NULL auto_increment,
              `website_owner_name` varchar(100) NOT NULL,
              `website_owner_email` varchar(100) NOT NULL,
              `website_date_added` int(10) NOT NULL,
              `website_name` varchar(100) NOT NULL,
              `website_description` varchar(100) NOT NULL,
              `website_domein` varchar(100) NOT NULL,
              `website_url` varchar(100) NOT NULL,
              `website_backlink` varchar(100) NOT NULL,
              `website_total_inlink` int(11) NOT NULL default '0',
              `website_total_outlink` int(11) NOT NULL default '0',
              `website_last2days_inlink` int(11) NOT NULL default '0',
              `website_last2days_outlink` int(11) NOT NULL default '0',
              `website_last_update` int(10) NOT NULL,
              `website_status` enum('a','u') NOT NULL default 'u',
              `website_change_id` varchar(50) NOT NULL,
              `website_has_backlink` smallint(1) NOT NULL default '0',
              `website_ip` varchar(50) NOT NULL,
              PRIMARY KEY  (`website_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
            ";
            dbDelta($sql);
        }
        if(!$wpdb->get_var("show tables like '".$wpdb->prefix."ublinks'"))
        {
            $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ublinks` (
              `links_id` int(11) NOT NULL auto_increment,
              `links_website_id` int(11) NOT NULL,
              `links_ip` varchar(100) NOT NULL,
              `links_date` int(10) NOT NULL,
              `links_type` enum('i','o') NOT NULL,
              `links_counted` smallint(1) NOT NULL default '0',
              PRIMARY KEY  (`links_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
            dbDelta($sql);
        }
        update_option("ultimate_blogroll_db_version", "1.0");
    }
    
    public function GetPagesWithUltimateBlogrollTag()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $result = $wpdb->get_results("select id, post_title from ".$table_name." where `post_type` = 'page' and `post_status` = 'publish' and `post_content` like '%<!--ultimate-blogroll-->%'", ARRAY_A);
        return $result;
    }
}
?>