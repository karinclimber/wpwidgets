<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
class WidgetLanguageChooser extends WidgetDialogBase
{
    const DISPLAY = "languageChooserDisplay";
    const DISPLAY_LIST = "languageChooserDisplayList";
    const DISPLAY_SELECT = "languageChooserDisplaySelect";

    function __construct()
    {
        parent::__construct(__('Language Chooser', 'wptheme'),
            __('Allows your visitors to choose a Language.', 'qtranslate'));
    }

    function initFields()
    {
        $this->addField(new WidgetField(WidgetField::RADIO, self::DISPLAY, __('Display'), [
            self::DISPLAY_LIST => __('List'),
            self::DISPLAY_SELECT => __('Select')
        ], self::DISPLAY_LIST));
    }

    function widget($args, $instance)
    {
        $content = "";
        $getSortedLanguages = 'qtranxf_getSortedLanguages';
        $convertLanguageUrl = 'qtranxf_convertURL';
        $getFlagLocation = 'qtranxf_flag_location';
        $insertLanguageSelectOption = 'qtranxf_insertDropDownElement';
        if (function_exists($getSortedLanguages) &&
            function_exists($convertLanguageUrl) &&
            function_exists($getFlagLocation)) {
            $languageChooserDisplay = self::getInstanceValue($instance, self::DISPLAY, $this);
            global $q_config;
            $url = '';
            if (is_404()) {
                $url = get_option('home');
            }
            if ($languageChooserDisplay == self::DISPLAY_SELECT) {
                foreach ($getSortedLanguages() as $language) {
                    $languageHref = $convertLanguageUrl($url, $language, false, true);
                    $insertLanguageSelectOption($language, $languageHref, $this->id);
                }
                $content = "<select id='languageChooser{$this->id}' onchange='document.location.href=this.value;'>$content</select>";
            } else {
                foreach ($getSortedLanguages() as $language) {
                    $alt = $q_config['language_name'][$language] . ' (' . $language . ')';

                    $languageName = $q_config['language_name'][$language];
                    $languageHref = $convertLanguageUrl($url, $language, false, true);
                    $languageFlag = $getFlagLocation() . $q_config['flag'][$language];
                    $cssClasses = "lang-$language";
                    if ($language == $q_config['language']) {
                        $cssClasses .= ' active';
                    }
                    $content .= "<li class='$cssClasses'>
                    <a href='$languageHref' hreflang='$language' title='$alt' class='languageChooser_{$language}'>
                    <img src='{$languageFlag}' alt='$alt'><span>$languageName</span></a></li>";
                }
                $content = "<ul id='languageChooser{$this->id}' class='languageChooser'>$content</ul>";
            }

        }
        $args[WPSidebar::CONTENT] = $content;
        parent::widget($args, $instance);
    }
}