function ValidateEmail(Email) {

    if (Email != '') {

        jQuery(document).ready(function() {

            $.ajaxSetup({
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: "{{  url('/Validate/post')}}",
                method: 'post',
                data: {
                    Email: jQuery('#email').val(),
                },
                success: function(result) {

                    if (result.error == undefined) {

                        jQuery('#success').show();
                        jQuery('#success').html(result.success);
                        jQuery('#danger').hide();
                        $("#sucess").animate({ left: '250px' });
                        jQuery('#success').hide();

                        var id = setInterval(validate, 500);

                        function validate() {

                            if ((Email.indexOf("@") < 1) || ((Email.indexOf("@") == (Email.length < 1)))) {
                                jQuery('#warning').show();
                                jQuery('#warning').html('Invalid Email Axerate Symbol is Absent');
                                document.getElementById('email').focus();
                                document.getElementById('Register').disabled = true;
                                clearInterval(id);
                                return false
                            }

                            if (Email.indexOf("@") <= 2) {
                                jQuery('#warning').show();
                                jQuery('#warning').html('Email length should be at least 3 charcter long.');
                                document.getElementById('email').focus();
                                document.getElementById('Register').disabled = true;
                                clearInterval(id);
                                return false
                            }

                            if ((Email.indexOf(".") == -1)) {
                                document.getElementById('Register').disabled = true;
                                jQuery('#warning').show();
                                jQuery('#warning').html('Invalid Email (.) Symbol is Absent');
                                document.getElementById('email').focus();
                                clearInterval(id);
                                return false
                            }

                            if ((Email.indexOf("@") > 1) || ((Email.indexOf("@") != (Email.length < 1)) || (Email.indexOf(".") != -1))) {
                                document.getElementById('Reg_Register').disabled = false;
                                jQuery('#warning').hide();
                                document.getElementById('Email').focus();
                                clearInterval(id);
                            }



                        }

                    } else {
                        jQuery('#danger').show();
                        jQuery('#danger').html(result.error);
                        $("#danger").animate({ left: '250px' });
                        jQuery('#success').hide();
                        jQuery('#warning').hide();
                        document.getElementById('Reg_Register').disabled = true;


                    }
                },
            });

        });

    } else {

        jQuery('#danger').hide();
        jQuery('#warning').hide();
        jQuery('#success').hide();
    }

}



function check_strength_password(password) {


    document.getElementById('confirm_password').value = "";


    if (password != '') {

        $.ajax({
            data: { password: password, },
            url: "{{ route('check_password') }}",

            type: "GET",
            dataType: 'json',
            success: function(data) {
                if (data.Message == true) {

                    jQuery('#pass_response_email_warning').hide('100');
                    jQuery('#Register').show('100');
                    jQuery('#pass_email_success').show('100');
                    document.getElementById('pass_email_success').innerHTML = data.result;


                } else if (data.Message == false) {

                    document.getElementById('Register').disabled = false;
                    jQuery('#pass_email_success').hide('100');
                    jQuery('#pass_response_email_warning').show('100');
                    document.getElementById('pass_response_email_warning').innerHTML = data.result;


                }
            },
            error: function(data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }


        });
    } else

    {

        document.getElementById('pass_email_success').style.display = "none";
        document.getElementById('pass_response_email_warning').style.display = "none";
    }

}





function check_strength_password_confirm(password, confirm_password)

{

    if (confirm_password != '') {

        $.ajax({
            data: { password: confirm_password, },
            url: "{{   route('check_password')   }}",
            type: "GET",
            dataType: 'json',
            success: function(data) {
                if (data.Message == true) {


                    var id = setInterval(delay_refresh_page, 6000);

                    function delay_refresh_page() {
                        jQuery('#pass_response_email_warning').hide('100');
                        jQuery('#Register').show('100');
                        jQuery('#pass_email_success').show('100');
                        jQuery('#pass_response_email_danger').hide('100');
                        document.getElementById('pass_email_success').innerHTML = data.result;
                        clearInterval(id);;

                    }

                    delay_refresh_page();






                    if (confirm_password != password) {



                        var id = setInterval(delay_refresh_page, 6000);

                        function delay_refresh_page() {
                            jQuery('#pass_response_email_warning').hide('100');
                            jQuery('#Register').hide('100');
                            jQuery('#pass_email_success').hide('100');
                            jQuery('#pass_response_email_danger').show('100');
                            document.getElementById('pass_response_email_danger').innerHTML = "Password do not match!";
                            clearInterval(id);;

                        }

                        delay_refresh_page();

                    }


                } else if (data.Message == false) {

                    var id = setInterval(delay_refresh_page, 6000);

                    function delay_refresh_page() {
                        jQuery('#Register').hide('100');
                        jQuery('#pass_email_success').hide('100');
                        jQuery('#pass_response_email_warning').show('100');
                        jQuery('#pass_response_email_danger').hide('100');
                        document.getElementById('pass_response_email_warning').innerHTML = data.result;
                        clearInterval(id);;

                    }

                    delay_refresh_page();

                }






            },
            error: function(data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }


        });
    } else

    {

        document.getElementById('pass_email_success').style.display = "none";
        document.getElementById('pass_response_email_warning').style.display = "none";
    }

}