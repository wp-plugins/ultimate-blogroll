<?php
global $gui, $path;
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/header.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/functions.php");
?>
<script type='text/javascript'>
    jQuery(document).ready(function($) { 
        $('#wp_website_url').click(function(){ $('#website_url').val('<?php echo $gui["site_url"]; ?>') })
        $('#wp_website_title').click(function(){ $('#website_title').val('<?php echo $gui["blogname"]; ?>') })
        $('#wp_website_description').click(function(){ $('#website_description').val('<?php echo $gui["description"]; ?>') })
        $('#wp_blogroll_contact').click(function(){ $('#blogroll_contact').val('<?php echo $gui["admin_email"]; ?>') })
    });
</script>

    <div class="postbox" id="general-settings">
        <div class="handlediv" title="<?php echo __("Click to open/close", "ultimate-blogroll") ?>">
            <br />
        </div>
        <h3 class="hndle"><span><?php echo __("General settings", "ultimate-blogroll") ?></span></h3>
        <div class="inside" style="display: block;">
            <form id="form1" method="POST" action="#general-settings">
                <?php
                if(isset($gui["error"]["msg"]["general"]) && !empty($gui["error"]["msg"]["general"])) {
                    echo "<ul class=\"error\">";
                    echo getErrorMessages();
                    echo "</ul>";
                }
                if(isset($gui["succes"]["general"]) && $gui["succes"]["general"] == true) {
                    echo "<ul class=\"succes updated\"><li>".__("Changes were successfully saved", "ultimate-blogroll")."</li></ul>";
                }
                ?>
                <table>
                    <tr <?php echo getErrorField("website_url"); ?>>
                        <td class="column1"><?php echo __("Website url", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><div style="float: left;"><input type="text" class="form_text" id="website_url" name="website_url" value="<?php echo @$gui["value"]["website_url"]; ?>" /></div><div style="float: left;" id="wp_website_url" class="reload"><img src="../wp-content/plugins/ultimate-blogroll/images/arrow_refresh.png" alt="reset" title="reset" /></div></td>
                        <td><?php echo __("Your wordpress website url, just check if it is correct.", "ultimate-blogroll") ?> <?php echo htmlentities('<a href="');?><b><?php echo __("website url", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr <?php echo getErrorField("website_title"); ?>>
                        <td class="column1"><?php echo __("Website title", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><div style="float: left;"><input type="text" class="form_text" id="website_title" name="website_title" value="<?php echo @$gui["value"]["website_title"]; ?>" /></div><div style="float: left;" id="wp_website_title" class="reload"><img src="../wp-content/plugins/ultimate-blogroll/images/arrow_refresh.png" alt="reset" title="reset" /></div></td>
                        <td><?php echo __("That's the part between the", "ultimate-blogroll") . htmlentities('<a>'); ?><b><?php echo __("website title", "ultimate-blogroll") ?></b><?php echo htmlentities('</a>'); ?> tags</td>
                    </tr>
                    <tr <?php echo getErrorField("website_description"); ?>>
                        <td class="column1"><?php echo __("Website description", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><div style="float: left;"><input type="text" class="form_text" id="website_description" name="website_description" value="<?php echo @$gui["value"]["website_description"]; ?>" /></div><div style="float: left;" class="reload" id="wp_website_description"><img src="../wp-content/plugins/ultimate-blogroll/images/arrow_refresh.png" alt="reset" title="reset" /></div></td>
                        <td><?php echo __("Add an extra description to your link.", "ultimate-blogroll") ?> <?php echo htmlentities('<a title="'); ?><b><?php echo __("website description", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr>
                        <td><br /></td>
                    </tr>
                    <tr <?php echo getErrorField("blogroll_contact"); ?>>
                        <td class="column1"><?php echo __("Email address", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><div style="float: left;"><input type="text" class="form_text" id="blogroll_contact" name="blogroll_contact" value="<?php echo @$gui["value"]["blogroll_contact"]; ?>" /></div><div style="float: left;" id="wp_blogroll_contact" class="reload"><img src="../wp-content/plugins/ultimate-blogroll/images/arrow_refresh.png" alt="reset" title="reset" /></div></td>
                        <td><?php echo __("The address you wish to receive an anouncement if a new linktrade was made.", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Send email", "ultimate-blogroll") ?>:</td>
                        <!--<td class="column2"><input type="checkbox" id="blogroll_email_checkbox" name="blogroll_email_checkbox" <?php echo ((@$gui["value"]["blogroll_email_checkbox"] == "on") ? "checked=\"yes\"" : "") ?> /></td>-->
                        <td class="column2">
                            <select class="form_text" name="send_mail">
                                <option value="yes" selected="yes" <?php echo ((@$gui["value"]["send_mail"] == "yes") ? "selected=\"yes\"" : ""); ?>><?php echo __("Yes, mail me", "ultimate-blogroll") ?></option>
                                <option value="no" <?php echo ((@$gui["value"]["send_mail"] == "no") ? "selected=\"yes\"" : ""); ?>><?php echo __("No", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><label for="blogroll_email_checkbox"><?php echo __("I would like to receive an email everytime a new linktrade was made.", "ultimate-blogroll") ?></label></td>
                    </tr>
                    <tr>
                        <td>
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Reciprocal link", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="reciprocal_link">
                                <option value="yes" <?php echo ((@$gui["value"]["reciprocal_link"] == "yes") ? "selected=\"yes\"" : ""); ?>><?php echo __("Yes, required", "ultimate-blogroll") ?></option>
                                <option value="no" <?php echo ((@$gui["value"]["reciprocal_link"] == "no") ? "selected=\"yes\"" : ""); ?>><?php echo __("No", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("Do people who request a linktrade need to have a reciprocal link?", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Fight spam", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="fight_spam" <?php echo ((@$gui["html"]["recaptcha"] == true)?"":"disabled=\"true\"") ?>>
                                <option value="yes" <?php echo ((@$gui["value"]["fight_spam"] == "yes") ? "selected=\"yes\"" : ""); ?>><?php echo __("Yes", "ultimate-blogroll") ?></option>
                                <option value="no" <?php echo ((@$gui["value"]["fight_spam"] == "no") ? "selected=\"yes\"" : ""); ?> ><?php echo __("No", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("First you need to configure <a href=\"#recaptcha\">Recaptcha</a> in order to function.", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td>
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Target", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="target">
                                <option value="_blank" <?php echo ((@$gui["value"]["target"] == "_blank") ? "selected=\"yes\"" : ""); ?>>_blank</option>
                                <option value="_top" <?php echo ((@$gui["value"]["target"] == "_top") ? "selected=\"yes\"" : ""); ?> >_top</option>
                                <option value="_none" <?php echo ((@$gui["value"]["target"] == "_none") ? "selected=\"yes\"" : ""); ?> >_none</option>
                            </select>
                        </td>
                        <td><?php echo __("Select your target: _blank = new window; _top = current window; _none = same window;", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Nofollow", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="nofollow">
                                <option value="yes" <?php echo ((@$gui["value"]["nofollow"] == "yes") ? "selected=\"yes\"" : ""); ?> ><?php echo __("Yes", "ultimate-blogroll") ?></option>
                                <option value="no" <?php echo ((@$gui["value"]["nofollow"] == "no") ? "selected=\"yes\"" : ""); ?> ><?php echo __("No", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("Use nofollow if not on the homepage, so that links on the homepage weight more then other links. <a href=\"http://www.google.com/support/webmasters/bin/answer.py?answer=96569\" target=\"_blank\">It's SEO friendly.</a>", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"><?php echo __("Support developer", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="support">
                                <option value="yes" <?php echo ((@$gui["value"]["support"] == "yes") ? "selected=\"yes\"" : ""); ?> ><?php echo __("Yes", "ultimate-blogroll") ?></option>
                                <option value="no" <?php echo ((@$gui["value"]["support"] == "no") ? "selected=\"yes\"" : ""); ?> ><?php echo __("No", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("Allows me to place a link in the overview table at a random position.", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"></td>
                        <td class="column2"><input type="submit" name="general_settings" value="<?php echo __("Update settings", "ultimate-blogroll")?>" /></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    
    <div class="postbox" id="widget-settings">
        <div class="handlediv" title="Click to open/close">
            <br />
        </div>
        <h3 class="hndle"><span><?php echo __("Widget settings", "ultimate-blogroll") ?></span></h3>
        <div class="inside" style="">
            <?php
                /*
                if(@$gui["error"]["no_tag"] === true)
                {
                    $page_error = '<div class="ultimate_blogroll_error"><p>';
                    $page_error .= sprintf('<b><a href="%s">Ultimate Blogroll</a> Info:</b> The tag <b>%s</b> has not been set on any <a href="edit.php?post_type=page">page</a>. Use the <a href="%s">wizard</a> if you are unsure what this means.', 
                        admin_url('admin.php?page=ultimate-blogroll-overview'),
                        htmlentities("<!--ultimate-blogroll-->"),
                        admin_url('admin.php?page=ultimate-blogroll-overview&action=wizard')
                    );
                    $page_error .=  '</p></div>'. "\n";
                    echo $page_error;
                }
                */
                if(isset($gui["error"]["msg"]["widget"]) && !empty($gui["error"]["msg"]["widget"])) {
                    echo "<ul class=\"error\">";
                    echo getErrorMessages();
                    echo "</ul>";
                }
                if(isset($gui["succes"]["widget"]) && $gui["succes"]["widget"] == true) {
                    echo "<ul class=\"succes updated\"><li>".__("Changes were successfully saved", "ultimate-blogroll")."</li></ul>";
                }
            ?>
            <form id="form2" method="POST" action="#widget-settings">
                <table>
                    <tr <?php echo getErrorField("widget_title"); ?>>
                        <td class="column1"><?php echo __("Widget title", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="widget_title" class="form_text" value="<?php echo @$gui["value"]["widget_title"]; ?>" <?php if(@$gui["error"]["no_tag"] === true) echo "disabled"; ?> /></td>
                        <td><?php echo __("The title of the widget as it will appear in the sidebar", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr <?php echo getErrorField("limit_linkpartners"); ?>>
                        <td class="column1"><?php echo __("Limit of linkpartners", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="limit_linkpartners" class="form_text" value="<?php echo @$gui["value"]["limit_linkpartners"]; ?>" <?php if(@$gui["error"]["no_tag"] === true) echo "disabled"; ?> /></td>
                        <td><?php echo __("How many linkpartners do you want to be visible in the widget?", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr <?php echo getErrorField("order_by"); ?>>
                        <td class="column1"><?php echo __("Order by", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="order_by" <?php if(@$gui["error"]["no_tag"] === true) echo "disabled"; ?>>
                                <option value="id" <?php echo ((@$gui["value"]["order_by"] == "id") ? "selected=\"yes\"" : ""); ?>><?php echo __("ID", "ultimate-blogroll") ?></option>
                                <option value="name" <?php echo ((@$gui["value"]["order_by"] == "name") ? "selected=\"yes\"" : ""); ?>><?php echo __("Name", "ultimate-blogroll") ?></option>
                                <option value="inlinks" <?php echo ((@$gui["value"]["order_by"] == "inlinks") ? "selected=\"yes\"" : ""); ?>><?php echo __("Inlinks", "ultimate-blogroll") ?></option>
                                <option value="outlinks" <?php echo ((@$gui["value"]["order_by"] == "outlinks") ? "selected=\"yes\"" : ""); ?>><?php echo __("Outlinks", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("Select on which criteria you want to order your linkpartners", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr <?php echo getErrorField("ascending"); ?>>
                        <td class="column1"><?php echo __("Ascending/Descending", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="ascending" <?php if(@$gui["error"]["no_tag"] === true) echo "disabled"; ?> >
                                <option value="asc" <?php echo ((@$gui["value"]["ascending"] == "asc") ? "selected=\"yes\"" : ""); ?>><?php echo __("Ascending", "ultimate-blogroll") ?></option>
                                <option value="desc" <?php echo ((@$gui["value"]["ascending"] == "desc") ? "selected=\"yes\"" : ""); ?>><?php echo __("Descending", "ultimate-blogroll") ?></option>
                            </select>
                        </td>
                        <td><?php echo __("Do you want the links to appear Ascending (1-2-3) or Descending (3-2-1)?", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr <?php echo getErrorField("permalink"); ?>>
                        <td class="column1"><?php echo __("Link exchange page", "ultimate-blogroll") ?>:</td>
                        <td class="column2">
                            <select class="form_text" name="permalink" <?php if(@$gui["error"]["no_tag"] === true) echo "disabled"; ?> >
                                <?php
                                    foreach($gui["html"]["permalink"] as $optie)
                                    {
                                        echo "<option value=\"".$optie["id"]."\" ".((@$gui["value"]["permalink"] == $optie["id"]) ? "selected=\"yes\"" : "").">".$optie["post_title"]."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td><?php echo __("Choose the page with the", "ultimate-blogroll") . " " . htmlentities('<!--ultimate-blogroll-->') ." ". __("tag on.", "ultimate-blogroll"); ?></td>
                    </tr>
                    <tr>
                        <td class="column1"></td>
                        <td class="column2">
                                <?php /*if(@$gui["error"]["no_tag"] != true) { */ ?>
                                    <input type="submit" name="widget_settings" value="<?php echo __("Update settings", "ultimate-blogroll")?>" />
                                <?php /* } */ ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="postbox" id="recaptcha">
        <div class="handlediv" title="Click to open/close">
            <br />
        </div>
        <h3 class="hndle"><span><?php echo __("Recaptcha settings", "ultimate-blogroll") ?></span></h3>
        <div class="inside" style="">
            <?php
            if(isset($gui["error"]["msg"]["recaptcha"]) && !empty($gui["error"]["msg"]["recaptcha"])) {
                echo "<ul class=\"error\">";
                echo getErrorMessages();
                echo "</ul>";
            }
            if(isset($gui["succes"]["recaptcha"]) && $gui["succes"]["recaptcha"] == true) {
                    echo "<ul class=\"succes updated\"><li>".__("Changes were successfully saved", "ultimate-blogroll")."</li></ul>";
                }
            ?>
            <form id="form3" method="POST" action="#recaptcha">
                <table>
                    <tr <?php echo getErrorField("recaptcha_public_key"); ?>>
                        <td class="column1"><?php echo __("Public key", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" class="form_text" id="recaptcha_public_key" name="recaptcha_public_key" value="<?php echo @$gui["value"]["recaptcha_public_key"]; ?>" /></td>
                        <td><?php echo __("The public key you received from", "ultimate-blogroll") ?> <a href="https://www.google.com/recaptcha/admin/create" target="_new">Recaptcha</a></td>
                    </tr>
                    <tr <?php echo getErrorField("recaptcha_private_key"); ?>>
                        <td class="column1"><?php echo __("Private key", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" class="form_text" id="recaptcha_private_key" name="recaptcha_private_key" value="<?php echo @$gui["value"]["recaptcha_private_key"]; ?>" /></td>
                        <td><?php echo __("The private key you received from", "ultimate-blogroll")?> <a href="https://www.google.com/recaptcha/admin/create" target="_new">Recaptcha</a></td>
                    </tr>
                    <tr>
                        <td class="column1"></td>
                        <td class="column2"><input type="submit" name="recaptcha_settings" value="<?php echo __("Update settings", "ultimate-blogroll")?>" /></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
<?php
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/footer.php");
?>