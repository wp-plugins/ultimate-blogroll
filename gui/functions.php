<?php
global $gui;
function getErrorField($field) {
    global $gui;
    if(isset($gui["error"]["fields"][$field]))
    {
        return "class=\"red\"";
    }
}

function getErrorMessages($messages = null) {
    global $gui;
    if(empty($messages)) {
        $messages = $gui["error"]["messages"];
    }
    $result = "";
    foreach($messages as $error)
    {
        $result .= $error;
    }
    return $result;
}
?>