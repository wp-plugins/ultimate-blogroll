<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/11/11
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */
 
class Main {
    public function __construct() {}
    /**
     * @param $limit
     * @return int
     */
    protected function GetLimit($limit) {
        return (int)$limit;
    }

    /**
     * @param $order
     * @return string
     */
    protected function GetOrder($order) {
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
    protected function GetOrderBy($orderby) {
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
