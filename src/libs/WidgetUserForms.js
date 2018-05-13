/**
 * Created by Vitalie Lupu on 6/2/17.
 */
function WidgetUserForms() {
    var self = this, formSubmitted = false;
    self.userName = ko.observable();
    self.userPassword = ko.observable();
    self.hasCredentials = ko.pureComputed(function () {
        return self.userName().length > 2 && self.userPassword().length > 6 && !formSubmitted;
    });
    self.handleOnSubmit = function handleOnSubmit(formElement) {
        if (jQuery !== undefined && jQuery().ajaxSubmit) {
            var $form = jQuery(formElement);
            $form.ajaxSubmit({
                beforeSubmit: function () {
                    formSubmitted = true;
                },
                success: function (ajax_response, statusText, xhr, form) {
                    formSubmitted = false;
                    var response = jQuery.parseJSON(ajax_response);
                    if (response.success) {
                        form.resetForm();
                    }
                    if (response.redirect) {
                        window.location.replace(response.redirect);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    };
}