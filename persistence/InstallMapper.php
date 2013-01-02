<?php
namespace UltimateBlogroll;
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 22/11/11
 * Time: 20:15
 */
 
class InstallMapper {
    /**
     * We added some tables automatically, but maybe the wordpress database credentials were not authorized to do so
     * Check if the tables exist as we expect them to be.
     * @return bool
     */
    public function doRequiredTablesExist() {
        global $wpdb;
        if(!$wpdb->get_var("show tables like '".$wpdb->prefix."ubsites'")) {
            return false;
        }
        return true;
    }

    /**
     * Create the necessary tables for ultimate blogroll
     */
    public function installDatabase()
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
              `website_image` VARCHAR(100) NOT NULL,
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
    }

    /**
     * Primarily, check if curl is working
     * Fallback, check if file_get_contents is working
     * @return bool, true if working
     */
    public function isGetExternalSiteWorking() {
        if($this->isCurlWorking())
            return true;
        if($this->isFileGetContentsWorking())
            return true;
        return false;
    }

    /**
     * Check if file_get_contents is working
     * first: check if the function file_get_contents is enabled
     * second: do a page request of the wordpress site itself and check if the result is not empty
     * @return bool
     */
    public function isFileGetContentsWorking() {
        if (function_exists('file_get_contents')) {
            $content = @file_get_contents(get_bloginfo('siteurl'));
            if( ! is_null($content) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if Curl is active
     * @return bool, true if active
     */
    public function isCurlWorking() {
        if  (in_array  ('curl', get_loaded_extensions())) {
                return true;
        }
        return false;
    }

    /**
     * Loads the getPagesWithUltimateBlogrollTag() method to check if an ultimate blogroll page was set
     * it gets the result array of the query and counts the result, must be greater than zero.
     * @return bool
     */
    public function isThereAnUltimateBlogrollPageCreated() {
        if(count($this->getPagesWithUltimateBlogrollTag()) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Query to get every page with the ultimate blogroll tag in it
     * @return array of ultimate blogroll pages
     */
    public function getPagesWithUltimateBlogrollTag()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $result = $wpdb->get_results("select id, post_title from ".$table_name." where `post_type` = 'page' and `post_status` = 'publish' and `post_content` like '%<!--ultimate-blogroll-->%'", ARRAY_A);
        return $result;
    }

    /**
     * return all published pages within wordpress
     * @return mixed
     */
    public function getPublishedPages() {
        global $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $result = $wpdb->get_results("select id, post_title from ".$table_name." where `post_type` = 'page' and `post_status` = 'publish'", ARRAY_A);
        return $result;
    }

    /**
     * Generate a random hash: a-Z 0-9
     * @param $length, the length of the generated hash
     * @return String, containing the generated random hash
     */
    public function makeRandom($length)
    {
        $availableChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $generatedID = null;
        $lengthOfAvailableChars = count($availableChars) - 1;

        for($i = 0; $i < $length; $i++)
        {
            $randomChar = mt_rand(0, $lengthOfAvailableChars);
            $generatedID .= $availableChars[$randomChar];
        }
        return $generatedID;
    }
}