/**
 * Created by Vitalie Lupu on 6/2/17.
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        //Register Form
        if (jQuery().validate && jQuery().ajaxSubmit) {
            /** User Forms */
            var closeAlert = function ($form) {
                $('.alert', $form).alert('close');
            };
            var showAlert = function ($form, message) {
                var alertMessage = '<div class="alert alert-success alert-dismissible text-center" role="alert">' + message + '</div>';
                $('.modal-body', $form).append(alertMessage);
                // if (autoDissmis){ setTimeout(function(){ closeAlert($form); }, 8000); }
            };
            /** Login  */
            var $formLogin = $('#formLogin'), $formRegister = $('#formRegister'), $formResetPassword = $('#formResetPassword');
            $formLogin.validate({
                submitHandler: function (form) {
                    var btnLogin = $('#btnLogin');
                    btnLogin.enable();
                    $formLogin.ajaxSubmit({
                        beforeSubmit: function () {
                            closeAlert($formLogin);
                            btnLogin.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnLogin.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                                window.location.replace(response.redirect);
                            } else {
                                showAlert($form, response.message);
                            }
                        }
                    });
                }
            });
            $('.modal').on('hidden.bs.modal', function (e) {
                closeAlert($(this));
            });
            $('button[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                closeAlert($formLogin);
                closeAlert($formRegister);
                closeAlert($formResetPassword);
            });
            /** Register  */
            $formRegister.validate({
                rules: {
                    register_username: {required: true},
                    register_email: {required: true, email: true}
                },
                submitHandler: function (form) {
                    var btnRegister = $('#btnRegister');
                    $formRegister.ajaxSubmit({
                        beforeSubmit: function () {
                            closeAlert($formRegister);
                            btnRegister.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnRegister.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                            }
                            showAlert($form, response.message);
                        }
                    });
                }
            });
            /**  Reset Password  */
            $formResetPassword.validate({
                submitHandler: function (form) {
                    var btnResetPassword = $('#btnResetPassword');
                    $formResetPassword.ajaxSubmit({
                        beforeSubmit: function () {
                            closeAlert($formResetPassword);
                            btnResetPassword.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnResetPassword.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                            }
                            showAlert($form, response.message);
                        }
                    });
                }
            });
        }
    });
})(jQuery);
