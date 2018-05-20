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
        $siteHomeUrl = esc_url(home_url('/'));
        $siteName = get_bloginfo('name', 'display');
        $cssSiteName = WPOptions::SITE_NAME;
        $siteDescription = get_bloginfo('description', 'display');
        $cssSiteDescription = WPOptions::SITE_DESCRIPTION;
        $cssSiteLogo = WPOptions::SITE_LOGO;
        $siteLogoId = get_theme_mod('custom_logo');
        if ($siteLogoId && $image = wp_get_attachment_image_src($siteLogoId, WPImages::FULL)) {
            list($src, $width, $height) = $image;
            $hwData = image_hwstring($width, $height);
            /*$imageMarkup = "<img src='{$src}' class='{$cssSiteLogo}' usemap='#{$cssSiteLogo}' alt='{$siteName}' {$hwData}>
            <map name='{$cssSiteLogo}'>
            <area shape='rect' coords='0,0,$width,$height' href='$siteHomeUrl' alt='$siteName'></map>";*/
            return "<a href='{$siteHomeUrl}' class='{$cssSiteLogo}-link' rel='home'><img src='{$src}' class='{$cssSiteLogo}' alt='{$siteName}' {$hwData}></a>";
        } else {
            return "<div style='display: inline-block;'><a href='{$siteHomeUrl}' class='{$cssSiteName}'>{$siteName}</a><br>
            <small class='{$cssSiteDescription} hidden-xs'>{$siteDescription}</small></div>";
        }
    }

    function widget($args, $instance)
    {
        $args[WPSidebar::CONTENT] = get_custom_logo();
        parent::widget($args, $instance);
    }
}