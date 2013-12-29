<?php
/**
 * Created by PhpStorm.
 * User: jensgheerardyn
 * Date: 29/12/13
 * Time: 13:43
 */

class UbSortingHelper {
    /**
     * @param $order
     * @return string
     */
    public static function GetOrder($order) {
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
    public static function GetOrderBy($orderby) {
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
    public static function GetLimit($limit) {
        return (int)$limit;
    }
} 