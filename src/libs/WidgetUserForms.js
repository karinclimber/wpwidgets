/**
 * Created by Vitalie Lupu on 6/2/17.
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        if (jQuery().validate && jQuery().ajaxSubmit) {
            /** User Forms */
            var alertClose = function ($form) {
                $('.alert', $form).alert('close');
            };
            var alertShow = function ($form, message) {
                var alertMessage = '<div class="alert alert-success alert-dismissible text-center" role="alert">' + message + '</div>';
                $('.modal-body', $form).append(alertMessage);
            };
            $('.modal').on('hidden.bs.modal', function () {
                alertClose($(this));
            });
            $('button[data-toggle="tab"]').on('shown.bs.tab', function () {
                alertClose($formLogin);
                alertClose($formRegister);
                alertClose($formResetPassword);
            });
            /** Login  */
            var $formLogin = $('#formLogin');
            var btnLogin = $('#btnLogin');
            $formLogin.validate({
                submitHandler: function (form) {
                    form.ajaxSubmit({
                        beforeSubmit: function () {
                            alertClose(form);
                            btnLogin.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnLogin.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                                window.location.replace(response.redirect);
                            } else {
                                alertShow($form, response.message);
                            }
                        }
                    });
                }
            });
            /**  Reset Password  */
            var $formResetPassword = $('#formResetPassword');
            var btnResetPassword = $('#btnResetPassword');
            $formResetPassword.validate({
                submitHandler: function (form) {
                    form.ajaxSubmit({
                        beforeSubmit: function () {
                            alertClose(form);
                            btnResetPassword.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnResetPassword.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                            }
                            alertShow($form, response.message);
                        }
                    });
                }
            });
            /** Register  */
            var $formRegister = $('#formRegister');
            var btnRegister = $('#btnRegister');
            $formRegister.validate({
                rules: {
                    register_username: {required: true},
                    register_email: {required: true, email: true}
                },
                submitHandler: function (form) {
                    form.ajaxSubmit({
                        beforeSubmit: function () {
                            alertClose(form);
                            btnRegister.attr('disabled', 'disabled');
                        },
                        success: function (ajax_response, statusText, xhr, $form) {
                            var response = $.parseJSON(ajax_response);
                            btnRegister.removeAttr('disabled');
                            if (response.success) {
                                $form.resetForm();
                            }
                            alertShow($form, response.message);
                        }
                    });
                }
            });
        }
    });
})(jQuery);