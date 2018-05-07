<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetUserForms extends WidgetDialogBase
{
    const USER_NAME = "user_login";
    const USER_PASSWORD = "user_password";
    const USER_PASS = "user_pass";
    const USER_EMAIL = "user_email";
    const USER_FIRST_NAME = "first_name";
    const USER_DISPLAY_NAME = "display_name";
    const USER_NICE_NAME = "user_nicename";
    const USER_LAST_NAME = "last_name";
    const USER_REMEMBER = "remember";
    const AJAX_LOGIN = "ajaxLogin";
    const AJAX_REGISTER = "ajaxRegister";
    const AJAX_FORGOT = "ajaxForgot";
    const REDIRECT_LINK = "rtHref";

    function __construct()
    {
        parent::__construct(__('Login Form', 'wptheme'), __('This widget displays a Login Form.', 'wptheme'));
        /** Enable the user with no privileges to request */
        //TODO Set this handler in concordance with Widget Configuration
        WPActions::addAjaxHandler([$this, self::AJAX_LOGIN]);
        /** Ajax Login */
        WPActions::addAjaxHandler([$this, self::AJAX_REGISTER]);
        /** Ajax Register */
        WPActions::addAjaxHandler([$this, self::AJAX_FORGOT]);
        /** Ajax Password Reset */
        $this->iconModalToggle = "fa-sign-in";
    }

    function enqueueScriptsTheme()
    {
        $uriToDirLibs = WPUtils::getUriToLibsDir(__FILE__);
        wp_enqueue_script('WidgetUserForms', "{$uriToDirLibs}/WidgetUserForms.js", ['jquery-validate'], false, true);
        // jQuery Validate
//        wp_enqueue_script('jqform', includes_url('js/jquery/jquery.form.min.js'), ['jquery'], false, true);
//        wp_enqueue_script('jqvalidate', $this->uriToLibs . 'jquery.validate.min.js', ['jquery'], false, true);
        parent::enqueueScriptsTheme();
    }


    function getResultContent($message, $valid = false, $redirectLink = "")
    {
        return json_encode([
            'message' => $message,
            'success' => $valid,
            'redirect' => $redirectLink
        ]);
    }

    /** Email to Admin*/
    function sendMailToAdmin($user)
    {
        $siteName = WPOptions::getSiteName();
        $messageSubject = sprintf(__('New user registration on your site %s:', 'wptheme'), $siteName);
        $message = sprintf('%1$s<br>%2$s<br>%3$s', $messageSubject, sprintf(__('Username: %s', 'wptheme'), $user->user_login), sprintf(__('Email: %s', 'wptheme'), $user->user_email));
        wp_mail(get_option(WPOptions::ADMIN_EMAIL), $messageSubject, $message);
    }

    function sendMailAboutNewUser($user_id, $user_password)
    {
        $user = get_userdata($user_id);
        $siteName = WPOptions::getSiteName();
        /** Email to Registered User*/
        $messageSubject = sprintf(__('Welcome to %s', 'wptheme'), $siteName);
        $message = sprintf('%1$s<br> %2$s<br> %3$s<br> %4$s<br>', $messageSubject, sprintf(__('Your username is: %s', 'wptheme'), "<strong>$user->user_login</strong>"), sprintf(__('Your password is: %s', 'wptheme'), "<strong>$user_password</strong>"), __('Your User Account was sent to the site administrator for approval', 'wptheme'));
        wp_mail($user->user_email, $messageSubject, $message, ['Content-Type: text/html; charset=UTF-8']);

    }

    function set_html_content_type()
    {
        return "text/html";
    }

    function getFloatingUserMenu()
    {
        //TODO Check FORCE_SSL_ADMIN if is defined then redirect to SSL Page
        $linkOfRedirect = add_query_arg('_', false);
        $urlLogout = wp_logout_url($linkOfRedirect);
        $authorId = get_current_user_id();
        $author = get_userdata($authorId);
        $authorAvatar = get_avatar($authorId, 32, "", "", ["class" => "media-object img-circle"]);
        $urlAuthorPage = get_author_posts_url($authorId);
        $urlAuthorPropertyAdd = admin_url('post-new.php?post_type=property');
        $urlAuthorEditProfile = admin_url('profile.php');
        $markup = '<div class="usermenu btn-group dropup">
			        <figure class="btn btn-primary dropdown-toggle clearfix" data-toggle="dropdown" aria-haspopup="true"
			                aria-expanded="false">%s<figcaption>%s</figcaption>
			        </figure>
			        <ul class="dropdown-menu dropdown-menu-right">
			            <li><a href="%s"><span>%s</span></a></li>
			            <li><a href="%s"><span>%s</span></a></li>
			            <li><a href="%s"><span>%s</span></a></li>
			            <li><a href="%s"><span>%s</span></a></li>
			        </ul></div>';
        $content = sprintf($markup,
            $authorAvatar,
            $author->display_name,
            $urlAuthorPage,
            __('My Page', 'wptheme'),
            $urlAuthorPropertyAdd,
            __('Add Property', 'wptheme'),
            $urlAuthorEditProfile,
            __('Edit Profile', 'wptheme'),
            $urlLogout,
            __('Logout', 'wptheme'));

        return $content;
    }

    function generateUserName()
    {
        $authors = get_users([
            QueryUsers::ROLE => WPUserRoles::AUTHOR,
            QueryUsers::ORDER_BY => 'registered',
            QueryUsers::ORDER => WPOrder::DESC,
            QueryUsers::NUMBER => 1,
        ]);
        $userName = "Agent";
        $lastRegisteredAuthor = $authors[0]; // the first user from the list
        if (isset($lastRegisteredAuthor)) {
            $userName .= $lastRegisteredAuthor->ID++;
        } else {
            $users = get_users([
                QueryUsers::ORDER_BY => WPOrderBy::REGISTERED,
                QueryUsers::ORDER => WPOrder::DESC,
                QueryUsers::NUMBER => 1,
            ]);
            $lastRegisteredAuthor = $users[0];
            $userName .= $lastRegisteredAuthor->ID++;
        }

        return $userName;
    }

    /** AJAX Request Handler: Register */
    function ajaxRegister()
    {
        $result = json_encode(['success' => false]);
        if (check_ajax_referer(self::AJAX_REGISTER, self::AJAX_REGISTER) && isset($_POST[self::USER_EMAIL])) {
            $userdata = [];
            if (isset($_POST[self::USER_FIRST_NAME])) {
                $userdata[self::USER_FIRST_NAME] = sanitize_text_field($_POST[self::USER_FIRST_NAME]);
            }
            if (isset($_POST[self::USER_LAST_NAME])) {
                $userdata[self::USER_LAST_NAME] = sanitize_text_field($_POST[self::USER_LAST_NAME]);
            }
            $userdata[self::USER_NAME] = $this->generateUserName();

            $nameNumber = filter_var($userdata[self::USER_NAME], FILTER_SANITIZE_NUMBER_INT);
            $userdata[self::USER_NICE_NAME] = "realtor" . $nameNumber;//Риелтор

            $userdata[self::USER_EMAIL] = sanitize_email($_POST[self::USER_EMAIL]);
            $userdata[self::USER_PASS] = wp_generate_password(12);
            $user_register = wp_insert_user($userdata);
            if (is_wp_error($user_register)) {
                $error = $user_register->get_error_codes();
                if (in_array('empty_user_login', $error)) {
                    $result = $this->getResultContent(__($user_register->get_error_message('empty_user_login')));
                } elseif (in_array('existing_user_login', $error)) {
                    $result = $this->getResultContent(__('This username already exists.', 'wptheme'));
                } elseif (in_array('existing_user_email', $error)) {
                    $result = $this->getResultContent(__('This email is already registered.', 'wptheme'));
                } else {
                    $result = $this->getResultContent($user_register->get_error_message());
                }
            } else {
                $this->sendMailAboutNewUser($user_register, $userdata[self::USER_PASS]);
                $result = $this->getResultContent(__('Registration is complete. Check your email for details!', 'wptheme'), true);
            }
        }
        echo $result;
        die();
    }

    /** AJAX Request Handler: Login */
    function ajaxLogin()
    {
        $result = json_encode(['success' => false]);
        // First check the nonce, if it fails the function will break
        if (check_ajax_referer(self::AJAX_LOGIN, self::AJAX_LOGIN)) {
            $credentials = [self::USER_REMEMBER => true];
            if (isset($_POST[self::USER_NAME]) && !empty($_POST[self::USER_NAME])) {
                $credentials[self::USER_NAME] = sanitize_user($_POST[self::USER_NAME]);
            }
            if (isset($_POST[self::USER_PASSWORD]) && !empty($_POST[self::USER_PASSWORD])) {
                $credentials[self::USER_PASSWORD] = $_POST[self::USER_PASSWORD];
            }
            $user = wp_signon($credentials, is_ssl());
            if (is_wp_error($user)) {
                $result = $this->getResultContent(__('Wrong username or password.', 'wptheme'));
            } else {
                wp_set_current_user($user->ID);
                $result = $this->getResultContent("", true, $_POST[self::REDIRECT_LINK]);
            }
        }
        echo $result;
        die();
    }

    /** AJAX Request Handler: Forgot Password */
    function ajaxForgot()
    {
        $result = json_encode(['success' => false]);
        /**
         * TODO Only If user has Email access can change password,
         * fix case when someone will introduce agent email to change password
         * also is posible to send to user the real email
         */
        if (check_ajax_referer(self::AJAX_FORGOT, self::AJAX_FORGOT) && isset($_POST[self::USER_EMAIL])) {
            $userEmail = sanitize_email($_POST[self::USER_EMAIL]);
            $errorMessage = "";
            if (empty($userEmail)) {
                $errorMessage = __('Provide a valid username or email address!', 'wptheme');
            } else {
                if (is_email($userEmail) && email_exists($userEmail)) {
                    // Generate new random password
                    $generatedPassword = wp_generate_password();
                    // Get user data by field ( fields are id, slug, email or login )
                    $target_user = get_user_by('email', $userEmail);
                    $target_user->user_pass = $generatedPassword;
                    $update_user = wp_update_user($target_user);
                    // if  update_user return true then send user an email containing the new password
                    if ($update_user) {
                        $to = $target_user->user_email;
                        $subject = sprintf(__('Your New Password For %s', 'wptheme'), WPOptions::getSiteName());
                        $message = sprintf(__('Your new password is: %s', 'wptheme'), $generatedPassword);
                        /** Email Headers ( Reply To and Content Type )*/
                        if (wp_mail($to, $subject, $message, ["Content-Type: text/html; charset=UTF-8"])) {
                            $success = __('Check your email for new password', 'wptheme');
                        } else {
                            $errorMessage = __('Failed to send you new password email!', 'wptheme');
                        }
                    } else {
                        $errorMessage = __('Oops! Something went wrong while resetting your password!', 'wptheme');
                    }
                } else {
                    $errorMessage = __('No user found for given email!', 'wptheme');
                }
            }
            if (!empty($errorMessage)) {
                $result = $this->getResultContent($errorMessage);
            } elseif (!empty($success)) {
                $result = $this->getResultContent($success, true);
            }
        }
        echo $result;
        die();
    }

    function getFormRegister($linkOfAdmin, $linkOfRedirect)
    {
        $markup = '<div id="sectionRegister" class="tab-pane" role="tabpanel">
            <h4><span>%1$s</span></h4>
            <form method="post" enctype="multipart/form-data" action="%2$s" id="formRegister">
            <fieldset>
                <input id="%3$s" name="%3$s" type="text" required>
                <label for="%3$s"><span>%4$s</span></label>
            </fieldset>
            <fieldset>
                <input id="%5$s" name="%5$s" type="text" required>
                <label for="%5$s"><span>%6$s</span></label>
            </fieldset>
            <fieldset>
                <input id="%7$s" name="%7$s" type="email" required>
                <label for="%7$s"><i class="fa fa-envelope"></i> <span>%8$s</span></label>
            </fieldset>
            <fieldset>
	            <a href="#sectionLogin" class="button float-xs-left">
	            	<i class="fa fa-angle-left"></i> 
	            	<span>%9$s</span>
	            </a>
	            <button type="submit" id="btnRegister">
	                <span>%10$s</span>
	            </button>
	            <input type="hidden" autocomplete="off" name="action"      value="%11$s">
	            <input type="hidden" autocomplete="off" name="user-cookie" value="1">
	            <input type="hidden" autocomplete="off" name="%12$s"       value="%13$s">
	            %14$s
            </fieldset></form></div>';
        $nonceFieldValue = WPUtils::getNonceField(self::AJAX_REGISTER, self::AJAX_REGISTER, true, false);
        return sprintf($markup,
            __('Register', 'wptheme'),
            $linkOfAdmin,
            self::USER_FIRST_NAME,
            __('First Name', 'wptheme'),
            self::USER_LAST_NAME,
            __('Last Name', 'wptheme'),
            self::USER_EMAIL,
            __('Your Email', 'wptheme'),
            __('Login', 'wptheme'),
            __('Sign Up', 'wptheme'),
            self::AJAX_REGISTER,
            self::REDIRECT_LINK,
            $linkOfRedirect,
            $nonceFieldValue,
            $this->modalDialogId);
    }

    function getFormLogin($linkOfAdmin, $linkOfRedirect, $enableRegistration = false)
    {
        $registrationButton = "";
        $btnResetPassword = "";
        if ($enableRegistration) {
            $markup = '<a href="#sectionRegister" class="button float-xs-left">
					<i class="fa fa-user-plus"></i> <span>%s</span></a>';
            $registrationButton = sprintf($markup, __('Register', 'wptheme'));
            /*$markup = '<a href="#sectionResetPassword" data-toggle="tab" class="btn btn-link"><span>%s</span></a>';
            $btnResetPassword = sprintf($markup,__( 'Reset Password', 'wptheme' ));*/
        }
        $markup = '<div id="sectionLogin" class="tab-pane active" role="tabpanel">
            <h4><span>%1$s</span></h4>
            <form method="post" enctype="multipart/form-data" action="%2$s" id="formLogin">
            <fieldset>
                <input id="%3$s" name="%3$s" type="text" autofocus required>
                <label for="%3$s"><i class="fa fa-user"></i> <span>%4$s</span></label>
            </fieldset>
            <fieldset>
                <input id="%5$s" name="%5$s" type="password" required>
                <label for="%5$s"><i class="fa fa-key"></i> <span>%6$s</span></label>
            </fieldset>
            <fieldset>
            	%8$s
	            <button type="submit" id="btnLogin">
                    <i class="fa fa-unlock"></i>
                    <span>%9$s</span>
	            </button>
	            <input type="hidden" autocomplete="off" name="action"      value="%10$s">
	            <input type="hidden" autocomplete="off" name="user-cookie" value="1">
	            <input type="hidden" autocomplete="off" name="%11$s"       value="%12$s">
	            %13$s
            </fieldset></form></div>';
        $nonceFieldValue = WPUtils::getNonceField(self::AJAX_LOGIN, self::AJAX_LOGIN, true, false);
        return sprintf($markup,
            __('Login', 'wptheme'),
            $linkOfAdmin,
            self::USER_NAME,
            __('User Name', 'wptheme'),
            self::USER_PASSWORD,
            __('Password', 'wptheme'),
            $btnResetPassword,
            $registrationButton,
            __('Sign In', 'wptheme'),
            self::AJAX_LOGIN,
            self::REDIRECT_LINK,
            $linkOfRedirect,
            $nonceFieldValue);
    }

    function getFormForgot($linkOfAdmin)
    {
        $markup = '<div id="sectionResetPassword" class="tab-pane" role="tabpanel">
            <h4><span>%1$s</span></h4>
			<form method="post" enctype="multipart/form-data" action="%2$s" id="formResetPassword">
			<fieldset>
                <input id="%3$s" name="%3$s" type="text" class="form-control" required>
                <label for="%3$s"><i class="fa fa-envelope"></i> <span>%4$s</span></label>
            </fieldset>
            <fieldset>
	            <a href="#sectionLogin" data-toggle="tab" class="button float-xs-left">
	                <i class="fa fa-angle-left"></i> 
	                <span>%5$s</span>
	            </a>
	            <button type="submit" id="btnResetPassword">
	                <i class="fa fa-repeat"></i> 
	                <span>%1$s</span>
	            </button>
	            <input type="hidden" name="action"      value="%6$s">
	            <input type="hidden" name="user-cookie" value="1">
	            %7$s
            </fieldset></form></div>';
        $nonceFieldValue = WPUtils::getNonceField(self::AJAX_FORGOT, self::AJAX_FORGOT, true, false);
        return sprintf($markup,
            __('Reset Password', 'wptheme'),
            $linkOfAdmin,
            self::USER_EMAIL,
            __('Email', 'wptheme'),
            __('Login', 'wptheme'),
            self::AJAX_FORGOT,
            $nonceFieldValue);
    }

    function widget($args, $instance)
    {
        $content = "";
        $linkOfRedirect = add_query_arg('_', false);
        if (is_user_logged_in()) {
            $urlLogout = wp_logout_url($linkOfRedirect);
            $content = sprintf('<a href="%s"><i class="fa fa-sign-out"></i><span>%s</span></a>', $urlLogout, __('Logout', 'wptheme'));
            $instance[self::FORM_TYPE] = self::INLINE;
        } else {
            $linkOfAdmin = admin_url('admin-ajax.php');
            $enableRegistration = get_option(CustomizerSetting::SITE_REGISTRATION);
            $content .= $this->getFormLogin($linkOfAdmin, $linkOfRedirect, $enableRegistration);
            $content .= $this->getFormForgot($linkOfAdmin);
            if ($enableRegistration) {
                $content .= $this->getFormRegister($linkOfAdmin, $linkOfRedirect);
            }
            $content = sprintf('<div class="tab-content">%s</div>', $content);
        }

        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}