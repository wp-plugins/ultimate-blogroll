<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/12/11
 * Time: 19:18
 * To change this template use File | Settings | File Templates.
 */
 
class LinkpartnerMapper {
    /**
     * This query requests every column from the linkpartners
     * Is used for the overview in the admin panel
     * @param null $where, $where = approved|unapproved|null
     * @param null $search
     * @return array with the requested linkpartners
     */
    public function getLinkpartners($where = null, $search = null) {
        global $wpdb;
        $query = "";
        $table_name = $wpdb->prefix . "ubsites";
        if(!empty($where) and !empty($search))
        {
            switch($where){
                case "approved":
                    $query = $wpdb->prepare("select * from (select * from ".$table_name." where website_status = 'a') as linkpartners where website_name like '%s' or website_url like '%s'", array("%".$search."%", "%".$search."%"));
                    break;
                case "unapproved":
                    $query = $wpdb->prepare("select * from (select * from ".$table_name." where website_status = 'u') as linkpartners where website_name like '%s' or website_url like '%s'", array("%".$search."%", "%".$search."%"));
                    break;
                default:
                    $query = $wpdb->prepare("select * from ".$table_name." where website_name like '%s' or website_url like '%s'", array("%".$search."%", "%".$search."%"));
                    break;
            }
        } elseif(!empty($where)) {
            switch($where){
                case "approved":
                    $query = "select * from ".$table_name." where website_status = 'a'";
                    break;
                case "unapproved":
                    $query = "select * from ".$table_name." where website_status = 'u'";
                    break;
                default:
                    $query = "select * from ".$table_name;
                    break;
            }
        } elseif(!empty($search)) {
            $query = $wpdb->prepare("select * from ".$table_name." where website_name like '%s' or website_url like '%s'", array("%".$search."%", "%".$search."%"));
        } else {
            $query = "select * from ".$table_name;
        }
        return $wpdb->get_results($query, ARRAY_A);
    }

    /**
         * Get the linkpartners with all details
         * @param $order, asc or desc
         * @param $orderby, order by a column
         * @return array with the requested linkpartners
         */
        public function getLinkpartnersPage($order, $orderby) {
            global $wpdb;
            $table_name = $wpdb->prefix . "ubsites";
            $sql = "SELECT * from ".$table_name." where website_status = 'a' order by ".$orderby." ".$order;
            return $wpdb->get_results($sql, ARRAY_A);
        }

    /**
     * Get the linkpartners for the widget
     * @param $amount, how many linkpartners do you want
     * @param $order, asc or desc
     * @param $orderby, order by a column
     * @return array with the requested linkpartners
     */
    public function getLinkpartnersWidget($amount, $order, $orderby) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        if($amount > 0)
        {
            $q = " limit 0, ".intval($amount);
        } else {
            $q = "";
        }
        $sql = "SELECT website_name, website_description, website_url, website_image from ".$table_name." where website_status = 'a' order by ".$orderby." ".$order.$q;
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Checks if our link is actually on the page
     * @param $url, the reciprocal link, on which page do we find our website_url
     * @internal param $website_url , look for this $website_url in the $url
     * @return bool
     */
    public function checkreciprocalLink($url) {
            $html = false;
            if(Mapper::getInstance(Mapper::Install)->isCurlWorking()) {
                $crl = curl_init();
                $timeout = 5;
                curl_setopt ($crl, CURLOPT_URL,$url);
                curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
                $html = curl_exec($crl);
                curl_close($crl);
            } else {
                $html = @file_get_contents($url);
            }
            if($html === false)
                return false;
            $html = strtolower($html);
            $website_url = strtolower(Mapper::getInstance(Mapper::Settings)->getConfig("website_url"));

            $found = false;
            if (preg_match_all('/<a\s[^>]*href=([\"\']??)([^" >]*?)\\1([^>]*)>/siU', $html, $matches, PREG_SET_ORDER)) {
                foreach($matches as $match)
                {
                    if ($match[2] == $website_url || $match[2] == $website_url.'/')
                    {
                        return true;
                    }
                }
            }
            return false;
        }

