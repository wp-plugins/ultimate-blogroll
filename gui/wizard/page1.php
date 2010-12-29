<?php
global $gui, $path;
$gui["title"] = "Wizard/Installation Proces";
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/header.php");
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/functions.php");
?>
<form method="POST" action="">
    <?php
    if(isset($gui["error"]["messages"]) and !empty($gui["error"]["messages"])) {
        echo "<ul class=\"error\">";
        echo getErrorMessages();
        echo "</ul>";
    }
    if(isset($gui["succes"]["general"]) && $gui["succes"]["general"] == true) {
        echo "<ul class=\"succes updated\"><li>".__("Plugin was successfully configured", "ultimate-blogroll")."</li></ul>";
    }
    ?>
    <div class="ultimate_blogroll_info">
        <b>Step 1: <?= __("Create Page", "ultimate-blogroll") ?></b><br />
        <?= __("In order to function and for your linkpartners to add their website, we need to set-up a public page. Please give this page a name.<br /><b>WARNING:</b> If you give in a page who actually exists it will be replaced by the Ultimate Blogroll page, but you can recall it with the build-in revision function in wordpress.", "ultimate-blogroll") ?><br /><br />
        <table>
            <tr <?=getErrorField("page_title"); ?>>
                <td class="column1"><?= __("Page title", "ultimate-blogroll") ?>*:</td>
                <td class="column2"><input type="text" name="page_title" class="form_text" value="<?= $gui["value"]["page_title"] ?>" /></td>
            </tr>
        </table>
    </div>
    <div class="ultimate_blogroll_info">
        <b>Step 2: <?= __("Set up the widget", "ultimate-blogroll") ?></b><br />
        <?= __("We detected that you had", "ultimate-blogroll")." <b>".$gui["number_of_sidebars"]."</b> ".__("sidebars. Please choose in which one Ultimate Blogroll will appear. If you are not sure what this means, just let it on ", "ultimate-blogroll")." ".$gui["widget_names"][0]["name"].". ".__("You can always alter it in the widget section of the admin panel.", "ultimate-blogroll") ?><br /><br />
        <table>
            <tr <?=getErrorField("sidebar"); ?>>
                <td class="column1" style="vertical-align: top;"><?= __("Select sidebar", "ultimate-blogroll") ?>*:</td>
                <td class="column2">
                    <?php
                    foreach($gui["widget_names"] as $key => $value) {
                        echo "<input id=\"".$value["id"]."\" ".((@$gui["value"]["sidebar"] == $value["id"]) ? "checked=\"true\"" : "")." type=\"radio\" name=\"sidebar\" value=\"".$value["id"]."\" /> <label for=\"".$value["id"]."\">".$value["name"]."</label><br />";
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="ultimate_blogroll_info">
        <b>Step 3: Recaptcha</b> (<?= __("optional", "ultimate-blogroll") ?>)<br />
        <?= __("We use an external party for the <a target=\"_new\" href=\"http://en.wikipedia.org/wiki/CAPTCHA\">captcha</a> (<a target=\"_new\" href=\"https://www.google.com/recaptcha/admin/create\">Recaptcha</a>). If you want to use the anti-spam (captcha) function, you need to register some keys at <a target=\"_new\" href=\"https://www.google.com/recaptcha/admin/create\">Recaptcha</a> after that you need to fill them in below here. <b>Warning</b> The anti-spam (recaptcha) is by default inactive, you need to activate it in the settings section.", "ultimate-blogroll") ?><br /><br />
        <table>
            <tr>
                <td class="column1"><?= __("Public key", "ultimate-blogroll") ?>:</td>
                <td class="column2"><input type="text" name="public_key" class="form_text" value="<?= $gui["value"]["public_key"] ?>" /></td>
            </tr>
            <tr>
                <td class="column1"><?= __("Private key", "ultimate-blogroll") ?>:</td>
                <td class="column2"><input type="text" name="private_key" class="form_text" value="<?= $gui["value"]["private_key"] ?>" /></td>
            </tr>
        </table>
    </div>
    <div class="ultimate_blogroll_info">
        <table>
            <tr>
                <td class="column1"><b><?= __("Submit", "ultimate-blogroll") ?></b></td>
                <td class="column2"><input type="submit" name="wizard" value="<?= __("Finish", "ultimate-blogroll") ?>" class="form_text" /></td>
            </tr>
        </table>
    </div>
</form>
<?php
require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/footer.php");
?>