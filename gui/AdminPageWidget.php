<script type='text/javascript'>
jQuery(document).ready(function($) {
    $(':checkbox').iphoneStyle();
});
</script>
<p><?php echo __("Set this option to <b>ON</b> if you want this to become the Ultimate Blogroll page.", "ultimate-blogroll"); ?></p>
<input type="checkbox" name="ub_page" value="true" <?php echo $ub_page; ?> />
<p></p><b><?php echo __("Warning", "ultimate-blogroll")?>:</b> <?php echo __("only one page can be the Ultimate Blogroll page at the same time.", "ultimate-blogroll"); ?></p>