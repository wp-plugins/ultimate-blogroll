<?php
global $gui, $path;
$gui["title"] = "Wizard step 2/3";
require_once($path."gui/header.php");
?>
<div class="ultimate_blogroll_info">
    <b>INFO:</b><br />
    <?= __("Now we will set up the widget, you will need to choose a sidebar where you want the widget to be placed in.", "ultimate-blogroll") ?><br /><br />
    <table>
        <tr>
            <td class="column1"><?= __("Sidebar", "ultimate-blogroll") ?>:</td>
            <?php
                $widgets = get_option("sidebars_widgets");
                $widgets = unserialize($widgets);
                echo "<pre>";
                var_dump($widgets);
                echo "</pre>";
            ?>
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