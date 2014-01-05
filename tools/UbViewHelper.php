<?php
/**
 * Created by PhpStorm.
 * User: jensgheerardyn
 * Date: 30/12/13
 * Time: 20:28
 */
class UbViewHelper{
    public static function View($view, $viewModel){
        ob_start();
        if($view == null){
            $callers=debug_backtrace();
            $caller = $callers[1];
            $className = $caller["class"];
            $methodName = $caller["function"];
            $fileName = $className.DIRECTORY_SEPARATOR.$methodName.".php";
        } else {
            $fileName = $view;
        }
        ub_require("gui".DIRECTORY_SEPARATOR.$fileName, $viewModel);
        $result = ob_get_clean();
        return $result;
    }
}

function ub_require($file, $Model = null){
    require(UB_PLUGIN_DIR.$file);
}

function ub_require_once($file, $Model = null){
    require_once(UB_PLUGIN_DIR.$file);
}