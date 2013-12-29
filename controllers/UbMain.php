<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 23/05/12
 * Time: 11:47
 * To change this template use File | Settings | File Templates.
 */
class UbMain
{
    public function __construct() {}
    
    /**
     * Send a notification mail that a new linkpartner has been added
     * Keep parameters $linkpartner and $id, they are passed to Mail.php
     * @param $linkpartner
     * @param $id
     */

    public function sendMail($linkpartner, $id) {

        ob_start();
        require_once(UB_PLUGIN_DIR."gui".DIRECTORY_SEPARATOR."Mail.php");
        $body = ob_get_clean();
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset='.get_option('blog_charset') . "\r\n";
        $headers .= 'From: WordPress Ultimate Blogroll <'.get_bloginfo('admin_email').'> '."\r\n";
        $subject = __("New link submitted at", "ultimate-blogroll")." ".home_url().''."\r\n";
        if(function_exists('wp_mail')) {
            add_filter('wp_mail_charset',create_function('', 'return \''.get_option('blog_charset').'\'; '));
            wp_mail(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("blogroll_contact"), $subject, $body, $headers);
        } else {
            mail(UbPersistenceRouter::getInstance(UbPersistenceRouter::Settings)->getConfig("blogroll_contact"), $subject, $body, $headers);
        }
    }
    /**
    * @param $order
    * @return string
    */
    protected  function GetOrder($order) {
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

    /**
    * @param $orderby
    * @return string
    */
    protected  function GetOrderBy($orderby) {
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

    /**
     * @param $limit
     * @return int
     */
    protected  function GetLimit($limit) {
        return (int)$limit;
    }

    /**
     * @param $target
     * @return string
     */
    protected function GetTarget($target) {
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

    /**
     * @param $follow
     * @return string
     */
    protected function GetFollow($follow){
        if(!is_home() && $follow == "yes") {
            return " rel=\"nofollow\"";
        }
    }
}
