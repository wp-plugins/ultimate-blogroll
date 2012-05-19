/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 9/04/12
 * Time: 18:57
 * To change this template use File | Settings | File Templates.
 */
jQuery(document).ready(function($) {
    $("table#linkpartners")
    .tablesorter(
    {
        sortList: [[1,0]],
        headers: {
            0:{sorter: false},
            9:{sorter: false}
        }
    })
    .tablesorterFilter(
    {
        filterContainer: $("#post-search-input"),
        filterColumns: [0],
        filterCaseSensitive: false
    });
    $(".delete").click(function(event) {
        var link = $(this).find("a").attr("href");
        event.preventDefault();
        $.apprise('Are you sure you want to <b><u>delete</u></b> this entry?', {'verify':true, 'textYes':'Yes already!', 'textNo':'No, not yet'}, function(r) {
            if(r) {
                window.location = link;
            }
        });
    });
});