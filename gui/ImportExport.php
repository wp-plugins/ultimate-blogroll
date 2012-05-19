<?php require_once("Header.php"); ?>
<div class="wrap">
    <div class="icon32" id="icon-themes"></div>
    <h2><?php echo __("Import/Export", "ultimate-blogroll"); ?></h2>
    <?php
        if(isset($gui["succes"]["import"]) && $gui["succes"]["import"] == true) {
            echo '<div class="updated fade"><p><b>Successfully imported: </b>'.__("The links were successfully imported into Ultimate Blogroll", "ultimate-blogroll").'</p></div>';
        }
        if(isset($gui["succes"]["export"]) && $gui["succes"]["export"] == true) {
            echo '<div class="updated fade"><p><b>Successfully exported: </b>'.__("The links were successfully exported into Wordpress", "ultimate-blogroll").'</p></div>';
        }
    ?>
    <div class="metabox-holder" id="poststuff">
        <div class="postbox">
            <div class="handlediv" title="Click to open/close">
                <br />
            </div>
            <h3 class="hndle"><span><?php echo __("Import/Export", "ultimate-blogroll") ?></span></h3>
            <div class="inside" style="display: block;">
                <div style="float: left; width: 49%; border-right: 1px solid #DFDFDF;">
                    <form method="POST" action="">
                        <h4 style="margin-top: 0px;"><?php echo __("Import", "ultimate-blogroll") ?>:</h4>
                        <?php echo __("We are going to import all the links from Wordpress.", "ultimate-blogroll") ?>
                        <input type="hidden" name="ub_type" value="import" />
                        <br /><br /><input type="submit" class="button-primary form_button" value="<?php echo __("Import links from Wordpress", "ultimate-blogroll") ?>" name="ub_submit">
                    </form>
                </div>
                <div style="float: left; padding-left: 10px;">
                    <form method="POST" action="">
                        <h4 style="margin-top: 0px;"><?php echo __("Export", "ultimate-blogroll") ?>:</h4>
                        <?php echo __("Export all the links from Ultimate Blogroll to Wordpress links.", "ultimate-blogroll") ?>
                        <input type="hidden" name="ub_type" value="export" />
                        <br /><br /><input type="submit" class="button-primary form_button" value="<?php echo __("Export links to Wordpress", "ultimate-blogroll") ?>" name="ub_submit">
                    </form>
                </div>
                <div style="clear: both"></div>
            </div>
        </div>
    </div>
</div>