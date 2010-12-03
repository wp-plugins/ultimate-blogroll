<?php
/**
 * Description of add_linkpartner
 *
 * @author Jens
 */
global $gui, $path;
require_once($path."gui/header.php");
?>
    <div class="postbox">                                  
        <div class="handlediv" title="Click to open/close">
            <br>
        </div>
        <h3 class="hndle"><span><?= (($gui["edit"] === true) ? __("Edit linkpartner", "ultimate-blogroll").": ".@$gui["value"]["website_title"]." (<a href=\"".@$gui["value"]["website_url"]."\" target=\"_blank\">".@$gui["value"]["website_url"]."</a>)" : __("Add linkpartner", "ultimate-blogroll")); ?></span></h3>
        <div class="inside" style="display: block;">
            <?php
            if(isset($gui["error"]["msg"]["addlinkpartner"])) {
                echo "<ul class=\"ub_error\">";
                foreach($gui["error"]["msg"]["addlinkpartner"] as $error)
                {
                    echo html_entity_decode($error);
                }
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
                    <tr <?=@$gui["error"]["your_name"]; ?>>
                        <td class="column1"><?= __("Website owner's name", "ultimate-blogroll"); ?>*:</td>
                        <td class="column2"><input type="text" name="your_name" class="form_text" value="<?=@$gui["value"]["your_name"]; ?>" /></td>
                        <td><?= __("Website owner's name, so we know who to contact", "ultimate-blogroll"); ?></td>
                    </tr>
                    <tr <?=@$gui["error"]["your_email"]; ?>>
                        <td class="column1"><?= __("Website owner's email", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="your_email" class="form_text" value="<?=@$gui["value"]["your_email"]; ?>" /></td>
                        <td><?= __("Website owner's email, so we know who to contact", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td><br /></td>
                    </tr>
                    <tr <?=@$gui["error"]["website_url"]; ?>>
                        <td class="column1"><?= __("Website url", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_url" class="form_text" value="<?=@$gui["value"]["website_url"]; ?>" /></td>
                        <td><?=htmlentities('<a href="');?><b><?= __("website url", "ultimate-blogroll") ?></b><?=htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr <?=@$gui["error"]["website_title"]; ?>>
                        <td class="column1"><?= __("Website title", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_title" class="form_text" value="<?=@$gui["value"]["website_title"]; ?>" /></td>
                        <td><?=htmlentities('<a>'); ?><b><?= __("website title", "ultimate-blogroll") ?></b><?=htmlentities('</a>'); ?></td>
                    </tr>
                    <tr <?=@$gui["error"]["website_description"]; ?>>
                        <td class="column1"><?= __("Website description", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_description" class="form_text" value="<?=@$gui["value"]["website_description"]; ?>" /></td>
                        <td><?=htmlentities('<a title="'); ?><b><?= __("website description", "ultimate-blogroll") ?></b><?=htmlentities('"></a>'); ?></td>
                    </tr>
                    <tr>
                        <td><br /></td>
                    </tr>
                    <tr <?=@$gui["error"]["website_domain"]; ?>>
                        <td class="column1"><?= __("Website domain", "ultimate-blogroll") ?>*:</td>
                        <td class="column2"><input type="text" name="website_domain" class="form_text" value="<?=@$gui["value"]["website_domain"]; ?>" /></td>
                        <td><?= __("Website domain", "ultimate-blogroll") ?>, <b>example.com</b> (<a href="http://en.wikipedia.org/wiki/Second-level_domain" target="_new">sld</a>.<a href="http://en.wikipedia.org/wiki/Top-level_domain" target="_new">tld</a>) <?= __("without", "ultimate-blogroll") ?> http://www </td>
                    </tr>
                    <tr <?=@$gui["error"]["website_reciprocal"]; ?>>
                        <td class="column1"><?= __("Website reciprocal", "ultimate-blogroll") ?>:</td>
                        <td class="column2"><input type="text" name="website_reciprocal" class="form_text" value="<?=@$gui["value"]["website_reciprocal"]; ?>" /></td>
                        <td><?= __("Where can we find our link back? (Leave blank if not required)", "ultimate-blogroll") ?></td>
                    </tr>
                    <tr>
                        <td class="column1"></td>
                        <td class="column2"><input type="submit" name="add_linkpartner" value="<?= (($gui["edit"] === true) ? __("Update linkpartner", "ultimate-blogroll") : __("Submit linkpartner", "ultimate-blogroll")) ?>" /></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
<?php
require_once($path."gui/footer.php");
?>
