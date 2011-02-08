<?php
/**
 * Description of add_linkpartner
 *
 * @author Jens
 */
global $gui, $path;
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/header.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/functions.php");
?>
    <div class="postbox">                                  
        <div class="handlediv" title="Click to open/close">
            <br />
        </div>
        <h3 class="hndle"><span><?php echo  (($gui["edit"] === true) ? __("Edit linkpartner", "ultimate-blogroll").": ".@$gui["value"]["website_title"]." (<a href=\"".@$gui["value"]["website_url"]."\" target=\"_blank\">".@$gui["value"]["website_url"]."</a>)" : __("Add linkpartner", "ultimate-blogroll")); ?></span></h3>
        <div class="inside" style="display: block;">
            <?php
            if(isset($gui["error"]["messages"]["addlinkpartner"]) && !empty($gui["error"]["messages"]["addlinkpartner"])) {
                echo "<ul class=\"error\">";
                echo getErrorMessages($gui["error"]["messages"]["addlinkpartner"]);
                echo "</ul>";
            }
            if(isset($gui["success"])) {
                //echo "<div>".$gui["success"]."</div>";
                if($gui["edit"] === true) {
                    echo "<ul class=\"succes updated\"><li>".__("Linkpartner was successfully updated.", "ultimate-blogroll")."</li></ul>";
                } else {
                    echo "<ul class=\"succes updated\"><li>".__("Linkpartner was successfully added.", "ultimate-blogroll")."</li></ul>";
                }
                
            }
            ?>
            <form id="form1" method="POST" action="">
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
                        <td class="column2"><input type="text" name="website_url" class="form_text" value="<?php echo @$gui["value"]["website_url"]; ?>" /></td>
                        <td><?php echo htmlentities('<a href="');?><b><?php echo __("website url", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr <?php echo getErrorField("website_title"); ?>>
                        <td class="column1"><?php echo __("Website title", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_title" class="form_text" value="<?php echo @$gui["value"]["website_title"]; ?>" /></td>
                        <td><?php echo htmlentities('<a>'); ?><b><?php echo __("website title", "ultimate-blogroll") ?></b><?php echo htmlentities('</a>'); ?></td>
                    </tr>
                    <tr <?php echo getErrorField("website_description"); ?>>
                        <td class="column1"><?php echo __("Website description", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_description" class="form_text" value="<?php echo @$gui["value"]["website_description"]; ?>" /></td>
                        <td><?php echo htmlentities('<a title="'); ?><b><?php echo __("website description", "ultimate-blogroll") ?></b><?php echo htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr>
                        <td><br /></td>
                    </tr>
                    <tr <?php echo getErrorField("website_domain"); ?>>
                        <td class="column1"><?php echo __("Website domain", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_domain" class="form_text" value="<?php echo @$gui["value"]["website_domain"]; ?>" /></td>
                        <td><?php echo __("Website domain", "ultimate-blogroll") ?>, <b>example.com</b> (<a href="http://en.wikipedia.org/wiki/Second-level_domain" target="_new">sld</a>.<a href="http://en.wikipedia.org/wiki/Top-level_domain" target="_new">tld</a>) <?php echo  __("without", "ultimate-blogroll") ?> http://www </td>
                    </tr>
                    <tr <?php echo getErrorField("website_reciprocal"); ?>>
                        <td class="column1"><?php echo __("Website reciprocal", "ultimate-blogroll") ?>:</td>
                        <td class="column2"><input type="text" name="website_reciprocal" class="form_text" value="<?php echo @$gui["value"]["website_reciprocal"]; ?>" /></td>
                        <td><?php echo __("Where can we find our link back? (Leave blank if not required)", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr <?php echo getErrorField("website_image"); ?>>
                        <td class="column1"><?php echo __("Website image", "ultimate-blogroll") ?>:</td>
                        <td class="column2"><input type="text" name="website_image" class="form_text" value="<?php echo @$gui["value"]["website_image"]; ?>" /></td>
                        <td><?php echo __("Add a image/logo", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"></td>
                        <td class="column2"><input type="submit" name="add_linkpartner" value="<?php echo (($gui["edit"] === true) ? __("Update linkpartner", "ultimate-blogroll") : __("Submit linkpartner", "ultimate-blogroll")) ?>" /></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
<?php
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/footer.php");
?>