    /**
     * Check if we already have the submitted website in our system
     * @param $url
     * @param $reciprocal
     * @param $domain
     * @param $website_name
     * @return bool, returns true if we already hava a submission
     */
    public function doWeAlreadyHaveThisSubmission($url, $reciprocal, $domain, $website_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        if(empty($reciprocal))
            $sql = $wpdb->prepare("select website_id from ".$table_name." where website_url = %s or website_domein = %s or website_name = %s", array($url, $domain, $website_name));
        else
            $sql = $wpdb->prepare("select website_id from ".$table_name." where website_url = %s or website_backlink = %s or website_domein = %s or website_name = %s", array($url, $reciprocal, $domain, $website_name));
        $wpdb->get_results($sql);
        if($wpdb->num_rows > 0)
            return true;
        return false;
    }

    /**
     * Add a new linkpartner into ultimate blogroll
     * @param $your_name
     * @param $your_email
     * @param $website_url
     * @param $website_title
     * @param $website_description
     * @param $domain
     * @param $reciprocal
     * @param $image
     * @param $has_link_back
     * @return mixed
     */
    public function addLinkpartner($your_name, $your_email, $website_url, $website_title, $website_description, $domain, $reciprocal, $image, $has_link_back) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $data = array(
            website_owner_name => $your_name,
            website_owner_email => $your_email,
            website_name => $website_title,
            website_description => $website_description,
            website_domein => str_replace('www.', '', $domain),
            website_url => $website_url,
            website_backlink => $reciprocal,
            website_last_update => time(),
            website_change_id => Mapper::getInstance(Mapper::Install)->makeRandom(50),
            website_date_added => time(),
            website_has_backlink => $has_link_back,
            website_ip => $_SERVER['REMOTE_ADDR'],
            website_image => $image
        );
        $format = array(
            "%s",//website_owner_name
            "%s",//website_owner_email
            "%s",//website_name
            "%s",//website_description
            "%s",//website_domein
            "%s",//website_url
            "%s",//website_backlink
            "%d",//website_last_update
            "%s",//website_change_id
            "%d",//website_date_added
            "%d",//website_has_backlink
            "%s",//website_ip
            "%s"//website_image
        );
        return $wpdb->insert($table_name, $data, $format);
    }

    /**
     * @param $your_name
     * @param $your_email
     * @param $website_url
     * @param $website_title
     * @param $website_description
     * @param $domain
     * @param $reciprocal
     * @param $image
     * @param $has_link_back
     * @param $website_id
     * @return mixed
     */
    public function editLinkpartner($your_name, $your_email, $website_url, $website_title, $website_description, $domain, $reciprocal, $image, $has_link_back, $website_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";

        $result = array();
        $result["website_owner_name"] = $your_name;
        $result["website_owner_email"] = $your_email;
        $result["website_name"] = $website_title;
        $result["website_description"] = $website_description;
        $result["website_domein"] = str_replace('www.', '', $domain);
        $result["website_url"] = $website_url;
        $result["website_backlink"] = $reciprocal;
        $result["website_last_update"] = time();
        $result["website_change_id"] = Mapper::getInstance(Mapper::Install)->makeRandom(50);
        $result["website_date_added"] = time();
        $result["website_has_backlink"] = $has_link_back;
        $result["website_ip"] = $_SERVER['REMOTE_ADDR'];
        $result["website_image"] = $image;
        $result["website_id"] = $website_id;

        $sql = $wpdb->prepare("update ".$table_name." set
            website_owner_name = %s,
            website_owner_email = %s,
            website_name = %s,
            website_description = %s,
            website_domein = %s,
            website_url = %s,
            website_backlink = %s,
            website_last_update = %d,
            website_change_id = %s,
            website_date_added = %d,
            website_has_backlink = %d,
            website_ip = %s,
            website_image = %s
            WHERE
            website_id = %d", $result
        );
        return $wpdb->query($sql);
    }

    /**
     * @param $id
     * @return bool
     */
    public function getLinkpartnerByID($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $sql = $wpdb->prepare("SELECT * FROM ".$table_name." WHERE website_id = %d", array($id));
        $result = $wpdb->get_results($sql, ARRAY_A);
        if(isset($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * @param $id
     * @param $status
     */
    public function updateApproveStatus($id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $wpdb->update( $table_name, array("website_status" => $status), array("website_id" => $id) );
    }

    /**
     * @param $id
     * @param $status
     */
    public function updateBacklinkStatus($id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $wpdb->update( $table_name, array("website_has_backlink" => $status), array("website_id" => $id) );
    }

    /**
     * Delete a linkpartner with a given id
     * @param $id
     */
    public function deleteLinkpartner($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $sql = $wpdb->prepare("DELETE FROM ".$table_name." WHERE website_id = %d", array($id));
        $wpdb->query($sql);
    }

    /**
     * This is displayed in the overview menu in the admin panel
     * all (3) approved (2) unapproved (1)
     * @return array with the amount of linkpartners in each category
     */
    public function getNumberOfStatus() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";

        $result["approved"] = 0;
        $result["unapproved"] = 0;

        $status_approved = $wpdb->get_var('SELECT count(*) FROM `'.$table_name.'` where `website_status` = \'a\'');
        $status_unapproved = $wpdb->get_var('SELECT count(*) FROM `'.$table_name.'` where `website_status` = \'u\'');

        $result["approved"] = (int)$status_approved;
        $result["unapproved"] = (int)$status_unapproved;

        $result["total"] = $result["approved"] + $result["unapproved"];
        return $result;
    }

    /**
     * @param $host
     * @return mixed, return NULL on failure
     */
    public function getIdByHost($host) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        return $wpdb->get_var( $wpdb->prepare("select website_id from ".$table_name." where website_domein = %s", $host) );
    }

    /**
     * @param $id, website_id
     * @param $io, inlink (i) or outlink (o)
     * @return bool, true on success; false on failure
     */
    public function addLink($id, $io) {
        switch($io) {
            case "i":
                $ioResult = "i";
                break;
            case "o":
                $ioResult = "o";
                break;
        }
        if(!isset($ioResult)) {
            return false;
        }
        global $wpdb;
        $content = array(
            "links_website_id" => $id,
            "links_ip" => $_SERVER["REMOTE_ADDR"],
            "links_date" => time(),
            "links_type" => $ioResult
        );
        $table_name = $wpdb->prefix . "ublinks";
        $wpdb->insert($table_name, $content, array("%d", "%s", "%d", "%s"));
        return true;
    }

    /**
     * update ubsites, add total hits
     * @param $id
     * @param $io
     * @return bool, true on success
     */
    public function addTotalLink($id, $io) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        if($io == "i") {
            $sql = $wpdb->prepare("update ".$table_name." set website_total_inlink = website_total_inlink + 1 where website_id = %d", $id);
        } elseif($io == "o") {
            $sql = $wpdb->prepare("update ".$table_name." set website_total_outlink = website_total_outlink + 1 where website_id = %d", $id);
        }
        if(isset($sql)) {
            $wpdb->query( $sql );
        }
        return true;
    }

    /**
     * update ubsites set the last2days columns
     * @param $id, site_id
     * @param $inlinks, amount of inlinks
     * @param $outlinks, amount of outlinks
     * @return bool, true on success
     */
    public function addTotaltoSites($id, $inlinks, $outlinks) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ubsites";
        $data = array(
            "website_last2days_inlink" => $inlinks,
            "website_last2days_outlink" => $outlinks
        );
        $where = array("website_id" => $id);
        $wpdb->update($table_name, $data, $where, array("%d", "%d"), array("%d"));
        return true;
    }

    /**
     * @param $id, website_id
     * @param $io, Inlink or Outlink
     * @return bool, true on success
     */
    public function checkLink($id, $io) {
        switch($io) {
            case "i":
                $ioResult = "i";
                break;
            case "o":
                $ioResult = "o";
                break;
        }
        if(!isset($ioResult)) {
            return false;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "ublinks";
        $content = array(
            $id,
            $_SERVER["REMOTE_ADDR"],
            time(),
            $ioResult
        );
        $sql = $wpdb->prepare("select count(*) from ".$table_name." where links_website_id = %s and links_ip = %s and links_date < %d and links_type = %s", $content);
        $result = $wpdb->get_var( $sql );
        return $result;
    }

    /**
     * @param $deleteLinksOlderThan, the period in seconds, example: delete hits older than 48 hours = 60 (seconds) * 60 (minutes) * 48 (hours)
     */
    public function removeOldLinks($deleteLinksOlderThan) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ublinks";
        $time = time() - (int)$deleteLinksOlderThan;
        $wpdb->query($wpdb->prepare("delete from ".$table_name." where links_date < %d", $time));
    }

    /**
     * Count the in- and outlinks
     * @return mixed
     */
    public function coundHits() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ublinks";
        return $wpdb->get_results( "select links_website_id as 'id', count(case links_type when 'o' then 1 end) as 'outlink', count(case links_type when 'i' then 1 end) as 'inlink' from ".$table_name." group by links_website_id", ARRAY_A );
    }
}