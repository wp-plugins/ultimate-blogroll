<?php
function getErrorField($field) {
    //var_dump("getErrorField");
    $errors = UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getError();
    if(isset($errors[$field]))
    {
        return "class=\"red\"";
    }
}
function errors() {
    if( count(UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getError()) != 0 ) {
        return '<div class="error fade">
            <p>
                <b>'.__('Errors').':</b>
                <ul>'.UbPersistenceRouter::getInstance(UbPersistenceRouter::Error)->getErrorAsString().'</ul>
            </p>
        </div>';
    }
    return null;
}
?>