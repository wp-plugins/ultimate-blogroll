<?php
global $gui, $path;
$gui["title"] = "Wizard step 1/3";
require_once($path."gui/header.php");
?>
<div class="ultimate_blogroll_info">
    <b>INFO:</b><br />
    <?= __("We are going to make a page and set up a widget, let's start with the page:", "ultimate-blogroll") ?><br /><br />
    <table>
        <tr>
            <td class="column1"><?= __("Page title", "ultimate-blogroll") ?>:</td>
            <td class="column2"><input type="text" name="page_title" class="form_text" /></td>
        </tr>
        <tr>
            <td class="column1"></td>
            <td class="column2"><input type="submit" name="form_page" value="<?= __("Add page", "ultimate-blogroll") ?>" class="form_text" /></td>
        </tr>
    </table>
</div>
<?php
require_once($path."gui/footer.php");
?>