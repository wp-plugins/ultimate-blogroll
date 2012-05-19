<?php if(is_admin()) { ?><link rel="stylesheet" href="<?php echo UB_ASSETS_URL; ?>style.css" type="text/css"><?php } ?>
<?php
function getErrorField($field) {
    //var_dump("getErrorField");
    $errors = Mapper::getInstance(Mapper::Error)->getError();
    if(isset($errors[$field]))
    {
        return "class=\"red\"";
    }
}
function errors() {
    if( count(Mapper::getInstance(Mapper::Error)->getError()) != 0 ) {
        return '<div class="error fade">
            <p>
                <b>Errors:</b>
                <ul>'.Mapper::getInstance(Mapper::Error)->getErrorAsString().'</ul>
            </p>
        </div>';
    }
    return null;
}
?>