<?php
    require_once("functions.php");
    global $gui;
?>
<style type="text/css">
    .ub_table {
        width: 100%;
        -moz-border-radius: 4px 4px 4px 4px;
        border-spacing: 0;
        border-style: solid;
        border-width: 1px;
        border-color: #DFDFDF;
    }
    .ub_table th {
        line-height: 1.3em;
        padding: 7px 7px 8px;
        text-align: left;

    }
    .ub_table td {
        padding: 3px 7px;
        border-left: 1px solid #DFDFDF;
        border-bottom: 1px solid #DFDFDF;
    }

    .ub_table tr {
        border-collapse: collapse;
    }

    .first tr {
        width: 30%;
    }
    .first td {
        font-weight: bold;
    }
    .wb-data {
        
    }
    .column1 {
        width: 25%;
    }
    .form_text {
        width: 170px;
        -moz-box-sizing:border-box;
        -moz-border-radius:4px 4px 4px 4px;
        border-style:solid;
        border-color:#DFDFDF;
        border-width: 1px;
        padding:3px;
    }
    .column2 {
        width: 190px;
    }
    ul.error {
        padding-top: 6px;
        background-color: #ee5a5a;
        border: 1px solid #ff0000;
        -moz-border-radius:3px 3px 3px 3px;
        line-height:140%;
        padding-bottom:6px;
        list-style:none outside none;
    }
    ul.succes {
        padding-top: 6px;
        background-color: #87ee5a;
        border: 1px solid #00ff00;
        -moz-border-radius:3px 3px 3px 3px;
        line-height:140%;
        padding-bottom:6px;
        list-style:none outside none;
    }
    .red {
        color: #ff0000;
    }
    #ub_code {
        border: 1px solid #DFDFDF;
    }
</style>
<script type="text/javascript" >
    jQuery(document).ready(function($) {
        $("#ultimate-blogroll-table a").click(function () {
            var data = {
                action: "ub_ajax_action_callback",
                linkpartner: $(this).attr("href")
            };
            jQuery.post("<?php echo $gui["url"]; ?>/wp-admin/admin-ajax.php", data, function(response) {
                //alert("Got this from the server: " + response);
            });
        });
        $("ub_code").click(function() {
            alert('selected');
            SelectText("ub_code");
        });
        function SelectText(element) {
            switchEditors.go('content', 'html');
            var text = document.getElementById(element);
            if ($.browser.msie) {
                var range = document.body.createTextRange();
                range.moveToElementText(text);
                range.select();
            } else if ($.browser.mozilla || $.browser.opera) {
                var selection = window.getSelection();
                var range = document.createRange();
                range.selectNodeContents(text);
                selection.removeAllRanges();
                selection.addRange(range);
            } else if ($.browser.safari) {
                var selection = window.getSelection();
                selection.setBaseAndExtent(text, 0, text, 1);
            }
        }
    });
     var RecaptchaOptions = {
        theme : 'white'
     };
