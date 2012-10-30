/**
 *
 * Custom JS
 *
 * @since 2012-08-13
 * @copyright parobri
 *
 * NOTES: Javascript der Hilfedatei

 */

$(document).ready(function () {

    $('#opener').click(function () {
        $.get(WEBROOT + "lib/redaktion/add_files/hilfe.html", function (result) {
            if (result) {
                $('#hilfe').html(result);
            }

            return false;
        });
        // Dialog
        $('#hilfe').dialog({
            autoOpen:false,
            Width:250,
            minWidth: 175,
            height:500,
            resizable:true,
            title:"Bedienungsanleitung",
            position:['left', 'top']

        });
        $("#hilfe").dialog('open');
    });
});

