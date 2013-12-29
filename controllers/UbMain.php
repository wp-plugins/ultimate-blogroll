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

}
