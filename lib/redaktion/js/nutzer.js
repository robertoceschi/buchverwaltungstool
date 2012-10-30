/**
 *
 * Custom JS
 *
 * @since 2012-08-13
 * @copyright parobri
 *
 * NOTES:

 */



//$(document).ready(function () {

jQuery(document).ready(function ($) {
    if (window.location.href.indexOf('edit') > -1) {
        $("#commentForm").validate({
            rules:{
                vorname:{
                    required:true,
                    minlength:2
                },
                nachname:{
                    required:true,
                    minlength:2
                },
                emailadresse:{
                    required:true,
                    email:true

                },

                benutzername:{
                    required:true,
                    minlength:2

                },

                passwort:{
                    required:false,
                    minlength:4
                },
                passwort2:{
                    required:false,
                    minlength:4,
                    equalTo:"#passwort"
                }
            }
        });
    } else {

        $("#commentForm").validate({



            rules:{
                vorname:{
                    required:true,
                    minlength:2
                },
                nachname:{
                    required:true,
                    minlength:2
                },
                emailadresse:{
                    required:true,
                    email:true,
                    remote:{
                        url:WEBROOT + "redaktion/nutzer/register_email_exists",
                        type:"post",
                        data:{
                            emailadresse:function () {
                                return $("#emailadresse").val();
                            }
                        }
                    }
                },

                benutzername:{
                    required:true,
                    minlength:2,
                    remote:{
                        url:WEBROOT + "redaktion/nutzer/register_benutzer_exists",
                        type:"post",
                        data:{
                            benutzername:function () {
                                return $("#benutzername").val();
                            }
                        }
                    }

                },
                passwort:{
                    required:true,
                    minlength:4
                },
                passwort2:{
                    required:true,
                    minlength:4,
                    equalTo:"#passwort"
                }
            }
        });
    }


    function doIt() {
        $("div").show("slow");
        $("span").hide("slow");
    }

    $(".newpassword").click(doIt);


});
//});