</script>
<p><?php echo __("The following links are from yesterday and today.<br />You can add your website at the bottom of this page.", "ultimate-blogroll") ?></p>
<table class="ub_table ub_register_clicks" cellspacing="1" id="ultimate-blogroll-table">
    <tr class="first">
        <td class="first"><?php echo __("Website", "ultimate-blogroll") ?></td>
        <td class="wb-data"><?php echo __("Last 48h in", "ultimate-blogroll") ?></td>
        <td class="wb-data"><?php echo __("Last 48h out", "ultimate-blogroll") ?></td>
        <td class="wb-data"><?php echo __("Total in", "ultimate-blogroll") ?></td>
        <td class="wb-data"><?php echo __("Total out", "ultimate-blogroll") ?></td>
        <td class="wb-data"><?php echo __("Ratio", "ultimate-blogroll") ?></td>
    </tr>
    <?php
    if(!empty($gui["table_links"])) {
        $max = count($gui["table_links"]);
        //var_dump($max);
        $rand = rand(1, $max);
        //$rand = $rand;
        //var_dump($rand);
        $tel = 0;
        foreach($gui["table_links"] as $links) {
            if(++$tel == $rand && @$gui["support"] == "yes") {
                echo "<tr>";
                echo "<td><a href=\"http://www.cadeauwebwinkel.be\" title=\"Cadeau (kado) of origineel geschenk voor verjaardag, Valentijn en Moederdag\" ".$gui["table_links_target"].">Cadeauwebwinkel.be</a></td>";
                echo "<td>n/a</td>";
                echo "<td>n/a</td>";
                echo "<td>n/a</td>";
                echo "<td>n/a</td>";
                echo "<td>n/a</td>";
                echo "</tr>";
            }
            if($links["website_total_inlink"] > 0 && $links["website_total_outlink"] > 0) {
                $ratio = ((int)$links["website_total_inlink"]/(int)$links["website_total_outlink"])*100;
            } else {
                $ratio = 0;
            }
            echo "<tr>";

                if(!empty($links["website_image"]) && PersistentieMapper::Instance()->GetConfig("logo") == "yes") {
                    $logo_usage = PersistentieMapper::Instance()->GetConfig("logo_usage");
                    if($logo_usage == "text") {
                        echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" ".$this->GetTarget(PersistentieMapper::Instance()->GetConfig("target")).$this->GetFollow(PersistentieMapper::Instance()->GetConfig("nofollow")).">".$links["website_name"]."</a></td>";
                    }
                    elseif($logo_usage == "image") {
                        echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" ".$this->GetTarget(PersistentieMapper::Instance()->GetConfig("target")).$this->GetFollow(PersistentieMapper::Instance()->GetConfig("nofollow"))."><img style=\"width: ".PersistentieMapper::Instance()->GetConfig("logo_width")."px; max-height: ".PersistentieMapper::Instance()->GetConfig("logo_height")."px;\" src=\"".$links["website_image"]."\" alt=\"".($links["website_description"])."\" /></a></td>";
                    }
                    else {
                        echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" ".$this->GetTarget(PersistentieMapper::Instance()->GetConfig("target")).$this->GetFollow(PersistentieMapper::Instance()->GetConfig("nofollow")).">".$links["website_name"]."<img style=\"width: ".PersistentieMapper::Instance()->GetConfig("logo_width")."px; max-height: ".PersistentieMapper::Instance()->GetConfig("logo_height")."px;\" src=\"".$links["website_image"]."\" alt=\"".($links["website_description"])."\" /></a></td>";
                    }
                } else {
                    echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" title=\"".($links["website_description"])."\"".$this->GetTarget(PersistentieMapper::Instance()->GetConfig("target")).$this->GetFollow(PersistentieMapper::Instance()->GetConfig("nofollow")).">".$links["website_name"]."</a></td>";
                }
/*
                if(!empty($links["website_image"])) {
                    echo "<td class=\"first\"><img src=\"".$links["website_image"]."\" alt=\"".($links["website_description"])."\" /><a href=\"".$links["website_url"]."\" ".$gui["table_links_target"].">".$links["website_name"]."</a></td>";
                } else {
                    echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" title=\"".($links["website_description"])."\"".$gui["table_links_target"].">".$links["website_name"]."</a></td>";
                }
 * 
 */
                echo "<td>".$links["website_last2days_inlink"]."</td>";
                echo "<td>".$links["website_last2days_outlink"]."</td>";
                echo "<td>".$links["website_total_inlink"]."</td>";
                echo "<td>".$links["website_total_outlink"]."</td>";
                if($ratio > 100)
                    $color = "color: #008000;";
                else
                    $color ="color: #CC3300;";
                echo "<td style=\"".$color."\">".round($ratio, 2)."% </td>";
            echo "</tr>";
        }
    }
    ?>
</table>
<h3 id="wp-add-your-site" style="margin-top: 30px; margin-bottom: 10px;"><?php echo __("Add your site", "ultimate-blogroll") ?></h3>
<h4><?php echo __("Step 1: First things first: Add our link to your website.", "ultimate-blogroll") ?></h4>
<table width="100%">
    <tr>
        <td style="width: 50%"><?php echo __("Website url", "ultimate-blogroll") ?>:</td>
        <td><?php echo $gui["url"] ?></td>
    </tr>
    <tr>
        <td><?php echo __("Website title", "ultimate-blogroll") ?>:</td>
        <td><?php echo $gui["title"] ?></td>
    </tr>
    <tr>
        <td><?php echo __("Website description", "ultimate-blogroll") ?>:</td>
        <td><?php echo $gui["description"] ?></td>
    </tr>
</table>
<fieldset id="ub_code">
    <legend >Code:</legend>
    <div>&lt;a href="<?php echo $gui["url"] ?>" title="<?php echo $gui["description"] ?>" <?php echo $gui["table_links_target"]?>&gt;<?php echo $gui["title"] ?>&lt;/a&gt;</div>
