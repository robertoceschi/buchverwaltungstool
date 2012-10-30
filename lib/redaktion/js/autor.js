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
        $("#text").limiter(2000, elem);
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




