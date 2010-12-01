<?php
    require_once("recaptchalib.php");
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
        color: #333333;
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
        width: 13%;
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
</style>
<script type="text/javascript" >
    jQuery(document).ready(function($) {
        $("#ultimate-blogroll-table a").click(function () {
            var data = {
                action: "ub_ajax_action_callback",
                linkpartner: $(this).attr("href")
            };
            jQuery.post("<?= $gui["url"]; ?>/wp-admin/admin-ajax.php", data, function(response) {
                //alert("Got this from the server: " + response);
            });
        });
    });
     var RecaptchaOptions = {
        theme : 'white'
     };
</script>
<p>The following links are from yesterday and today.<br />You can add your website at the bottom of this page.</p>
<table class="ub_table" cellspacing="1" id="ultimate-blogroll-table">
    <tr class="first">
        <td class="first">Website</td>
        <td class="wb-data">Last 48h in</td>
        <td class="wb-data">Last 48h out</td>
        <td class="wb-data">Total in</td>
        <td class="wb-data">Total out</td>
        <td class="wb-data">Ratio</td>
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
                echo "<td class=\"first\"><a href=\"".$links["website_url"]."\" title=\"".($links["website_description"])."\"".$gui["table_links_target"].">".$links["website_name"]."</a></td>";
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
<h3 id="wp-add-your-site" style="margin-top: 30px; margin-bottom: 10px;">Add your site</h3>
<h4>Step 1: First things first: Add our link to your website.</h4>
<table width="100%">
    <tr>
        <td class="column1">Website url:</td>
        <td class="column2"><?= $gui["url"] ?></td>
    </tr>
    <tr>
        <td>Website title:</td>
        <td><?= $gui["title"] ?></td>
    </tr>
    <tr>
        <td>Website description:</td>
        <td><?= $gui["description"] ?></td>
    </tr>
</table>
<fieldset>
    <legend>Code:</legend>
    &lt; a href="<?= $gui["url"] ?>" title="<?= $gui["description"] ?>" <?= $gui["table_links_target"]?>&gt;<?= $gui["title"] ?>&lt;/a&gt;
</fieldset>
<h4 style="margin-top: 20px;">Step 2: Submit your linktrade</h4>
<form method="POST" action="#wp-add-your-site">
<?php
if(isset($gui["error"]["msg"]["addlinkpartner"])) {
    echo "<ul class=\"error\">";
    foreach($gui["error"]["msg"]["addlinkpartner"] as $error)
    {
        echo html_entity_decode($error);
    }
    echo "</ul>";
}
if(isset($gui["success"])) {
    //echo "<div>".$gui["success"]."</div>";
    echo "<ul class=\"succes updated\"><li>Your website was successfully added.</li><li>Your website is awaiting approval, it will be visible within a short notice.</li></ul>";

}
?>
<table style="color: #333333;">
    <tr <?=html_entity_decode(@$gui["error"]["your_name"]); ?>>
        <td class="column1">Your name*:</td>
        <td class="column2"><input type="text" name="your_name" class="form_text" value="<?=@$gui["value"]["your_name"]; ?>" /></td>
        <td>So we know who to contact</td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["your_email"]); ?>>
        <td class="column1">Your email*:</td>
        <td class="column2"><input type="text" name="your_email" class="form_text" value="<?=@$gui["value"]["your_email"]; ?>" /></td>
        <td>Existing email</td>
    </tr>
    <tr>
        <td><br /></td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["website_url"]); ?>>
        <td class="column1">Website url*:</td>
        <td class="column2"><input type="text" name="website_url" class="form_text" value="<?=@$gui["value"]["website_url"]; ?>" /></td>
        <td>&lt;a href=&quot;<b>website url</b>&quot;&gt;&lt;/a&gt;</td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["website_title"]); ?>>
        <td class="column1">Website title*:</td>
        <td class="column2"><input type="text" name="website_title" class="form_text" value="<?=@$gui["value"]["website_title"]; ?>" /></td>
        <td>&lt;a&gt;<b>website title</b>&lt;/a&gt;</td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["website_description"]); ?>>
        <td class="column1">Website description*:</td>
        <td class="column2"><input type="text" name="website_description" class="form_text" value="<?=@$gui["value"]["website_description"]; ?>" /></td>
        <td>&lt;a title=&quot;<b>website description</b>&quot;&gt;&lt;/a&gt;</td>
    </tr>
    <tr>
        <td><br /></td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["website_domain"]); ?>>
        <td class="column1">Website domain*:</td>
        <td class="column2"><input type="text" name="website_domain" class="form_text" value="<?=@$gui["value"]["website_domain"]; ?>" /></td>
        <td><b>example.com</b> (<a href="http://en.wikipedia.org/wiki/Second-level_domain" target="_new">sld</a>.<a href="http://en.wikipedia.org/wiki/Top-level_domain" target="_new">tld</a>) without http://www </td>
    </tr>
    <tr <?=html_entity_decode(@$gui["error"]["website_reciprocal"]); ?>>
        <td class="column1">Website reciprocal*:</td>
        <td class="column2"><input type="text" name="website_reciprocal" class="form_text" value="<?=@$gui["value"]["website_reciprocal"]; ?>" /></td>
        <td>Where can we find our link back?</td>
    </tr>
    <?php
        if(($gui["fight_spam"]) == "yes") {
            
    ?>
    <tr <?=html_entity_decode(@$gui["error"]["captcha"]); ?>>
        <td class="column1">Anti-spam*:</td>
        <td class="column2" colspan="2">
        <?php
            echo recaptcha_get_html($gui["captcha_settings"]["recaptcha_public_key"]);
        ?></td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td class="column1"></td>
        <td class="column2"><input type="submit" name="add_linkpartner" value="Submit website" class="form_text" /></td>
        <td></td>
    </tr>
</table>
</form>