</fieldset>
<h4 style="margin-top: 20px;"><?php echo __("Step 2: Submit your linktrade", "ultimate-blogroll") ?></h4>
<form method="POST" action="#wp-add-your-site">
<?php
if(isset($gui["error"]["messages"]) && !empty($gui["error"]["messages"])) {
    echo "<ul class=\"error\">";
    echo getErrorMessages($gui["error"]["messages"]);
    echo "</ul>";
}
if(isset($gui["success"])) {
    echo "<ul class=\"succes updated\"><li>". __("Your website was successfully added.", "ultimate-blogroll")."</li><li>".__("Your website is awaiting approval, it will be visible within a short notice.", "ultimate-blogroll")."</li></ul>";
}
?>
<table>
    <tr <?php echo getErrorField("your_name"); ?>>
        <td class="column1"><?php echo __("Your name", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="your_name" class="form_text" value="<?php echo @$gui["value"]["your_name"]; ?>" /></td>
        <td><?php echo __("So we know who to contact", "ultimate-blogroll") ?></td>
    </tr>
    <tr <?php echo getErrorField("your_email"); ?>>
        <td class="column1"><?php echo __ ("Your email", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="your_email" class="form_text" value="<?php echo @$gui["value"]["your_email"]; ?>" /></td>
        <td><?php echo __("Existing email", "ultimate-blogroll") ?></td>
    </tr>
    <tr>
        <td><br /></td>
        <td></td>
        <td></td>
    </tr>
    <tr <?php echo getErrorField("website_url"); ?>>
        <td class="column1"><?php echo __("Website url", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="website_url" class="form_text" value="<?php echo @$gui["value"]["website_url"]; ?>" /></td>
        <td>&lt;a href=&quot;<b><?php echo __("website url", "ultimate-blogroll") ?></b>&quot;&gt;&lt;/a&gt;</td>
    </tr>
    <tr <?php echo getErrorField("website_title"); ?>>
        <td class="column1"><?php echo __("Website title", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="website_title" class="form_text" value="<?php echo @$gui["value"]["website_title"]; ?>" /></td>
        <td>&lt;a&gt;<b><?php echo __("website title", "ultimate-blogroll") ?></b>&lt;/a&gt;</td>
    </tr>
    <tr <?php echo getErrorField("website_description"); ?>>
        <td class="column1"><?php echo __("Website description", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="website_description" class="form_text" value="<?php echo @$gui["value"]["website_description"]; ?>" /></td>
        <td>&lt;a title=&quot;<b><?php echo __("website description", "ultimate-blogroll") ?></b>&quot;&gt;&lt;/a&gt;</td>
    </tr>
    <tr>
        <td><br /></td>
        <td></td>
        <td></td>
    </tr>
    <tr <?php echo getErrorField("website_domain"); ?>>
        <td class="column1"><?php echo __("Website domain", "ultimate-blogroll") ?>*:</td>
        <td class="column2"><input type="text" name="website_domain" class="form_text" value="<?php echo @$gui["value"]["website_domain"]; ?>" /></td>
        <td><b>example.com</b> (<a href="http://en.wikipedia.org/wiki/Second-level_domain" target="_new">sld</a>.<a href="http://en.wikipedia.org/wiki/Top-level_domain" target="_new">tld</a>) <?php echo __("without", "ultimate-blogroll") ?> http://www </td>
    </tr>
    <tr <?php echo getErrorField("website_reciprocal"); ?>>
        <td class="column1"><?php echo __("Website reciprocal", "ultimate-blogroll") ?><?php if($gui["reciprocal_link"] == "yes") echo "*"; ?>:</td>
        <td class="column2"><input type="text" name="website_reciprocal" class="form_text" value="<?php echo @$gui["value"]["website_reciprocal"]; ?>" /></td>
        <td><?php echo __("Where can we find our link back?", "ultimate-blogroll") ?></td>
    </tr>
    <?php
    if(PersistentieMapper::Instance()->GetConfig("logo") == "yes"){
    ?>
    <tr <?php echo getErrorField("website_image"); ?>>
        <td class="column1"><?php echo __("Website image", "ultimate-blogroll") ?>:</td>
        <td class="column2"><input type="text" name="website_image" class="form_text" value="<?php echo @$gui["value"]["website_image"]; ?>" /></td>
        <td><?php echo __("Add an image/logo (.png | transparent)", "ultimate-blogroll") ?></td>
    </tr>
    <?php
    }
        if(($gui["fight_spam"]) == "yes") {
            
    ?>
    <tr <?php echo html_entity_decode(@$gui["error"]["captcha"]); ?>>
        <td class="column1"><?php echo __("Anti-spam", "ultimate-blogroll") ?>*:</td>
        <td class="column2" colspan="2">
        <?php
            if(!function_exists("recaptcha_get_html")) {
                require_once("recaptchalib.php");
            }
            echo recaptcha_get_html($gui["captcha_settings"]);
        ?></td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td class="column1"></td>
        <td class="column2"><input type="submit" name="add_linkpartner" value="<?php echo __("Submit website", "ultimate-blogroll")?>" class="form_text" /></td>
        <td></td>
    </tr>
</table>
</form>