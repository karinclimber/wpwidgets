<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetContacts extends Widget
{
    const SITE_CONTACTS = "siteContacts";

    function __construct()
    {
        parent::__construct(__('Site Contacts', 'wptheme'));
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::SELECT_MULTIPLE, WidgetContacts::SITE_CONTACTS,
            __("Site Contacts", 'wptheme'), CustomizerSetting::getSiteContacts(), ""));
        parent::initFields();
    }

    function widget($args, $instance)
    {
        $content = "";
        $contacts = (array)$instance[self::SITE_CONTACTS];
        if (count($contacts) == 0) {
            $contacts = array_keys(CustomizerSetting::getSiteContacts());
        }
        foreach ($contacts as $key) {
            $contactValue = get_option($key);
            if ($contactValue) {
                if ($key == CustomizerSetting::SITE_PHONES) {
                    $phones = explode(",", $contactValue);
                    foreach ($phones as $contactValue) {
                        $phone = preg_replace('/[^0-9]/', '', $contactValue);
                        $content .= sprintf('<a href="%1$s%2$s" class="%3$s btn-custom" rel="nofollow"><i class="%4$s"></i><span>%5$s</span></a>',
                            CustomizerSetting::getReferencePrefixes($key),
                            $phone,
                            $key,
                            CustomizerSetting::getSettingsIconFa($key),
                            $contactValue);
                    }
                } else if ($key == CustomizerSetting::SITE_ADDRESS) {
                    $content .= sprintf('<a href="%1$s" class="%2$s btn-custom"><i class="%3$s"></i><span>%4$s</span></a>',
                        CustomizerSetting::getReferencePrefixes($key),
                        $key,
                        CustomizerSetting::getSettingsIconFa($key),
                        $contactValue);
                } else {
                    $content .= sprintf('<a href="%1$s%2$s" class="%3$s btn-custom" rel="nofollow"><i class="%4$s"></i><span>%2$s</span></a>',
                        CustomizerSetting::getReferencePrefixes($key),
                        $contactValue,
                        $key,
                        CustomizerSetting::getSettingsIconFa($key));
                }
            }
        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}