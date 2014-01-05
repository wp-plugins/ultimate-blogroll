<style type="text/css">
    .widget_text {
        width: 220px;
    }
</style>
<p><label><?php echo __("Widget title", "ultimate-blogroll"); ?>:</label><br/>
    <input type="text" class="widget_text" name="widget_title" value="<?php echo $Model->widget_title; ?>"/>
</p>
<p><label><?php echo __("Limit of linkpartners", "ultimate-blogroll"); ?>:</label><br/>
    <input type="text" class="widget_text" name="limit_linkpartners" value="<?php echo $Model->limit_linkpartners; ?>"/>
</p>
<p><label><?php echo __("Order by", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "id" => __("ID", "ultimate-blogroll"),
        "name" => __("Name", "ultimate-blogroll"),
        "inlinks" => __("Inlinks", "ultimate-blogroll"),
        "outlinks" => __("Outlinks", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("order_by", $values, $Model->order_by, "widget_text");
    ?>
</p>
<p><label><?php echo __("Ascending/Descending", "ultimate-blogroll"); ?>:</label><br />
    <?php
    $values = array(
        "asc" => __("Ascending", "ultimate-blogroll"),
        "desc" => __("Descending", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("ascending", $values, $Model->ascending, "widget_text");
    ?>
</p>
<p><label><?php echo __("Target", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "_blank" => "_blank",
        "_top" => "_top",
        "_none" => "_none"
    );
    echo UbHtmlHelper::DropdownList("target", $values, $Model->target, "widget_text");
    ?>
</p>
<p><label><?php echo __("Nofollow", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "yes" => __("Yes", "ultimate-blogroll"),
        "no" => __("No", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("nofollow", $values, $Model->nofollow, "widget_text");
    ?>
</p>
<p><label><?php echo __("Support developer", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "yes" => __("Yes", "ultimate-blogroll"),
        "no" => __("No", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("support", $values, $Model->support, "widget_text");
    ?>
</p>
<p><label><?php echo __("Website logo", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "yes" => __("Yes", "ultimate-blogroll"),
        "no" => __("No", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("logo", $values, $Model->logo, "widget_text");
    ?>
</p>
<p><label><?php echo __("Logo width", "ultimate-blogroll"); ?>:</label><br/>
    <input type="text" class="widget_text" name="logo_width" value="<?php echo $Model->logo_width; ?>"/>
</p>
<p><label><?php echo __("Logo height", "ultimate-blogroll"); ?>:</label><br/>
    <input type="text" class="widget_text" name="logo_height" value="<?php echo $Model->logo_height; ?>"/>
</p>
<p><label><?php echo __("Logo usage", "ultimate-blogroll"); ?>:</label><br/>
    <?php
    $values = array(
        "both" => __("Text and image", "ultimate-blogroll"),
        "text" => __("Text", "ultimate-blogroll"),
        "image" => __("Image", "ultimate-blogroll")
    );
    echo UbHtmlHelper::DropdownList("logo_usage", $values, $Model->logo_usage, "widget_text");
    ?>
</p>