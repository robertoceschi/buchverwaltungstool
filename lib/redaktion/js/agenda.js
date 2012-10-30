/**
 *
 * Custom JS
 *
 * @since 2012-08-13
 * @copyright parobri
 *
 * NOTES:

 */



$(document).ready(function () {

   /* <!--Tiny MCE-->
   $("textarea.tinymce").tinymce({
        // Location of TinyMCE script
        script_url:WEBROOT + 'lib/redaktion/js/tiny_mce/tiny_mce.js',


        // General options
        theme:"simple"
    });*/

    $('#timepicker').timepicker();
    <!-- Datum -->


    <!-- Datum -->


        $( "#datepicker" ).datepicker({dateFormat : 'dd.mm.yy'
        });
    /**************** datepicker ***********************/
    /*jquery.ui.datepicker-de.js*/
    <!-- Zeit -->

    $.datepicker.regional['de'] = {
        closeText:'schließen',
        prevText:'&#x3c;zurück',
        nextText:'vor&#x3e;',
        currentText:'heute',
        monthNames:['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
        monthNamesShort:['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
            'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
        dayNames:['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
        dayNamesShort:['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        dayNamesMin:['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
        weekHeader:'Wo',
        dateFormat:'dd.mm.yy',
        firstDay:1,
        isRTL:false,
        showMonthAfterYear:false,
        yearSuffix:''};
    $.datepicker.setDefaults($.datepicker.regional['de']);

    //Input & Textarea Character Limit Display in Formular

    (function ($) {
        $.fn.extend({
            limiter:function (limit, elem) {
                $(this).on("keyup focus", function () {
                    setCount(this, elem);
                });
                function setCount(src, elem) {
                    var chars = src.value.length;
                    if (chars > limit) {
                        src.value = src.value.substr(0, limit);
                        chars = limit;
                    }
                    elem.html(limit - chars);
                }

                setCount($(this)[0], elem);
            }
        });
    })(jQuery);


    $(document).ready(function () {
        var elem = $("#chars");
        $("#text").limiter(1000, elem);
    });


    var SITE = SITE || {};
    var VIGET = '';

    SITE.fileInputs = function () {
        var $this = $(this),
            $val = $this.val(),
            valArray = $val.split('\\'),
            newVal = valArray[valArray.length - 1],
            $button = $this.siblings('.button'),
            $fakeFile = $this.siblings('.file-holder');
        if (newVal !== '') {
            $button.text('hochladen');
            if ($fakeFile.length === 0) {
                $button.after('<span class="file-holder">' + newVal + '</span>');
            } else {
                $fakeFile.text(newVal);
            }
        }
    };

    $(document).ready(function () {
        $('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);
    });

    $(document).ready(function () {
        $('.file-wrapper input[type=file]')
            .bind('change focus click', VIGET.fileInputs);
    });

});




