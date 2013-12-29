<?php
/**
 * Created by PhpStorm.
 * User: jensgheerardyn
 * Date: 28/12/13
 * Time: 12:12
 */
class UbHtmlHelper{
    public static function DropdownList(){

    }

    /**
     * @param $target
     * @return string
     */
    public static function GetTarget($target) {
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
    public static function GetFollow($follow){
        if(!is_home() && $follow == "yes") {
            return " rel=\"nofollow\"";
        }
    }
}