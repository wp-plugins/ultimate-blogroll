<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 12/04/12
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */
require_once("Header.php");
?>
<script src="../wp-content/plugins/ultimate-blogroll/assets/checkbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="../wp-content/plugins/ultimate-blogroll/assets/checkbox.css" type="text/css" media="screen" />
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('#create').iphoneStyle({
        checkedLabel: 'Create',
        uncheckedLabel: 'Select',
        onChange: function(elem, value) {
            //$('span#status').html(value.toString());
            //alert(value.toString());
            showStep1(value);
        }
    });

    function showStep1(select) {
        //alert(edit);
        if(select == true) {
            $('#createPage').show();
            $('#selectPage').hide();
        } else {
            $('#createPage').hide();
            $('#selectPage').show();
        }
    }
    $(':checkbox').iphoneStyle();
    showStep1($('#create').is(':checked'));
});
</script>
<div class="wrap">
    <div class="icon32" id="icon-themes"></div>
    <h2><?php echo __("Wizard", "ultimate-blogroll"); ?></h2>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo '<div class="updated fade"><p><b>'.__("Success").':</b> Ultimate Blogroll '.__("was successfully installed.", "ultimate-blogroll").' To start click <a href="'.admin_url("admin.php?page=ultimate-blogroll-overview").'">here</a>.</p></div>';
    } ?>
    <?php echo errors(); ?>
    <form id="wizard" method="POST" action="#">
        <div class="metabox-holder" id="poststuff">
            <div class="postbox" id="wizard-1">
                <div class="handlediv" title="<?php echo __("Click to open/close", "ultimate-blogroll") ?>">
                    <br />
                </div><!--.handlediv-->
                <h3 class="hndle"><span><div style="float: left;">Step 1:&nbsp;</div><div style="float: left; margin-top: -6px;"><input type="checkbox" id="create" name="frmCreate" <?php echo $frmCreate; ?> /></div>&nbsp;<?php echo __("a page", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="display: block;">
                    <div id="createPage">
                        <p <?php echo getErrorField("pages"); ?>><?php echo __("Create a new page which will be your public Ultimate Blogroll page where visitors can register their links.", "ultimate-blogroll"); ?></p>
                        <table>
                            <tr>
                                <td class="column1">
                                    <?php echo __("Page title", "ultimate-blogroll") ?>*:
                                </td>
                                <td class="column2">
                                    <input type="text" class="form_text" name="frmPageName" value="<?php echo $frmPageName; ?>" />
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div id="selectPage">
                        <p <?php echo getErrorField("pages"); ?>><?php echo __("Select the page you want to appear as the Ultimate Blogroll page (you can only choose one)", "ultimate-blogroll"); ?>:</p>
                        <table>
                            <?php
                            foreach($allPages as $page) { ?>
                            <tr>
                                <td class="column1">
                                    <input name="pages" value="<?php echo $page["id"]; ?>" class="normal" type="radio" id="r_<?php echo $page["id"]; ?>" <?php if($page["id"] == $frmSelectedPage) echo "checked"; ?>>
                                </td>
                                <td>
                                    <label for="r_<?php echo $page["id"]; ?>"><?php if(empty($page["post_title"])) echo __("(no title)"); else echo $page["post_title"]; ?></label>&nbsp;<a href="<?php echo get_bloginfo('siteurl')."?page_id=".$page["id"]; ?>" target="_new"><?php echo __("view page", "ultimate-blogroll"); ?></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>

                </div><!--.inside-->
            </div><!--.postbox #general-settings-->
            <div class="postbox" id="wizard-2">
                <div class="handlediv" title="<?php echo __("Click to open/close", "ultimate-blogroll") ?>">
                    <br />
                </div><!--.handlediv-->
                <h3 class="hndle"><span><?php echo __("Step 2: Set up the widget", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="display: block;">
                    <p><?php echo __("Select the widgetbar(s) you want Ultimate Blogroll to appear in (you can select multiple)", "ultimate-blogroll"); ?>:</p>
                    <table>
                        <?php
                        foreach($sidebars_names as $sides) { ?>
                        <tr>
                            <td class="column1">
                                <input name="sides[]" id="c_<?php echo $sides["id"];?>" value="<?php echo $sides["id"]; ?>" class="normal" type="checkbox" <?php if(in_array($sides["id"], $frmSelectedWidgetBars)) echo "checked"; ?>>
                            </td>
                            <td>
                                <label for="c_<?php echo $sides["id"];?>"><?php echo $sides["name"]; ?></label>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div><!--.inside-->
            </div><!--.postbox #general-settings-->
            <div class="postbox" id="wizard-3">
                <div class="handlediv" title="<?php echo __("Click to open/close", "ultimate-blogroll") ?>">
                    <br />
                </div><!--.handlediv-->
                <h3 class="hndle"><span><?php echo __("Step 3: Import links from Wordpress", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="display: block;">
                    <p><?php echo __("Do you want to import your links from Wordpress? Ultimate Blogroll handles everything related to links, get started by importing all your links.", "ultimate-blogroll"); ?></p>
                    <input type="checkbox" name="frmImport" value="true" <?php echo $frmImport; ?> />
                </div><!--.inside-->
            </div><!--.postbox #general-settings-->
            <input name="save" type="submit" class="button-primary form_button" id="submit" value="<?php echo __("Let's go", "ultimate-blogroll"); ?>">
        </div>
    </form>
</div>