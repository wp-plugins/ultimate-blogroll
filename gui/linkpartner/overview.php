<?php
global $gui, $path;
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/header.php");
?>
<style type="text/css">
.column-link-website {
    width: 20%;
}
.column-link-date {
    width: 20%;
}
.inactive {
    background-color: #EEEEEE;
}
.reciprocal_error {
    width: 22px;
    height: 22px;
    background-image: url("../wp-content/plugins/ultimate-blogroll/images/exclamation.png");
}
</style>
<br class="clear">
<form id="form_overview" method="GET" action="">
    <input type="hidden" name="page" value="<?= @$_GET["page"];?>" />
    <ul class="subsubsub">
        <li><a <?=(((@$_GET["status"] == "all" && !isset($_GET["search_button"])) || (!isset($_GET["status"]) && !isset($_GET["search_button"]))) ? 'class="current"' : ""); ?> href="<?=$gui["status_count"]["link_all"]; ?>"><?= __("All", "ultimate-blogroll") ?> <span class="count">(<?= $gui["status_count"]["total"]; ?>)</span></a> |</li>
        <li><a <?=((@$_GET["status"] == "approved") ? 'class="current"' : ""); ?> href="<?=$gui["status_count"]["link_approved"]; ?>"><?= __("Approved", "ultimate-blogroll") ?> <span class="count">(<?= $gui["status_count"]["approved"]; ?>)</span></a> |</li>
        <li><a <?=((@$_GET["status"] == "unapproved") ? 'class="current"' : ""); ?> href="<?=$gui["status_count"]["link_unapproved"]; ?>"><?= __("Unapproved", "ultimate-blogroll") ?> <span class="count">(<?= $gui["status_count"]["unapproved"];?>)</span></a></li>
    </ul>
    <p class="search-box">
	<label for="post-search-input" class="screen-reader-text">Search links:</label>
	<input type="text" value="<?= htmlentities(@$_GET["s"]); ?>" name="s" id="post-search-input">
	<input type="submit" class="button" value="<?= __("Search links", "ultimate-blogroll") ?>" name="search_button">
    </p>
    <?php if(isset($_GET["search_button"]) && empty($gui["linkpartners"]))
    {
        echo '<div class="clear"></div>';
        echo "<p>".__("No results found.", "ultimate-blogroll")."</p>";
    } else {
    ?>
    <div class="tablenav">
        <div class="alignleft">
            <select name="overview_actions">
                <option value="-1" selected="selected"><?= __("Bulk actions", "ultimate-blogroll") ?></option>
                <option value="approve"><?= __("Approve", "ultimate-blogroll") ?></option>
                <option value="unapprove"><?= __("Unapprove", "ultimate-blogroll") ?></option>
                <option value="delete"><?= __("Delete", "ultimate-blogroll") ?></option>
            </select>
            <input type="submit" class="button-secondary" name="bulk_action" value="<?= __("Apply", "ultimate-blogroll") ?>">
            <input type="submit" class="button-secondary" name="check_reciprocal_url" value="<?= __("Check Reciprocal URL", "ultimate-blogroll")?>">
        </div><!-- /alignleft -->

        <div class="alignright">
            
        </div><!-- /alignright -->
    </div>
    <br class="clear">
    
    <table cellspacing="0" class="widefat post fixed">
        <thead>
            <tr>
                <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
                <th style="" class="manage-column column-link-website" scope="col"><?= __("Website name", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column column-link-date" scope="col"><?= __("URL", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Last 48h in", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Last 48h out", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Total in", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Total out", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Ratio", "ultimate-blogroll") ?></th>
                <th style="width: 2%" class="manage-column" scope="col"></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
                <th style="" class="manage-column column-link-website" scope="col"><?= __("Website name", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column column-link-date" scope="col"><?= __("URL", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Last 48h in", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Last 48h out", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Total in", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Total out", "ultimate-blogroll") ?></th>
                <th style="" class="manage-column" scope="col"><?= __("Ratio", "ultimate-blogroll") ?></th>
                <th style="width: 2%" class="manage-column" scope="col"></th>
            </tr>
        </tfoot>
        <tbody>
        <?php
        if(isset($gui["linkpartners"])) {
        foreach($gui["linkpartners"] as $linkpartner) { ?>
            <tr valign="top" class="<?= ($linkpartner["website_status"] == "u") ? "inactive" : "" ?>">
                <th class="check-column" scope="row">
                    <input type="checkbox" value="<?= $linkpartner["website_id"]; ?>" name="linkpartner[]">
                </th>
                <td class="post-title column-title">
                    <strong>
                        <a title="<?= __("Edit", "ultimate-blogroll") . " " . $linkpartner["website_name"]; ?>" href="<?= $gui["base_url"].http_build_query(array("page" => @$_GET["page"], "action" => "edit", "id" => $linkpartner["website_id"] )); ?>#edit" class=""><?= $linkpartner["website_name"]; ?></a>
                    </strong>
                    <div class="row-actions">
                        <span class="edit"><a href="<?= $gui["base_url"].http_build_query(array("page" => @$_GET["page"], "action" => "edit", "id" => $linkpartner["website_id"] )); ?>#edit" title="<?= __("Edit this linkpartner", "ultimate-blogroll")?>"><?= __("Edit", "ultimate-blogroll") ?></a> | </span>
                        <?php
                        if($linkpartner["website_status"] == "u") {
                            echo '<span><a href="'.$gui["base_url"].http_build_query(array("page" => @$_GET["page"], "overview_actions" => "approve", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])).'" title="'.__("Approve this linkpartner", "ultimate-blogroll").'">'.__("Approve", "ultimate-blogroll").'</a> | </span>';
                        } else {
                            echo '<span><a href="'.$gui["base_url"].http_build_query(array("page" => @$_GET["page"], "overview_actions" => "unapprove", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])).'" title="'.__("Unapprove this linkpartner", "ultimate-blogroll").'">'.__("Unapprove", "ultimate-blogroll").'</a> | </span>';
                        }
                        ?>
                        <span class="delete"><a href="<?= $gui["base_url"].http_build_query(array("page" => @$_GET["page"], "overview_actions" => "delete", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])); ?>" href="link.php?action=delete&amp;link_id=1&amp;_wpnonce=57e7e3410b" title="<?= __("Delete this linkpartner", "ultimate-blogroll")?>"><?= __("Delete", "ultimate-blogroll") ?></a></span>
                        
                    </div>
                </td>

                <td class=""><?= '<a href="'.$linkpartner["website_url"].'" target="_blank">'.$linkpartner["website_url"].'</a>'; ?></td>
                <td class=""><?= $linkpartner["website_last2days_inlink"]; ?></td>
                <td class=""><?= $linkpartner["website_last2days_outlink"]; ?></td>
                <td class=""><?= $linkpartner["website_total_inlink"]; ?></td>
                <td class=""><?= $linkpartner["website_total_outlink"]; ?></td>
                <?php
                    if($linkpartner["website_total_inlink"] > 0 && $linkpartner["website_total_outlink"] > 0) {
                        $ratio = ((int)$linkpartner["website_total_inlink"]/(int)$linkpartner["website_total_outlink"])*100;
                    } else {
                        $ratio = 0;
                    }
                ?>
                <td style="<?= ($ratio < 100) ? "color: #CC3300;" : "color: #008000;" ?>"><?= round($ratio, 2)."%"; ?></td>
                <td>
                    <?php if($linkpartner["website_has_backlink"] == false && !empty($linkpartner["website_backlink"])) { echo "<div class=\"reciprocal_error\"></div>"; } ?>
                </td>
            </tr>
        <?php

        }//foreach lus linkpartners

        }//isset linkpartners
        ?>
    </table>
    <?php
    //maybe a feature, at the moment dropped because of a conflict
    //if you chose approve at the top but selected delete at the bottom and forgotten you selected delete
    //then what has to happen?
    /* ?>
    <div class="tablenav">
        <div class="alignleft actions">
            <select name="overview_actions2">
                <option value="-1" selected="selected">Bulk actions</option>
                <option value="approve">Approve</option>
                <option value="unapprove">Unapprove</option>
                <option value="delete">Delete</option>
            </select>
            <input type="submit" class="button-secondary" name="bulk_action" value="Apply">
            <input type="submit" class="button-secondary" name="check_reciprocal_url" value="Check Reciprocal URL">
            <br class="clear">
        </div>
        <br class="clear">
    </div>
    <?php */ ?>
    <?php }//search results ?>
</form>
<?php
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/footer.php");
?>