<?php
function getErrorField($field) {
    //var_dump("getErrorField");
    $errors = UltimateBlogroll\Mapper::getInstance(UltimateBlogroll\Mapper::Error)->getError();
    if(isset($errors[$field]))
    {
        return "class=\"red\"";
    }
}
function errors() {
    if( count(UltimateBlogroll\Mapper::getInstance(UltimateBlogroll\Mapper::Error)->getError()) != 0 ) {
        return '<div class="error fade">
            <p>
                <b>Errors:</b>
                <ul>'.UltimateBlogroll\Mapper::getInstance(UltimateBlogroll\Mapper::Error)->getErrorAsString().'</ul>
            </p>
        </div>';
    }
    return null;
}
?>