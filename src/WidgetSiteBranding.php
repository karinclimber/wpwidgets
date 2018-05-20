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
        $siteName = get_bloginfo('name', 'display');
        $siteDescription = get_bloginfo('description', 'display');
        $siteHomeUrl = esc_url(home_url('/'));
        $siteLogoId = get_theme_mod(WPOptions::SITE_LOGO);
        $siteNameStyle = '';
        $imageMarkup = '';
        if ($siteLogoId) {
            $image = wp_get_attachment_image_src($siteLogoId, WPImages::FULL);
            if ($image) {
                list($src, $width, $height) = $image;
                $hwData = image_hwstring($width, $height);
                $cssSiteLogo = WPOptions::SITE_LOGO;
                $imageMarkup = "<img src='{$src}' class='{$cssSiteLogo}' usemap='#{$cssSiteLogo}' alt='{$siteName}' {$hwData}>
                <map name='{$cssSiteLogo}'>
                <area shape='rect' coords='0,0,$width,$height' href='$siteHomeUrl' alt='$siteName'></map>";
                $siteNameStyle = "style='display:none;'";
            }
        }
        $cssSiteName = WPOptions::SITE_NAME;
        $cssSiteDescription = WPOptions::SITE_DESCRIPTION;
        return "<figure style='display: inline-block;' rel='home'>{$imageMarkup}
        <figcaption {$siteNameStyle}><a href='{$siteHomeUrl}'>
            <span class='{$cssSiteName}'>{$siteName}</span><br>
            <small class='{$cssSiteDescription} hidden-xs'>{$siteDescription}</small>
        </a></figcaption></figure>";
    }

    function widget($args, $instance)
    {
        $args[WPSidebar::CONTENT] = get_custom_logo();
        parent::widget($args, $instance);
    }
}