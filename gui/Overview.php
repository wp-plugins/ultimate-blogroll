<?php require_once("shared".DIRECTORY_SEPARATOR."Header.php"); ?>
<div class="wrap">
    <div class="icon32" id="icon-themes"></div>
    <h2><?php echo __("Overview", "ultimate-blogroll"); ?></h2>
    <?php
    if( isset($gui["success"]["check"]) ) {
        echo '<div class="updated fade">
        <p>'.__("Reciprocal link was successfully planned.<br />Due to the possible length of the task we planned it to be executed in the background.", "ultimate-blogroll").'</p>
        </div>';
    }
    if( isset($_GET["additional"]) && $_GET["additional"] == "mail" ) {
        echo '<div class="updated fade">
        <p>'.__("You came here by mail, please confirm your actions on the linkpartners as we highlighted them for you in blue.", "ultimate-blogroll").'</p>
        </div>';
    }
    if( isset($_GET["success"]["action"]) ) {
        echo '<div class="updated fade">
        <p>'.__("Successfully executed the action.", "ultimate-blogroll").'</p>
        </div>';
    }
    ?>
    <form id="form_overview" method="GET" action="">
        <input type="hidden" name="page" value="<?php echo  @$_GET["page"];?>" />
        <ul class="subsubsub">
            <li><a <?php echo ((@$_GET["status"] == "all" or empty($_GET["status"])) ? 'class="current"' : ""); ?> href="<?php echo UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "all") ); ?>"><?php echo  __("All", "ultimate-blogroll") ?> <span class="count">(<?php echo $gui["status_count"]["total"]; ?>)</span></a> |</li>
            <li><a <?php echo ((@$_GET["status"] == "approved") ? 'class="current"' : ""); ?> href="<?php echo UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "approved") ); ?>"><?php echo  __("Approved", "ultimate-blogroll") ?> <span class="count">(<?php echo $gui["status_count"]["approved"]; ?>)</span></a> |</li>
            <li><a <?php echo ((@$_GET["status"] == "unapproved") ? 'class="current"' : ""); ?> href="<?php echo UB_PUBLIC_URL.http_build_query( array("page" => @$_GET["page"], "status" => "unapproved") ); ?>"><?php echo  __("Unapproved", "ultimate-blogroll") ?> <span class="count">(<?php echo $gui["status_count"]["unapproved"]; ?>)</span></a></li>
        </ul>
        <p class="search-box">
            <label for="post-search-input" class="screen-reader-text">Search links:</label>
            <input type="text" value="<?php echo htmlentities(@$_GET["s"]); ?>" name="s" id="post-search-input">
            <input type="submit" class="button" value="<?php echo  __("Search links", "ultimate-blogroll") ?>" name="search_button">
        </p>
        <?php if(isset($_GET["search_button"]) && empty($gui["linkpartners"]))
        {
            echo '<div class="clear"></div>';
            echo "<p>".__("No results found.", "ultimate-blogroll")."</p>";
        } else { ?>
            <div class="tablenav">
                <div class="alignleft">
                    <select name="overview_actions">
                        <option value="-1" selected="selected"><?php echo  __("Bulk actions", "ultimate-blogroll") ?></option>
                        <option value="approve"><?php echo  __("Approve", "ultimate-blogroll") ?></option>
                        <option value="unapprove"><?php echo  __("Unapprove", "ultimate-blogroll") ?></option>
                        <option value="delete"><?php echo  __("Delete", "ultimate-blogroll") ?></option>
                    </select>
                    <input type="submit" class="button-secondary" name="bulk_action" value="<?php echo  __("Apply", "ultimate-blogroll") ?>">
                    <input type="submit" class="button-secondary" name="check_reciprocal_url" value="<?php echo  __("Check Reciprocal URL", "ultimate-blogroll")?>">
                </div><!-- /alignleft -->

                <div class="alignright"></div><!-- /alignright -->
            </div>
            <br class="clear">
            <table cellspacing="0" class="widefat post fixed" id="linkpartners">
                <thead>
                    <tr>
                        <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
                        <th style="" class="manage-column ub-column-fat " scope="col"><?php echo  __("Website name", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column ub-column-fat " scope="col"><?php echo  __("URL", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Last 48h in", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Last 48h out", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Total in", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Total out", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Ratio", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Link back", "ultimate-blogroll") ?></th>
                        <th style="width: 2%" class="manage-column" scope="col"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
                        <th style="" class="manage-column column-link-website" scope="col"><?php echo  __("Website name", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column column-link-date" scope="col"><?php echo  __("URL", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Last 48h in", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Last 48h out", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Total in", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Total out", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Ratio", "ultimate-blogroll") ?></th>
                        <th style="" class="manage-column" scope="col"><?php echo  __("Link back", "ultimate-blogroll") ?></th>
                        <th style="width: 2%" class="manage-column" scope="col"></th>
                    </tr>
                </tfoot>
                <tbody>
                <?php
                if(isset($gui["linkpartners"])) {
                    foreach($gui["linkpartners"] as $linkpartner) { ?>
                        <tr valign="top" class="<?php echo  ($linkpartner["website_status"] == "u") ? "inactive " : " "; echo (isset($_GET["additional"]) && $_GET["additional"] == "mail" && isset($_GET["linkpartner"]) && in_array($linkpartner["website_id"], $_GET["linkpartner"])) ? "selected" : ""; ?>">
                            <th class="check-column" scope="row">
                                <input type="checkbox" value="<?php echo  $linkpartner["website_id"]; ?>" name="linkpartner[]">
                            </th>
                            <td class="post-title column-title">
                                <strong>
                                    <a title="<?php echo __("Edit", "ultimate-blogroll") . " " . $linkpartner["website_name"]; ?>" href="<?php echo UB_PUBLIC_URL.http_build_query(array("page" => @$_GET["page"], "action" => "edit", "id" => $linkpartner["website_id"] )); ?>#edit" class=""><?php echo $linkpartner["website_name"]; ?></a>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit"><a href="<?php echo UB_PUBLIC_URL.http_build_query(array("page" => @$_GET["page"], "action" => "edit", "id" => $linkpartner["website_id"] )); ?>#edit" title="<?php echo  __("Edit this linkpartner", "ultimate-blogroll")?>"><?php echo  __("Edit", "ultimate-blogroll") ?></a> | </span>
                                    <?php
                                    if($linkpartner["website_status"] == "u") {
                                        echo '<span><a href="'.UB_PUBLIC_URL.http_build_query(array("page" => @$_GET["page"], "overview_actions" => "approve", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])).'" title="'.__("Approve this linkpartner", "ultimate-blogroll").'">'.__("Approve", "ultimate-blogroll").'</a> | </span>';
                                    } else {
                                        echo '<span><a href="'.UB_PUBLIC_URL.http_build_query(array("page" => @$_GET["page"], "overview_actions" => "unapprove", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])).'" title="'.__("Unapprove this linkpartner", "ultimate-blogroll").'">'.__("Unapprove", "ultimate-blogroll").'</a> | </span>';
                                    }
                                    ?>
                                    <span class="delete"><a href="<?php echo UB_PUBLIC_URL.http_build_query(array("page" => @$_GET["page"], "overview_actions" => "delete", "bulk_action" => "Apply", "linkpartner[]" => $linkpartner["website_id"])); ?>" title="<?php echo  __("Delete this linkpartner", "ultimate-blogroll")?>"><?php echo  __("Delete", "ultimate-blogroll") ?></a></span>

                                </div>
                            </td>

                            <td class=""><?php echo '<a href="'.$linkpartner["website_url"].'" target="_blank">'.$linkpartner["website_url"].'</a>'; ?></td>
                            <td class=""><?php echo $linkpartner["website_last2days_inlink"]; ?></td>
                            <td class=""><?php echo $linkpartner["website_last2days_outlink"]; ?></td>
                            <td class=""><?php echo $linkpartner["website_total_inlink"]; ?></td>
                            <td class=""><?php echo $linkpartner["website_total_outlink"]; ?></td>
                            <?php
                                if($linkpartner["website_total_inlink"] > 0 && $linkpartner["website_total_outlink"] > 0) {
                                    $ratio = ((int)$linkpartner["website_total_inlink"]/(int)$linkpartner["website_total_outlink"])*100;
                                } else {
                                    $ratio = 0;
                                }
                            ?>
                            <td style="<?php echo  ($ratio < 100) ? "color: #CC3300;" : "color: #008000;" ?>"><?php echo  round($ratio, 2)."%"; ?></td>
                            <td>
                                <?php if($linkpartner["website_has_backlink"] == false) echo __("No", "ultimate-blogroll"); else echo __("Yes", "ultimate-blogroll") ?>
                            </td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        <?php } ?>
        <input type="hidden" name="status" value="<?php echo @$_GET["status"]; ?>" />
    </form>
</div>