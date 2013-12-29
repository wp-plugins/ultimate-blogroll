<?php require_once("shared".DIRECTORY_SEPARATOR."Header.php"); ?>
<script src="<?php echo UB_ASSETS_URL; ?>js/jquery.placeholder.min.js" type="text/javascript"></script>
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('input').placeholder();
});
</script>
<div class="wrap">
    <div class="icon32" id="icon-themes"></div>
    <h2><?php if(isset($gui["edit"])) { echo __("Edit linkpartner", "ultimate-blogroll"); } else { echo __("Add linkpartner", "ultimate-blogroll"); } ?></h2>
    <?php echo errors();
    if( isset($gui["success"]["insert"]) ) {
        echo '<div class="updated fade">
        <p>'.__("Linkpartner was successfully added.", "ultimate-blogroll").'</p>
        </div>';
    }
    if( isset($gui["success"]["update"]) ) {
        echo '<div class="updated fade">
        <p>'.__("Linkpartner was successfully updated", "ultimate-blogroll").'! <a href="'.admin_url("admin.php?page=ultimate-blogroll-overview").'">Click here</a> to go back to the overview</p>
        </div>';
    }
    ?>
    <div class="metabox-holder" id="poststuff">
        <form id="form1" method="POST" action="">
            <div class="postbox">
                <div class="handlediv" title="<?php echo __("Click to open/close"); ?>">
                    <br />
                </div>
                <h3 class="hndle"><span><?php echo ((isset($gui["edit"])) ? __("Edit linkpartner", "ultimate-blogroll").": ".@$gui["value"]["website_title"]." (<a href=\"".@$gui["value"]["website_url"]."\" target=\"_blank\">".@$gui["value"]["website_url"]."</a>)" : __("Add linkpartner", "ultimate-blogroll")); ?></span></h3>
                <div class="inside" style="display: block;">
                    <table>
                        <tr <?php echo getErrorField("your_name"); ?>>
                            <td class="column1"><?php echo __("Website owner's name", "ultimate-blogroll"); ?>*:</td>
                            <td class="column2"><input type="text" name="your_name" class="form_text" value="<?php echo @$gui["value"]["your_name"]; ?>" /></td>
                            <td><?php echo __("Website owner's name, so we know who to contact", "ultimate-blogroll"); ?></td>
                        </tr>
                        <tr <?php echo getErrorField("your_email"); ?>>
                            <td class="column1"><?php echo __("Website owner's email", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="your_email" class="form_text" value="<?php echo @$gui["value"]["your_email"]; ?>" /></td>
                            <td><?php echo __("Website owner's email, so we know who to contact", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr>
                            <td><br /></td>
                        </tr>
                        <tr <?php echo getErrorField("website_url"); ?>>
                            <td class="column1"><?php echo __("Website url", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="website_url" class="form_text" value="<?php echo @$gui["value"]["website_url"]; ?>" placeholder="<?php echo __("http://example.com", "ultimate-blogroll") ?>" /></td>
                            <td><?php echo htmlentities('<a href="');?><b><?php echo __("website url", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                        </tr>
                        <tr <?php echo getErrorField("website_title"); ?>>
                            <td class="column1"><?php echo __("Website title", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="website_title" class="form_text" value="<?php echo @$gui["value"]["website_title"]; ?>" placeholder="<?php echo __("Put your title here", "ultimate-blogroll");?>" /></td>
                            <td><?php echo htmlentities('<a>'); ?><b><?php echo __("website title", "ultimate-blogroll") ?></b><?php echo htmlentities('</a>'); ?></td>
                        </tr>
                        <tr <?php echo getErrorField("website_description"); ?>>
                            <td class="column1"><?php echo __("Website description", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="website_description" class="form_text" value="<?php echo @$gui["value"]["website_description"]; ?>" placeholder="<?php echo __("Put your description here", "ultimate-blogroll");?>" /></td>
                            <td><?php echo htmlentities('<a title="'); ?><b><?php echo __("website description", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                        </tr>
                        <tr>
                            <td><br /></td>
                        </tr>
                        <tr <?php echo getErrorField("website_reciprocal"); ?>>
                            <td class="column1"><?php echo __("Website reciprocal", "ultimate-blogroll") ?>:</td>
                            <td class="column2"><input type="text" name="website_reciprocal" class="form_text" value="<?php echo @$gui["value"]["website_reciprocal"]; ?>" /></td>
                            <td><?php echo __("Where can we find our link back? (Leave blank if not required)", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("website_image"); ?>>
                            <td class="column1"><?php echo __("Website image", "ultimate-blogroll") ?>:</td>
                            <td class="column2"><input type="text" name="website_image" class="form_text" value="<?php echo @$gui["value"]["website_image"]; ?>" /></td>
                            <td><?php echo __("Add an image/logo", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr>
                            <td class="column1"></td>
                            <td class="column2"></td>
                            <td></td>
                        </tr>
                    </table>
                </div><!-- .inside -->
            </div><!-- .postbox -->
            <p class="submit">
                <input type="submit" class="button button-primary button-large" name="add_linkpartner" value="<?php echo ((@$gui["edit"] === true) ? __("Update linkpartner", "ultimate-blogroll") : __("Submit linkpartner", "ultimate-blogroll")) ?>" />
            </p>
        </form>
    </div><!-- #poststuff -->
</div><!-- .wrap -->