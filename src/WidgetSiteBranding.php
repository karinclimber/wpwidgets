<?php
/**
 * Author: Vitali Lupu <vitaliix@gmail.com>
 * Date: 3/5/18
 * Time: 6:32 PM
 */

namespace wp;
final class WidgetSiteBranding extends Widget
{
    function __construct()
    {
        parent::__construct(__('Site Brand', 'wptheme'),
            __('This widget displays a Site Brand.', 'wptheme'));
        add_filter('get_custom_logo', [$this, 'handleCustomLogo'], 10, 0);
    }

    function handleCustomLogo()
    {
        $logoId = get_theme_mod(CustomizerSetting::SITE_LOGO);
        $siteName = get_bloginfo('name', 'display');
        $siteDescription = get_bloginfo('description', 'display');

        if (!$logoId) {
            if (empty($siteName)) {
                $siteName = __("Site Title");

            }
            if (empty($siteDescription)) {
                $siteDescription = __("Site Description");
            }
        } else {
            $siteName = "";
        }
        $markup = '<a href="%1$s" class="custom-logo-link" rel="home">%2$s
                   <span class="site-title">%3$s</span><br>
                   <small class="site-description hidden-xs">%4$s</small></a>';
        $imageMarkup = wp_get_attachment_image($logoId, WPImages::FULL, false, [
            'class' => 'custom-logo',
            'alt' => get_bloginfo('name', 'display'),
        ]);

        return sprintf($markup, esc_url(home_url('/')), $imageMarkup, $siteName, $siteDescription);
    }

    function widget($args, $instance)
    {
        $args[WPSidebar::CONTENT] = get_custom_logo();
        parent::widget($args, $instance);
    }
}