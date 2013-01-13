<?php
function getErrorField($field) {
    //var_dump("getErrorField");
    $errors = UbMapper::getInstance(UbMapper::Error)->getError();
    if(isset($errors[$field]))
    {
        return "class=\"red\"";
    }
}
function errors() {
    if( count(UbMapper::getInstance(UbMapper::Error)->getError()) != 0 ) {
        return '<div class="error fade">
            <p>
                <b>Errors:</b>
                <ul>'.UbMapper::getInstance(UbMapper::Error)->getErrorAsString().'</ul>
            </p>
        </div>';
    }
    return null;
}
?>