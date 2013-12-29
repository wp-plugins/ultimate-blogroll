<?php require_once("shared".DIRECTORY_SEPARATOR."Header.php"); ?>
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('.fight_spam :checkbox').iphoneStyle({
        onChange: function(elem, value) {
            if(value == false) {
                $('#recaptcha_public_key_tr').fadeOut('fast');
                $('#recaptcha_private_key_tr').fadeOut('fast');
            } else {
                $('#recaptcha_public_key_tr').fadeIn('fast');
                $('#recaptcha_private_key_tr').fadeIn('fast');
            }
        }
    });

    $('#wp_blogroll_logo').iphoneStyle({
        onChange: function(elem, value) {
            if(value == false) {
                $('#wp_blogroll_logo_width_tr').fadeOut('fast');
                $('#wp_blogroll_logo_height_tr').fadeOut('fast');
                $('#wp_blogroll_logo_usage_tr').fadeOut('fast');
            } else {
                $('#wp_blogroll_logo_width_tr').fadeIn('fast');
                $('#wp_blogroll_logo_height_tr').fadeIn('fast');
                $('#wp_blogroll_logo_usage_tr').fadeIn('fast');
            }
        }
    });

    $(':checkbox').iphoneStyle();
    $('#wp_website_url').click(function(){ $('#website_url').val('<?php echo $gui["value"]["default_website_url"] ?>') })
    $('#wp_website_title').click(function(){ $('#website_title').val('<?php echo $gui["value"]["default_website_title"] ?>') })
    $('#wp_website_description').click(function(){ $('#website_description').val('<?php echo $gui["value"]["default_website_description"] ?>') })
    $('#wp_blogroll_contact').click(function(){ $('#blogroll_contact').val('<?php echo $gui["value"]["default_blogroll_contact"] ?>') })
    $('#wp_blogroll_logo').change(function () {
        alert("AAA");
        if($(this).val() == "no") {
            $('#wp_blogroll_logo_width_tr').fadeOut('fast');
            $('#wp_blogroll_logo_height_tr').fadeOut('fast');
            $('#wp_blogroll_logo_usage_tr').fadeOut('fast');
        } else {
            $('#wp_blogroll_logo_width_tr').fadeIn('fast');
            $('#wp_blogroll_logo_height_tr').fadeIn('fast');
            $('#wp_blogroll_logo_usage_tr').fadeIn('fast');
        }
    })
    if($('#wp_blogroll_logo').val() == "no") {
        $('#wp_blogroll_logo_width_tr').fadeOut('fast');
        $('#wp_blogroll_logo_height_tr').fadeOut('fast');
        $('#wp_blogroll_logo_usage_tr').fadeOut('fast');
    }

    if(!$('#fight_spam').attr('checked')) {
        $('#recaptcha_public_key_tr').fadeOut('fast');
        $('#recaptcha_private_key_tr').fadeOut('fast');
    }

    if(!$('#wp_blogroll_logo').attr('checked')) {
        $('#wp_blogroll_logo_width_tr').fadeOut('fast');
        $('#wp_blogroll_logo_height_tr').fadeOut('fast');
        $('#wp_blogroll_logo_usage_tr').fadeOut('fast');
    }
});
</script>
<div class="wrap">
    <div class="icon32" id="icon-themes"></div>
    <h2><?php echo __("Settings", "ultimate-blogroll"); ?></h2>
    <?php echo errors();
    if( isset($gui["success"]["save"]) ) {
        echo '<div class="updated fade">
        <p>'.__("Settings were successfully updated.", "ultimate-blogroll").'</p>
        </div>';
    }
    ?>
    <form id="settings" method="POST" action="#">
        <div class="metabox-holder" id="poststuff">
            <!-- Start General Settings -->
            <div class="postbox" id="general-settings">
                <div class="handlediv" title="<?php echo __("Click to open/close", "ultimate-blogroll") ?>">
                    <br />
                </div><!--.handlediv-->
                <h3 class="hndle"><span><?php echo __("General settings", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="display: block;">
                    <table>
                        <tr <?php echo getErrorField("website_url"); ?>>
                            <td class="column1"><?php echo __("Website url", "ultimate-blogroll") ?>*:</td>
                            <td class="column2">
                                <div style="float: left;">
                                    <input type="text" class="form_text" id="website_url" name="website_url" value="<?php echo @$gui["value"]["website_url"]; ?>" />
                                </div>
                                <div style="float: left;" id="wp_website_url" class="reload">
                                    <img src="<?php echo UB_ASSETS_URL; ?>images/arrow_refresh.png" alt="reset" title="reset" />
                                </div>
                            </td>
                            <td><?php echo __("Your wordpress website url, just check if it is correct.", "ultimate-blogroll") ?> <?php echo htmlentities('<a href="');?><b><?php echo __("website url", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                        </tr>
                        <tr <?php echo getErrorField("website_title"); ?>>
                            <td class="column1"><?php echo __("Website title", "ultimate-blogroll") ?>*:</td>
                            <td class="column2">
                                <div style="float: left;">
                                    <input type="text" class="form_text" id="website_title" name="website_title" value="<?php echo @$gui["value"]["website_title"]; ?>" />
                                </div>
                                <div style="float: left;" id="wp_website_title" class="reload">
                                    <img src="<?php echo UB_ASSETS_URL; ?>images/arrow_refresh.png" alt="reset" title="reset" />
                                </div>
                            </td>
                            <td><?php echo __("That's the part between the", "ultimate-blogroll") . htmlentities('<a>'); ?><b><?php echo __("website title", "ultimate-blogroll") ?></b><?php echo htmlentities('</a>'); ?> tags</td>
                        </tr>
                        <tr <?php echo getErrorField("website_description"); ?>>
                            <td class="column1"><?php echo __("Website description", "ultimate-blogroll") ?>*:</td>
                            <td class="column2">
                                <div style="float: left;">
                                    <input type="text" class="form_text" id="website_description" name="website_description" value="<?php echo @$gui["value"]["website_description"]; ?>" />
                                </div>
                                <div style="float: left;" class="reload" id="wp_website_description">
                                    <img src="<?php echo UB_ASSETS_URL; ?>images/arrow_refresh.png" alt="reset" title="reset" />
                                </div>
                            </td>
                            <td><?php echo __("Add an extra description to your link.", "ultimate-blogroll") ?> <?php echo htmlentities('<a title="'); ?><b><?php echo __("website description", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                        </tr>
                        <tr>
                            <td><br /></td>
                        </tr>
                        <tr <?php echo getErrorField("blogroll_contact"); ?>>
                            <td class="column1"><?php echo __("Email address", "ultimate-blogroll") ?>*:</td>
                            <td class="column2">
                                <div style="float: left;">
                                    <input type="text" class="form_text" id="blogroll_contact" name="blogroll_contact" value="<?php echo @$gui["value"]["blogroll_contact"]; ?>" />
                                </div>
                                <div style="float: left;" id="wp_blogroll_contact" class="reload">
                                    <img src="<?php echo UB_ASSETS_URL; ?>images/arrow_refresh.png" alt="reset" title="reset" />
                                </div>
                            </td>
                            <td><?php echo __("The address you wish to receive an anouncement if a new linktrade was made.", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr>
                            <td class="column1"><?php echo __("Send email", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <input name="send_mail" value="yes" <?php echo ($gui["value"]["send_mail"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox">
                                <!--<select class="form_text" name="send_mail">
                                    <option value="yes" selected="yes" <?php /*echo ((@$gui["value"]["send_mail"] == "yes") ? "selected=\"yes\"" : ""); */?>><?php /*echo __("Yes, mail me", "ultimate-blogroll") */?></option>
                                    <option value="no" <?php /*echo ((@$gui["value"]["send_mail"] == "no") ? "selected=\"yes\"" : ""); */?>><?php /*echo __("No", "ultimate-blogroll") */?></option>
                                </select>-->
                            </td>
                            <td><?php echo __("I would like to receive an email everytime a new linktrade was made.", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr>
                            <td>
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td class="column1"><?php echo __("Reciprocal link", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <input name="reciprocal_link" value="yes" <?php echo ($gui["value"]["reciprocal_link"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox">
                                <!--<select class="form_text" name="reciprocal_link">
                                    <option value="yes" <?php /*echo ((@$gui["value"]["reciprocal_link"] == "yes") ? "selected=\"yes\"" : ""); */?>><?php /*echo __("Yes, required", "ultimate-blogroll") */?></option>
                                    <option value="no" <?php /*echo ((@$gui["value"]["reciprocal_link"] == "no") ? "selected=\"yes\"" : ""); */?>><?php /*echo __("No", "ultimate-blogroll") */?></option>
                                </select>-->
                            </td>
                            <td><?php echo __("Do people who request a linktrade need to have a reciprocal link?", "ultimate-blogroll") ?></td>
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
                                <input name="nofollow" value="yes" <?php echo ($gui["value"]["nofollow"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox">
                                <!--<select class="form_text" name="nofollow">
                                    <option value="yes" <?php /*echo ((@$gui["value"]["nofollow"] == "yes") ? "selected=\"yes\"" : ""); */?> ><?php /*echo __("Yes", "ultimate-blogroll") */?></option>
                                    <option value="no" <?php /*echo ((@$gui["value"]["nofollow"] == "no") ? "selected=\"yes\"" : ""); */?> ><?php /*echo __("No", "ultimate-blogroll") */?></option>
                                </select>-->
                            </td>
                            <td><?php echo __("Use nofollow if not on the homepage, so that links on the homepage weight more then other links. <a href=\"http://www.google.com/support/webmasters/bin/answer.py?answer=96569\" target=\"_blank\">It's SEO friendly.</a>", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr>
                            <td class="column1"><?php echo __("Support developer", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <input name="support" value="yes" <?php echo ($gui["value"]["support"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox">
                                <!--<select class="form_text" name="support">
                                    <option value="yes" <?php /*echo ((@$gui["value"]["support"] == "yes") ? "selected=\"yes\"" : ""); */?> ><?php /*echo __("Yes", "ultimate-blogroll") */?></option>
                                    <option value="no" <?php /*echo ((@$gui["value"]["support"] == "no") ? "selected=\"yes\"" : ""); */?> ><?php /*echo __("No", "ultimate-blogroll") */?></option>
                                </select>-->
                            </td>
                            <td><?php echo __("Allows me to place a link in the overview table at a random position.", "ultimate-blogroll") ?></td>
                        </tr>
                    </table>
                </div><!--.inside-->
            </div><!--.postbox #general-settings-->
            <!-- End General Settings -->
            <div class="postbox" id="pages">
                <div class="handlediv" title="Click to open/close">
                    <br />
                </div>
                <h3 class="hndle"><span><?php echo __("Select Ultimate blogroll page", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="">
                    <p <?php echo getErrorField("pages"); ?>>Select the page you want to appear as the Ultimate Blogroll page:</p>
                    <table>
                        <?php foreach($gui["value"]["pages"] as $page) { ?>
                            <tr>
                                <td class="column1">
                                    <input name="pages" value="<?php echo $page["id"]; ?>" class="normal" type="radio" id="r_<?php echo $page["id"]; ?>" <?php if($page["id"] == $gui["value"]["selected_page"]) echo "checked"; ?>>
                                </td>
                                <td>
                                    <label for="r_<?php echo $page["id"]; ?>"><?php if(empty($page["post_title"])) echo __("(no title)"); else echo $page["post_title"]; ?></label>&nbsp;<a href="<?php echo get_bloginfo('siteurl')."?page_id=".$page["id"]; ?>" target="_new"><?php echo __("view page", "ultimate-blogroll"); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div><!-- .inside -->
            </div><!-- .postbox #recaptcha -->
            <div class="postbox" id="widget-settings">
                <div class="handlediv" title="Click to open/close">
                    <br />
                </div>
                <h3 class="hndle"><span><?php echo __("Widget settings", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="">
                    <table>
                        <tr <?php echo getErrorField("widget_title"); ?>>
                            <td class="column1"><?php echo __("Widget title", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="widget_title" class="form_text" value="<?php echo @$gui["value"]["widget_title"]; ?>" /></td>
                            <td><?php echo __("The title of the widget as it will appear in the sidebar", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("limit_linkpartners"); ?>>
                            <td class="column1"><?php echo __("Limit of linkpartners", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input type="text" name="limit_linkpartners" class="form_text" value="<?php echo @$gui["value"]["limit_linkpartners"]; ?>" /></td>
                            <td><?php echo __("How many linkpartners do you want to be visible in the widget?", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("order_by"); ?>>
                            <td class="column1"><?php echo __("Order by", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <select class="form_text" name="order_by">
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
                                <select class="form_text" name="ascending">
                                    <option value="asc" <?php echo ((@$gui["value"]["ascending"] == "asc") ? "selected=\"yes\"" : ""); ?>><?php echo __("Ascending", "ultimate-blogroll") ?></option>
                                    <option value="desc" <?php echo ((@$gui["value"]["ascending"] == "desc") ? "selected=\"yes\"" : ""); ?>><?php echo __("Descending", "ultimate-blogroll") ?></option>
                                </select>
                            </td>
                            <td><?php echo __("Do you want the links to appear Ascending (1-2-3) or Descending (3-2-1)?", "ultimate-blogroll") ?></td>
                        </tr>
                        <?php /*
                        <tr <?php echo getErrorField("permalink"); ?>>
                            <td class="column1"><?php echo __("Link exchange page", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <?php if(!empty($gui["html"]["permalink"])) { ?>
                                <select class="form_text" name="permalink" >
                                    <?php
                                        foreach($gui["html"]["permalink"] as $optie)
                                        {
                                            echo "<option value=\"".$optie["id"]."\" ".((@$gui["value"]["permalink"] == $optie["id"]) ? "selected=\"yes\"" : "").">".$optie["post_title"]."</option>";
                                        }
                                    ?>
                                </select>
                                <?php
                                } else {
                                    echo "<b>Set up a page first!</b>";
                                } ?>
                            </td>
                            <td><?php echo __("Choose the page with the", "ultimate-blogroll") . " " . htmlentities('<!--ultimate-blogroll-->') ." ". __("tag on.", "ultimate-blogroll"); ?></td>
                        </tr>
                        */ ?>
                        <tr <?php echo getErrorField("logo"); ?>>
                            <td class="column1"><?php echo __("Website logo", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <input name="logo" value="yes" <?php echo ($gui["value"]["logo"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox" id="wp_blogroll_logo">
                            </td>
                            <td><?php echo __("Allow linkpartners to place a logo", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("logo_width"); ?> id="wp_blogroll_logo_width_tr">
                            <td class="column1"><?php echo __("Logo width", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input id="wp_blogroll_logo_width" type="text" name="logo_width" class="form_text" value="<?php echo @$gui["value"]["logo_width"]; ?>" /></td>
                            <td><?php echo __("Width (pixels) of the logo", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("logo_height"); ?> id="wp_blogroll_logo_height_tr">
                            <td class="column1"><?php echo __("Logo height", "ultimate-blogroll") ?>*:</td>
                            <td class="column2"><input id="wp_blogroll_logo_height" type="text" name="logo_height" class="form_text" value="<?php echo @$gui["value"]["logo_height"]; ?>" /></td>
                            <td><?php echo __("Height (pixels) of the logo", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("logo_usage"); ?> id="wp_blogroll_logo_usage_tr">
                            <td class="column1"><?php echo __("Logo usage", "ultimate-blogroll") ?>:</td>
                            <td class="column2">
                                <select id="wp_blogroll_logo_usage" class="form_text" name="logo_usage" >
                                    <option value="both" <?php echo ((@$gui["value"]["logo_usage"] == "both") ? "selected=\"yes\"" : ""); ?>><?php echo __("Text and image", "ultimate-blogroll") ?></option>
                                    <option value="text" <?php echo ((@$gui["value"]["logo_usage"] == "text") ? "selected=\"yes\"" : ""); ?>><?php echo __("Text", "ultimate-blogroll") ?></option>
                                    <option value="image" <?php echo ((@$gui["value"]["logo_usage"] == "image") ? "selected=\"yes\"" : ""); ?>><?php echo __("Image", "ultimate-blogroll") ?></option>
                                </select>
                            </td>
                            <td><?php echo __("How do you want to use the logo?", "ultimate-blogroll") ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="postbox" id="recaptcha">
                <div class="handlediv" title="Click to open/close">
                    <br />
                </div>
                <h3 class="hndle"><span><?php echo __("Recaptcha settings", "ultimate-blogroll") ?></span></h3>
                <div class="inside" style="">
                    <table>
                        <tr>
                            <td class="column1"><?php echo __("Fight spam", "ultimate-blogroll") ?>:</td>
                            <td class="column2 fight_spam">
                                <input id="fight_spam" name="fight_spam" value="yes" <?php echo ($gui["value"]["fight_spam"] == "yes") ? "checked='checked'" : "" ?>  class="normal" type="checkbox">
                                <!--<select class="form_text" name="fight_spam" id="fight_spam">
                                    <option value="yes" <?php /*echo ((@$gui["value"]["fight_spam"] == "yes") ? "selected=\"yes\"" : ""); */?>><?php /*echo __("Yes", "ultimate-blogroll") */?></option>
                                    <option value="no" <?php /*echo ((@$gui["value"]["fight_spam"] == "no") ? "selected=\"yes\"" : ""); */?> ><?php /*echo __("No", "ultimate-blogroll") */?></option>
                                </select>-->
                            </td>
                            <td><?php echo __("First you need to configure <a href=\"#recaptcha\">Recaptcha</a> in order to function.", "ultimate-blogroll") ?></td>
                        </tr>
                        <tr <?php echo getErrorField("recaptcha_public_key"); ?> id="recaptcha_public_key_tr">
                            <td class="column1"><?php echo __("Public key", "ultimate-blogroll") ?>:</td>
                            <td class="column2"><input type="text" class="form_text" id="recaptcha_public_key" name="recaptcha_public_key" value="<?php echo @$gui["value"]["recaptcha_public_key"]; ?>" /></td>
                            <td><?php echo __("The public key you received from", "ultimate-blogroll") ?> <a href="https://www.google.com/recaptcha/admin/create" target="_new">Recaptcha</a></td>
                        </tr>
                        <tr <?php echo getErrorField("recaptcha_private_key"); ?> id="recaptcha_private_key_tr">
                            <td class="column1"><?php echo __("Private key", "ultimate-blogroll") ?>:</td>
                            <td class="column2"><input type="text" class="form_text" id="recaptcha_private_key" name="recaptcha_private_key" value="<?php echo @$gui["value"]["recaptcha_private_key"]; ?>" /></td>
                            <td><?php echo __("The private key you received from", "ultimate-blogroll")?> <a href="https://www.google.com/recaptcha/admin/create" target="_new">Recaptcha</a></td>
                        </tr>
                    </table>
                </div><!-- .inside -->
            </div><!-- .postbox #recaptcha -->
            <p class="submit">
                <input type="submit" class="button button-primary button-large" name="save" value="Update settings" />
            </p>
        </div><!--.metabox-holder #poststuff-->
    </form>
</div><!--.wrap-->