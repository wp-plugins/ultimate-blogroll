/**
 * Created with JetBrains PhpStorm.
 * User: User01
 * Date: 26/12/12
 * Time: 18:03
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function($) {
    jQuery(".Widget_widgetCreator a").click(function () {
        var data = {
            action: "ub_ajax_action_callback",
            linkpartner: $(this).attr("href")
        };
        jQuery.post(wpurl+"/wp-admin/admin-ajax.php", data, function(response) {
        });
    });
    $('input').placeholder();
});
var RecaptchaOptions = {
    theme : 'white'
};